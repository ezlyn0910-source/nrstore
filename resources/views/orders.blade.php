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

    <!-- Footer -->
    <footer class="footer-minimal">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 class="footer-heading">About NR Store</h3>
                    <p class="footer-text">Your trusted partner for quality laptops and computing solutions. We provide the latest technology with exceptional service.</p>
                    <div class="footer-social">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}" class="footer-link">Home</a></li>
                        <li><a href="{{ url('/products') }}" class="footer-link">Products</a></li>
                        <li><a href="{{ url('/bid') }}" class="footer-link">Bid</a></li>
                        <li><a href="{{ url('/orders') }}" class="footer-link">Orders</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-heading">Customer Service</h3>
                    <ul class="footer-links">
                        <li><a href="#" class="footer-link">Shipping Information</a></li>
                        <li><a href="#" class="footer-link">Returns & Refunds</a></li>
                        <li><a href="#" class="footer-link">Privacy Policy</a></li>
                        <li><a href="#" class="footer-link">Terms & Conditions</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-heading">Contact Info</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@nrstore.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+1 234 567 890</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Tech Street, Digital City</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-divider"></div>
                <div class="footer-copyright">
                    <p>&copy; 2024 NR Store. All rights reserved.</p>
                    <div class="footer-payment">
                        <span>We accept:</span>
                        <div class="payment-methods">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-paypal"></i>
                            <i class="fab fa-cc-apple-pay"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
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
/* Additional CSS for the new layout */
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

/* Footer Styles */
.footer-minimal {
    background: #f8f9fa;
    color: #1f2937;
    padding: 3rem 0 1rem;
    margin-top: 4rem;
    border-top: 1px solid #e5e7eb;
}

.footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-section {
    display: flex;
    flex-direction: column;
}

.footer-heading {
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    letter-spacing: -0.5px;
}

.footer-text {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.footer-social {
    display: flex;
    gap: 0.75rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: white;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    color: #1f2937;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #10b981;
    border-color: #10b981;
    color: white;
    transform: translateY(-2px);
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-link {
    color: #6b7280;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.footer-link:hover {
    color: #d97706;
    transform: translateX(5px);
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #6b7280;
    font-size: 0.9rem;
}

.contact-item i {
    color: #d97706;
    width: 16px;
}

.footer-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 2rem 0 1.5rem;
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.footer-copyright {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.footer-copyright p {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
}

.footer-payment {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.payment-methods {
    display: flex;
    gap: 0.5rem;
}

.payment-methods i {
    font-size: 1.5rem;
    color: #6b7280;
    transition: color 0.2s ease;
}

.payment-methods i:hover {
    color: #d97706;
}

/* Responsive design */
@media (max-width: 768px) {
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

    .footer-content {
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    .footer-copyright {
        flex-direction: column;
        gap: 1rem;
    }
    
    .footer-payment {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .footer-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .footer-social {
        justify-content: center;
    }
    
    .footer-heading {
        text-align: center;
    }
    
    .footer-text {
        text-align: center;
    }
}
</style>
@endsection