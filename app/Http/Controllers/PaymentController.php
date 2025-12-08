<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    /**
     * Payment method values used by checkout form
     */
    public const METHOD_STRIPE      = 'stripe_card';
    public const METHOD_TOYYIBPAY   = 'fpx_toyyibpay';
    public const METHOD_BILLPLZ     = 'fpx_billplz';

    /**
     * Main entry: called by Place Order button (POST /payment/process)
     */
    public function process(Request $request)
    {
        $user = $request->user();

        // Basic validation
        $rules = [
            'payment_method'   => 'required|in:' . implode(',', [
                self::METHOD_STRIPE,
                self::METHOD_TOYYIBPAY,
                self::METHOD_BILLPLZ,
            ]),
            'selected_address' => 'required|integer',
            'amount'           => 'required|numeric|min:0.50',
        ];

        $paymentMethod = $request->input('payment_method');

        // For FPX (Toyyibpay/Billplz) we still require bank selection (for UI),
        // even though gateway itself will show bank list again.
        if (in_array($paymentMethod, [self::METHOD_TOYYIBPAY, self::METHOD_BILLPLZ], true)) {
            $rules['online_banking_bank'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // ⚠️ In production you should always re-calculate cart total on the server.
        // For now we trust the posted amount to keep changes small.
        $amount   = (float) $validated['amount'];
        $currency = config('services.stripe.currency', 'myr'); // we use same 3-letter code everywhere

        // Create a new pending order
        $order = Order::create([
            'user_id'            => $user->id,
            'shipping_address_id'=> $validated['selected_address'],
            'billing_address_id' => $validated['selected_address'], // simple: use same as shipping
            'total_amount'       => $amount,
            'shipping_cost'      => 0,
            'tax_amount'         => 0,
            'discount_amount'    => 0,
            'status'             => Order::STATUS_PENDING,
            'payment_method'     => $paymentMethod,
            'payment_gateway'    => $this->mapGatewayName($paymentMethod),
            'payment_status'     => Order::PAYMENT_STATUS_PENDING,
            'currency'           => strtoupper($currency),
        ]);

        // Decide which gateway flow to use
        switch ($paymentMethod) {
            case self::METHOD_STRIPE:
                return $this->redirectToStripeCheckout($order, $request);

            case self::METHOD_TOYYIBPAY:
                return $this->redirectToToyyibpayFPX($order, $request);

            case self::METHOD_BILLPLZ:
                return $this->redirectToBillplzFPX($order, $request);

            default:
                // should never hit here because of validation
                return redirect()
                    ->route('checkout.failed')
                    ->with('error', 'Unsupported payment method selected.');
        }
    }

    /**
     * Map payment method → human readable gateway name
     */
    protected function mapGatewayName(string $method): string
    {
        return match ($method) {
            self::METHOD_STRIPE    => 'Stripe',
            self::METHOD_TOYYIBPAY => 'Toyyibpay',
            self::METHOD_BILLPLZ   => 'Billplz',
            default                => 'Unknown',
        };
    }

    /* ============================================================
     *  STRIPE – CARD PAYMENT
     * ============================================================
     */

    protected function redirectToStripeCheckout(Order $order, Request $request)
    {
        $secret  = config('services.stripe.secret');

        if (empty($secret)) {
            Log::error('Stripe secret not configured.');
            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Payment gateway is not configured. Please contact support.');
        }

        $stripe  = new StripeClient($secret);
        $currency = strtolower(config('services.stripe.currency', $order->currency ?: 'myr'));

        // Stripe expects smallest unit (sen/cents)
        $amountInCents = (int) round($order->total_amount * 100);

        try {
            $session = $stripe->checkout->sessions->create([
                'mode'                 => 'payment',
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => $currency,
                        'product_data' => [
                            'name' => 'Order #' . $order->order_number,
                        ],
                        'unit_amount'  => $amountInCents,
                    ],
                    'quantity'   => 1,
                ]],
                'customer_email'     => $request->user()->email,
                'client_reference_id'=> $order->id,
                'success_url'        => route('payment.stripe.success', ['order' => $order->id])
                                        . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'         => route('payment.stripe.cancel', ['order' => $order->id]),
            ]);

            // Save Stripe session ID as reference
            $order->payment_reference = $session->id ?? null;
            $order->save();

            return redirect()->away($session->url);
        } catch (\Throwable $e) {
            Log::error('Stripe Checkout error', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            $order->markAsFailed('Stripe initialisation failed');

            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Unable to start card payment. Please try again or use another method.');
        }
    }

    /**
     * Stripe success redirect (browser)
     * Route: GET /payment/stripe/success
     */
    public function stripeSuccess(Request $request, Order $order)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId || $order->payment_reference !== $sessionId) {
            // We still let user see success if Stripe later confirms via webhook.
            return redirect()
                ->route('checkout.success', ['order' => $order->id])
                ->with('info', 'We are verifying your payment. If it does not appear, please contact support.');
        }

        try {
            $secret = config('services.stripe.secret');
            $stripe = new StripeClient($secret);

            $session = $stripe->checkout->sessions->retrieve($sessionId, []);
            if (($session->payment_status ?? null) === 'paid') {
                $order->markAsPaid('Stripe Checkout completed');
            } else {
                $order->markAsFailed('Stripe payment not completed on success URL');
            }
        } catch (\Throwable $e) {
            Log::error('Stripe verify on success URL failed', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }

        return redirect()->route('checkout.success', ['order' => $order->id]);
    }

    /**
     * Stripe cancel redirect (browser)
     * Route: GET /payment/stripe/cancel
     */
    public function stripeCancel(Request $request, Order $order)
    {
        // Keep order as pending/failed so user can retry
        $order->markAsFailed('Stripe payment cancelled by customer');

        return redirect()
            ->route('checkout.failed')
            ->with('error', 'You cancelled the card payment. You can try again or choose another method.');
    }

    /* ============================================================
     *  TOYYIBPAY – FPX
     * ============================================================
     */

    protected function redirectToToyyibpayFPX(Order $order, Request $request)
    {
        $config = config('services.toyyibpay', []);

        if (empty($config['secret_key']) || empty($config['category_code'])) {
            Log::error('Toyyibpay config missing.');
            return redirect()
                ->route('checkout.failed')
                ->with('error', 'FPX gateway (Toyyibpay) is not configured. Please contact support.');
        }

        $endpoint   = rtrim($config['endpoint'] ?? 'https://toyyibpay.com', '/');
        $callbackUrl= route('payment.toyyibpay.callback');
        $returnUrl  = route('checkout.success', ['order' => $order->id]);

        // Toyyibpay amount is also in sen (cents)
        $amountInCents = (int) round($order->total_amount * 100);

        try {
            $response = Http::asForm()->post($endpoint . '/index.php/api/createBill', [
                'userSecretKey'          => $config['secret_key'],
                'categoryCode'           => $config['category_code'],
                'billName'               => 'Order #' . $order->order_number,
                'billDescription'        => 'FPX payment for order #' . $order->order_number,
                'billPriceSetting'       => 1,
                'billPayorInfo'          => 1,
                'billAmount'             => $amountInCents,
                'billReturnUrl'          => $returnUrl,
                'billCallbackUrl'        => $callbackUrl,
                'billExternalReferenceNo'=> $order->id,
                'billTo'                 => $request->user()->name,
                'billEmail'              => $request->user()->email,
                'billPhone'              => $request->user()->phone ?? '',
                'billPaymentChannel'     => '0', // all channels
            ]);

            if (!$response->ok()) {
                Log::error('Toyyibpay createBill HTTP error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                $order->markAsFailed('Unable to create Toyyibpay bill');

                return redirect()
                    ->route('checkout.failed')
                    ->with('error', 'Unable to start FPX payment. Please try again later.');
            }

            $data = $response->json();

            // Official API returns an array, index 0, key BillCode
            $billCode = $data[0]['BillCode'] ?? null;

            if (!$billCode) {
                Log::error('Toyyibpay BillCode missing in response', ['response' => $data]);
                $order->markAsFailed('Toyyibpay response invalid');

                return redirect()
                    ->route('checkout.failed')
                    ->with('error', 'Unable to start FPX payment. Please try again.');
            }

            // Save bill code as reference
            $order->payment_reference = $billCode;
            $order->save();

            // Redirect customer to payment page
            $redirectUrl = $endpoint . '/' . $billCode;

            return redirect()->away($redirectUrl);
        } catch (\Throwable $e) {
            Log::error('Toyyibpay error', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            $order->markAsFailed('Toyyibpay initialisation failed');

            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Unable to start FPX payment. Please try again.');
        }
    }

    /**
     * Toyyibpay callback (server-to-server)
     * Route: POST /payment/toyyibpay/callback
     *
     * NOTE: Adjust field names according to your Toyyibpay dashboard / latest docs.
     */
    public function toyyibpayCallback(Request $request)
    {
        Log::info('Toyyibpay callback received', $request->all());

        // Common fields: refno (billcode) and status (1 = success, 0 = failed)
        $refNo  = $request->input('refno');   // often the BillCode or internal ref
        $status = $request->input('status');  // "1" or "0"

        if (!$refNo) {
            return response('MISSING REFNO', 400);
        }

        $order = Order::where('payment_reference', $refNo)->first();

        if (!$order) {
            Log::warning('Toyyibpay callback: order not found', ['refno' => $refNo]);
            return response('ORDER NOT FOUND', 404);
        }

        if ((string) $status === '1') {
            $order->markAsPaid('Toyyibpay callback success');
        } else {
            $order->markAsFailed('Toyyibpay callback failed/cancelled');
        }

        return response('OK', 200);
    }

    /* ============================================================
     *  BILLPLZ – FPX
     * ============================================================
     */

    protected function redirectToBillplzFPX(Order $order, Request $request)
    {
        $config = config('services.billplz', []);

        if (empty($config['key']) || empty($config['collection_id'])) {
            Log::error('Billplz config missing.');
            return redirect()
                ->route('checkout.failed')
                ->with('error', 'FPX gateway (Billplz) is not configured. Please contact support.');
        }

        $endpoint    = rtrim($config['endpoint'] ?? 'https://www.billplz.com', '/');
        $callbackUrl = route('payment.billplz.callback');
        $redirectUrl = route('checkout.success', ['order' => $order->id]);

        $amountInCents = (int) round($order->total_amount * 100);

        try {
            $response = Http::withBasicAuth($config['key'], '')
                ->asForm()
                ->post($endpoint . '/api/v3/bills', [
                    'collection_id'      => $config['collection_id'],
                    'email'              => $request->user()->email,
                    'name'               => $request->user()->name,
                    'amount'             => $amountInCents,
                    'description'        => 'Order #' . $order->order_number,
                    'reference_1_label'  => 'Order ID',
                    'reference_1'        => $order->id,
                    'callback_url'       => $callbackUrl,
                    'redirect_url'       => $redirectUrl,
                ]);

            if (!$response->ok()) {
                Log::error('Billplz create bill HTTP error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                $order->markAsFailed('Billplz create bill failed');

                return redirect()
                    ->route('checkout.failed')
                    ->with('error', 'Unable to start FPX payment. Please try again later.');
            }

            $data = $response->json();

            $billId = $data['id']  ?? null;
            $url    = $data['url'] ?? null;

            if (!$billId || !$url) {
                Log::error('Billplz response missing id/url', ['response' => $data]);

                $order->markAsFailed('Billplz response invalid');

                return redirect()
                    ->route('checkout.failed')
                    ->with('error', 'Unable to start FPX payment. Please try again.');
            }

            $order->payment_reference = $billId;
            $order->save();

            return redirect()->away($url);
        } catch (\Throwable $e) {
            Log::error('Billplz error', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            $order->markAsFailed('Billplz initialisation failed');

            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Unable to start FPX payment. Please try again.');
        }
    }

    /**
     * Billplz callback (server-to-server)
     * Route: POST /payment/billplz/callback
     *
     * NOTE: Adjust params (id, paid, reference_1, etc.) based on Billplz docs.
     */
    public function billplzCallback(Request $request)
    {
        Log::info('Billplz callback received', $request->all());

        $billId   = $request->input('id');          // Bill ID
        $paidFlag = $request->input('paid');        // "true"/"false" or 1/0
        $orderId  = $request->input('reference_1'); // we sent this in create bill

        $order = null;

        if ($orderId) {
            $order = Order::find($orderId);
        } elseif ($billId) {
            $order = Order::where('payment_reference', $billId)->first();
        }

        if (!$order) {
            Log::warning('Billplz callback: order not found', [
                'bill_id'  => $billId,
                'order_id' => $orderId,
            ]);

            return response('ORDER NOT FOUND', 404);
        }

        $paid = filter_var($paidFlag, FILTER_VALIDATE_BOOLEAN);

        if ($paid) {
            $order->markAsPaid('Billplz callback success');
        } else {
            $order->markAsFailed('Billplz callback failed/cancelled');
        }

        return response('OK', 200);
    }

    /* ============================================================
     *  GENERIC SUCCESS / CANCEL PAGES (OPTIONAL)
     * ============================================================
     */

    public function success()
    {
        // This route is optional – you can redirect here from gateways
        return redirect()
            ->route('orders.index')
            ->with('success', 'Payment processed. You can review your order details in your account.');
    }

    public function cancel()
    {
        return redirect()
            ->route('checkout.failed')
            ->with('error', 'Payment was cancelled. You can try again or choose another method.');
    }

    /**
     * Generic webhook receiver – optional.
     * Route: POST /payment/webhook/{gateway}
     *
     * IMPORTANT:
     * In real deployment, verify signatures for each provider BEFORE trusting data.
     */
    public function webhook(Request $request, string $gateway)
    {
        Log::info("Payment webhook received from {$gateway}", $request->all());

        // For now we just acknowledge; real logic depends on which gateways
        // you enable webhook for (Stripe, Toyyibpay, Billplz, etc).
        return response('OK', 200);
    }
}