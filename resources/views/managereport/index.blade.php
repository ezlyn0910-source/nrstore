@extends('admin.adminbase')

@section('title', 'Report')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_report/index.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="reports-dashboard">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="header-content">
                <h1 class="page-title">Manage Report</h1>
                <p class="page-subtitle">View and manage your reports</p>
            </div>
            <div class="export-buttons">
                <a href="{{ route('admin.managereport.export', ['type' => 'excel']) }}" class="btn-export excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.managereport.export', ['type' => 'pdf']) }}" class="btn-export pdf">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="date-range-filter">
            <form method="GET" action="{{ route('admin.managereport.index') }}" class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_range">Date Range</label>
                        <select name="date_range" id="date_range" class="form-control" onchange="this.form.submit()">
                            <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $dateRange == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="week" {{ $dateRange == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $dateRange == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="quarter" {{ $dateRange == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                            <option value="year" {{ $dateRange == 'year' ? 'selected' : '' }}>This Year</option>
                            <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                               value="{{ $startDate->format('Y-m-d') }}" 
                               {{ $dateRange != 'custom' ? 'disabled' : '' }}>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                               value="{{ $endDate->format('Y-m-d') }}"
                               {{ $dateRange != 'custom' ? 'disabled' : '' }}>
                    </div>
                </div>
                @if($dateRange == 'custom')
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
                @endif
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card sales">
                <div class="card-value">RM {{ number_format($reports['summary']['total_revenue'] ?? 0, 2) }}</div>
                <div class="card-label">Total Revenue</div>
            </div>
            <div class="summary-card orders">
                <div class="card-value">{{ $reports['summary']['total_orders'] ?? 0 }}</div>
                <div class="card-label">Total Orders</div>
            </div>
            <div class="summary-card users">
                <div class="card-value">{{ $reports['summary']['total_users'] ?? 0 }}</div>
                <div class="card-label">New Users</div>
            </div>
            <div class="summary-card bids">
                <div class="card-value">{{ $reports['summary']['total_bids'] ?? 0 }}</div>
                <div class="card-label">Total Bids</div>
            </div>
        </div>

        <!-- Sales Report Section -->
        <div class="report-section">
            <h4>Sales Report</h4>
            
            <!-- Sales Metrics -->
            <div class="metrics-grid">
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['sales']['summary']->total_orders ?? 0 }}</div>
                    <div class="metric-label">Total Orders</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">RM {{ number_format($reports['sales']['summary']->total_revenue ?? 0, 2) }}</div>
                    <div class="metric-label">Total Revenue</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">RM {{ number_format($reports['sales']['summary']->average_order_value ?? 0, 2) }}</div>
                    <div class="metric-label">Average Order Value</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['sales']['summary']->completed_orders ?? 0 }}</div>
                    <div class="metric-label">Completed Orders</div>
                </div>
            </div>

            <!-- Payment Method Breakdown -->
            <h4 class="mt-4 mb-3">Payment Method Breakdown</h4>
            <div class="report-table">
                <table>
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports['sales']['payment_methods'] as $payment)
                        <tr>
                            <td>{{ $payment->payment_method ?: 'Unknown' }}</td>
                            <td>{{ $payment->payment_count }}</td>
                            <td>RM {{ number_format($payment->total_revenue, 2) }}</td>
                            <td>{{ $reports['sales']['summary']->total_orders > 0 ? round(($payment->payment_count / $reports['sales']['summary']->total_orders) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Top Selling Products -->
            <h4 class="mt-4 mb-3">Top Selling Products</h4>
            <div class="report-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                            <th>Orders</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports['sales']['top_products'] as $product)
                        <tr>
                            <td>{{ $product->product->name ?? 'N/A' }}</td>
                            <td>{{ $product->total_quantity }}</td>
                            <td>RM {{ number_format($product->total_revenue, 2) }}</td>
                            <td>{{ $product->order_count ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bidding Activity Report -->
        <div class="report-section">
            <h4>Bidding Activity Report</h4>
            
            <!-- Bidding Metrics -->
            <div class="metrics-grid">
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['bidding']['summary']->total_auctions ?? 0 }}</div>
                    <div class="metric-label">Total Auctions</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['bidding']['summary']->total_bids ?? 0 }}</div>
                    <div class="metric-label">Total Bids</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ number_format($reports['bidding']['summary']->average_bids_per_auction ?? 0, 1) }}</div>
                    <div class="metric-label">Avg Bids/Auction</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">RM {{ number_format($reports['bidding']['summary']->total_bid_revenue ?? 0, 2) }}</div>
                    <div class="metric-label">Bid Revenue</div>
                </div>
            </div>

            <!-- Top Bidders -->
            <h4 class="mt-4 mb-3">Top Bidders</h4>
            <div class="report-table">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Bids</th>
                            <th>Highest Bid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports['bidding']['top_bidders'] as $bidder)
                        <tr>
                            <td>{{ $bidder->user->name ?? 'Unknown User' }}</td>
                            <td>{{ $bidder->total_bids }}</td>
                            <td>RM {{ number_format($bidder->highest_bid, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Product Performance Report -->
        <div class="report-section">
            <h4>Product Performance Report</h4>

            <!-- Top Performing Products -->
            <h4 class="mt-4 mb-3">Top Performing Products</h4>
            <div class="report-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Orders</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports['products']['products'] as $product)
                        <tr>
                            <td>{{ $product->product->name ?? 'N/A' }}</td>
                            <td>{{ $product->product->category->name ?? 'N/A' }}</td>
                            <td>{{ $product->total_orders }}</td>
                            <td>{{ $product->total_quantity_sold }}</td>
                            <td>RM {{ number_format($product->total_revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Category Performance -->
            <h4 class="mt-4 mb-3">Category Performance</h4>
            <div class="report-table">
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Products</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports['products']['categories'] as $category)
                        <tr>
                            <td>{{ $category->category_name }}</td>
                            <td>{{ $category->product_count }}</td>
                            <td>{{ $category->total_quantity }}</td>
                            <td>RM {{ number_format($category->total_revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inventory Report -->
        <div class="report-section">
            <h4>Inventory Report</h4>
            
            <!-- Inventory Metrics -->
            <div class="metrics-grid">
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['inventory']['summary']->total_products ?? 0 }}</div>
                    <div class="metric-label">Total Products</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['inventory']['summary']->products_with_variations ?? 0 }}</div>
                    <div class="metric-label">Products with Variations</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['inventory']['variation_stock']->total_variations ?? 0 }}</div>
                    <div class="metric-label">Total Variations</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $reports['inventory']['variation_stock']->in_stock_variations ?? 0 }}</div>
                    <div class="metric-label">In Stock Variations</div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <h4 class="mt-4 mb-3">Low Stock Alert</h4>
            @if($reports['inventory']['low_stock']->count() > 0)
            <div class="report-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports['inventory']['low_stock'] as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->has_variations ? 'Variable' : 'Simple' }}</td>
                            <td>
                                @if($product->has_variations)
                                    {{ $product->variations->sum('stock') }}
                                @else
                                    {{ $product->stock_quantity }}
                                @endif
                            </td>
                            <td><span class="status-badge low-stock">Low Stock</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-success">No low stock products found.</div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRangeSelect = document.getElementById('date_range');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    function toggleCustomDateInputs() {
        const isCustom = dateRangeSelect.value === 'custom';
        startDateInput.disabled = !isCustom;
        endDateInput.disabled = !isCustom;
    }
    
    dateRangeSelect.addEventListener('change', toggleCustomDateInputs);
    toggleCustomDateInputs(); // Initialize on page load
});
</script>
@endsection