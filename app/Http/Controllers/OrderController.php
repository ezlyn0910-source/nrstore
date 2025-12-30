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
        if (Auth::check()) {
            $orders = Order::with([
                'orderItems.product',
                'orderItems.variation',
                'shippingAddress',
            ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

            return view('orders.index', compact('orders'));
        } else {
            $orders = collect();
            $isGuest = true;
            return view('orders.index', compact('orders', 'isGuest'));
        }
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
            'user',
            'statusHistory',
        ])
        ->where('user_id', Auth::id())
        ->where('id', $orderId)
        ->firstOrFail();

        return response()->json([
            'id' => $order->id,
            'order_number' => $order->order_number,
            'order_date' => $order->created_at->format('d M Y'),
            'status' => ucfirst($order->status),
            'payment_status' => ucfirst($order->payment_status),
            'payment_method' => $order->payment_method,
            'tracking_number' => $order->tracking_number,

            'customer_name' => $order->user->name,
            'customer_email' => $order->user->email,

            'shipping_address' => $order->shippingAddress
                ? $order->shippingAddress->first_name . ' ' .
                $order->shippingAddress->last_name . "\n" .
                $order->shippingAddress->phone . "\n" .
                $order->shippingAddress->formatted_address
                : '-',

            'billing_address' => $order->shippingAddress
                ? $order->shippingAddress->first_name . ' ' .
                $order->shippingAddress->last_name . "\n" .
                $order->shippingAddress->phone . "\n" .
                $order->shippingAddress->formatted_address
                : '-',

            'items' => $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->product_name ?? $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => (float) $item->price
                ];
            }),

            'status_history' => $order->statusHistory->map(function ($history) {
                return [
                    'status' => $history->status_label,
                    'notes' => $history->notes,
                    'date' => $history->created_at->format('d M Y, h:i A')
                ];
            }),

            'shipping_cost' => (float) ($order->shipping_cost ?? 0),
            'discount_amount' => (float) ($order->discount_amount ?? 0),
            'total_amount' => (float) $order->total_amount,
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
                'Order cancelled by customer'
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