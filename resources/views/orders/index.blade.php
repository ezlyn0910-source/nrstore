@extends('layouts.app')

@section('styles')
<style>
.orders-page {
    background: #f8f9fa;
    min-height: 100vh;
}

/* Page Title */
.page-title-section {
    width: 100%;
    padding: 2.5rem 0;
}

.page-title-container {
    text-align: center;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.25rem 0;
    text-align: center;
}

/* Main Content */
.main-content-section {
    padding: 0;
    position: relative;
    margin-top: 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Two Column Layout */
.orders-layout {
    display: grid;
    grid-template-columns: 230px 1fr;
    gap: 1.5rem;
    margin: 0 auto;
    padding: 0;
    margin-top: -2rem;
    max-width: 1200px;
}

/* Left Column - Categories */
.categories-column {
    padding: 0 !important;
    height: fit-content;
}

.categories-title {
    font-size: 1.375rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--dark-text);
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--primary-green);
}

.status-categories {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    align-items: flex-start;
}

.status-category {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 1.5rem;
    background: var(--white);
    border: 2px solid var(--border-light);
    border-radius: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    width: 100%;
    font-size: 1rem;
}

.status-category:hover {
    border-color: var(--primary-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.15);
}

.status-category.active {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: var(--white);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(45, 74, 53, 0.25);
}

.status-text {
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
    flex: 1;
}

.status-count {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    background: #6b7280;
    border-radius: 50%;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--white);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.status-category.active .status-count {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary-green);
}

/* Right Column - Orders */
.orders-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-right: 0;
}

.orders-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Order Card */
.order-card {
    background: var(--white);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    overflow: hidden;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--primary-green);
}

/* Order Header */
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
    font-size: 0.9rem;
    color: var(--grey-text);
    margin-bottom: 0.2rem;
    font-weight: 500;
}

.order-id-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--dark-text);
    letter-spacing: 0.5px;
    margin-bottom: 0.2rem;
}

.order-status-badge {
    padding: 0.5rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 110px;
    text-align: center;
    display: inline-block;
    white-space: nowrap;
    box-sizing: border-box;
}

.order-status-badge.pending {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #f59e0b;
}

.order-status-badge.processing {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #3b82f6;
}

.order-status-badge.shipped {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.order-status-badge.delivered {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #22c55e;
}

.order-status-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

.order-status-badge.returned {
    background: #f3e8ff;
    color: #7c3aed;
    border: 1px solid #a855f7;
}

/* Shipping Section */
.shipping-section {
    margin-bottom: 2rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 0.75rem;
    border-left: 4px solid var(--primary-green);
}

.shipping-label {
    font-size: 0.875rem;
    color: var(--grey-text);
    margin-bottom: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
    gap: 0;
    padding: 0;
    background: var(--white);
    border: 2px solid var(--border-light);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    align-items: stretch;
}

.order-item:hover {
    border-color: var(--primary-green);
    transform: translateX(4px);
}

.item-image {
    width: 120px;
    height: 120px;
    flex-shrink: 0;
    border-radius: 0.75rem 0 0 0.75rem;
    overflow: hidden;
    border: none;
    border-right: 1px solid var(--border-light);
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.order-item:hover .item-image img {
    transform: scale(1.05);
}

.item-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
    flex-wrap: wrap;
}

.item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 1rem;
    gap: 0;
}

.item-top-row {
    margin-bottom: 0;
}

.item-name-specs {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.item-name {
    font-weight: 700;
    color: var(--dark-text);
    font-size: 1.125rem;
    margin: 0;
}

.item-specs {
    font-size: 0.875rem;
    color: var(--grey-text);
    margin: 0;
}

.item-price {
    font-size: 1rem;
    color: var(--dark-text);
    font-weight: 700;
}

.item-quantity {
    font-size: 0.875rem;
    color: var(--grey-text);
    font-weight: 600;
    margin: 0;
}

/* Order Footer */
.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: #efefef;
    border-radius: 0 0 0.75rem 0.75rem;
    margin: 1rem -1.5rem -1.5rem -1.5rem;
    margin-top: 1rem;
    min-height: auto;
}

.order-total {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark-text);
}

.item-count {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
    background: var(--white);
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 2px dashed var(--border-light);
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: 2rem;
    opacity: 0.7;
}

.empty-state h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--light-text);
    margin-bottom: 2.5rem;
    max-width: 400px;
    line-height: 1.6;
    font-size: 1.125rem;
}

.btn-primary {
    padding: 0.5rem 2rem;
    background: var(--primary-green);
    color: var(--white);
    border: none;
    border-radius: 2rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(45, 74, 53, 0.4);
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
}

.pagination {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.pagination-btn,
.pagination-number {
    padding: 0.75rem 1.25rem;
    border: 2px solid var(--border-light);
    background: var(--white);
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.875rem;
}

.pagination-number.active {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.3);
}

.pagination-btn:hover,
.pagination-number:hover:not(.active) {
    border-color: var(--primary-green);
    transform: translateY(-1px);
}

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

