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

/* Status Filter Navigation */
.status-filter-nav {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
    flex-wrap: wrap;
}

.status-filter-item {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    background: var(--light-bone);
    color: var(--light-text);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
}

.status-filter-item:hover {
    background: rgba(45, 74, 53, 0.05);
    color: var(--primary-green);
    transform: translateY(-2px);
}

.status-filter-item.active {
    background: var(--primary-green);
    color: var(--white);
}

.status-filter-item.active:hover {
    background: var(--primary-dark);
    color: var(--white);
}

.status-badge-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
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
.status-paid { background: #d4edda; color: #155724; }

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
    
    .status-filter-nav {
        overflow-x: auto;
        flex-wrap: nowrap;
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

/* Modal Overlay Fix */
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
    overflow-y: auto;
    padding: 1rem;
}

.modal-content {
    background: var(--white);
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    max-height: 90vh;
    overflow-y: auto;
    animation: modalFadeIn 0.3s ease;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Date and number input styling */
.date-input, 
input[type="number"] {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.date-input:focus,
input[type="number"]:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(45, 74, 53, 0.1);
}

/* Pagination */
.pagination-wrapper {
    padding: 1.5rem;
    display: flex;
    justify-content: center;
    background: var(--white);
    border-top: 1px solid var(--border-light);
}

.pagination {
    display: flex;
    gap: 0.25rem;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item .page-link {
    padding: 0.5rem 0.9rem;
    border-radius: 8px;
    border: 1px solid var(--border-light);
    color: var(--primary-dark);
    background: var(--light-bone);
    font-weight: 500;
    transition: all 0.25s ease;
}

.page-item .page-link:hover {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
}

.page-item.active .page-link {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: var(--white);
}

.page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
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
            <div class="stat-icon total"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total'] }}</h3>
                <p class="stat-label">Total Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon paid"><i class="fas fa-credit-card"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['paid'] }}</h3>
                <p class="stat-label">Paid</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon processing"><i class="fas fa-cog"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['processing'] }}</h3>
                <p class="stat-label">Processing</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon delivered"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['shipped'] }}</h3>
                <p class="stat-label">Shipped</p>
            </div>
        </div>
    </div>

    <!-- Status Filter Navigation -->
    <div class="status-filter-nav">
        @php
            $filters = request()->all(); // Preserve all filters
        @endphp
        @foreach(['' => 'All Orders', 'paid' => 'Paid', 'processing' => 'Processing', 'shipped' => 'Shipped', 'cancelled' => 'Cancelled'] as $key => $label)
            @php
                $params = array_merge($filters, ['status' => $key ?: null]);
            @endphp
            <a href="{{ route('admin.manageorder.index', $params) }}"
               class="status-filter-item {{ request('status') == $key ? 'active' : (!$key && !request('status') ? 'active' : '') }}">
                <i class="fas fa-shopping-bag"></i>
                {{ $label }}
                <span class="status-badge-count">{{ $stats[$key ?: 'total'] }}</span>
            </a>
        @endforeach
    </div>

    <!-- Orders Table & Filters Form -->
    <div class="orders-table-container">
        <div class="table-header">
            <h2 class="table-title">
                @if(request('status'))
                    {{ ucfirst(request('status')) }} Orders
                @else
                    Recent Orders
                @endif
                <span class="orders-count" style="font-size:1rem; color:var(--light-text); margin-left:0.5rem;">
                    ({{ $orders->total() }} orders)
                </span>
            </h2>
        </div>

        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Products</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="order-row">
                        <td class="order-id">#{{ $order->id }}</td>
                        <td class="order-products">
                            @foreach($order->orderItems as $item)
                                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                                    <img src="{{ $item->product->main_image_url ?? '/placeholder.png' }}" 
                                         alt="{{ $item->product_name ?? $item->product->name }}" 
                                         style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                    <div>
                                        <div style="font-weight:600;">{{ $item->product_name ?? $item->product->name }}</div>
                                        @if($item->variation_name)
                                            <div style="font-size:0.8rem;color:#6b7c72;">{{ $item->variation_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td class="customer-info">
                            <div class="customer-name">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="customer-email">{{ $order->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="order-date">
                            {{ $order->created_at->format('M d, Y') }}
                            <div class="order-time">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="order-amount">RM {{ number_format($order->total_amount,2) }}</td>
                        <td class="order-status">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="order-actions">
                            <a href="{{ route('admin.manageorder.show', $order) }}" class="action-btn view-btn" title="View Order"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.manageorder.edit', $order) }}" class="action-btn edit-btn" title="Edit Order"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="no-orders">
                            <div class="no-orders-content">
                                <i class="fas fa-box-open"></i>
                                <h3>No Orders Found</h3>
                                <p>
                                    @if(request('status'))
                                        No {{ request('status') }} orders found.
                                    @else
                                        There are no orders to display.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($orders->hasPages())
<div class="pagination-wrapper">
    {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>
@endif


@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('filterBtn');
    const filterModal = document.getElementById('filterModal');
    const closeFilterModal = document.getElementById('closeFilterModal');

    filterBtn.addEventListener('click', () => {
        filterModal.style.display = 'flex';
    });

    closeFilterModal.addEventListener('click', () => {
        filterModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === filterModal) filterModal.style.display = 'none';
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && filterModal.style.display === 'flex') {
            filterModal.style.display = 'none';
        }
    });
});
</script>
@endsection
