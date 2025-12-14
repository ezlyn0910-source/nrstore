<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ManageOrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(): View
    {
        $orders = Order::with(['user', 'shippingAddress', 'billingAddress'])
            ->where('status', '!=', Order::STATUS_PENDING)
            ->latest()
            ->paginate(10);

        $stats = [
            'paid'       => Order::where('status', Order::STATUS_PAID)->count(),
            'processing' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'shipped'    => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'cancelled'  => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('manageorder.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
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
            Order::STATUS_PAID => 'Paid',
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_SHIPPED => 'Shipped',
            Order::STATUS_CANCELLED => 'Cancelled'
        ];

        return view('manageorder.show', compact('order', 'statusOptions'));
    }

    /**
     * Show the form for editing the order.
     */
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
            Order::STATUS_PAID => 'Paid',
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_SHIPPED => 'Shipped',
            Order::STATUS_CANCELLED => 'Cancelled'
        ];

        return view('manageorder.edit', compact('order', 'statusOptions'));
    }

    /**
     * Update the order status and tracking number.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_CANCELLED
            ]),
            'tracking_number' => 'nullable|string|max:255|required_if:status,' . Order::STATUS_SHIPPED
        ], [
            'tracking_number.required_if' => 'Tracking number is required when status is set to Shipped.'
        ]);

        $oldStatus = $order->status;

        // Use the model's updateStatus method for consistency
        if ($request->status === Order::STATUS_SHIPPED && $request->tracking_number) {
            $order->setTrackingNumber($request->tracking_number);
        }

        $order->updateStatus($request->status, "Status updated via admin panel");

        return redirect()->route('admin.manageorder.show', $order)
            ->with('success', 
                "Order status updated from " . $order->getStatusLabelAttribute() . " to " . ucfirst($request->status)
            );
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
                Order::STATUS_CANCELLED
            ])
        ]);

        $oldStatus = $order->status;

        // If status is changed to shipped and no tracking number exists, redirect to edit page
        if ($request->status === Order::STATUS_SHIPPED && empty($order->tracking_number)) {
            return redirect()->route('admin.manageorder.edit', $order)
                ->with('info', 'Please enter tracking number for shipped order.');
        }

        $order->updateStatus($request->status, "Quick status update via admin panel");

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
                            Order::STATUS_CANCELLED
                        ])
                    ]);
                    
                    $orders = Order::whereIn('id', $orderIds)->get();
                    foreach ($orders as $order) {
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