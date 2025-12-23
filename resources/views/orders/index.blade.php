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
    position: relative;
    z-index: 1;
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
    z-index: 5;
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
    max-width: 1200px;
}

/* Left Column - Categories */
.categories-column {
    padding: 0 !important;
    height: fit-content;
    position: relative;
    z-index: 1;
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
    pointer-events: auto !important;
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
    position: relative;
    z-index: 10;
    user-select: none;
    outline: none;
}

.status-category:hover {
    border-color: var(--primary-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.15);
}

.status-category:active {
    transform: translateY(0);
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

.order-date {
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.shipping-address {
    font-size: 0.85rem;
    color: #4b5563;
    margin-top: 0.5rem;
    line-height: 1.4;
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

.order-status-badge.paid {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #22c55e;
}

.order-status-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

/* Ensure filter empty state styles */
.filter-empty-state {
    margin-top: 2rem;
    animation: fadeIn 0.5s ease;
    grid-column: 1 / -1;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Make sure orders container handles empty state properly */
.orders-container {
    position: relative;
    min-height: 200px;
}

/* Ensure order cards have smooth transitions */
.order-card {
    transition: all 0.3s ease;
}

/* Debug styles (remove in production) */
.debug-border {
    border: 1px solid red !important;
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
    transition: transform 0.3s ease;
}

.order-item:hover .item-image img {
    transform: scale(1.05);
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
    margin-bottom: 0.5rem;
}

.item-name-specs {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
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

.item-bottom-row {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
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

.item-total {
    font-size: 1rem;
    color: var(--primary-green);
    font-weight: 700;
    margin-left: auto;
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

.order-total-section {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
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
    margin-left: 0.5rem;
}

.payment-status {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Order Actions */
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
    display: inline-block;
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

/* Payment Status Badges */
.payment-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid;
    display: inline-block;
    margin-left: 0.25rem;
}

.payment-status-badge.paid { 
    background: #d1fae5; 
    color: #065f46; 
    border-color: #10b981;
}

.payment-status-badge.pending { 
    background: #fef3c7; 
    color: #92400e; 
    border-color: #f59e0b;
}

.payment-status-badge.failed { 
    background: #fee2e2; 
    color: #991b1b; 
    border-color: #ef4444;
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

    .cancel-btn {
        align-self: center;
        width: 100%;
        max-width: 200px;
    }

    .order-item {
        flex-direction: column;
    }

    .item-image {
        width: 100%;
        height: 200px;
        border-radius: 0.75rem 0.75rem 0 0;
        border-right: none;
        border-bottom: 1px solid var(--border-light);
    }

    .item-details {
        gap: 1rem;
    }
    
    .item-bottom-row {
        justify-content: space-between;
    }
    
    .item-total {
        margin-left: 0;
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

    <!-- Debug Section (remove after testing) -->
    <div style="display: none; background: #f0f0f0; padding: 10px; margin: 10px; border-radius: 5px;">
        <h4>Debug Info:</h4>
        @php
            $statuses = [];
            foreach($orders as $order) {
                $statuses[] = $order->status;
            }
        @endphp
        <p>All Statuses in Database: {{ implode(', ', array_unique($statuses)) }}</p>
        <p>Order Count: {{ $orders->count() }}</p>
    </div>
    
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
                        <button type="button" class="status-category active" data-status="all">
                            <span class="status-text">All Orders</span>
                            <span class="status-count">{{ $orders->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="processing">
                            <span class="status-text">Processing</span>
                            <span class="status-count">{{ $orders->where('status', 'processing')->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="shipped">
                            <span class="status-text">Shipped</span>
                            <span class="status-count">{{ $orders->where('status', 'shipped')->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="delivered">
                            <span class="status-text">Delivered</span>
                            <span class="status-count">{{ $orders->where('status', 'delivered')->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="cancelled">
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
                                <a href="{{ route('login') }}" class="btn-primary">Log In</a>
                            </div>
                        @elseif($orders->count() > 0)
                            @foreach($orders as $order)
                            <div class="order-card" data-status="{{ $order->status }}">
                                <div class="order-header">
                                    <div class="order-id-section">
                                        <div class="order-id-label">Order ID</div>
                                        <div class="order-id-value">#{{ $order->order_number }}</div>
                                        <div class="order-date">
                                            Ordered on: {{ $order->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="shipping-address">
                                            Deliver to: 
                                            @if($order->shippingAddress)
                                                @php
                                                    $addressParts = [
                                                        $order->shippingAddress->full_name,
                                                        $order->shippingAddress->address_line_1,
                                                        $order->shippingAddress->address_line_2,
                                                        $order->shippingAddress->city,
                                                        $order->shippingAddress->state,
                                                        $order->shippingAddress->postal_code,
                                                        $order->shippingAddress->country
                                                    ];
                                                    $addressLine = implode(', ', array_filter($addressParts, function($part) {
                                                        return !empty($part);
                                                    }));
                                                @endphp
                                                {{ $addressLine }}
                                                @if($order->shippingAddress->phone)
                                                    <br><small>Phone: {{ $order->shippingAddress->phone }}</small>
                                                @endif
                                            @else
                                                Address not available
                                            @endif
                                        </div>
                                    </div>
                                    <div class="order-status-badge {{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </div>

                                <div class="order-items">
                                    @foreach($order->orderItems as $item)
                                    <div class="order-item">
                                        <div class="item-image">
                                            @if($item->product && $item->product->main_image_url)
                                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}">
                                            @else
                                                <img src="{{ asset('images/default-product.png') }}" alt="Product Image">
                                            @endif
                                        </div>
                                        <div class="item-details">
                                            <div class="item-top-row">
                                                <div class="item-name-specs">
                                                    <span class="item-name">{{ $item->product_name ?? ($item->product->name ?? 'Product Not Available') }}</span>
                                                    <span class="item-specs">
                                                        @if($item->variation)
                                                            @php
                                                                $specs = [];
                                                                if ($item->variation->processor) $specs[] = $item->variation->processor;
                                                                if ($item->variation->ram) $specs[] = $item->variation->ram;
                                                                if ($item->variation->storage) $specs[] = $item->variation->storage;
                                                                if (!empty($specs)) {
                                                                    echo '('.implode(' | ', $specs).')';
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
                                                                        echo '('.implode(' | ', $specs).')';
                                                                    }
                                                                @endphp
                                                            @elseif($item->product->specifications)
                                                                ({{ $item->product->specifications }})
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="item-bottom-row">
                                                <div class="item-price">RM{{ number_format($item->price, 2) }}</div>
                                                <div class="item-quantity">Qty: {{ $item->quantity }}</div>
                                                <div class="item-total">RM{{ number_format($item->price * $item->quantity, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

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
                                        @if($order->payment_status)
                                        <div class="payment-status">
                                            Payment: <span class="payment-status-badge {{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="order-actions">
                                        @if(in_array($order->status, ['processing']))
                                            <button class="cancel-btn" data-order-id="{{ $order->id }}">
                                                Cancel Order
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">📦</div>
                                <h3>No Orders Yet</h3>
                                <p>You haven't placed any orders. Start shopping to see your orders here.</p>
                                <a href="{{ route('products.index') }}" class="btn-primary">Start Shopping</a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== ORDERS PAGE FILTER INITIALIZATION ===');
    
    // Get all filter buttons and order cards
    const filterButtons = document.querySelectorAll('.status-category');
    const orderCards = document.querySelectorAll('.order-card');
    
    // Debug: Log what we found
    console.log('Filter buttons found:', filterButtons.length);
    console.log('Order cards found:', orderCards.length);
    
    // Test: Add a simple click handler to verify buttons work
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Button clicked:', this.getAttribute('data-status'));
            
            // Remove active class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get the selected status
            const selectedStatus = this.getAttribute('data-status');
            
            // Filter the orders
            filterOrdersByStatus(selectedStatus);
        });
    });
    
    // Filter function
    function filterOrdersByStatus(status) {
        console.log('Filtering by status:', status);
        
        let visibleCount = 0;
        
        // Loop through all order cards
        orderCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            
            // Show or hide based on status
            if (status === 'all' || cardStatus === status) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        console.log('Visible orders after filtering:', visibleCount);
        
        // Show/hide empty state message
        const ordersContainer = document.querySelector('.orders-container');
        const existingEmptyState = ordersContainer.querySelector('.filter-empty-state');
        const existingMainEmptyState = ordersContainer.querySelector('.empty-state:not(.filter-empty-state)');
        
        // If no orders visible and we're not showing "all" orders
        if (visibleCount === 0 && status !== 'all') {
            // Remove any existing filter empty state
            if (existingEmptyState) {
                existingEmptyState.remove();
            }
            
            // Hide the main "no orders" empty state if it exists
            if (existingMainEmptyState) {
                existingMainEmptyState.style.display = 'none';
            }
            
            // Create and show filter empty state
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-state filter-empty-state';
            emptyState.innerHTML = `
                <div class="empty-icon">📭</div>
                <h3>No ${capitalizeFirstLetter(status)} Orders</h3>
                <p>You don't have any orders with "${status}" status.</p>
                <button type="button" class="btn-primary show-all-btn">Show All Orders</button>
            `;

            ordersContainer.appendChild(emptyState);
            
            // Add event listener to "Show All" button
            emptyState.querySelector('.show-all-btn').addEventListener('click', function() {
                // Reset to show all orders
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.getAttribute('data-status') === 'all') {
                        btn.classList.add('active');
                    }
                });
                
                filterOrdersByStatus('all');
                emptyState.remove();
                
                // Show main empty state if it was hidden
                if (existingMainEmptyState) {
                    existingMainEmptyState.style.display = 'flex';
                }
            });
        } else {
            // Remove filter empty state if it exists
            if (existingEmptyState) {
                existingEmptyState.remove();
            }
            
            // Show main empty state if it exists and we're showing all orders
            if (existingMainEmptyState && status === 'all') {
                existingMainEmptyState.style.display = 'flex';
            }
        }
    }
    
    // Helper function to capitalize first letter
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    // Initialize by showing all orders
    console.log('Initial filter state: showing all orders');
    filterOrdersByStatus('all');
    
    // Order cancellation functionality
    const cancelButtons = document.querySelectorAll('.cancel-btn');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.getAttribute('data-order-id');
            
            if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
                cancelOrder(orderId, this);
            }
        });
    });
    
});

function cancelOrder(orderId, buttonElement) {
    const originalText = buttonElement.textContent;
    buttonElement.textContent = 'Cancelling...';
    buttonElement.disabled = true;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    fetch(`/orders/${orderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ _method: 'PUT' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const orderCard = buttonElement.closest('.order-card');
            const statusBadge = orderCard.querySelector('.order-status-badge');
            
            if (statusBadge) {
                statusBadge.textContent = 'Cancelled';
                statusBadge.className = 'order-status-badge cancelled';
            }
            
            orderCard.setAttribute('data-status', 'cancelled');
            buttonElement.remove();
            
            // Show success message
            alert('Order cancelled successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Failed to cancel order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Error cancelling order');
        buttonElement.textContent = originalText;
        buttonElement.disabled = false;
    });
}
</script>

<style>
/* Add some styles for the filter empty state */
.filter-empty-state {
    margin-top: 2rem;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Make sure buttons are properly clickable */
.status-category {
    cursor: pointer !important;
}

/* Additional styles for the filtering system */
.no-orders-state {
    margin-top: 2rem;
}

.show-all-btn {
    margin-top: 1rem;
}

/* Ensure buttons are properly styled */
.status-category:focus {
    outline: 2px solid var(--primary-green);
    outline-offset: 2px;
}

/* Fix for pagination styling */
.pagination .page-link {
    padding: 0.75rem 1.25rem;
    border: 2px solid var(--border-light);
    background: var(--white);
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    color: var(--dark-text);
    display: block;
}

.pagination .page-item.active .page-link {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.3);
}

.pagination .page-link:hover {
    border-color: var(--primary-green);
    transform: translateY(-1px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .orders-layout {
        grid-template-columns: 1fr;
    }
    
    .categories-column {
        order: 2;
        margin-top: 2rem;
    }
    
    .status-categories {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .status-category {
        width: auto;
        min-width: 140px;
    }
}

@media (max-width: 480px) {
    .status-category {
        min-width: 120px;
        padding: 0.5rem 1rem;
    }
    
    .status-text {
        font-size: 0.8rem;
    }
    
    .status-count {
        width: 24px;
        height: 24px;
        font-size: 0.75rem;
    }
}
</style>
@endsection