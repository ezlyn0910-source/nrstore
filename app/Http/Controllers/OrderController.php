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

    public function getOrderDetailsPopup(Order $order)
    {
        try {

            if ($order->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Load all necessary relationships
            $order->load([
                'user',
                'shippingAddress',
                'billingAddress',
                'orderItems.product',
                'orderItems.variation',
                'statusHistories' => function($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ]);

            // Calculate subtotal
            $subtotal = $order->orderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });

            // Format response data
            $data = [
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'status' => $order->status,
                    'status_label' => ucfirst($order->status),
                    'payment_status' => $order->payment_status,
                    'payment_status_label' => ucfirst($order->payment_status),
                    'payment_method' => $order->payment_method,
                    'payment_method_label' => $order->payment_method_label ?? ucfirst(str_replace('_', ' ', $order->payment_method)),
                    'tracking_number' => $order->tracking_number,
                    'shipping_cost' => (float) $order->shipping_cost,
                    'tax_amount' => (float) $order->tax_amount,
                    'discount_amount' => (float) $order->discount_amount,
                    'total_amount' => (float) $order->total_amount,
                ],
                'user' => [
                    'name' => $order->user->name ?? 'N/A',
                    'email' => $order->user->email ?? 'N/A',
                    'phone' => $order->user->phone ?? 'N/A',
                ],
                'shipping_address' => $order->shippingAddress ? [
                    'full_name' => $order->shippingAddress->first_name . ' ' . $order->shippingAddress->last_name,
                    'address_line_1' => $order->shippingAddress->address_line_1,
                    'address_line_2' => $order->shippingAddress->address_line_2,
                    'city' => $order->shippingAddress->city,
                    'state' => $order->shippingAddress->state,
                    'postal_code' => $order->shippingAddress->postal_code,
                    'country' => $order->shippingAddress->country,
                    'phone' => $order->shippingAddress->phone,
                ] : null,
                'billing_address' => $order->billingAddress ? [
                    'full_name' => $order->billingAddress->first_name . ' ' . $order->billingAddress->last_name,
                    'address_line_1' => $order->billingAddress->address_line_1,
                    'address_line_2' => $order->billingAddress->address_line_2,
                    'city' => $order->billingAddress->city,
                    'state' => $order->billingAddress->state,
                    'postal_code' => $order->billingAddress->postal_code,
                    'country' => $order->billingAddress->country,
                    'phone' => $order->billingAddress->phone,
                ] : null,
                'items' => $order->orderItems->map(function($item) {
                    return [
                        'product_name' => $item->product_name ?? $item->product->name ?? 'Product',
                        'variation_name' => $item->variation_name ?? ($item->variation ? $item->variation->name : null),
                        'price' => (float) $item->price,
                        'quantity' => (int) $item->quantity,
                        'total' => (float) ($item->price * $item->quantity)
                    ];
                }),
                'status_history' => $order->statusHistories->map(function($history) {
                    return [
                        'status' => $history->status,
                        'notes' => $history->notes,
                        'created_at' => $history->created_at->format('Y-m-d H:i:s'),
                    ];
                })->toArray(),
                'subtotal' => $subtotal,
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            \Log::error('Error in getOrderDetailsPopup: ' . $e->getMessage(), [
                'order_id' => $id ?? null,
                'user_id' => auth()->id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load order details. Please try again.'
            ], 500);
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