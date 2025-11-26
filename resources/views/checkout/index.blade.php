@extends('layouts.app')

@section('styles')
    @vite(['resources/css/checkout.css'])
@endsection

@section('content')
<div class="checkout-page">
    <!-- Banner -->
    <div class="checkout-banner">
        <div class="banner-content">
            <h1>CHECKOUT</h1>
        </div>
    </div>

    <div class="checkout-container">
        <div class="checkout-content">
            <!-- Left Column - Forms -->
            <div class="checkout-left">
                <!-- Shipping Address Section -->
                <section class="checkout-section">
                    <h2>Shipping Address</h2>
                    
                    @php
                        // Temporary fix - if addresses variable doesn't exist, treat as empty
                        $userAddresses = $addresses ?? collect();
                    @endphp
                    
                    @if($userAddresses->count() > 0)
                        <!-- Display saved addresses -->
                        <div class="address-list">
                            @foreach($userAddresses as $address)
                            <div class="address-item">
                                <input type="radio" 
                                       id="address_{{ $address->id }}" 
                                       name="selected_address" 
                                       value="{{ $address->id }}"
                                       {{ $address->is_primary ? 'checked' : '' }}
                                       class="address-radio">
                                <label for="address_{{ $address->id }}" class="address-label">
                                    <div class="address-header">
                                        <strong>{{ $address->first_name }} {{ $address->last_name }}</strong>
                                        @if($address->is_primary)
                                            <span class="primary-badge">Primary</span>
                                        @endif
                                    </div>
                                    <div class="address-details">
                                        <p>{{ $address->address }}</p>
                                        @if($address->address2)
                                            <p>{{ $address->address2 }}</p>
                                        @endif
                                        <p>{{ $address->city }}, {{ $address->state }} {{ $address->postcode }}</p>
                                        <p>Phone: {{ $address->phone }}</p>
                                        @if($address->email)
                                            <p>Email: {{ $address->email }}</p>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- No addresses message -->
                        <div class="no-address">
                            <div class="no-address-icon">📍</div>
                            <h3>No Address Yet</h3>
                            <p>Add address to continue with your order</p>
                        </div>
                    @endif

                    <!-- Add New Address Button -->
                    <button type="button" class="add-address-btn" id="addAddressBtn">
                        + Add New Address
                    </button>
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
                                @php
                                    // Safe image handling - check if product has images
                                    $imageUrl = asset('images/default-product.png'); // Default image
                                    
                                    if ($item->product && $item->product->images && $item->product->images->isNotEmpty()) {
                                        $firstImage = $item->product->images->first();
                                        if ($firstImage && $firstImage->image_path) {
                                            $imageUrl = asset('storage/' . $firstImage->image_path);
                                        }
                                    } elseif ($item->product && $item->product->image) {
                                        // Fallback to main product image
                                        $imageUrl = asset('storage/' . $item->product->image);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $item->product->name ?? 'Product' }}" onerror="this.src='{{ asset('images/default-product.png') }}'">
                            </div>
                            <div class="item-details">
                                <h4>{{ $item->product->name ?? 'Product' }}</h4>
                                @if($item->product)
                                    <p class="item-specs">
                                        @if($item->product->brand)
                                            <span class="spec">{{ $item->product->brand }}</span>
                                        @endif
                                        @if($item->product->ram)
                                            <span class="spec">{{ $item->product->ram }} RAM</span>
                                        @endif
                                        @if($item->product->storage)
                                            <span class="spec">{{ $item->product->storage }} Storage</span>
                                        @endif
                                    </p>
                                @endif
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
                            <span>FREE</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span>RM{{ number_format($tax ?? 0, 2) }}</span>
                        </div>
                        @if(($discount ?? 0) > 0)
                        <div class="summary-row discount">
                            <span>Discount</span>
                            <span>-RM{{ number_format($discount ?? 0, 2) }}</span>
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

<!-- Add Address Modal -->
<div id="addressModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Address</h3>
            <span class="close">&times;</span>
        </div>
        <form id="addressForm">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="state">State *</label>
                    <select id="state" name="state" required>
                        <option value="">Select State</option>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Labuan">Labuan</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Penang">Penang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Putrajaya">Putrajaya</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">City/Region *</label>
                    <select id="city" name="city" required disabled>
                        <option value="">Select State First</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="postcode">Postal Code *</label>
                    <input type="text" id="postcode" name="postcode" required>
                </div>

                <div class="form-group">
                    <label for="address">Address *</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="address2">Address 2 (Optional)</label>
                    <textarea id="address2" name="address2" rows="2"></textarea>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="is_primary" name="is_primary">
                    <label for="is_primary">Set as primary address</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelAddress">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Address</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Malaysian states and their cities
