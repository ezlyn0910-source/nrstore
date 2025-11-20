<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Bid;
use App\Models\BidBid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManageReportController extends Controller
{
    /**
     * Display the main reports dashboard
     */
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Set date range based on selection
        list($startDate, $endDate) = $this->getDateRange($dateRange, $startDate, $endDate);

        $reports = [
            'sales' => $this->getSalesReport($startDate, $endDate),
            'bidding' => $this->getBiddingReport($startDate, $endDate),
            'products' => $this->getProductPerformanceReport($startDate, $endDate),
            'inventory' => $this->getInventoryReport(),
            'summary' => $this->getSummaryReport($startDate, $endDate)
        ];

        return view('admin.manage_report.index', compact('reports', 'dateRange', 'startDate', 'endDate'));
    }

    /**
     * Get sales report data
     */
    private function getSalesReport($startDate, $endDate)
    {
        $salesData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select([
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('AVG(total_amount) as average_order_value'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as completed_revenue'),
                DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_orders'),
                DB::raw('COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled_orders'),
                'payment_method',
                DB::raw('COUNT(*) as payment_count')
            ])
            ->groupBy('payment_method')
            ->get();

        // Daily sales trend
        $dailyTrend = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as daily_revenue'),
                DB::raw('COUNT(*) as daily_orders')
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top selling products
        $topProducts = OrderItem::whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
            })
            ->select([
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total) as total_revenue')
            ])
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return [
            'summary' => $salesData,
            'daily_trend' => $dailyTrend,
            'top_products' => $topProducts
        ];
    }

    /**
     * Get bidding activity report
     */
    private function getBiddingReport($startDate, $endDate)
    {
        $biddingSummary = Bid::whereBetween('created_at', [$startDate, $endDate])
            ->select([
                DB::raw('COUNT(*) as total_auctions'),
                DB::raw('COUNT(CASE WHEN status = "active" THEN 1 END) as active_auctions'),
                DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_auctions'),
                DB::raw('SUM(bid_count) as total_bids'),
                DB::raw('AVG(bid_count) as average_bids_per_auction'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN winning_bid_amount ELSE 0 END) as total_bid_revenue')
            ])
            ->first();

        $topBidders = BidBid::whereBetween('created_at', [$startDate, $endDate])
            ->select([
                'user_id',
                DB::raw('COUNT(*) as total_bids'),
                DB::raw('MAX(amount) as highest_bid')
            ])
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total_bids')
            ->limit(10)
            ->get();

        $activeAuctions = Bid::active()
            ->with(['product', 'highestBid'])
            ->orderBy('end_time')
            ->get();

        $recentBids = BidBid::with(['bid', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return [
            'summary' => $biddingSummary,
            'top_bidders' => $topBidders,
            'active_auctions' => $activeAuctions,
            'recent_bids' => $recentBids
        ];
    }

    /**
     * Get product performance report
     */
    private function getProductPerformanceReport($startDate, $endDate)
    {
        $productPerformance = Product::with(['category', 'variations'])
            ->withCount(['orderItems as total_quantity_sold' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
                });
            }])
            ->withSum(['orderItems as total_revenue' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
                });
            }], 'total')
            ->withCount(['orderItems as total_orders' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
                });
            }])
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get();

        // Product categories performance
        $categoryPerformance = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->select([
                'categories.name as category_name',
                DB::raw('COUNT(DISTINCT products.id) as product_count'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_revenue')
            ])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        return [
            'products' => $productPerformance,
            'categories' => $categoryPerformance
        ];
    }

    /**
     * Get inventory report
     */
    private function getInventoryReport()
    {
        $inventorySummary = Product::select([
                DB::raw('COUNT(*) as total_products'),
                DB::raw('COUNT(CASE WHEN has_variations = true THEN 1 END) as products_with_variations'),
                DB::raw('COUNT(CASE WHEN is_active = true THEN 1 END) as active_products'),
                DB::raw('SUM(CASE WHEN has_variations = false THEN stock_quantity ELSE 0 END) as simple_product_stock')
            ])
            ->first();

        $variationStock = Variation::select([
                DB::raw('SUM(stock) as total_variation_stock'),
                DB::raw('COUNT(*) as total_variations'),
                DB::raw('COUNT(CASE WHEN stock > 0 THEN 1 END) as in_stock_variations'),
                DB::raw('COUNT(CASE WHEN stock = 0 THEN 1 END) as out_of_stock_variations'),
                DB::raw('COUNT(CASE WHEN stock < 10 AND stock > 0 THEN 1 END) as low_stock_variations')
            ])
            ->first();

        $lowStockProducts = Product::where(function($query) {
                $query->where('has_variations', false)
                      ->where('stock_quantity', '<', 10)
                      ->where('stock_quantity', '>', 0);
            })
            ->orWhereHas('variations', function($query) {
                $query->where('stock', '<', 10)
                      ->where('stock', '>', 0);
            })
            ->with(['variations' => function($query) {
                $query->where('stock', '<', 10)
                      ->where('stock', '>', 0);
            }])
            ->get();

        $outOfStockProducts = Product::where(function($query) {
                $query->where('has_variations', false)
                      ->where('stock_quantity', 0);
            })
            ->orWhereHas('variations', function($query) {
                $query->where('stock', 0);
            })
            ->with(['variations' => function($query) {
                $query->where('stock', 0);
            }])
            ->get();

        return [
            'summary' => $inventorySummary,
            'variation_stock' => $variationStock,
            'low_stock' => $lowStockProducts,
            'out_of_stock' => $outOfStockProducts
        ];
    }

    /**
     * Get summary report
     */
    private function getSummaryReport($startDate, $endDate)
    {
        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
        $totalBids = BidBid::whereBetween('created_at', [$startDate, $endDate])->count();

        return [
            'total_users' => $totalUsers,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_bids' => $totalBids
        ];
    }

    /**
     * Calculate date range based on selection
     */
    private function getDateRange($range, $customStart = null, $customEnd = null)
    {
        $endDate = Carbon::now()->endOfDay();
        
        switch ($range) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'quarter':
                $startDate = Carbon::now()->startOfQuarter();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                break;
            case 'custom':
                $startDate = $customStart ? Carbon::parse($customStart)->startOfDay() : Carbon::now()->startOfMonth();
                $endDate = $customEnd ? Carbon::parse($customEnd)->endOfDay() : Carbon::now()->endOfDay();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
        }

        return [$startDate, $endDate];
    }

    /**
     * Export reports
     */
    public function export(Request $request, $type)
    {
        $dateRange = $request->input('date_range', 'month');
        list($startDate, $endDate) = $this->getDateRange($dateRange);
        
        // Implementation for Excel/PDF export would go here
        // This would typically use Laravel Excel or similar package
        
        return back()->with('success', 'Export functionality to be implemented');
    }
}