/* Responsive Design */
@media (max-width: 1024px) {
    .orders-layout {
        grid-template-columns: 280px 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .page-title-container {
        padding: 1.5rem 0 1.5rem;
    }

    .page-title {
        font-size: 2.5rem;
    }

    .orders-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .orders-column {
        order: 1;
    }

    .order-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .order-footer {
        flex-direction: column;
        gap: 1.5rem;
        align-items: stretch;
    }

    .details-btn {
        align-self: center;
        width: 100%;
        max-width: 200px;
    }

    .order-item {
        flex-direction: column;
        text-align: center;
    }

    .item-image {
        width: 100%;
        height: 200px;
        margin: 0 auto;
    }

    .item-details {
        gap: 1rem;
    }

    .product-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .product-name,
    .product-specs,
    .product-price-qty {
        width: 100%;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .order-actions {
        display: flex;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 2rem;
    }

    .status-category {
        padding: 1rem 1.25rem;
    }

    .order-card {
        padding: 1.5rem;
    }

    .order-id-value {
        font-size: 1.25rem;
    }

    .order-total {
        font-size: 1.25rem;
    }

    .shipping-section {
        padding: 1rem;
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 4rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
    }

    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<div class="orders-page">
    <section class="page-title-section">
        <div class="container">
            <div class="page-title-container">
                <h1 class="page-title">Orders</h1>
            </div>
        </div>
    </section>

    <section class="main-content-section">
        <div class="container">
            <div class="orders-layout">
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
                        <button class="status-category" data-status="paid">
                            <span class="status-text">Paid</span>
                            <span class="status-count">{{ $orders->where('status', 'paid')->count() }}</span>
                        </button>
                        <button class="status-category" data-status="processing">
                            <span class="status-text">Processing</span>
                            <span class="status-count">{{ $orders->where('status', 'processing')->count() }}</span>
                        </button>
                        <button class="status-category" data-status="shipped">
                            <span class="status-text">Shipped</span>
                            <span class="status-count">{{ $orders->where('status', 'shipped')->count() }}</span>
                        </button>
                        <button class="status-category" data-status="cancelled">
                            <span class="status-text">Cancelled</span>
                            <span class="status-count">{{ $orders->where('status', 'cancelled')->count() }}</span>
                        </button>
                    </div>
                </div>

                <div class="orders-column">
                    <div class="orders-container">
                        @if(isset($isGuest) && $isGuest)
                            <div class="empty-state">
                                <div class="empty-icon">🔒</div>
                                <h3>Please Log In</h3>
                                <p>You need to be logged in to view your orders.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
                            </div>
                        @elseif($orders->count() > 0)
                            @foreach($orders as $order)
                            <div class="order-card" data-status="{{ $order->status }}">
                                {{-- Row 1: Order ID + Status Badge --}}
                                <div class="order-header">
                                    <div class="order-id-section">
                                        <div class="order-id-label">Order ID</div>
                                        <div class="order-id-value">#{{ $order->order_number }}</div>
                                    </div>
                                    <div class="order-status-badge {{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </div>

                                {{-- Row 2: Ordered Date --}}
                                <div class="order-date-row">
                                    <span class="order-label">Ordered on:</span>
                                    <span class="order-value">{{ $order->created_at->format('l, M d, Y') }}</span>
                                </div>

                                {{-- Row 3: Shipping Address --}}
                                <div class="shipping-address-row">
                                    <span class="order-label">Deliver to:</span>
                                    <span class="order-value">
                                        @if($order->shippingAddress)
                                            {{ $order->shippingAddress->full_name }},
                                            {{ $order->shippingAddress->address_line_1 }},
                                            @if($order->shippingAddress->address_line_2)
                                                {{ $order->shippingAddress->address_line_2 }},
                                            @endif
                                            {{ $order->shippingAddress->city }},
                                            {{ $order->shippingAddress->state }},
                                            {{ $order->shippingAddress->postal_code }},
                                            {{ $order->shippingAddress->country }}
                                        @else
                                            Address not available
                                        @endif
                                    </span>
                                </div>

                                {{-- Row 4: Phone Number --}}
                                <div class="phone-row">
                                    <span class="order-label">Phone:</span>
                                    <span class="order-value">
                                        @if($order->shippingAddress && $order->shippingAddress->phone)
                                            {{ $order->shippingAddress->phone }}
                                        @else
                                            Not provided
                                        @endif
                                    </span>
                                </div>

                                {{-- Row 5: Divider --}}
                                <div class="order-divider"></div>

                                {{-- Row 6: Product Details List --}}
                                <div class="product-details-list">
                                    <div class="order-label" style="margin-bottom: 0.5rem;">Items:</div>
                                    @foreach($order->orderItems as $item)
                                    <div class="product-item">
                                        <span class="product-name">{{ $item->product->name ?? 'Product Not Available' }}</span>
                                        <span class="product-specs">
                                            @if($item->variation)
                                                @php
                                                    $specs = [];
                                                    if ($item->variation->processor) $specs[] = $item->variation->processor;
                                                    if ($item->variation->ram) $specs[] = $item->variation->ram;
                                                    if ($item->variation->storage) $specs[] = $item->variation->storage;
                                                    if (!empty($specs)) {
                                                        echo '- '.implode(' | ', $specs);
                                                    }
                                                @endphp
                                            @elseif($item->product)
                                                @if($item->product->processor || $item->product->ram || $item->product->storage)
                                                    @php
                                                        $specs = [];
                                                        if ($item->product->processor) $specs[] = $item->product->processor;
                                                        if ($item->product->ram) $specs[] = $item->product->ram;
                                                        if ($item->product->storage) $specs[] = $item->product->storage;
                                                        if (!empty($specs)) {
                                                            echo '- '.implode(' | ', $specs);
                                                        }
                                                    @endphp
                                                @elseif($item->product->specifications)
                                                    - {{ $item->product->specifications }}
                                                @endif
                                            @endif
                                        </span>
                                        <span class="product-price-qty">
                                            RM{{ number_format($item->price, 2) }} x {{ $item->quantity }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Row 7: Order Footer --}}
                                <div class="order-footer">
                                    <div class="order-total-section">
                                        <div class="order-total">
                                            Total: RM{{ number_format($order->total_amount, 2) }}
                                            <span class="item-count">
                                                ({{ $order->orderItems->sum('quantity') }} 
                                                @if($order->orderItems->sum('quantity') == 1)
                                                    item
                                                @else
                                                    items
                                                @endif
                                                )
                                            </span>
                                        </div>
                                    </div>
                                    <div class="order-actions">
                                        @if(in_array($order->status, ['pending', 'processing']))
                                            <button class="cancel-btn" data-order-id="{{ $order->id }}">
                                                Cancel Order
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            {{-- Empty state remains the same --}}
                            <div class="empty-state">
                                <div class="empty-icon">📦</div>
                                <h3>No Orders Yet</h3>
                                <p>You haven't placed any orders. Start shopping to see your orders here.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                            </div>
                        @endif
                    </div>

                    @if($orders->count() > 0 && method_exists($orders, 'hasPages') && $orders->hasPages())
                    <div class="pagination-container">
                        <div class="pagination">
                            {{ $orders->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
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

    // Order cancellation
    const cancelButtons = document.querySelectorAll('.cancel-btn');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
                cancelOrder(orderId);
            }
        });
    });
});

