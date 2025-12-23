<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

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
            'billingAddress'
        ])
        ->where('user_id', Auth::id())
        ->where('id', $orderId)
        ->firstOrFail();

        return view('orders.details', compact('order'));
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

}