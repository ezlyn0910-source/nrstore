@extends('layouts.app')

@section('styles')
    @vite(['resources/css/checkout.css'])
@endsection

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="checkout-container">
            <!-- Checkout Header -->
            <div class="checkout-header">
                <h1>Checkout</h1>
                <div class="checkout-steps">
                    <div class="step active">1. Cart</div>
                    <div class="step active">2. Information</div>
                    <div class="step">3. Shipping</div>
                    <div class="step">4. Payment</div>
                </div>
            </div>

            <div class="checkout-content">
                <!-- Left Column - Forms -->
                <div class="checkout-left">
                    <!-- Shipping Address -->
                    <section class="checkout-section">
                        <h2>Shipping Address</h2>
                        <form id="shippingForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_first_name">First Name *</label>
                                        <input type="text" id="shipping_first_name" name="shipping_first_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_last_name">Last Name *</label>
                                        <input type="text" id="shipping_last_name" name="shipping_last_name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shipping_address">Address *</label>
                                <input type="text" id="shipping_address" name="shipping_address" required>
                            </div>
                            <div class="form-group">
                                <label for="shipping_address2">Address 2 (Optional)</label>
                                <input type="text" id="shipping_address2" name="shipping_address2">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_city">City *</label>
                                        <input type="text" id="shipping_city" name="shipping_city" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_state">State *</label>
                                        <select id="shipping_state" name="shipping_state" required>
                                            <option value="">Select State</option>
                                            <!-- State options -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_postcode">Postcode *</label>
                                        <input type="text" id="shipping_postcode" name="shipping_postcode" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="shipping_phone">Phone *</label>
                                        <input type="tel" id="shipping_phone" name="shipping_phone" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" id="same_billing" name="same_billing" checked>
                                <label for="same_billing">Billing address same as shipping</label>
                            </div>
                        </form>
                    </section>

                    <!-- Billing Address (Hidden by default) -->
                    <section class="checkout-section" id="billingSection" style="display: none;">
                        <h2>Billing Address</h2>
                        <form id="billingForm">
                            <!-- Similar fields as shipping form -->
                        </form>
                    </section>

                    <!-- Shipping Method -->
                    <section class="checkout-section">
                        <h2>Shipping Method</h2>
                        <div class="shipping-methods">
                            <div class="shipping-method">
                                <input type="radio" id="standard" name="shipping_method" value="standard" checked>
                                <label for="standard">
                                    <span class="method-name">Standard Shipping</span>
                                    <span class="method-price">RM10.00</span>
                                    <span class="method-time">5-7 business days</span>
                                </label>
                            </div>
                            <div class="shipping-method">
                                <input type="radio" id="express" name="shipping_method" value="express">
                                <label for="express">
                                    <span class="method-name">Express Shipping</span>
                                    <span class="method-price">RM20.00</span>
                                    <span class="method-time">2-3 business days</span>
                                </label>
                            </div>
                            <div class="shipping-method">
                                <input type="radio" id="next_day" name="shipping_method" value="next_day">
                                <label for="next_day">
                                    <span class="method-name">Next Day Delivery</span>
                                    <span class="method-price">RM35.00</span>
                                    <span class="method-time">Next business day</span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- Payment Method -->
                    <section class="checkout-section">
                        <h2>Payment Method</h2>
                        <div class="payment-methods">
                            <div class="payment-method">
                                <input type="radio" id="credit_card" name="payment_method" value="credit_card" checked>
                                <label for="credit_card">
                                    <span class="method-name">Credit/Debit Card</span>
                                    <div class="card-icons">
                                        <i class="fab fa-cc-visa"></i>
                                        <i class="fab fa-cc-mastercard"></i>
                                        <i class="fab fa-cc-amex"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" id="paypal" name="payment_method" value="paypal">
                                <label for="paypal">
                                    <span class="method-name">PayPal</span>
                                    <i class="fab fa-cc-paypal"></i>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer">
                                <label for="bank_transfer">
                                    <span class="method-name">Bank Transfer</span>
                                    <i class="fas fa-university"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Credit Card Form -->
                        <div id="creditCardForm" class="payment-form">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="card_number">Card Number *</label>
                                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_expiry">Expiry Date *</label>
                                        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_cvc">CVC *</label>
                                        <input type="text" id="card_cvc" name="card_cvc" placeholder="123">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="card_name">Name on Card *</label>
                                <input type="text" id="card_name" name="card_name">
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="checkout-right">
                    <div class="order-summary">
                        <h3>Order Summary</h3>
                        <div class="order-items">
                            @foreach($cartItems as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product->name }}">
                                </div>
                                <div class="item-details">
                                    <h4>{{ $item->product->name }}</h4>
                                    <p class="item-specs">{{ $item->product->specifications }}</p>
                                    <div class="item-meta">
                                        <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                        <span class="item-price">RM{{ number_format($item->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>RM{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span id="shippingCost">RM10.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax</span>
                                <span id="taxAmount">RM{{ number_format($tax, 2) }}</span>
                            </div>
                            @if($discount > 0)
                            <div class="summary-row discount">
                                <span>Discount</span>
                                <span>-RM{{ number_format($discount, 2) }}</span>
                            </div>
                            @endif
                            <div class="summary-divider"></div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span id="totalAmount">RM{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Promo Code -->
                        <div class="promo-code">
                            <label for="promo_code">Promo Code</label>
                            <div class="promo-input">
                                <input type="text" id="promo_code" placeholder="Enter promo code">
                                <button type="button" id="applyPromo">Apply</button>
                            </div>
                        </div>

                        <!-- Terms and Place Order -->
                        <div class="checkout-actions">
                            <div class="terms-agreement">
                                <input type="checkbox" id="agree_terms" required>
                                <label for="agree_terms">I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></label>
                            </div>
                            <button type="button" id="placeOrderBtn" class="btn btn-primary btn-lg">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle billing address
    const sameBillingCheckbox = document.getElementById('same_billing');
    const billingSection = document.getElementById('billingSection');
    
    sameBillingCheckbox.addEventListener('change', function() {
        billingSection.style.display = this.checked ? 'none' : 'block';
    });

    // Update shipping cost
    const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
    shippingMethods.forEach(method => {
        method.addEventListener('change', function() {
            updateOrderSummary();
        });
    });

    // Toggle payment forms
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            togglePaymentForm(this.value);
        });
    });

    // Apply promo code
    document.getElementById('applyPromo').addEventListener('click', function() {
        const promoCode = document.getElementById('promo_code').value;
        applyPromoCode(promoCode);
    });

    // Place order
    document.getElementById('placeOrderBtn').addEventListener('click', function() {
        placeOrder();
    });
});