function cancelOrder(orderId) {
    const cancelBtn = document.querySelector(`.cancel-btn[data-order-id="${orderId}"]`);
    const originalText = cancelBtn.textContent;
    
    // Show loading state
    cancelBtn.textContent = 'Cancelling...';
    cancelBtn.disabled = true;
    
    fetch(`/orders/${orderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification(data.message, 'success');
            // Reload page to reflect changes
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to cancel order');
        }
    })
    .catch(error => {
        console.error('Error cancelling order:', error);
        showNotification(error.message || 'Error cancelling order. Please try again.', 'error');
        // Reset button
        cancelBtn.textContent = originalText;
        cancelBtn.disabled = false;
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
    `;
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>

<style>
    /* Order card rows styling */
.order-date-row,
.shipping-address-row,
.phone-row {
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.order-label {
    font-weight: 600;
    color: #2d4a35; /* Changed from white to dark green for better readability */
    margin-right: 0.5rem;
    font-size: 0.9rem;
}

.order-value {
    color: #1a2412; /* Dark text for values */
    font-size: 0.9rem;
}

/* Divider */
.order-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #e9ecef, transparent);
    margin: 1rem 0;
}

/* Product details list */
.product-details-list {
    margin: 1rem 0 1.5rem 0;
}

.product-item {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px dashed #e9ecef;
}

.product-item:last-child {
    border-bottom: none;
}

.product-name {
    font-weight: 600;
    color: #1a2412;
    margin-right: 0.5rem;
    flex: 1;
    min-width: 200px;
}

.product-specs {
    color: #6b7280;
    font-size: 0.85rem;
    margin-right: 1rem;
    flex: 1;
}

.product-price-qty {
    font-weight: 600;
    color: #2d4a35;
    white-space: nowrap;
    font-size: 0.9rem;
}

/* Order footer adjustments */
.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #f5f5f5; /* Light grey background */
    border-radius: 0 0 0.75rem 0.75rem;
    margin: 0 -1.5rem -1.5rem -1.5rem;
    margin-top: 1.5rem;
}

.order-total-section {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.order-total {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a2412;
}

.item-count {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

/* Payment status badges */
.payment-status-badge.paid { 
    background: #d1fae5; 
    color: #065f46; 
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #10b981;
}

.payment-status-badge.pending { 
    background: #fef3c7; 
    color: #92400e; 
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #f59e0b;
}

.payment-status-badge.failed { 
    background: #fee2e2; 
    color: #991b1b; 
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #ef4444;
}

/* Order actions */
.order-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.cancel-btn {
    padding: 0.5rem 1.25rem;
    background: #dc2626;
    color: var(--white);
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.cancel-btn:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.cancel-btn:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
}

.order-total-section {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.payment-status {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Loading spinner */
.loading-spinner {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .order-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-actions {
        width: 100%;
        justify-content: flex-end;
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