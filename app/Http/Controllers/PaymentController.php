<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\Ipay88Service;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        // This is called by your Place Order button (AJAX or normal POST)
        // It should receive:
        //  - payment_method: online_banking | credit_card
        //  - online_banking_bank (e.g. "maybank") if online_banking chosen
        //  - selected_address, cart info, etc.

        $user = $request->user();

        $request->validate([
            'payment_method'     => 'required|in:online_banking,credit_card',
            'selected_address'   => 'required|integer',
            // add your own validations...
        ]);

        // 1. Determine PaymentId based on user choice
        $paymentMethod = $request->input('payment_method');
        $paymentId     = null;
        $paymentDesc   = '';

        if ($paymentMethod === 'online_banking') {
            $bankKey = $request->input('online_banking_bank'); // e.g. "maybank"
            if (!$bankKey) {
                return back()->withErrors(['payment_method' => 'Please choose a bank.']);
            }

            $paymentId   = config("ipay88.payment_ids.$bankKey");
            $paymentDesc = 'Online Banking - ' . ucfirst(str_replace('_', ' ', $bankKey));
        } else { // credit / debit card
            $paymentId   = config("ipay88.payment_ids.credit_card");
            $paymentDesc = 'Credit / Debit Card';
        }

        if (!$paymentId) {
            return back()->withErrors(['payment_method' => 'Payment method is not configured.']);
        }

        // 2. Create Order in your DB (simplified example)
        // Replace with your real cart total and order logic
        $amount    = $request->input('amount') ?? 0; // you should compute from cart
        $amountStr = Ipay88Service::formatAmount($amount);
        $currency  = config('ipay88.currency');

        $order = Order::create([
            'user_id'        => $user->id,
            'total_amount'   => $amount,
            'currency'       => $currency,
            'status'         => 'pending',
            'payment_method' => $paymentMethod,
            'payment_desc'   => $paymentDesc,
            // other fields (address, cart snapshot, etc.)
        ]);

        // 3. Prepare iPay88 parameters
        $merchantCode = config('ipay88.merchant_code');
        $refNo        = $order->id; // must be unique
        $prodDesc     = 'Order #' . $order->id;
        $userName     = $user->name;
        $userEmail    = $user->email;
        $userContact  = $user->phone ?? '';

        $responseUrl  = route('payment.ipay88.response');
        $backendUrl   = route('payment.ipay88.backend');

        $signature = Ipay88Service::signatureRequest($refNo, $amountStr, $currency);

        // 4. Redirect to a view that auto-posts to iPay88
        return view('payments.ipay88_redirect', [
            'paymentUrl'   => config('ipay88.payment_url'),
            'params'       => [
                'MerchantCode' => $merchantCode,
                'PaymentId'    => $paymentId,
                'RefNo'        => $refNo,
                'Amount'       => $amountStr,
                'Currency'     => $currency,
                'ProdDesc'     => $prodDesc,
                'UserName'     => $userName,
                'UserEmail'    => $userEmail,
                'UserContact'  => $userContact,
                'Remark'       => '',
                'Lang'         => 'UTF-8',
                'Signature'    => $signature,
                'ResponseURL'  => $responseUrl,
                'BackendURL'   => $backendUrl,
            ],
        ]);
    }

    public function ipay88Redirect()
    {
        // Not used in this approach (we redirect directly to the view above),
        // but you can keep this if you prefer a separate endpoint.
    }

    public function ipay88Response(Request $request)
    {
        // User browser is redirected back here from iPay88 (ResponseURL)
        $data = $request->all();

        // Verify signature
        $expected = Ipay88Service::signatureResponse($data);
        if (($data['Signature'] ?? '') !== $expected) {
            // Signature mismatch – do not trust this response
            // You might want to log and show generic error
            return redirect()->route('payment.failed')->with('error', 'Payment verification failed.');
        }

        $refNo  = $data['RefNo'] ?? null;
        $status = $data['Status'] ?? 0;

        $order = Order::find($refNo);
        if (!$order) {
            return redirect()->route('payment.failed')->with('error', 'Order not found.');
        }

        if ($status == 1) {
            $order->status = 'paid';
        } else {
            $order->status = 'failed';
        }

        $order->save();

        if ($status == 1) {
            return redirect()->route('checkout.success', ['order' => $order->id]);
        }

        return redirect()->route('checkout.failed');
    }

    public function ipay88Backend(Request $request)
    {
        // BackendURL callback (server-to-server, no browser)
        $data = $request->all();

        $expected = Ipay88Service::signatureResponse($data);
        if (($data['Signature'] ?? '') !== $expected) {
            return response('INVALID SIGNATURE', 400);
        }

        $refNo  = $data['RefNo'] ?? null;
        $status = $data['Status'] ?? 0;

        $order = Order::find($refNo);
        if ($order) {
            if ($status == 1) {
                $order->status = 'paid';
            } else {
                $order->status = 'failed';
            }
            $order->save();
        }

        // iPay88 expects some response; "RECEIVEOK" is common
        return response('RECEIVEOK', 200);
    }
}
