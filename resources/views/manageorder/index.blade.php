@extends('admin.adminbase')
@section('title', 'Manage Orders')

@section('content')

<style> 
/* Minimalist Color Theme */
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-gold: #DAA112;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
    --success: #28a745;
    --warning: #ffc107;
    --danger: #dc3545;
    --info: #17a2b8;
}

/* Orders Container */
.orders-container {
    padding: 2rem;
    background: var(--light-bone);
    min-height: 100vh;
}

/* Header Section */
.orders-header {
    margin-bottom: 2rem;
}

.orders-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.5rem 0;
}

.orders-subtitle {
    font-size: 1.1rem;
    color: var(--light-text);
    margin: 0;
}


/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 100px));
    gap: 1rem;
    margin-bottom: 2rem;
    width: 800px;
}

.stat-card {
    background: var(--white);
    padding: 0.5rem 0.5rem;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
    display: flex;
    align-items: center;
    gap: 0.1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin: 0;
    width: 180px;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(26, 36, 18, 0.12);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: var(--light-bone);
    color: var(--primary-green);
}

.stat-icon.total {
    background: rgba(40, 167, 69, 0.1);
    color: var(--info);
}

.stat-icon.pending {
    background: rgba(218, 161, 18, 0.1);
    color: var(--accent-gold);
}

.stat-icon.processing {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
}

.stat-icon.delivered {
    background: rgba(255, 193, 2, 0.1);
    color: var(--success)
}

.stat-content .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.25rem 0;
    line-height: 1;
}

.stat-content .stat-label {
    color: var(--light-text);
    margin: 0;
    font-size: 0.7rem;
}

/* Table Container */
.orders-table-container {
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid var(--border-light);
}

.table-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin: 0;
}

.table-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box input {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    font-size: 0.9rem;
    width: 250px;
    transition: all 0.3s ease;
    background: var(--light-bone);
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(45, 74, 53, 0.1);
}

.search-box i {
    position: absolute;
    left: 1rem;
    color: var(--light-text);
}

.filter-btn {
    padding: 0.75rem 1.5rem;
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    color: var(--primary-dark);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-btn:hover {
    background: var(--light-bone);
    border-color: var(--primary-green);
    color: var(--primary-green);
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th {
    background: var(--light-bone);
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
    color: var(--primary-dark);
    border-bottom: 1px solid var(--border-light);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.orders-table td {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
}

.order-row {
    transition: all 0.3s ease;
}

.order-row:hover {
    background: var(--light-bone);
}

.order-id {
    font-weight: 600;
    color: var(--primary-green);
    font-family: 'Courier New', monospace;
}

.customer-name {
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 0.25rem;
}

.customer-email {
    font-size: 0.85rem;
    color: var(--light-text);
}

.order-date {
    color: var(--primary-dark);
    font-weight: 500;
}

.order-time {
    font-size: 0.8rem;
    color: var(--light-text);
    margin-top: 0.25rem;
}

.order-amount {
    font-weight: 700;
    color: var(--primary-dark);
    font-size: 1.1rem;
}

/* Status Badges */
.status-badge, .payment-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1ecf1; color: #0c5460; }
.status-processing { background: #cce7ff; color: #004085; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: var(--success); color: var(--white); }
.status-cancelled { background: #f8d7da; color: #721c24; }

.payment-pending { background: #fff3cd; color: #856404; }
.payment-paid { background: #d4edda; color: #155724; }
.payment-failed { background: #f8d7da; color: #721c24; }

/* Action Buttons */
.order-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.view-btn {
    background: var(--light-bone);
    color: var(--primary-green);
}

.view-btn:hover {
    background: var(--primary-green);
    color: var(--white);
}

.edit-btn {
    background: var(--light-bone);
    color: var(--accent-gold);
}

.edit-btn:hover {
    background: var(--accent-gold);
    color: var(--white);
}

/* No Orders State */
.no-orders {
    text-align: center;
    padding: 4rem 2rem;
}

.no-orders-content i {
    font-size: 4rem;
    color: var(--border-light);
    margin-bottom: 1rem;
}

.no-orders-content h3 {
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
}

.no-orders-content p {
    color: var(--light-text);
}

/* Pagination */
.pagination-container {
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: center;
}

.pagination-container .pagination {
    display: flex;
    gap: 0.5rem;
}

.pagination-container .page-link {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    color: var(--primary-dark);
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination-container .page-link:hover {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
}

.pagination-container .page-item.active .page-link {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: var(--white);
}

/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: var(--white);
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--primary-dark);
    font-size: 1.25rem;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--light-text);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.close-modal:hover {
    background: var(--light-bone);
    color: var(--primary-dark);
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.filter-group {
    margin-bottom: 1.5rem;
}

.filter-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--primary-dark);
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(45, 74, 53, 0.1);
}

.filter-group input {
    margin-bottom: 0.5rem;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-green);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--light-bone);
    color: var(--primary-dark);
    border: 1px solid var(--border-light);
}

.btn-secondary:hover {
    background: var(--border-light);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .orders-container {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .table-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .table-actions {
        justify-content: space-between;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .search-box input {
        width: 200px;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 1rem;
    }
    
    .order-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .orders-title {
        font-size: 2rem;
    }
    
    .table-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .search-box input {
        width: 100%;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }

    .stat-icon.shipped {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success);
    }
}
</style>

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
