@extends('layouts.app')

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
                        <button class="status-category" data-status="processing">
                            <span class="status-text">Processing</span>
                            <span class="status-count">{{ $orders->where('status', 'processing')->count() }}</span>
                        </button>
                        <button class="status-category" data-status="shipped">
                            <span class="status-text">Shipped</span>
                            <span class="status-count">{{ $orders->where('status', 'shipped')->count() }}</span>
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
                                                    <span class="item-name">{{ $item->product->name ?? 'Product Not Available' }}</span>
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

@push('scripts')
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
@endpush