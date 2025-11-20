@extends('layouts.app')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/cart/index.css'])
@endsection

@section('content')
<div class="simple-cart-page">
    <!-- Banner -->
    <div class="cart-banner">
        <div class="banner-content">
            <h1>SHOPPING CART</h1>
        </div>
    </div>

    <div class="cart-container">
        @if($cartItems->count() > 0)
        <!-- Cart Header -->
        <div class="cart-header">
            <div class="header-product">PRODUCT NAME</div>
            <div class="header-price">UNIT PRICE</div>
            <div class="header-quantity">QUANTITY</div>
            <div class="header-total">TOTAL</div>
        </div>

        <!-- Cart Items -->
        @foreach($cartItems as $item)
        <div class="cart-item" data-item-id="{{ $item->id }}">
            <div class="product-info">
                <div class="product-image">
                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}">
                </div>
                <div class="product-details">
                    <a href="{{ route('products.show', $item->product->slug) }}" class="product-name">
                        {{ $item->product_name }}
                    </a>
                    <span class="product-sku">SKU: {{ $item->product->sku ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="unit-price">
                RM {{ number_format($item->price, 2) }}
            </div>

            <div class="quantity-controls">
                <button class="qty-btn minus" data-action="decrease">-</button>
                <input type="number" class="qty-input" value="{{ $item->quantity }}" min="1" max="99">
                <button class="qty-btn plus" data-action="increase">+</button>
            </div>

            <div class="item-total">
                RM {{ number_format($item->subtotal, 2) }}
            </div>
        </div>
        @endforeach

        <!-- Cart Footer -->
        <div class="cart-footer">
            <div class="footer-left">
                <a href="{{ route('products.index') }}" class="continue-btn">
                    CONTINUE SHOPPING
                </a>
                <button class="clear-btn">
                    CLEAR SHOPPING CART
                </button>
            </div>
            
            <div class="footer-right">
                <div class="subtotal-row">
                    <span>Subtotal:</span>
                    <span class="subtotal-amount" id="subtotal">RM {{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="divider"></div>
                <button class="order-btn">
                    ORDER NOW
                </button>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-icon">🛒</div>
            <h2>YOUR CART IS EMPTY</h2>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="start-shopping-btn">
                START SHOPPING
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = this.closest('.cart-item');
            const itemId = item.dataset.itemId;
            const action = this.dataset.action;
            
            if (action === 'increase') {
                increaseQuantity(itemId, item);
            } else if (action === 'decrease') {
                decreaseQuantity(itemId, item);
            }
        });
    });

    // Quantity input change
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            const item = this.closest('.cart-item');
            const itemId = item.dataset.itemId;
            updateCartItem(itemId, parseInt(this.value), item);
        });
    });

    // Clear cart button
    document.querySelector('.clear-btn')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            clearCart();
        }
    });

    // Order now button
    document.querySelector('.order-btn')?.addEventListener('click', function() {
        alert('Proceeding to checkout...');
        // Add checkout logic here
    });

    function clearCart() {
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to clear cart');
        });
    }

    // Your existing cart functions
    function increaseQuantity(itemId, itemElement) {
        // Your increase quantity logic
    }

    function decreaseQuantity(itemId, itemElement) {
        // Your decrease quantity logic
    }

    function updateCartItem(itemId, quantity, itemElement) {
        // Your update cart logic
    }
});
</script>
@endpush