const malaysiaCities = {
    'Johor': ['Johor Bahru', 'Batu Pahat', 'Muar', 'Segamat', 'Kluang', 'Kota Tinggi', 'Pontian', 'Mersing', 'Kulai', 'Tangkak'],
    'Kedah': ['Alor Setar', 'Sungai Petani', 'Kulim', 'Langkawi', 'Yan', 'Baling', 'Kuala Muda', 'Kubang Pasu'],
    'Kelantan': ['Kota Bharu', 'Pasir Mas', 'Tanah Merah', 'Pasir Puteh', 'Bachok', 'Kuala Krai', 'Machang', 'Jeli'],
    'Kuala Lumpur': ['Kuala Lumpur'],
    'Labuan': ['Labuan'],
    'Melaka': ['Melaka City', 'Alor Gajah', 'Jasin'],
    'Negeri Sembilan': ['Seremban', 'Port Dickson', 'Nilai', 'Rembau', 'Jempol', 'Kuala Pilah'],
    'Pahang': ['Kuantan', 'Temerloh', 'Bentong', 'Raub', 'Jerantut', 'Cameron Highlands', 'Genting Highlands'],
    'Penang': ['George Town', 'Butterworth', 'Bayan Lepas', 'Batu Ferringhi', 'Nibong Tebal'],
    'Perak': ['Ipoh', 'Taiping', 'Teluk Intan', 'Sitiawan', 'Kuala Kangsar', 'Lumut', 'Batu Gajah'],
    'Perlis': ['Kangar', 'Arau'],
    'Putrajaya': ['Putrajaya'],
    'Sabah': ['Kota Kinabalu', 'Sandakan', 'Tawau', 'Lahad Datu', 'Keningau', 'Semporna'],
    'Sarawak': ['Kuching', 'Miri', 'Sibu', 'Bintulu', 'Limbang', 'Sri Aman', 'Sarikei'],
    'Selangor': ['Shah Alam', 'Petaling Jaya', 'Subang Jaya', 'Klang', 'Kajang', 'Ampang', 'Selayang', 'Rawang'],
    'Terengganu': ['Kuala Terengganu', 'Kemaman', 'Dungun', 'Marang', 'Hulu Terengganu']
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addressModal');
    const addBtn = document.getElementById('addAddressBtn');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancelAddress');
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');

    // Open modal
    addBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    // Close modal
    function closeModal() {
        modal.style.display = 'none';
        document.getElementById('addressForm').reset();
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Select State First</option>';
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // State change handler
    stateSelect.addEventListener('change', function() {
        const selectedState = this.value;
        citySelect.innerHTML = '<option value="">Select City/Region</option>';
        
        if (selectedState && malaysiaCities[selectedState]) {
            citySelect.disabled = false;
            malaysiaCities[selectedState].forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        } else {
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Select State First</option>';
        }
    });

    // Address form submission
    document.getElementById('addressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveAddress();
    });

    // Toggle payment forms
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            togglePaymentForm(this.value);
        });
    });

    // Apply promo code
    const applyPromoBtn = document.getElementById('applyPromo');
    if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', function() {
            const promoCode = document.getElementById('promo_code').value;
            applyPromoCode(promoCode);
        });
    }

    // Place order
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function() {
            placeOrder();
        });
    }
});

function saveAddress() {
    const formData = new FormData(document.getElementById('addressForm'));
    
    fetch('{{ route("checkout.address.store") }}', {
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
            alert('Error saving address: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving address. Please try again.');
    });
}

function togglePaymentForm(method) {
    const creditCardForm = document.getElementById('creditCardForm');
    
    if (creditCardForm) {
        if (method === 'credit_card') {
            creditCardForm.style.display = 'block';
        } else {
            creditCardForm.style.display = 'none';
        }
    }
}

function applyPromoCode(promoCode) {
    if (!promoCode.trim()) {
        alert('Please enter a promo code');
        return;
    }

    fetch('{{ route("checkout.apply-promo") }}', {
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
    })
    .catch(error => {
        console.error('Error applying promo code:', error);
        alert('Error applying promo code. Please try again.');
    });
}

function calculateTotal() {
    try {
        const subtotal = {{ $subtotal }};
        const tax = {{ $tax ?? 0 }};
        const discount = {{ $discount ?? 0 }};
        
        // Free shipping
        const shippingCost = 0;
        
        const total = subtotal + shippingCost + tax - discount;
        const totalAmountElement = document.getElementById('totalAmount');
        
        if (totalAmountElement) {
            totalAmountElement.textContent = `RM${total.toFixed(2)}`;
        }
    } catch (error) {
        console.error('Error calculating total:', error);
    }
}

function placeOrder() {
    // Validate address is selected
    const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
    if (!selectedAddress) {
        alert('Please select a shipping address');
        return;
    }

    // Validate required fields
    const agreeTerms = document.getElementById('agree_terms');
    if (!agreeTerms.checked) {
        alert('Please agree to the terms and conditions');
        return;
    }

    const formData = new FormData();
    
    // Add selected address
    formData.append('address_id', selectedAddress.value);
    
    // Add payment method
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (paymentMethod) {
        formData.append('payment_method', paymentMethod.value);
    }
    
    // Show loading state
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const originalText = placeOrderBtn.textContent;
    placeOrderBtn.textContent = 'Processing...';
    placeOrderBtn.disabled = true;

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
        alert('Error placing order. Please try again.');
    })
    .finally(() => {
        // Reset button state
        placeOrderBtn.textContent = originalText;
        placeOrderBtn.disabled = false;
    });
}
</script>
@endsection