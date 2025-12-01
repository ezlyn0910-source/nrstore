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
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Order::count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
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
            'orderItems.variation'
        ]);

        $statusOptions = [
            'paid' => 'Paid',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'cancelled' => 'Cancelled'
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
            'orderItems.variation'
        ]);

        $statusOptions = [
            'paid' => 'Paid',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'cancelled' => 'Cancelled'
        ];

        return view('manageorder.edit', compact('order', 'statusOptions'));
    }

    /**
     * Update the order status and tracking number.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:paid,processing,shipped,cancelled',
            'tracking_number' => 'nullable|string|max:255|required_if:status,shipped'
        ], [
            'tracking_number.required_if' => 'Tracking number is required when status is set to Shipped.'
        ]);

        $oldStatus = $order->status;
        
        $updateData = [
            'status' => $request->status,
            'updated_at' => now()
        ];
        
        // Handle tracking number for shipped orders
        if ($request->status === 'shipped') {
            if (empty($request->tracking_number)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['tracking_number' => 'Tracking number is required when status is set to Shipped.']);
            }
            
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['shipped_at'] = now();
        } else {
            // Clear tracking number if status is not shipped
            $updateData['tracking_number'] = null;
            $updateData['shipped_at'] = null;
        }

        $order->update($updateData);

        return redirect()->route('admin.manageorder.show', $order)
            ->with('success', 
                "Order status updated from " . ucfirst($oldStatus) . " to " . ucfirst($request->status)
            );
    }

    /**
     * Update only the order status (quick update)
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:paid,processing,shipped,cancelled'
        ]);

        $oldStatus = $order->status;
        
        $updateData = ['status' => $request->status];
        
        // If status is changed to shipped and no tracking number exists, redirect to edit page
        if ($request->status === 'shipped' && empty($order->tracking_number)) {
            return redirect()->route('manageorder.edit', $order)
                ->with('info', 'Please enter tracking number for shipped order.');
        }
        
        // If status is shipped, ensure shipped_at is set
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $updateData['shipped_at'] = now();
        }
        
        // If status is changed from shipped, clear tracking info
        if ($oldStatus === 'shipped' && $request->status !== 'shipped') {
            $updateData['tracking_number'] = null;
            $updateData['shipped_at'] = null;
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 
            "Order status updated from " . ucfirst($oldStatus) . " to " . ucfirst($request->status)
        );
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $orderId = $order->id;
        $order->delete();
        
        return redirect()->route('manageorder.index')
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
                        'bulk_status' => 'required|in:paid,processing,shipped,cancelled'
                    ]);
                    
                    Order::whereIn('id', $orderIds)->update([
                        'status' => $request->bulk_status,
                        'updated_at' => now()
                    ]);
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