function updateOrderSummary() {
    const selectedShipping = document.querySelector('input[name="shipping_method"]:checked').value;
    let shippingCost = 10.00; // Default standard shipping
    
    switch(selectedShipping) {
        case 'express':
            shippingCost = 20.00;
            break;
        case 'next_day':
            shippingCost = 35.00;
            break;
    }
    
    document.getElementById('shippingCost').textContent = `RM${shippingCost.toFixed(2)}`;
    calculateTotal();
}

function togglePaymentForm(method) {
    const creditCardForm = document.getElementById('creditCardForm');
    
    if (method === 'credit_card') {
        creditCardForm.style.display = 'block';
    } else {
        creditCardForm.style.display = 'none';
    }
}

function applyPromoCode(promoCode) {
    // Implement promo code validation and application
    fetch('/apply-promo-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ promo_code: promoCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            calculateTotal();
            alert('Promo code applied successfully!');
        } else {
            alert('Invalid promo code: ' + data.message);
        }
    });
}

function calculateTotal() {
    // Calculate total based on subtotal, shipping, tax, and discount
    const subtotal = {{ $subtotal }};
    const shippingCost = parseFloat(document.getElementById('shippingCost').textContent.replace('RM', ''));
    const tax = {{ $tax }};
    const discount = {{ $discount }};
    
    const total = subtotal + shippingCost + tax - discount;
    document.getElementById('totalAmount').textContent = `RM${total.toFixed(2)}`;
}

function placeOrder() {
    const formData = new FormData();
    
    // Collect all form data
    const shippingData = new FormData(document.getElementById('shippingForm'));
    for (let [key, value] of shippingData) {
        formData.append(key, value);
    }
    
    // Add other data
    formData.append('shipping_method', document.querySelector('input[name="shipping_method"]:checked').value);
    formData.append('payment_method', document.querySelector('input[name="payment_method"]:checked').value);
    
    fetch('{{ route("checkout.place-order") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            alert('Error placing order: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error placing order');
    });
}
</script>
@endsection