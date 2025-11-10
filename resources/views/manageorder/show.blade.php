@extends('admin.adminbase')
@section('title', 'Order Details')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_order/show.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="order-details-container">
    <!-- Header -->
    <div class="order-header">
        <div class="header-left">
            <h1 class="order-title">Order #{{ $order->id }}</h1>
            <p class="order-date">Placed on {{ $order->created_at->format('F d, Y \\a\\t h:i A') }}</p>
        </div>
        <div class="header-right">
            <form action="{{ route('admin.manageorder.update-status', $order) }}" method="POST" class="status-form">
                @csrf
                @method('PATCH')
                <select name="status" class="status-select" onchange="this.form.submit()">
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </form>
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
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>Payment Status:</strong>
                    <span class="payment-badge payment-{{ $order->payment_status ?? 'pending' }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>Total Amount:</strong>
                    <span class="amount">RM {{ number_format($order->total_amount, 2) }}</span>
                </div>
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
                <span>RM {{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Shipping:</span>
                <span>RM 0.00</span>
            </div>
            <div class="total-row">
                <span>Tax:</span>
                <span>RM 0.00</span>
            </div>
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
            <button class="btn btn-print">
                <i class="fas fa-print"></i>
                Print Invoice
            </button>
            <button class="btn btn-email">
                <i class="fas fa-envelope"></i>
                Email Customer
            </button>
        </div>
    </div>
</div>
@endsection