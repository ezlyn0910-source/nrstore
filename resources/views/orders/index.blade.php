@extends('layouts.app')

@section('styles')
    @vite(['resources/css/orders.css'])
@endsection

@section('content')
<div class="orders-page">
    <!-- Page Title -->
    <section class="page-title-section">
        <div class="container">
            <div class="page-title-container">
                <h1 class="page-title">Orders</h1>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="main-content-section">
        <div class="container">
            <!-- Two Column Layout -->
            <div class="orders-layout">
                <!-- Left Column - Categories -->
                <div class="categories-column">
                    <div class="status-categories">
                        <button class="status-category active" data-status="all">
                            <span class="status-text">All Orders</span>
                            <span class="status-count">{{ $orders->count() }}</span>
                        </button>
                        <button class="status-category" data-status="pending">
                            <span class="status-text">Pending</span>
                            <span class="status-count">{{ $orders->where('status', 'pending')->count() }}</span>
                        </button>
                        <button class="status-category" data-status="processing">
                            <span class="status-text">Processing</span>
                            <span class="status-count">{{ $orders->where('status', 'processing')->count() }}</span>
                        </button>
                        <button class="status-category" data-status="delivered">
                            <span class="status-text">Delivered</span>
                            <span class="status-count">{{ $orders->where('status', 'delivered')->count() }}</span>
                        </button>
                        <button class="status-category" data-status="cancelled">
                            <span class="status-text">Cancelled</span>
                            <span class="status-count">{{ $orders->where('status', 'cancelled')->count() }}</span>
                        </button>
                    </div>
                </div>

                <!-- Right Column - Orders -->
                <div class="orders-column">
                    <!-- Orders List -->
                    <div class="orders-container">
                        @if($orders->count() > 0)
                            @foreach($orders as $order)
                            <div class="order-card" data-status="{{ $order->status }}">
                                <!-- Order Header -->
                                <div class="order-header">
                                    <div class="order-id-section">
                                        <div class="order-id-label">Order ID</div>
                                        <div class="order-id-value">#{{ $order->order_number }}</div>
                                        <!-- Address placed directly here -->
                                        <div class="shipping-address">
                                            Deliver to: 
                                            @php
                                                $addressParts = [
                                                    $order->shipping_address->address_line_1 ?? 'N/A',
                                                    $order->shipping_address->address_line_2 ?? null,
                                                    $order->shipping_address->city ?? 'N/A',
                                                    $order->shipping_address->state ?? 'N/A',
                                                    $order->shipping_address->postal_code ?? 'N/A',
                                                    $order->shipping_address->country ?? 'N/A'
                                                ];
                                                $addressLine = implode(', ', array_filter($addressParts, function($part) {
                                                    return $part !== null;
                                                }));
                                            @endphp
                                            {{ $addressLine }}
                                        </div>
                                    </div>
                                    <div class="order-status-badge {{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="order-divider"></div>

                                <!-- Order Items -->
                                <div class="order-items">
                                    @foreach($order->items as $item)
                                    <div class="order-item">
                                        <div class="item-image">
                                            <img src="{{ asset('storage/products/orderpage.png') }}" alt="{{ $item->product->name }}">
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name">{{ $item->product->name }}</div>
                                            <div class="item-specs">
                                                @if(isset($item->product->processor) && $item->product->processor)
                                                    {{ $item->product->processor }} | {{ $item->product->ram }} | {{ $item->product->storage }}
                                                @elseif(isset($item->product->specifications) && $item->product->specifications)
                                                    {{ $item->product->specifications }}
                                                @endif
                                            </div>
                                            <div class="item-price">RM{{ number_format($item->price, 2) }}</div>
                                            <div class="item-quantity">Qty: {{ $item->quantity }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Order Footer -->
                                <div class="order-footer">
                                    <div class="order-total">RM{{ number_format($order->total_amount, 2) }}</div>
                                    <button class="details-btn" data-order-id="{{ $order->id }}">
                                        Details
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <div class="empty-icon">📦</div>
                                <h3>No Orders Yet</h3>
                                <p>You haven't placed any orders. Start shopping to see your orders here.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                            </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if($orders->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination">
                            <button class="pagination-btn">Previous</button>
                            <div class="pagination-numbers">
                                @for($i = 1; $i <= 3; $i++)
                                <button class="pagination-number {{ $i == 1 ? 'active' : '' }}">{{ $i }}</button>
                                @endfor
                            </div>
                            <button class="pagination-btn">Next</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Order details will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Order category filtering
    const categoryButtons = document.querySelectorAll('.status-category');
    const orderCards = document.querySelectorAll('.order-card');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const status = this.dataset.status;
            
            // Update active button
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter orders
            orderCards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Order details modal
    const detailsButtons = document.querySelectorAll('.details-btn');
    detailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            loadOrderDetails(orderId);
        });
    });
});

