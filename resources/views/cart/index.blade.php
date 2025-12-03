@extends('layouts.app')

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
            <!-- Debug: Check what data we have -->
            <div style="display: none; color: red; font-size: 10px;">
                Item ID: {{ $item->id }}<br>
                Product ID: {{ $item->product_id }}<br>
                Product exists: {{ isset($item->product) ? 'YES' : 'NO' }}<br>
                Product name from relation: {{ $item->product->name ?? 'NOT FOUND' }}<br>
                Item product_name: {{ $item->product_name ?? 'NOT SET' }}<br>
                Item name: {{ $item->name ?? 'NOT SET' }}
            </div>
            
            <a href="{{ route('products.show', $item->product->slug ?? '#') }}" class="product-name">
                <!-- Try different ways to get product name -->
                @if(isset($item->product) && $item->product)
                    {{ $item->product->name }}
                @elseif(isset($item->product_name))
                    {{ $item->product_name }}
                @else
                    Product #{{ $item->product_id }}
                @endif
            </a>
            
            <!-- Specs on second row -->
            @php
                $specs = [];
                if (isset($item->product) && $item->product) {
                    if (!empty($item->product->processor)) $specs[] = $item->product->processor;
                    if (!empty($item->product->ram)) $specs[] = $item->product->ram . ' RAM';
                    if (!empty($item->product->storage)) $specs[] = $item->product->storage . ' SSD';
                }
            @endphp
            
            @if(!empty($specs))
                <div class="product-specs">{{ implode(', ', $specs) }}</div>
            @endif
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
                <!-- Updated ORDER NOW button that redirects to checkout -->
                <a href="{{ route('checkout.index') }}" class="order-btn">
                    ORDER NOW
                </a>
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

    // Order now button - with validation
    document.querySelector('.order-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        validateAndProceedToCheckout();
    });

    // Function to validate cart and proceed to checkout
    async function validateAndProceedToCheckout() {
        try {
            // Show loading state
            const orderBtn = document.querySelector('.order-btn');
            const originalText = orderBtn.textContent;
            orderBtn.textContent = 'Validating...';
            orderBtn.disabled = true;

            // Validation 1: Check if cart is empty
            const cartItemsCount = {{ $cartItems->count() }};
            if (cartItemsCount === 0) {
                showValidationError('Your cart is empty. Please add items to your cart before proceeding to checkout.');
                resetButtonState(orderBtn, originalText);
                return;
            }

            // Validation 2: Check user authentication
            const isAuthenticated = await checkUserAuthentication();
            if (!isAuthenticated) {
                showValidationError('Please log in to proceed with your order.');
                resetButtonState(orderBtn, originalText);
                
                // Redirect to login page
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}?redirect=checkout';
                }, 2000);
                return;
            }

            // Validation 3: Check stock availability
            const stockValidation = await validateStockAvailability();
            console.log('Stock validation result:', stockValidation); // Debug log
            
            if (!stockValidation.valid) {
                let errorMessage = 'Sorry, some items are out of stock. Please update your cart.';
                
                if (stockValidation.outOfStockItems > 0) {
                    if (stockValidation.details && stockValidation.details.length > 0) {
                        // Show specific out of stock items
                        const itemNames = stockValidation.details.map(item => 
                            `${item.product_name} (Available: ${item.available}, Requested: ${item.requested})`
                        ).join(', ');
                        errorMessage = `The following items are out of stock: ${itemNames}. Please update quantities or remove items.`;
                    } else {
                        errorMessage = `Sorry, ${stockValidation.outOfStockItems} item(s) are out of stock. Please update your cart.`;
                    }
                }
                
                showValidationError(errorMessage);
                resetButtonState(orderBtn, originalText);
                return;
            }

            // Validation 4: Check minimum order amount (if applicable)
            const subtotal = {{ $subtotal }};
            const minOrderAmount = 10.00;
            if (subtotal < minOrderAmount) {
                showValidationError(`Minimum order amount is RM ${minOrderAmount.toFixed(2)}. Please add more items to your cart.`);
                resetButtonState(orderBtn, originalText);
                return;
            }

            // All validations passed - proceed to checkout
            window.location.href = '{{ route("checkout.index") }}';

        } catch (error) {
            console.error('Validation error:', error);
            showValidationError('An error occurred while validating your cart. Please try again.');
            resetButtonState(orderBtn, originalText);
        }
    }

    // Helper function to check user authentication
    async function checkUserAuthentication() {
        try {
            const response = await fetch('{{ route("api.check.auth") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            return data.authenticated;
        } catch (error) {
            console.error('Auth check failed:', error);
            return false;
        }
    }

    // Helper function to validate stock availability
    async function validateStockAvailability() {
        try {
            const response = await fetch('{{ route("cart.validate.stock") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            return await response.json();
        } catch (error) {
            console.error('Stock validation failed:', error);
            return { 
                valid: false, 
                outOfStockItems: 0,
                message: 'Error checking stock availability'
            };
        }
    }

    // Helper function to show validation errors
    function showValidationError(message) {
        alert(message);
    }

    // Helper function to reset button state
    function resetButtonState(button, originalText) {
        button.textContent = originalText;
        button.disabled = false;
    }

    // Cart quantity functions
    function increaseQuantity(itemId, itemElement) {
        const input = itemElement.querySelector('.qty-input');
        const currentValue = parseInt(input.value);
        if (currentValue < 99) {
            input.value = currentValue + 1;
            updateCartItem(itemId, currentValue + 1, itemElement);
        }
    }

    function decreaseQuantity(itemId, itemElement) {
        const input = itemElement.querySelector('.qty-input');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
            updateCartItem(itemId, currentValue - 1, itemElement);
        }
    }

    function updateCartItem(itemId, quantity, itemElement) {
        fetch(`/cart/update/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const itemTotal = itemElement.querySelector('.item-total');
                itemTotal.textContent = `RM ${data.item_subtotal.toFixed(2)}`;
                
                const subtotalElement = document.getElementById('subtotal');
                if (subtotalElement) {
                    subtotalElement.textContent = `RM ${data.cart_subtotal.toFixed(2)}`;
                }
            } else {
                alert('Failed to update cart: ' + data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating cart');
            location.reload();
        });
    }

    function clearCart() {
        fetch('{{ route("cart.clear") }}', {
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
});
</script>
@endpush

@section('styles')
<style>
    .simple-cart-page {
        min-height: 70vh;
        background: white;
        font-family: "Nunito", sans-serif;
    }

    /* Banner */
    .cart-banner {
        background: #2c3e50;
        padding: 60px 3rem;
        text-align: center;
        margin-bottom: 0;
    }

    .banner-content h1 {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
    }

    /* Container */
    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 3rem;
    }

    /* Cart Header */
    .cart-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 20px;
        padding: 20px;
        background: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        color: #2c3e50;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 0;
        margin-top: 50px;
        text-align: center;
    }

    .cart-header .header-total {
        text-align: center;
    }


    /* Cart Items */
    .cart-items {
        background: white;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 20px;
        align-items: center;
        padding: 25px 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    /* Product Info */
    .product-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .product-image {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
        flex-shrink: 0;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        flex: 1;
        line-height: 1.2;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        text-decoration: none;
        display: block;
        line-height: 1.3;
    }

    .product-name:hover {
        color: #3498db;
    }

    .product-specs {
        color: #666;
        font-size: 0.85em;
        margin-top: 3px;
        line-height: 1.2;
    }

    .product-sku {
        color: #888;
        font-size: 0.8em;
        display: block;
        margin-top: 2px;
    }

    /* Unit Price */
    .unit-price {
        text-align: center;
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }

    /* Quantity Controls */
    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .qty-btn {
        width: 20px;
        height: 20px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .qty-btn:hover {
        background: #e9ecef;
        border-color: #2c3e50;
    }

    .qty-input {
        width: 30px;
        height: 30px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0;
        -moz-appearance: textfield; /* Remove spinner in Firefox */
    }

    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .qty-input:focus {
        outline: none;
        border-color: #2c3e50;
    }

    /* Item Total */
    .item-total {
        text-align: center;
        font-weight: 700;
        color: #2c3e50;
        font-size: 1rem;
        white-space: nowrap;
        padding-right: 10px; 
    }

    /* Cart Footer */
    .cart-footer {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 20px;
        margin-top: 10px;
        padding: 30px 20px 0 20px; 
        align-items: start;
    }

    .footer-left {
        grid-column: 1 / 2; 
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .continue-btn {
        padding: 12px 20px;
        background: #2c3e50;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        transition: all 0.3s;
        display: inline-block;
        white-space: nowrap;
        text-align: center;
        min-width: 140px;
    }

    .continue-btn:hover {
        background: #34495e;
        color: white;
    }

    .clear-btn {
        padding: 12px 20px;
        background: transparent;
        color: #6c757d;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
        min-width: 140px;
    }

    .clear-btn:hover {
        background: #f8f9fa;
        color: #495057;
    }

    .footer-right {
        grid-column: 4;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: -30px;
    }

    .subtotal-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-size: 1.1rem;
        gap: 15px;
        width: 100%;
        max-width: 200px;
        white-space: nowrap;
        margin-bottom: 50px;
    }

    .subtotal-amount {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        min-width: 110px; 
        text-align: right;
        white-space: nowrap;
    }

    .divider {
        height: 1px;
        background: #e9ecef;
        margin: 20px 0;
        width: 100%;
        max-width: 200px;
    }

    .order-btn {
        display: inline-block;
        padding: 12px 30px;
        background-color: #2c3e50;
        color: #fff;
        text-decoration: none;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        text-align: center;
        transition: background-color 0.3s;
        width: 100%;
        max-width: 200px; 
        box-sizing: border-box;
        white-space: nowrap;
        margin-bottom: 50px;
    }

    .order-btn:hover {
        background-color: #333;
        color: #fff;
        text-decoration: none;
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
        max-width: 500px;
        margin: 0 auto;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .empty-cart h2 {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .empty-cart p {
        color: #6c757d;
        margin-bottom: 25px;
        font-size: 1rem;
    }

    .start-shopping-btn {
        padding: 12px 30px;
        background: #2c3e50;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        transition: all 0.3s;
        display: inline-block;
    }

    .start-shopping-btn:hover {
        background: #34495e;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .cart-header {
            display: none;
        }

        .cart-item {
            grid-template-columns: 1fr;
            gap: 15px;
            text-align: center;
            padding: 20px;
        }

        .product-info {
            flex-direction: column;
            text-align: center;
        }

        .cart-footer {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px;
        }

        .footer-left {
            grid-column: 1;
            flex-direction: row; /* Keep buttons in row on mobile */
            justify-content: center;
            width: 100%;
            flex-wrap: wrap; /* Allow wrapping if needed */
        }

        .footer-right {
            grid-column: 1;
            text-align: center;
            align-items: center;
            width: 100%;
            margin-left: 0; /* Reset adjustment */
        }

        .continue-btn,
        .clear-btn {
            width: auto; /* Don't force full width */
            min-width: 140px;
            font-size: 0.75rem; /* Even smaller on mobile */
            padding: 10px 15px;
        }

        .order-summary {
            min-width: auto;
            width: 100%;
        }

        .banner-content h1 {
            font-size: 2rem;
        }

        .subtotal-row {
            justify-content: center;
            width: 100%;
            max-width: 250px; 
        }

        .order-btn {
            width: 100%;
            max-width: 250px; 
        }

        .divider {
            max-width: 250px; 
        }
    }

    @media (max-width: 480px) {
        .cart-container {
            padding: 0 15px;
        }

        .banner-content h1 {
            font-size: 1.75rem;
        }

        .empty-cart {
            padding: 40px 20px;
        }

        .product-image {
            width: 60px;
            height: 60px;
        }
    }
</style>
@endsection