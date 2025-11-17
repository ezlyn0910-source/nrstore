<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Order;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $users = User::all();
        $query = User::query();

        if ($user->role !== 'admin') {
            abort(403, 'Access denied');
        }

        // Get recent orders (limit to 5) with relationships
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->take(5)
            ->get();

        // Get orders by status for the status navigation
        $status = $request->get('status', 'all');
        
        $ordersQuery = Order::with(['user', 'orderItems.product']);
        
        if ($status !== 'all') {
            if ($status === 'paid') {
                $ordersQuery->where('payment_status', 'paid');
            } else {
                $ordersQuery->where('status', $status);
            }
        }
        
        $ordersByStatus = $ordersQuery->latest()->get();

        // Get counts for status navigation
        $statusCounts = [
            'all' => Order::count(),
            'paid' => Order::paid()->count(),
            'processing' => Order::processing()->count(),
            'shipped' => Order::shipped()->count(),
            'cancelled' => Order::cancelled()->count(),
        ];

        $stats = [
            'total_users'=> User::count(),
            'total_products' => Product::count(),
            'monthly_revenue' => Order::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'uncomplete_orders'=> Order::where('status', '!=','shipped')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'users', 'recentOrders', 'ordersByStatus', 'statusCounts', 'status'));
    }
}