function loadOrderDetails(orderId) {
    fetch(`/orders/${orderId}/details`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('orderDetailsContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading order details:', error);
        });
}
</script>

<style>
/* CSS Variables */
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-gold: #daa112;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
    --grey-bg: #f5f5f5;
    --grey-text: #6b7280;
}

/* Page Title */
.page-title-container {
    text-align: center;
    padding: 2rem 0 1rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0;
}

/* Two Column Layout */
.orders-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Left Column - Categories */
.categories-column {
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: fit-content;
}

.categories-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--dark-text);
}

.status-categories {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.status-category {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    width: 100%;
}

.status-category:hover {
    border-color: var(--primary-green);
}

.status-category.active {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: var(--white);
}

.status-text {
    font-weight: 500;
    font-size: 1rem;
}

.status-count {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: var(--grey-bg);
    border-radius: 50%;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark-text);
}

.status-category.active .status-count {
    background: rgba(255, 255, 255, 0.2);
    color: var(--white);
}

/* Right Column - Orders */
.orders-column {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.orders-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Order Card */
.order-card {
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-light);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-id-section {
    display: flex;
    flex-direction: column;
}

.order-id-label {
    font-size: 0.875rem;
    color: var(--grey-text);
    margin-bottom: 0.25rem;
}

.order-id-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-text);
}

.order-status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: capitalize;
}

.order-status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.order-status-badge.processing {
    background: #dbeafe;
    color: #1e40af;
}

.order-status-badge.shipped {
    background: #d1fae5;
    color: #065f46;
}

.order-status-badge.delivered {
    background: #dcfce7;
    color: #166534;
}

.order-status-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.order-status-badge.returned {
    background: #f3e8ff;
    color: #7c3aed;
}

/* Divider */
.order-divider {
    height: 1px;
    background: var(--border-light);
    margin: 1.5rem 0;
}

/* Shipping Section */
.shipping-section {
    margin-bottom: 1.5rem;
}

.shipping-label {
    font-size: 0.875rem;
    color: var(--grey-text);
    margin-bottom: 0.5rem;
}

.shipping-address {
    font-size: 1rem;
    color: var(--dark-text);
    line-height: 1.5;
}

/* Order Items */
.order-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
}

.item-image {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}

.item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.item-name {
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 0.25rem;
}

.item-specs {
    font-size: 0.875rem;
    color: var(--grey-text);
    margin-bottom: 0.5rem;
}

.item-price, .item-quantity {
    font-size: 0.875rem;
    color: var(--dark-text);
}

/* Order Footer */
.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
}

.order-total {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--grey-text);
}

.details-btn {
    padding: 0.75rem 1.5rem;
    background: var(--primary-green);
    color: var(--white);
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.details-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
    text-align: center;
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--light-text);
    margin-bottom: 2rem;
    max-width: 400px;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn, .pagination-number {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-light);
    background: var(--white);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pagination-number.active {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
}

.pagination-btn:hover, .pagination-number:hover {
    border-color: var(--primary-green);
}

/* Responsive Design */
@media (max-width: 768px) {
    .orders-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .order-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .details-btn {
        align-self: flex-end;
    }
    
    .order-item {
        flex-direction: column;
    }
    
    .item-image {
        width: 100%;
        height: 200px;
    }
}
</style>
@endsection