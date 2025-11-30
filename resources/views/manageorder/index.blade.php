@extends('admin.adminbase')
@section('title', 'Manage Orders')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_order/index.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="orders-container">
    <!-- Header Section -->
    <div class="orders-header">
        <h1 class="orders-title">Order Management</h1>
        <p class="orders-subtitle">Manage and track customer orders</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total'] }}</h3>
                <p class="stat-label">Total Orders</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon paid">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['paid'] }}</h3>
                <p class="stat-label">Paid</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon processing">
                <i class="fas fa-cog"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['processing'] }}</h3>
                <p class="stat-label">Processing</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon delivered">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['shipped'] }}</h3>
                <p class="stat-label">Shipped</p>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-table-container">
        <div class="table-header">
            <h2 class="table-title">Recent Orders</h2>
            <div class="table-actions">
                <div class="search-box">
                    <input type="text" id="searchOrders" placeholder="Search orders...">
                    <i class="fas fa-search"></i>
                </div>
                <button class="filter-btn" id="filterBtn">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="order-row" data-status="{{ $order->status }}">
                        <td class="order-id">#{{ $order->id }}</td>
                        <td class="customer-info">
                            <div class="customer-name">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="customer-email">{{ $order->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="order-date">
                            {{ $order->created_at->format('M d, Y') }}
                            <div class="order-time">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="order-amount">RM {{ number_format($order->total_amount, 2) }}</td>
                        <td class="order-status">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="order-actions">
                            <a href="{{ route('admin.manageorder.show', $order) }}" class="action-btn view-btn" title="View Order">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.manageorder.edit', $order) }}" class="action-btn edit-btn" title="Edit Order">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="no-orders">
                            <div class="no-orders-content">
                                <i class="fas fa-box-open"></i>
                                <h3>No Orders Found</h3>
                                <p>There are no orders to display at the moment.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="pagination-container">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal-overlay" id="filterModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Orders</h3>
            <button class="close-modal" id="closeFilterModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="filter-group">
                <label>Status</label>
                <select id="statusFilter">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Date Range</label>
                <input type="date" id="dateFrom" placeholder="From Date">
                <input type="date" id="dateTo" placeholder="To Date">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" id="resetFilters">Reset</button>
            <button class="btn-primary" id="applyFilters">Apply Filters</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter Modal
    const filterBtn = document.getElementById('filterBtn');
    const filterModal = document.getElementById('filterModal');
    const closeFilterModal = document.getElementById('closeFilterModal');
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');

    filterBtn.addEventListener('click', () => {
        filterModal.style.display = 'flex';
    });

    closeFilterModal.addEventListener('click', () => {
        filterModal.style.display = 'none';
    });

    applyFilters.addEventListener('click', () => {
        // Implement filter logic here
        filterModal.style.display = 'none';
    });

    resetFilters.addEventListener('click', () => {
        document.getElementById('statusFilter').value = '';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
    });

    // Search functionality
    const searchInput = document.getElementById('searchOrders');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.order-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === filterModal) {
            filterModal.style.display = 'none';
        }
    });
});
</script>
@endsection
