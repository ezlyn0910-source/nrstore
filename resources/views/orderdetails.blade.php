@extends('layouts.app')

@section('styles')
    @vite(['resources/css/order-details.css'])
@endsection

@section('content')
<div class="order-details-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <img src="{{ asset('storage/images/orderbanner.png') }}" alt="Order Details Banner" class="hero-image">
        <div class="hero-title">
            <h1>Order Details</h1>
        </div>
    </section>

    <!-- Main Content -->
    <section class="main-content-section">
        <div class="container">
            <div class="content-box">
                <!-- Order Header -->
                <div class="order-header">
                    <div class="order-info">
                        <h2>Order #{{ $order->order_number }}</h2>
                        <div class="order-meta">
                            <span class="order-date">Placed on {{ $order->created_at->format('F d, Y \\a\\t h:i A') }}</span>
                            <span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-outline" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Invoice
                        </button>
                        @if($order->status == 'pending')
                        <button class="btn btn-danger" id="cancelOrderBtn">
                            Cancel Order
                        </button>
                        @endif
                    </div>
                </div>

                <div class="order-layout">
                    <!-- Left Column -->
                    <div class="order-left">
                        <!-- Order Items -->
                        <div class="order-section">
                            <h3>Order Items ({{ $order->items->count() }})</h3>
                            <div class="order-items">
                                @foreach($order->items as $item)
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product->name }}">
                                    </div>
                                    <div class="item-details">
                                        <h4 class="item-name">{{ $item->product->name }}</h4>
                                        <p class="item-specs">{{ $item->product->specifications }}</p>
                                        <div class="item-meta">
                                            <span class="item-quantity">Quantity: {{ $item->quantity }}</span>
                                            <span class="item-price">RM{{ number_format($item->price, 2) }}</span>
                                        </div>
                                        @if($order->status == 'delivered')
                                        <button class="btn btn-sm btn-outline write-review-btn" data-product-id="{{ $item->product->id }}">
                                            Write Review
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div class="order-section">
                            <h3>Shipping Information</h3>
                            <div class="shipping-info">
                                <div class="info-row">
                                    <span class="info-label">Shipping Method:</span>
                                    <span class="info-value">{{ $order->shipping_method }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Tracking Number:</span>
                                    <span class="info-value">
                                        @if($order->tracking_number)
                                        {{ $order->tracking_number }}
                                        <a href="{{ $order->tracking_url }}" target="_blank" class="track-link">Track Package</a>
                                        @else
                                        Not available yet
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Estimated Delivery:</span>
                                    <span class="info-value">
                                        @if($order->estimated_delivery)
                                        {{ $order->estimated_delivery->format('F d, Y') }}
                                        @else
                                        To be confirmed
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="order-right">
                        <!-- Order Summary -->
                        <div class="order-summary">
                            <h3>Order Summary</h3>
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>RM{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>RM{{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax</span>
                                <span>RM{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="summary-row discount">
                                <span>Discount</span>
                                <span>-RM{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="summary-divider"></div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span>RM{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="shipping-address">
                            <h3>Shipping Address</h3>
                            <div class="address-details">
                                <p><strong>{{ $order->shippingAddress->full_name }}</strong></p>
                                <p>{{ $order->shippingAddress->address_line_1 }}</p>
                                @if($order->shippingAddress->address_line_2)
                                <p>{{ $order->shippingAddress->address_line_2 }}</p>
                                @endif
                                <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                                <p>{{ $order->shippingAddress->country }}</p>
                                <p>Phone: {{ $order->shippingAddress->phone }}</p>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="billing-address">
                            <h3>Billing Address</h3>
                            <div class="address-details">
                                <p><strong>{{ $order->billingAddress->full_name }}</strong></p>
                                <p>{{ $order->billingAddress->address_line_1 }}</p>
                                @if($order->billingAddress->address_line_2)
                                <p>{{ $order->billingAddress->address_line_2 }}</p>
                                @endif
                                <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}</p>
                                <p>{{ $order->billingAddress->country }}</p>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="payment-info">
                            <h3>Payment Information</h3>
                            <div class="payment-details">
                                <div class="info-row">
                                    <span class="info-label">Payment Method:</span>
                                    <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Payment Status:</span>
                                    <span class="info-value status-badge {{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Paid Amount:</span>
                                    <span class="info-value">RM{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="order-timeline">
                    <h3>Order Status Timeline</h3>
                    <div class="timeline">
                        @foreach($order->statusHistory as $history)
                        <div class="timeline-item {{ $history->status }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <span class="timeline-status">{{ ucfirst($history->status) }}</span>
                                <span class="timeline-date">{{ $history->created_at->format('M d, Y \\a\\t h:i A') }}</span>
                                @if($history->notes)
                                <p class="timeline-notes">{{ $history->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel order #{{ $order->order_number }}? This action cannot be undone.</p>
                <form id="cancelOrderForm">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Reason for cancellation</label>
                        <select class="form-select" id="cancel_reason" name="cancel_reason" required>
                            <option value="">Select a reason</option>
                            <option value="changed_mind">Changed my mind</option>
                            <option value="found_cheaper">Found cheaper elsewhere</option>
                            <option value="delivery_time">Delivery takes too long</option>
                            <option value="other">Other reason</option>
                        </select>
                    </div>
                    <div class="mb-3" id="otherReasonContainer" style="display: none;">
                        <label for="other_reason" class="form-label">Please specify</label>
                        <textarea class="form-control" id="other_reason" name="other_reason" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrder">Cancel Order</button>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    @csrf
                    <input type="hidden" name="product_id" id="reviewProductId">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-stars">
                            <input type="radio" id="star5" name="rating" value="5">
                            <label for="star5">★</label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4">★</label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3">★</label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2">★</label>
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1">★</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="review_title" class="form-label">Review Title</label>
                        <input type="text" class="form-control" id="review_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="review_content" class="form-label">Your Review</label>
                        <textarea class="form-control" id="review_content" name="content" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitReview">Submit Review</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel order functionality
    const cancelOrderBtn = document.getElementById('cancelOrderBtn');
    if (cancelOrderBtn) {
        cancelOrderBtn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            modal.show();
        });
    }

    // Other reason toggle
    document.getElementById('cancel_reason').addEventListener('change', function() {
        const otherContainer = document.getElementById('otherReasonContainer');
        otherContainer.style.display = this.value === 'other' ? 'block' : 'none';
    });

    // Confirm cancel order
    document.getElementById('confirmCancelOrder').addEventListener('click', function() {
        const form = document.getElementById('cancelOrderForm');
        const formData = new FormData(form);
        
        fetch('{{ route("orders.cancel") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error cancelling order: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error cancelling order');
        });
    });

    // Review functionality
    const writeReviewButtons = document.querySelectorAll('.write-review-btn');
    const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
    
    writeReviewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            document.getElementById('reviewProductId').value = productId;
            reviewModal.show();
        });
    });

    // Star rating
    const ratingStars = document.querySelectorAll('.rating-stars input');
    ratingStars.forEach(star => {
        star.addEventListener('change', function() {
            const rating = this.value;
            // You can add visual feedback here
        });
    });

    // Submit review
    document.getElementById('submitReview').addEventListener('click', function() {
        const form = document.getElementById('reviewForm');
        const formData = new FormData(form);
        
        fetch('{{ route("reviews.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                reviewModal.hide();
                alert('Review submitted successfully!');
            } else {
                alert('Error submitting review: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting review');
        });
    });
});
</script>
@endsection