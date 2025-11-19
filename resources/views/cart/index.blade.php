@extends('layouts.app')

@section('content')
<div class="cart-page">
    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">Shopping Cart</h1>
            <p class="cart-subtitle">Review your items and proceed to checkout</p>
        </div>

        @if($cartItems->count() > 0)
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cartItems as $item)
                <div class="cart-item" data-item-id="{{ $item->id }}">
                    <div class="cart-item-image">
                        <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}">
                    </div>
                    
                    <div class="cart-item-details">
                        <a href="{{ route('products.show', $item->product->slug) }}" class="cart-item-name">
                            {{ $item->product_name }}
                        </a>
                        <div class="cart-item-specs">
                            <!-- Your specs here -->
                        </div>
                        <span class="cart-item-sku">SKU: {{ $item->product->sku ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="cart-item-quantity">
                        <button class="quantity-btn minus-btn" data-action="decrease">-</button>
                        <input type="number" class="quantity-input" value="{{ $item->quantity }}" min="1" max="99">
                        <button class="quantity-btn plus-btn" data-action="increase">+</button>
                    </div>
                    
                    <div class="cart-item-price">
                        <div class="item-price">RM {{ number_format($item->price, 2) }}</div>
                        <div class="item-total">RM {{ number_format($item->subtotal, 2) }}</div>
                    </div>
                    
                    <button class="cart-item-remove" title="Remove item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="summary-title">Order Summary</h3>
                
                <div class="summary-row">
                    <span class="summary-label">Items ({{ $cart->item_count }})</span>
                    <span class="summary-value" id="subtotal">RM {{ number_format($subtotal, 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-label">Calculated at checkout</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Tax</span>
                    <span class="summary-label">Calculated at checkout</span>
                </div>
                
                <div class="summary-divider"></div>
                
                <div class="summary-total">
                    <span>Total</span>
                    <span id="total">RM {{ number_format($total, 2) }}</span>
                </div>
                
                <button class="checkout-btn">
                    Proceed to Checkout
                </button>
                
                <a href="{{ route('products.index') }}" class="continue-shopping">
                    Continue Shopping
                </a>
            </div>
        </div>
        @else
        <!-- Empty Cart State -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2 class="empty-cart-title">Your cart is empty</h2>
            <p class="empty-cart-text">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="shop-now-btn">
                Start Shopping
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons - use increase/decrease endpoints
    document.querySelectorAll('.quantity-btn').forEach(btn => {
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

    // Quantity input change - use update endpoint
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            const item = this.closest('.cart-item');
            const itemId = item.dataset.itemId;
            updateCartItem(itemId, this.value, item);
        });
    });

    // Remove item
    document.querySelectorAll('.cart-item-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = this.closest('.cart-item');
            const itemId = item.dataset.itemId;
            
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                removeCartItem(itemId, item);
            }
        });
    });

    // Add loading states to cart items
    function setCartItemLoading(itemElement, isLoading) {
        if (isLoading) {
            itemElement.classList.add('updating');
        } else {
            itemElement.classList.remove('updating');
        }
    }

    function showCartUpdateAnimation(itemElement) {
        itemElement.classList.add('updated');
        setTimeout(() => {
            itemElement.classList.remove('updated');
        }, 600);
    }

    function increaseQuantity(itemId, itemElement) {
        setCartItemLoading(itemElement, true);
        
        fetch(`/cart/increase/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            setCartItemLoading(itemElement, false);
            if (data.success) {
                showCartUpdateAnimation(itemElement);
                updateCartUI(data, itemElement);
            }
        })
        .catch(error => {
            setCartItemLoading(itemElement, false);
            console.error('Error:', error);
        });
    }

    function decreaseQuantity(itemId, itemElement) {
        setCartItemLoading(itemElement, true);
        
        fetch(`/cart/decrease/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            setCartItemLoading(itemElement, false);
            if (data.success) {
                showCartUpdateAnimation(itemElement);
                updateCartUI(data, itemElement);
            }
        })
        .catch(error => {
            setCartItemLoading(itemElement, false);
            console.error('Error:', error);
        });
    }

    function updateCartItem(itemId, itemElement) {
        setCartItemLoading(itemElement, true);
        
        fetch(`/cart/update/${itemId}`, {
            method: 'UPDATE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            setCartItemLoading(itemElement, false);
            if (data.success) {
                showCartUpdateAnimation(itemElement);
                updateCartUI(data, itemElement);
            }
        })
        .catch(error => {
            setCartItemLoading(itemElement, false);
            console.error('Error:', error);
        });
    }

    function removeCartItem(itemId, itemElement) {
        setCartItemLoading(itemElement, true);
        
        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            setCartItemLoading(itemElement, false);
            if (data.success) {
                showCartUpdateAnimation(itemElement);
                updateCartUI(data, itemElement);
            }
        })
        .catch(error => {
            setCartItemLoading(itemElement, false);
            console.error('Error:', error);
        });
    }

    function updateCartUI(data, itemElement = null) {
        // Update cart totals
        document.getElementById('subtotal').textContent = data.cart_total;
        document.getElementById('total').textContent = data.cart_total;
        
        // Update item if provided
        if (itemElement && data.quantity !== undefined) {
            itemElement.querySelector('.quantity-input').value = data.quantity;
            if (data.item_total) {
                itemElement.querySelector('.item-total').textContent = data.item_total;
            }
        }
        
        // Update header cart count
        updateHeaderCartCount(data.cart_count);
    }

    function updateHeaderCartCount(count) {
        const cartBadge = document.querySelector('#cart-icon .action-badge');
        if (cartBadge) {
            if (count > 0) {
                cartBadge.textContent = count;
                cartBadge.style.display = 'flex';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    }
});
</script>
@endpush