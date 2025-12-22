@extends('layouts.app')

@section('content')
<div class="simple-cart-page">
    {{-- Remove from Cart alert --}}
    <div id="removeFromCartAlert"
        style="
            display:none;
            position: fixed;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #fca5a5;
            background: #fee2e2;
            color: #991b1b;
            font-size: 0.95rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transition: top 0.3s ease; /* Smooth transition */
        ">
        The item has been removed
    </div>

    {{-- Only show banner when there are items in the cart --}}
    @if($cartItems->count() > 0)
        <!-- Banner -->
        <div class="cart-banner">
            <div class="banner-content">
                <h1 class="cart-title">Shopping Cart</h1>

                <p class="cart-subtitle">
                    Review your items & proceed to secure checkout
                </p>

                <div class="cart-arrow">⌄</div>
            </div>
        </div>
    @endif

    <div class="cart-container">
        @if($cartItems->count() > 0)
        <!-- Cart Header -->
        <div class="cart-header">
            <div class="header-product">PRODUCT NAME</div>
            <div class="header-price">UNIT PRICE</div>
            <div class="header-quantity">QUANTITY</div>
            <div class="header-total">TOTAL</div>
            <div></div> <!-- X icon column -->
        </div>

<!-- Cart Items -->
@foreach($cartItems as $item)
<div class="cart-item"
     data-item-id="{{ $item->id }}"
     data-update-url="{{ route('cart.update', $item->id) }}"
     data-unit-price="{{ $item->price }}">
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
        RM {{ number_format($item->current_subtotal ?? $item->subtotal, 2) }}
    </div>

    <!-- REMOVE ICON -->
    <div class="remove-item" data-id="{{ $item->id }}">×</div>
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
                <a href="{{ route('checkout.index') }}" class="order-btn" onclick="clearBuyNowSession(event)">
                    ORDER NOW
                </a>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <h2>YOUR CART IS EMPTY</h2>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="start-shopping-btn">
                START SHOPPING
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Clear Cart Confirmation Modal -->
<div id="clearCartModal" class="clear-cart-overlay" style="display:none;">
    <div class="clear-cart-modal">
        <div class="clear-cart-icon">
            <span>!</span>
        </div>

        <h3 class="clear-cart-title">Clear shopping cart?</h3>
        <p class="clear-cart-text">
            This will remove <strong>all items</strong> from your cart. 
            Are you sure you want to continue?
        </p>

        <div class="clear-cart-actions">
            <button type="button" class="btn-clear-cancel" id="clearCartCancelBtn">
                Keep my items
            </button>
            <button type="button" class="btn-clear-confirm" id="clearCartConfirmBtn">
                Yes, clear cart
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove alert element
    const removeAlert = document.getElementById('removeFromCartAlert');

    function showRemoveAlert() {
        if (!removeAlert) return;
        
        // Ensure it's positioned correctly even when scrolling
        removeAlert.style.top = '20px';
        removeAlert.style.left = '50%';
        removeAlert.style.transform = 'translateX(-50%)';
        removeAlert.style.display = 'block';
        
        // Ensure it stays on top of other content
        removeAlert.style.zIndex = '9999';
        
        setTimeout(() => {
            removeAlert.style.display = 'none';
        }, 2000); // show for 2 seconds
    }

    function clearBuyNowSession(event) {
        // Prevent immediate navigation
        event.preventDefault();
        
        // Clear buy now session before navigating to checkout
        fetch('{{ route("checkout.clear-buy-now") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => {
            // Navigate to checkout after clearing session
            window.location.href = '{{ route("checkout.index") }}';
        })
        .catch(err => {
            console.error('Failed to clear buy now session:', err);
            // Still navigate to checkout even if API call fails
            window.location.href = '{{ route("checkout.index") }}';
        });
    }

    window.addEventListener('scroll', function() {
        if (removeAlert && removeAlert.style.display === 'block') {
            // Force the alert to stay at fixed top position
            removeAlert.style.top = '20px';
            removeAlert.style.left = '50%';
            removeAlert.style.transform = 'translateX(-50%)';
        }
    });

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

    // Remove item via X icon
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function () {
            const itemElement = this.closest('.cart-item');
            const itemId      = this.dataset.id;
            const prevQty     = parseInt(itemElement.querySelector('.qty-input').value) || 1;

            // optimistic UI
            optimisticRemoveItem(itemElement);
            updateCartItem(itemId, 0, itemElement, prevQty);
        });
    });

    // ---------- Clear Cart: custom modal ----------
    const clearBtn          = document.querySelector('.clear-btn');
    const clearCartModal    = document.getElementById('clearCartModal');
    const clearCartCancel   = document.getElementById('clearCartCancelBtn');
    const clearCartConfirm  = document.getElementById('clearCartConfirmBtn');

    function openClearCartModal() {
        if (!clearCartModal) return;
        clearCartModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeClearCartModal() {
        if (!clearCartModal) return;
        clearCartModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    if (clearBtn && clearCartModal && clearCartCancel && clearCartConfirm) {
        // Open modal
        clearBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openClearCartModal();
        });

        // Cancel → close modal
        clearCartCancel.addEventListener('click', function () {
            closeClearCartModal();
        });

        // Confirm → call existing clearCart() + close modal
        clearCartConfirm.addEventListener('click', function () {
            clearCartConfirm.disabled = true;
            clearCartConfirm.textContent = 'Clearing...';

            clearCart();   // uses your existing clearCart() function

            // We let the backend finish & page reload; just close modal visually
            closeClearCartModal();
        });

        // Click on backdrop to close
        clearCartModal.addEventListener('click', function (e) {
            if (e.target === clearCartModal) {
                closeClearCartModal();
            }
        });
    }

    // Order Now button
    document.querySelector('.order-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '{{ route("checkout.index") }}';
    });

    // Helper function to reset button state
    function resetButtonState(button, originalText) {
        button.textContent = originalText;
        button.disabled = false;
    }

    // Cart quantity functions
    function increaseQuantity(itemId, itemElement) {
        const input = itemElement.querySelector('.qty-input');
        const currentValue = parseInt(input.value) || 1;

        if (currentValue < 99) {
            const newQty = currentValue + 1;
            // optimistic UI
            optimisticUpdateItem(itemElement, currentValue, newQty);
            updateCartItem(itemId, newQty, itemElement, currentValue);
        }
    }

    function decreaseQuantity(itemId, itemElement) {
        const input = itemElement.querySelector('.qty-input');
        const currentValue = parseInt(input.value) || 1;

        if (currentValue > 1) {
            const newQty = currentValue - 1;
            // optimistic UI
            optimisticUpdateItem(itemElement, currentValue, newQty);
            updateCartItem(itemId, newQty, itemElement, currentValue);
        } else if (currentValue === 1) {
            // going to 0 = remove
            optimisticRemoveItem(itemElement);
            updateCartItem(itemId, 0, itemElement, currentValue);
        }
    }

    function parseMoney(text) {
        // turns "RM 3,850.00" → 3850
        return parseFloat(String(text).replace(/[^\d.]/g, '')) || 0;
    }

    // Update item row + subtotal immediately
    function optimisticUpdateItem(itemElement, oldQty, newQty) {
        const unitPrice = parseFloat(itemElement.dataset.unitPrice || '0');
        const itemTotalEl = itemElement.querySelector('.item-total');
        const qtyInput    = itemElement.querySelector('.qty-input');
        const subtotalEl  = document.getElementById('subtotal');

        if (!itemTotalEl || !qtyInput || !subtotalEl || !unitPrice) return;

        const oldItemTotal = unitPrice * oldQty;
        const newItemTotal = unitPrice * newQty;

        const currentSubtotal = parseMoney(subtotalEl.textContent);
        const newSubtotal     = currentSubtotal - oldItemTotal + newItemTotal;

        // instant UI updates
        qtyInput.value = newQty;
        itemTotalEl.textContent = 'RM ' + newItemTotal.toFixed(2);
        subtotalEl.textContent  = 'RM ' + newSubtotal.toFixed(2);
    }

    // Fade + collapse row instantly when removing
    function optimisticRemoveItem(itemElement) {
        const itemTotalEl = itemElement.querySelector('.item-total');
        const subtotalEl  = document.getElementById('subtotal');

        if (itemTotalEl && subtotalEl) {
            const itemTotal       = parseMoney(itemTotalEl.textContent);
            const currentSubtotal = parseMoney(subtotalEl.textContent);
            const newSubtotal     = Math.max(0, currentSubtotal - itemTotal);
            subtotalEl.textContent = 'RM ' + newSubtotal.toFixed(2);
        }

        // nice quick animation
        itemElement.style.transition = 'opacity 0.2s ease, height 0.2s ease, margin 0.2s ease, padding 0.2s ease';
        itemElement.style.opacity = '0';
        itemElement.style.height  = '0';
        itemElement.style.margin  = '0';
        itemElement.style.paddingTop = '0';
        itemElement.style.paddingBottom = '0';

        setTimeout(() => {
            itemElement.remove();
        }, 200);
    }

    function updateCartItem(itemId, quantity, itemElement, previousQuantity) {
        const updateUrl = itemElement.dataset.updateUrl;

        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            // if backend fails, just log it – UI already changed instantly
            if (!data || !data.success) {
                console.warn('Cart update failed:', data && data.message ? data.message : data);
                // Optional: you could revert UI here using previousQuantity if you want
                return;
            }

            // Backend confirms removal
            if (data.removed) {
                // row is already visually removed; nothing to do
                showRemoveAlert();
            } else {
                // Make sure UI matches server numbers (in case of rounding, discounts, etc.)
                const itemTotalEl = itemElement.querySelector('.item-total');
                const qtyInput    = itemElement.querySelector('.qty-input');
                const subtotalEl  = document.getElementById('subtotal');

                if (qtyInput)    qtyInput.value = quantity;
                if (itemTotalEl) itemTotalEl.textContent = data.item_total_html || itemTotalEl.textContent;
                if (subtotalEl)  subtotalEl.textContent  = data.cart_total_html  || subtotalEl.textContent;
            }

            // Update header cart count
            if (typeof data.cart_count !== 'undefined') {
                const cartBadge = document.querySelector('.cart-count, .cart-count-badge, .cart-items-count');
                if (cartBadge) cartBadge.textContent = data.cart_count;
            }
        })
        .catch(err => {
            console.error("Cart update error:", err);
            // Optional: revert UI to previousQuantity here if needed
        });
    }

    function clearCart() {
        fetch('{{ route("cart.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(async response => {
            const rawText = await response.text();

            try {
                const data = JSON.parse(rawText);
                if (data.success) {
                    // Either reload or fully clear DOM. Reload is simplest and always correct.
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to clear cart');
                }
            } catch (e) {
                console.error('Clear cart: response not JSON:', rawText);
                if (response.ok) {
                    // Cart most likely cleared, just reload.
                    window.location.reload();
                    return;
                }
                throw e;
            }
        })
        .catch(error => {
            console.error('Error clearing cart:', error);
            alert('Failed to clear cart');
        });
    }
});
</script>
@endpush

