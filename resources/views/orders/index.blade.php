@extends('layouts.app')

@section('styles')
    @vite(['resources/css/orders.css'])
@endsection

@section('content')
<div class="orders-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <img src="{{ asset('storage/images/orderbanner.jpg') }}" alt="Orders Banner" class="hero-image">
        <div class="hero-title">
            <h1>Orders</h1>
        </div>
    </section>

    <!-- Main Content -->
    <section class="main-content-section">
        <div class="container">
            <div class="content-box">
                <!-- Header -->
                <div class="page-header">
                    <h2>My Orders</h2>
                </div>

                <!-- Order Categories -->
                <div class="order-categories">
                    <button class="order-category active" data-status="all">All Orders</button>
                    <button class="order-category" data-status="pending">Pending</button>
                    <button class="order-category" data-status="processing">Processing</button>
                    <button class="order-category" data-status="shipped">Shipped</button>
                    <button class="order-category" data-status="delivered">Delivered</button>
                    <button class="order-category" data-status="cancelled">Cancelled</button>
                </div>

                <!-- Orders List -->
                <div class="orders-container">
                    @foreach($orders as $order)
                    <div class="order-card" data-status="{{ $order->status }}">
                        <div class="order-header">
                            <div class="order-status">
                                <span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                <div class="order-date">{{ $order->created_at->format('M d, Y • h:i A') }}</div>
                            </div>
                            <div class="order-meta">
                                <span class="order-id">Order #{{ $order->order_number }}</span>
                                <span class="order-price">RM{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <div class="order-header-divider"></div>

                        <div class="order-items">
                            @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ asset('storage/products/orderpage.png') }}" alt="{{ $item->product->name }}">
                                </div>
                                <div class="item-details">
                                <!-- Product Name and Specs -->
                                <div class="product-name-specs">
                                    <h5 class="item-name-with-specs">
                                        {{ $item->product->name }}
                                        @if(isset($item->product->processor) && $item->product->processor)
                                        ({{ $item->product->processor }} | {{ $item->product->ram }} | {{ $item->product->storage }})
                                        @elseif(isset($item->product->specifications) && $item->product->specifications)
                                        ({{ $item->product->specifications }})
                                        @endif
                                    </h5>
                                </div>
                                
                                <!-- Quantity and Order Details Button in one row -->
                                <div class="item-actions-row">
                                    <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                    <button class="specs-order-details-btn" data-order-id="{{ $order->id }}">
                                        Order Details
                                    </button>
                                </div>
                            </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Empty State -->
                @if($orders->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>No Orders Yet</h3>
                    <p>You haven't placed any orders. Start shopping to see your orders here.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                </div>
                @endif

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
    const categoryButtons = document.querySelectorAll('.order-category');
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

    // Order details modal - specs button
    const specsOrderDetailsButtons = document.querySelectorAll('.specs-order-details-btn');
    specsOrderDetailsButtons.forEach(button => {
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
/* Guest Empty State Styles */
.guest-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    text-align: center;
    padding: 3rem 2rem;
}

.guest-empty-icon {
    font-size: 4rem;
    color: var(--light-text);
    margin-bottom: 1.5rem;
    opacity: 0.7;
}

.guest-empty-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 1rem;
}

.guest-empty-description {
    color: var(--light-text);
    font-size: 1rem;
    margin-bottom: 2rem;
    max-width: 400px;
    line-height: 1.6;
}

.guest-auth-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom: 2rem;
}

.guest-btn {
    padding: 0.75rem 2rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 2px solid transparent;
}

.guest-btn-login {
    background: var(--primary-dark);
    color: var(--white);
    border-color: var(--primary-dark);
}

.guest-btn-login:hover {
    background: var(--primary-green);
    border-color: var(--primary-green);
    transform: translateY(-2px);
}

.guest-btn-register {
    background: transparent;
    color: var(--primary-dark);
    border-color: var(--primary-dark);
}

.guest-btn-register:hover {
    background: var(--primary-dark);
    color: var(--white);
    transform: translateY(-2px);
}

.guest-shopping-option {
    text-align: center;
}

.guest-shopping-option p {
    color: var(--light-text);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.guest-shopping-link {
    color: var(--accent-gold);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.guest-shopping-link:hover {
    color: var(--primary-green);
    gap: 0.75rem;
}

/* Use CSS variables from home.css */
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-gold: #daa112;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
}

.product-name-specs {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.item-name-with-specs {
    font-size: 1rem;
    font-weight: bold;
    color: #1f2937;
    margin: 0;
    line-height: 1.4;
    flex: 1;
    margin-right: 1rem;
}

.specs-order-details-btn {
    padding: 0.25rem 0.75rem;
    background: #1f2937;
    color: white;
    border: none;
    border-radius: 1rem;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    flex-shrink: 0;
}

.specs-order-details-btn:hover {
    background: #374151;
    transform: translateY(-1px);
}

/* Ensure proper spacing in item details */
.item-details {
    flex: 1;
    position: relative;
}

.item-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
}

/* Order status layout */
.order-status {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.order-date {
    color: #6b7280;
    font-size: 0.875rem;
}

/* Header divider */
.order-header-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 1.5rem 0;
    width: 100%;
}

/* Product box spacing */
.orders-container .order-card .order-items .order-item {
    display: flex !important;
    gap: 1rem !important;
    padding: 1.5rem !important;
    border-radius: 0.5rem !important;
    background: white !important;
    border: 1px solid #e5e7eb !important;
    margin-bottom: 1rem !important;
}

.orders-container .order-card .order-items {
    display: flex !important;
    flex-direction: column !important;
    gap: 1.5rem !important;
}

.orders-container .order-card .order-items .order-item .item-details {
    flex: 1 !important;
    padding: 0.5rem 0 !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .guest-auth-buttons {
        flex-direction: column;
        width: 100%;
        max-width: 250px;
    }
    
    .guest-btn {
        justify-content: center;
    }
    
    .guest-empty-state {
        padding: 2rem 1rem;
    }

    .product-name-specs {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .item-name-with-specs {
        margin-right: 0;
    }
    
    .specs-order-details-btn {
        align-self: flex-end;
    }
}

</style>
@endsection