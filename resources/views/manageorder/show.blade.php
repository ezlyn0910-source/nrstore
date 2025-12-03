@extends('admin.adminbase')
@section('title', 'Order Details')

@section('content')

<style>
/* Order Details Styles */
.order-details-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: var(--light-bone);
    min-height: 100vh;
}

/* Header Section */
.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border-light);
}

.header-left .order-title {
    color: var(--primary-dark);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.header-left .order-date {
    color: var(--light-text);
    font-size: 1rem;
    margin: 0;
}

.status-form {
    margin: 0;
}

.status-select {
    padding: 10px 15px;
    border: 2px solid var(--primary-green);
    border-radius: 8px;
    background-color: var(--white);
    color: var(--primary-dark);
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 200px;
}

.status-select:focus {
    outline: none;
    border-color: var(--accent-gold);
    box-shadow: 0 0 0 3px rgba(218, 161, 18, 0.1);
}

/* Order Summary Grid */
.order-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.info-card {
    background: var(--white);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 15px rgba(26, 36, 18, 0.08);
    border: 1px solid var(--border-light);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(26, 36, 18, 0.12);
}

.card-title {
    color: var(--primary-dark);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--accent-gold);
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.info-item strong {
    color: var(--primary-dark);
    font-weight: 600;
}

.info-item span {
    color: var(--light-text);
    text-align: right;
}

/* Status and Payment Badges */
.status-badge,
.payment-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending,
.payment-pending {
    background-color: var(--warning);
    color: var(--primary-dark);
}

.status-processing,
.payment-processing {
    background-color: var(--info);
    color: var(--white);
}

.status-completed,
.payment-completed,
.payment-paid {
    background-color: var(--success);
    color: var(--white);
}

.status-cancelled,
.payment-cancelled,
.payment-failed {
    background-color: var(--danger);
    color: var(--white);
}

.status-shipped {
    background-color: var(--accent-gold);
    color: var(--primary-dark);
}

.amount {
    color: var(--primary-green);
    font-weight: 700;
    font-size: 1.1rem;
}

/* Address Block */
.address-block {
    color: var(--light-text);
    line-height: 1.6;
}

.address-block strong {
    color: var(--primary-dark);
    font-size: 1.1rem;
}

.text-muted {
    color: var(--light-text) !important;
    font-style: italic;
}

/* Order Items Card */
.order-items-card {
    background: var(--white);
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(26, 36, 18, 0.08);
    border: 1px solid var(--border-light);
    margin-bottom: 30px;
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.order-item {
    display: grid;
    grid-template-columns: 80px 1fr auto auto auto;
    gap: 20px;
    align-items: center;
    padding: 20px;
    background-color: var(--light-bone);
    border-radius: 8px;
    border-left: 4px solid var(--accent-gold);
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
}

.item-name {
    color: var(--primary-dark);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.item-variation {
    color: var(--light-text);
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.item-sku {
    color: var(--light-text);
    font-size: 0.85rem;
    margin: 0;
}

.item-quantity {
    color: var(--primary-dark);
    font-weight: 500;
    text-align: center;
}

.item-price {
    color: var(--primary-green);
    font-weight: 600;
    text-align: right;
}

.item-total {
    color: var(--primary-dark);
    font-weight: 700;
    font-size: 1.1rem;
    text-align: right;
}

/* Order Totals */
.order-totals {
    border-top: 2px solid var(--border-light);
    padding-top: 20px;
    max-width: 300px;
    margin-left: auto;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    color: var(--light-text);
    font-size: 1rem;
}

.total-row.grand-total {
    border-top: 2px solid var(--accent-gold);
    margin-top: 10px;
    padding-top: 15px;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-dark);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 2px solid var(--border-light);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: none;
}

.btn-back {
    background-color: transparent;
    color: var(--primary-green);
    border: 2px solid var(--primary-green);
}

.btn-back:hover {
    background-color: var(--primary-green);
    color: var(--white);
    transform: translateY(-2px);
}

.action-group {
    display: flex;
    gap: 15px;
}

.btn-print {
    background-color: var(--accent-gold);
    color: var(--primary-dark);
}

.btn-print:hover {
    background-color: #c5910f;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(218, 161, 18, 0.3);
}

.btn-email {
    background-color: var(--primary-green);
    color: var(--white);
}

.btn-email:hover {
    background-color: #24382d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .order-details-container {
        padding: 15px;
    }
    
    .order-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .header-left .order-title {
        font-size: 1.5rem;
    }
    
    .order-summary-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .order-item {
        grid-template-columns: 60px 1fr;
        gap: 15px;
    }
    
    .item-quantity,
    .item-price,
    .item-total {
        grid-column: 2;
        text-align: left;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .action-group {
        justify-content: center;
    }
    
    .btn {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .info-card,
    .order-items-card {
        padding: 20px;
    }
    
    .order-item {
        padding: 15px;
    }
    
    .action-group {
        flex-direction: column;
    }
}

/* Loading States */
.status-select:disabled {
    background-color: var(--border-light);
    cursor: not-allowed;
    opacity: 0.7;
}

/* Print Styles */
@media print {
    .action-buttons,
    .status-form {
        display: none;
    }
    
    .order-details-container {
        background: white;
        padding: 0;
    }
    
    .info-card,
    .order-items-card {
        box-shadow: none;
        border: 1px solid #ccc;
    }
}
</style>

<div class="order-details-container">
    <!-- Header -->
    <div class="order-header">
        <div class="header-left">
            <h1 class="order-title">Order #{{ $order->order_number }}</h1>
            <p class="order-date">Placed on {{ $order->created_at->format('F d, Y \\a\\t h:i A') }}</p>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary-grid">
        <!-- Customer Information -->
        <div class="info-card">
            <h3 class="card-title">Customer Information</h3>
            <div class="info-content">
                <div class="info-item">
                    <strong>Name:</strong>
                    <span>{{ $order->user->name ?? 'Guest' }}</span>
                </div>
                <div class="info-item">
                    <strong>Email:</strong>
                    <span>{{ $order->user->email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <strong>Phone:</strong>
                    <span>{{ $order->user->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="info-card">
            <h3 class="card-title">Shipping Address</h3>
            <div class="info-content">
                @if($order->shippingAddress)
                    <div class="address-block">
                        <strong>{{ $order->shippingAddress->full_name }}</strong><br>
                        {{ $order->shippingAddress->address_line_1 }}<br>
                        @if($order->shippingAddress->address_line_2)
                            {{ $order->shippingAddress->address_line_2 }}<br>
                        @endif
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}<br>
                        {{ $order->shippingAddress->postal_code }}<br>
                        {{ $order->shippingAddress->country }}<br>
                        <strong>Phone:</strong> {{ $order->shippingAddress->phone }}
                    </div>
                @else
                    <p class="text-muted">No shipping address provided</p>
                @endif
            </div>
        </div>

        <!-- Order Details -->
        <div class="info-card">
            <h3 class="card-title">Order Details</h3>
            <div class="info-content">
                <div class="info-item">
                    <strong>Status:</strong>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>Total Amount:</strong>
                    <span class="amount">RM {{ number_format($order->total_amount, 2) }}</span>
                </div>
                @if($order->tracking_number)
                <div class="info-item">
                    <strong>Tracking Number:</strong>
                    <span class="tracking-number">{{ $order->tracking_number }}</span>
                </div>
                @endif
                @if($order->shipped_at)
                <div class="info-item">
                    <strong>Shipped Date:</strong>
                    <span>{{ $order->shipped_at->format('M d, Y h:i A') }}</span>
                </div>
                @endif
                @if($order->payment_method)
                <div class="info-item">
                    <strong>Payment Method:</strong>
                    <span>{{ ucfirst($order->payment_method) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="order-items-card">
        <h3 class="card-title">Order Items</h3>
        <div class="items-list">
            @foreach($order->orderItems as $item)
            <div class="order-item">
                <div class="item-image">
                    <img src="{{ $item->product->main_image_url ?? asset('images/default-product.png') }}" 
                         alt="{{ $item->product->name ?? 'Product' }}">
                </div>
                <div class="item-details">
                    <h4 class="item-name">{{ $item->product->name ?? 'Product' }}</h4>
                    @if($item->variation)
                        <p class="item-variation">{{ $item->variation->specifications_html ?? '' }}</p>
                    @endif
                    <p class="item-sku">SKU: {{ $item->variation->sku ?? $item->product->sku ?? 'N/A' }}</p>
                </div>
                <div class="item-quantity">
                    <strong>Qty:</strong> {{ $item->quantity }}
                </div>
                <div class="item-price">
                    RM {{ number_format($item->price, 2) }}
                </div>
                <div class="item-total">
                    <strong>RM {{ number_format($item->quantity * $item->price, 2) }}</strong>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Totals -->
        <div class="order-totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>RM {{ number_format($order->orderItems->sum(function($item) { return $item->quantity * $item->price; }), 2) }}</span>
            </div>
            @if($order->shipping_cost > 0)
            <div class="total-row">
                <span>Shipping:</span>
                <span>RM {{ number_format($order->shipping_cost, 2) }}</span>
            </div>
            @endif
            @if($order->tax_amount > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>RM {{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @endif
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>-RM {{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>Total:</span>
                <span>RM {{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('admin.manageorder.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i>
            Back to Orders
        </a>
        <div class="action-group">
            <a href="{{ route('admin.manageorder.edit', $order) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Edit Order Details
            </a>
        </div>
    </div>
</div>
@endsection