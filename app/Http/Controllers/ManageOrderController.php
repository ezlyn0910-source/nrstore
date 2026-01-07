<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; 

class ManageOrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with([
            'user', 
            'shippingAddress', 
            'billingAddress', 
            'orderItems.product',
        ])
        ->where('payment_status', '!=', Order::PAYMENT_STATUS_PENDING);
        
        // Filter by status if provided
        if ($request->filled('status') && in_array($request->status, [
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_REFUNDED,
        ], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('orderItems', function ($q2) use ($search) {
                    $q2->where('product_name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by amount range
        if ($request->has('amount_from') && $request->amount_from) {
            $query->where('total_amount', '>=', floatval($request->amount_from));
        }
        
        if ($request->has('amount_to') && $request->amount_to) {
            $query->where('total_amount', '<=', floatval($request->amount_to));
        }
        
        $orders = $query->latest()->paginate(10);

        $stats = [
            'total'      => Order::where('status', '!=', Order::STATUS_PENDING)->count(),
            'paid'       => Order::where('payment_status', Order::PAYMENT_STATUS_PAID)->count(),
            'processing' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'shipped'    => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'delivered'  => Order::where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled'  => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'refunded'   => Order::where('status', Order::STATUS_REFUNDED)->count(),
        ];

        return view('manageorder.index', compact('orders', 'stats'));
    }

    /** Display the specified order. **/
    public function show(Order $order): View
    {
        $order->load([
            'user',
            'shippingAddress',
            'billingAddress',
            'orderItems.product',
            'orderItems.variation',
            'statusHistory'
        ]);

        $statusOptions = [
            Order::STATUS_SHIPPED => 'Shipped',
            Order::STATUS_REFUNDED => 'Refunded',
        ];

        return view('manageorder.show', compact('order', 'statusOptions'));
    }

    /** Show the form for editing the order. **/
    public function edit(Order $order): View
    {
        $order->load([
            'user',
            'shippingAddress',
            'billingAddress',
            'orderItems.product',
            'orderItems.variation',
            'statusHistory'
        ]);

        $statusOptions = [
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_SHIPPED => 'Shipped',
            Order::STATUS_CANCELLED => 'Cancelled',
            Order::STATUS_REFUNDED => 'Refunded',
        ];

        return view('manageorder.edit', compact('order', 'statusOptions'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_SHIPPED,
                Order::STATUS_REFUNDED,
            ]),
            'tracking_number' => 'nullable|string|max:255|required_if:status,' . Order::STATUS_SHIPPED,
        ], [
            'tracking_number.required_if' => 'Tracking number is required when status is set to Shipped.',
        ]);

        try {
            $oldStatus = $order->status;

            if ($request->status === Order::STATUS_SHIPPED && $oldStatus !== Order::STATUS_SHIPPED) {
                $order->loadMissing(['orderItems.product', 'orderItems.variation']);

                foreach ($order->orderItems as $item) {
                    if ($item->variation_id && $item->variation) {
                        if ($item->variation->stock < $item->quantity) {
                            return back()
                                ->with('error', "Insufficient stock for {$item->product_name}. Available: {$item->variation->stock}, Needed: {$item->quantity}")
                                ->withInput();
                        }
                    } elseif ($item->product) {
                        if ($item->product->stock_quantity < $item->quantity) {
                            return back()
                                ->with('error', "Insufficient stock for {$item->product_name}. Available: {$item->product->stock_quantity}, Needed: {$item->quantity}")
                                ->withInput();
                        }
                    }
                }
            }

            $updated = $order->updateStatus(
                $request->status,
                'Status updated by admin'
            );

            if (! $updated) {
                return back()
                    ->with('error', 'Order status could not be updated due to business rules.')
                    ->withInput();
            }

            if ($request->status === Order::STATUS_SHIPPED) {
                $order->setTrackingNumber($request->tracking_number);
            }

            return redirect()
                ->route('admin.manageorder.show', $order)
                ->with('success', "Order #{$order->order_number} updated successfully.");

        } catch (\Throwable $e) {
            \Log::error('Admin order update failed', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Failed to update order. Please check logs.')
                ->withInput();
        }
    }

    /**
     * Update only the order status (quick update)
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_CANCELLED,
                Order::STATUS_REFUNDED,
            ])
        ]);

        $oldStatus = $order->status;

        // If status is changed to shipped and no tracking number exists, redirect to edit page
        if ($request->status === Order::STATUS_SHIPPED && empty($order->tracking_number)) {
            return redirect()->route('admin.manageorder.edit', $order)
                ->with('info', 'Please enter tracking number for shipped order.');
        }

        $success = $order->updateStatus($request->status, "Quick status update via admin panel");
        
        if (!$success) {
            return redirect()->back()->with('error', 'Failed to update order status. Please check order state.');
        }

        return redirect()->back()->with('success', 
            "Order status updated from " . ucfirst($oldStatus) . " to " . ucfirst($request->status)
        );
    }

    /**
     * Update tracking number
     */
    public function updateTracking(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100'
        ]);

        try {
            $order->setTrackingNumber($validated['tracking_number']);
            
            return redirect()->back()->with('success', 'Tracking number updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update tracking number: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $orderId = $order->id;
        $order->delete();
        
        return redirect()->route('admin.manageorder.index')
            ->with('success', "Order #{$orderId} deleted successfully");
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:delete,update_status',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $orderIds = $request->order_ids;
        $action = $request->action;

        try {
            switch ($action) {
                case 'delete':
                    Order::whereIn('id', $orderIds)->delete();
                    $message = count($orderIds) . ' order(s) deleted successfully';
                    break;
                    
                case 'update_status':
                    $request->validate([
                        'bulk_status' => 'required|in:' . implode(',', [
                            Order::STATUS_PAID,
                            Order::STATUS_PROCESSING,
                            Order::STATUS_SHIPPED,
                            Order::STATUS_CANCELLED,
                            Order::STATUS_REFUNDED,
                        ])
                    ]);
                    
                    $orders = Order::whereIn('id', $orderIds)->get();
                    foreach ($orders as $order) {
                        // Skip shipped orders without tracking
                        if ($request->bulk_status === Order::STATUS_SHIPPED && empty($order->tracking_number)) {
                            continue;
                        }
                        $order->updateStatus($request->bulk_status, "Bulk status update");
                    }
                    $message = count($orderIds) . ' order(s) status updated to ' . ucfirst($request->bulk_status);
                    break;
                    
                default:
                    return redirect()->back()->with('error', 'Invalid action');
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }
}