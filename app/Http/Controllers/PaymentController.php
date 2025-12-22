<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{

    public const METHOD_STRIPE      = 'stripe_card';
    public const METHOD_TOYYIBPAY   = 'fpx_toyyibpay';

    /* Map frontend payment method to internal gateway */
    private function mapToGateway(string $frontendMethod): string
    {
        return match($frontendMethod) {
            'credit_card', 'debit_card' => self::METHOD_STRIPE,
            'online_banking' => self::METHOD_TOYYIBPAY,
            default => $frontendMethod
        };
    }

    /* Main entry: called by Place Order button (POST /payment/process) */
    public function process(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'payment_method'   => 'required|in:credit_card,debit_card,online_banking',
            'selected_address' => 'required|integer',
        ]);

        $cart = Cart::where('user_id', $user->id)
            ->whereHas('items')
            ->with(['items.product', 'items.variation'])
            ->latest('id')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Please add items before making payment.');
        }

        $subtotal = $cart->items->sum(function ($item) {
            return (float)$item->price * (int)$item->quantity;
        });

        $shippingFee = 0;
        $discount    = 0;

        $finalAmount = max(0, $subtotal + $shippingFee - $discount);

        $order = Order::create([
            'user_id'             => $user->id,
            'shipping_address_id' => $validated['selected_address'],
            'billing_address_id'  => $validated['selected_address'],
            'total_amount'        => $finalAmount,
            'shipping_cost'       => $shippingFee,
            'tax_amount'          => 0,
            'discount_amount'     => $discount,
            'status'              => Order::STATUS_PENDING,
            'payment_method'      => $validated['payment_method'],
            'payment_gateway'     => $this->mapGatewayName($validated['payment_method']),
            'payment_status'      => Order::PAYMENT_STATUS_PENDING,
            'currency'            => strtoupper(config('services.stripe.currency', 'myr')),
        ]);

        foreach ($cart->items as $item) {
            $order->orderItems()->create([
                'product_id'      => $item->product_id,
                'variation_id'    => $item->variation_id,
                'quantity'        => $item->quantity,
                'price'           => $item->price,
                'total'           => $item->price * $item->quantity,
                'product_name'    => $item->product->name ?? null,
                'variation_name'  => $item->variation
                    ? trim(implode(' • ', array_filter([
                        $item->variation->model ?? null,
                        $item->variation->processor ?? null,
                        $item->variation->ram ?? null,
                        $item->variation->storage ?? null,
                    ])))
                    : null,
            ]);
        }

        return match ($validated['payment_method']) {
            'credit_card', 'debit_card' => $this->redirectToStripeCheckout($order, $request),
            'online_banking'            => $this->redirectToToyyibpayFPX($order, $request),
            default                     => redirect()->route('checkout.failed')->with('error', 'Unsupported payment method selected.'),
        };
    }

    /* Map payment method → human readable gateway name */
    protected function mapGatewayName(string $method): string
    {
        return match ($method) {
            'credit_card', 'debit_card' => 'Stripe',
            'online_banking' => 'Toyyibpay',
            default => 'Unknown',
        };
    }

    /* ============================================================
     *  STRIPE – CARD PAYMENT
     * ============================================================
     */
    protected function redirectToStripeCheckout(Order $order, Request $request)
    {
        $secret = config('services.stripe.secret');

        if (empty($secret)) {
            Log::error('Stripe secret not configured.');
            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Payment gateway is not configured. Please contact support.');
        }

        $stripe   = new \Stripe\StripeClient($secret);
        $currency = strtolower(config('services.stripe.currency', $order->currency ?: 'myr'));

        $amountInCents = (int) round(((float) $order->total_amount) * 100);

        if ($amountInCents < 200) {
            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Card payment minimum is RM2.00. Please add more items or use online banking.');
        }

        $user = $request->user();
        $customerId = $user->stripe_customer_id ?? null;

        try {
            if (!$customerId) {
                $customer = $stripe->customers->create([
                    'email' => $user->email,
                    'name'  => $user->name ?? null,
                ]);
                $customerId = $customer->id;

                try {
                    $user->forceFill(['stripe_customer_id' => $customerId])->save();
                } catch (\Throwable $e) {
                    Log::warning('Unable to save stripe_customer_id on user (proceeding anyway)', [
                        'user_id' => $user->id,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

            $sessionPayload = [
                'mode' => 'payment',
                'customer' => $customerId,
                'payment_method_types' => ['card'],

                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                    'metadata' => [
                        'order_id' => (string) $order->id,
                        'user_id'  => (string) $user->id,
                    ],
                ],

                'line_items' => [[
                    'price_data' => [
                        'currency'     => $currency,
                        'product_data' => [
                            'name' => 'Order #' . ($order->order_number ?? $order->id),
                        ],
                        'unit_amount'  => $amountInCents,
                    ],
                    'quantity' => 1,
                ]],

                'client_reference_id' => (string) $order->id,

                'success_url' => route('payment.stripe.success', ['order' => $order->id])
                    . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('payment.stripe.cancel', ['order' => $order->id]),
            ];

            $session = $stripe->checkout->sessions->create(
                $sessionPayload,
                ['idempotency_key' => 'order_'.$order->id.'_stripe_checkout']
            );

            $order->payment_reference = $session->id ?? null;
            $order->save();

            return redirect()->away($session->url);

        } catch (\Throwable $e) {
            Log::error('Stripe Checkout error', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            $order->markAsFailed('stripe_checkout_create_failed', null, [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Unable to start card payment. Please try again or use another method.');
        }
    }

    /* Stripe success redirect (browser) */
    public function stripeSuccess(Request $request, Order $order)
    {
        Log::info('STRIPE SUCCESS HIT', [
            'order_id' => $order->id,
            'url' => $request->fullUrl(),
            'session_id' => $request->query('session_id'),
        ]);

        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()
                ->route('checkout.success', ['order' => $order->id])
                ->with('info', 'We are verifying your payment. If it does not appear, please contact support.');
        }

        $secret = config('services.stripe.secret');
        if (empty($secret)) {
            Log::error('Stripe secret missing on success verify', ['order_id' => $order->id]);
            return redirect()
                ->route('checkout.success', ['order' => $order->id])
                ->with('info', 'We are verifying your payment. If it does not appear, please contact support.');
        }

        try {
            $stripe = new \Stripe\StripeClient($secret);

            $session = $stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['payment_intent'],
            ]);

            $order->payment_reference = $session->id ?? $order->payment_reference;

            $paymentStatus = $session->payment_status ?? null;
            $pi = $session->payment_intent ?? null;
            $piStatus = is_object($pi) ? ($pi->status ?? null) : null;
            $piId = is_object($pi) ? ($pi->id ?? null) : null;

            if ($paymentStatus === 'paid' || $piStatus === 'succeeded') {
                $order->markAsPaid('stripe', $piId, (array) $session);

                if ($order->user) {
                    $this->clearUserCart($order->user);
                }

                return redirect()->route('checkout.success', ['order' => $order->id]);
            }

            $order->payment_status = Order::PAYMENT_STATUS_PENDING;
            $order->save();

            return redirect()
                ->route('checkout.success', ['order' => $order->id])
                ->with('info', 'We are verifying your payment. If it does not appear, please contact support.');

        } catch (\Throwable $e) {
            Log::error('Stripe verify on success URL failed', [
                'order_id' => $order->id,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('checkout.success', ['order' => $order->id])
                ->with('info', 'We are verifying your payment. If it does not appear, please contact support.');
        }
    }

    /* Stripe cancel redirect (browser) */
    public function stripeCancel(Request $request, Order $order)
    {
        Log::warning('STRIPE CANCEL HIT', [
            'order_id' => $order->id,
            'url' => $request->fullUrl(),
        ]);

        $order->markAsFailed('stripe', null, ['reason' => 'cancelled_by_customer']);

        return redirect()
            ->route('checkout.failed')
            ->with('error', 'You cancelled the card payment. You can try again or choose another method.');
    }

    /* Stripe webhook handler */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (empty($webhookSecret)) {
            Log::error('Stripe webhook secret missing in config/services.php or .env');
            return response('Webhook secret missing', 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook invalid payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        } catch (\Throwable $e) {
            Log::error('Stripe webhook error', ['error' => $e->getMessage()]);
            return response('Webhook error', 500);
        }

        if ($event->type === 'checkout.session.completed') {
            /** @var \Stripe\Checkout\Session $session */
            $session = $event->data->object;

            $orderId = $session->client_reference_id ?? null;
            $sessionId = $session->id ?? null;
            $paymentIntentId = $session->payment_intent ?? null;

            if (!$orderId) {
                Log::warning('Stripe webhook: missing client_reference_id', ['session_id' => $sessionId]);
                return response('Missing order reference', 200);
            }

            $order = \App\Models\Order::find($orderId);

            if (!$order) {
                Log::warning('Stripe webhook: order not found', ['order_id' => $orderId, 'session_id' => $sessionId]);
                return response('Order not found', 200);
            }

            if ($order->payment_status === \App\Models\Order::PAYMENT_STATUS_PAID) {
                return response('Already processed', 200);
            }

            $order->payment_reference = $sessionId ?: $order->payment_reference;
            $order->markAsPaid('stripe', $paymentIntentId, (array)$session);

            if ($order->user) {
                $this->clearUserCart($order->user);
            }

            Log::info('Stripe webhook: order marked as paid', [
                'order_id' => $order->id,
                'session_id' => $sessionId,
                'payment_intent' => $paymentIntentId,
            ]);
        }

        return response('OK', 200);
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
        $returnUrl = route('payment.toyyibpay.return', ['order' => $order->id]);

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

            $billCode = $data[0]['BillCode'] ?? null;

            if (!$billCode) {
                Log::error('Toyyibpay BillCode missing in response', ['response' => $data]);
                $order->markAsFailed('Toyyibpay response invalid');

                return redirect()
                    ->route('checkout.failed')
                    ->with('error', 'Unable to start FPX payment. Please try again.');
            }

            $order->payment_reference = $billCode;
            $order->save();

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

    /* Toyyibpay callback (server-to-server) */
    public function toyyibpayCallback(Request $request)
    {
        Log::info('Toyyibpay callback received', $request->all());

        $refNo  = $request->input('refno');
        $status = $request->input('status');

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

            if ($order->user) {
                $this->clearUserCart($order->user);
            }
        } else {
            $order->markAsFailed('Toyyibpay callback failed/cancelled');
        }

        return response('OK', 200);
    }

    public function toyyibpayReturn(Request $request, Order $order)
    {
        $statusId = $request->input('status_id');
        $status   = $request->input('status');
        $billCode = $request->input('billcode') ?? $request->input('refno');

        if ($billCode && $order->payment_reference && $billCode !== $order->payment_reference) {
            $order->markAsFailed('Toyyibpay return mismatch');
            return redirect()->route('checkout.failed')->with('error', 'Payment verification failed. Please try again.');
        }

        $isSuccess =
            ((string)$status === '1') ||
            ((string)$statusId === '1');

        if ($isSuccess) {
            $order->markAsPaid('Toyyibpay return success');

            if ($order->user) {
                $this->clearUserCart($order->user);
            }

            return redirect()->route('checkout.success', ['order' => $order->id]);
        }

        $order->markAsFailed('Toyyibpay cancelled/failed on return');
        return redirect()->route('checkout.failed')->with('error', 'Payment was cancelled or failed.');
    }

    /* ============================================================
     *  GENERIC SUCCESS / CANCEL PAGES (OPTIONAL)
     * ============================================================
     */

    public function success()
    {
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

    /* Generic webhook receiver */
    public function webhook(Request $request, string $gateway)
    {
        Log::info("Payment webhook received from {$gateway}", $request->all());

        return response('OK', 200);
    }

    private function clearUserCart($user): void
    {
        $cart = Cart::where('user_id', $user->id)->first();
        if ($cart) {
            if (method_exists($cart, 'items')) {
                $cart->items()->delete();
            } else {
                $cart->cartItems()->delete();
            }
        }

        session()->forget('buy_now_order');

        session()->forget('cart_count');
    }
}