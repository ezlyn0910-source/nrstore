<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $isGuest = !Auth::check();

        if (Auth::check()) {
           $orders = Order::with([
                'orderItems.product',
                'orderItems.variation',
                'shippingAddress',
            ])
            ->where('user_id', Auth::id())

            ->where('payment_status', Order::PAYMENT_STATUS_PAID)

            ->orderBy('created_at', 'desc')
            ->paginate(10);

        } else {
            $orders = Order::whereRaw('1=0')->paginate(10);
        }

        return view('orders.index', compact('orders', 'isGuest'));
    }

    public function show($orderId)
    {
        $order = Order::with([
            'orderItems.product',
            'orderItems.variation',
            'shippingAddress',
            'billingAddress',
            'user'
        ])
        ->where('user_id', Auth::id())
        ->where('id', $orderId)
        ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function details($orderId)
    {
        $order = Order::with([
            'orderItems.product',
            'orderItems.variation',
            'shippingAddress',
            'billingAddress',
        ])
        ->where('user_id', auth()->id())
        ->where('id', $orderId)
        ->firstOrFail();

        $deliveryMethod =
            $order->delivery_method ??
            $order->shipping_method ??
            $order->shipping_type ??
            null;

        $user = auth()->user();
        $defaultAddressText = null;

        if ($user && method_exists($user, 'addresses')) {
            $da = $user->addresses()->where('is_default', 1)->first();
            if ($da) {
                $defaultAddressText = $da->formatted_address ?? implode(', ', array_filter([
                    $da->address_line_1 ?? null,
                    $da->address_line_2 ?? null,
                    $da->city ?? null,
                    $da->state ?? null,
                    $da->postal_code ?? null,
                    $da->country ?? null,
                ]));
            }
        }

        $isSelfPickup =
            in_array(strtolower((string)($deliveryMethod ?? '')), ['self_pickup','self-pickup','pickup','collect','store_pickup','self_collection'], true)
            || is_null($order->shipping_address_id);

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'is_self_pickup' => $isSelfPickup,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'tracking_number' => $order->tracking_number,
            'order_date' => optional($order->created_at)->format('d M Y'),
            'created_at' => $order->created_at,

            // 🔑 IMPORTANT
            'delivery_method' => $deliveryMethod,
            'user_default_address' => $defaultAddressText,

            // Addresses (keep your current logic if already exists)
            'shipping_address' => $order->shipping_address_text ?? null,
            'billing_address'  => $order->billing_address_text ?? null,

            'shipping_cost' => (float) ($order->shipping_cost ?? 0),
            'discount_amount' => (float) ($order->discount_amount ?? 0),
            'total_amount' => (float) ($order->total_amount ?? 0),

            'customer_name' => $user?->name,
            'customer_email' => $user?->email,

            'items' => $order->orderItems->map(fn ($item) => [
                'name' => $item->product_name ?? ($item->product->name ?? 'Item'),
                'quantity' => (int) $item->quantity,
                'price' => (float) $item->price,
            ]),
        ]);
    }


    public function cancel(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())
                    ->where('id', $orderId)
                    ->firstOrFail();

        if (!in_array($order->status, [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage'
            ], 422);
        }

        try {
            $order->updateStatus(
                Order::STATUS_CANCELLED,
                'Cancellation requested by customer'
            );

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadInvoicePdf($orderId)
    {
        $order = Order::with([
            'orderItems.product',
            'orderItems.variation',
            'shippingAddress',
            'billingAddress',
            'user',
        ])
        ->where('id', $orderId)
        ->where('user_id', Auth::id())
        ->firstOrFail(); // if not yours, it becomes 404 (not 403)

        $pdf = Pdf::loadView('orders.invoice-pdf', [
            'order' => $order,
        ])->setPaper('A4', 'portrait');

        $fileName = 'invoice-' . ($order->order_number ?? $order->id) . '.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $pdf->output();
        exit;
    }

}