@section('styles')
<style>
    .simple-cart-page {
        min-height: 50vh;
        background: white;
        font-family: "Nunito", sans-serif;
    }

    /* Banner */
    .cart-banner {
        background: white;
        padding: 50px 3rem;
        text-align: center;
        margin-bottom: 0;
    }

    .banner-content h1 {
        color: #2d4a35;
        font-size: 2.5rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
    }

    /* Container */
    .cart-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 48px;
        box-sizing: border-box;
    }

    .cart-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d4a35;
        letter-spacing: 2px;
        margin-bottom: 5px;
    }

    .cart-subtitle {
        font-size: 1rem;
        color: #6b7280;
        margin-top: 0;
        opacity: 0.9;
    }

    .cart-arrow {
        margin-top: -20px;
        margin-bottom: -50px;
        font-size: 3rem;
        color: 	#AFE1AF;
        animation: arrowPulse 1.5s infinite ease-in-out;
    }

    @keyframes arrowPulse {
        0% { transform: translateY(0); opacity: 0.7; }
        50% { transform: translateY(4px); opacity: 1; }
        100% { transform: translateY(0); opacity: 0.7; }
    }

    .cart-header,
    .cart-item {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 40px !important;
        column-gap: 20px;
        width: 100%;
        align-items: center;
    }

    /* X icon style */
    .remove-item {
        text-align: center;
        cursor: pointer;
        font-size: 1.3rem;
        color: 000000;
        font-weight: bold;
        transition: 0.2s;
    }

    .remove-item:hover {
        color: #ff0000;
        transform: scale(1.2);
    }

    /* Cart Header */
    .cart-header {
        padding: 20px 0;
        background: #2d4a35;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        color: white;
        border-bottom: 2px solid #e9ecef;
        margin: 50px 0 0;
    }

    .cart-header > div:nth-child(1) {
        text-align: center;
    }
    .cart-header > div:nth-child(n+2) {
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
        padding: 25px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item > div:nth-child(1) {
        text-align: left;
    }
    .cart-item > div:nth-child(n+2) {
        text-align: center;
    }

    .product-info,
    .product-details {
        min-width: 0 !important;
    }

    /* Product Info */
    .product-info {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0 !important;
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
        min-width: 0 !important;
    }

    .product-name,
    .product-specs {
        white-space: normal !important;
        overflow: hidden;
        word-break: break-word;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        text-decoration: none;
        display: block;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-name:hover {
        color: #3498db;
    }

    .product-specs {
        color: #666;
        font-size: 0.85em;
        margin-top: 3px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-sku {
        color: #888;
        font-size: 0.8em;
        display: block;
        margin-top: 2px;
    }

    .unit-price,
    .quantity-controls,
    .item-total {
        min-width: 0 !important;
        text-align: center;
    }

    /* Unit Price */
    .unit-price {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.1rem;
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
        font-weight: 700;
        color: #2c3e50;
        font-size: 1rem;
        white-space: nowrap;
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
        background: #2d4a35;
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

    /* Clear Cart Modal */
    .clear-cart-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(3px);
    }

    .clear-cart-modal {
        background: #ffffff;
        border-radius: 16px;
        max-width: 420px;
        width: 90%;
        padding: 1.75rem 1.75rem 1.5rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
        position: relative;
        text-align: center;
        border: 1px solid #e5e7eb;
    }

    .clear-cart-icon {
        width: 52px;
        height: 52px;
        border-radius: 999px;
        margin: 0 auto 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .clear-cart-icon span {
        font-weight: 800;
        font-size: 1.4rem;
        color: #ef4444;
    }

    .clear-cart-title {
        margin: 0 0 0.5rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2933;
    }

    .clear-cart-text {
        margin: 0 0 1.5rem;
        font-size: 0.95rem;
        color: #6b7280;
        line-height: 1.5;
    }

    .clear-cart-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
    }

    .btn-clear-cancel,
    .btn-clear-confirm {
        flex: 1;
        padding: 0.7rem 1rem;
        border-radius: 999px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s ease;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .btn-clear-cancel {
        background: #ffffff;
        color: #4b5563;
        border-color: #d1d5db;
    }

    .btn-clear-cancel:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    .btn-clear-confirm {
        background: #2d4a35;        /* theme green */
        color: #ffffff;
        border-color: #2d4a35;
    }

    .btn-clear-confirm:hover {
        background: #23402b;
        border-color: #23402b;
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
        background-color: #2d4a35;
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
        padding: 80px 30px;
        background: white;
        border-radius: 12px;
        max-width: 500px;
        margin: 100px auto 0 auto;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
    }

    .empty-cart h2 {
        font-size: 1.75rem;
        color: #2d4a35;
        margin-bottom: 15px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .empty-cart p {
        color: #6c757d;
        margin-bottom: 30px;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .simple-cart-page:has(.empty-cart) {
        background: white !important;
        padding-top: 50px !important;
        padding-bottom: 50px !important;
    }

    .simple-cart-page:has(.empty-cart) .cart-banner {
        display: none !important;
    }

    .simple-cart-page:has(.empty-cart) .cart-container {
        margin-top: 0 !important;
        padding-top: 0 !important;
        background: white !important;
    }

    .start-shopping-btn {
        padding: 12px 30px;
        background: #2d4a35;
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
        background: #AFE1AF;
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
            padding: 50px 20px;
            margin: 60px auto 0 auto;
        }

        .product-image {
            width: 60px;
            height: 60px;
        }

        .empty-cart h2 {
            font-size: 1.5rem;
        }
        
        .empty-cart p {
            font-size: 1rem;
        }

        .clear-cart-modal {
            padding: 1.5rem 1.25rem 1.25rem;
        }

        .clear-cart-actions {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .simple-cart-page:has(.empty-cart) .cart-container {
            margin: 60px auto; /* Smaller increase for mobile */
        }
    }

</style>
@endsection