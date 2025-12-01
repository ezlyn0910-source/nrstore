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
                        $userAddresses = $addresses ?? collect();
                    @endphp
                    
                    <!-- Address List -->
                    <div id="addressList" class="address-list">
                        @if($userAddresses->count() > 0)
                            @foreach($userAddresses as $index => $address)
                            <div class="address-item">
                                <input type="radio" 
                                    id="address_{{ $address->id }}" 
                                    name="selected_address" 
                                    value="{{ $address->id }}"
                                    {{ $index === 0 ? 'checked' : '' }}
                                    class="address-radio" required>
                                <label for="address_{{ $address->id }}" class="address-label">
                                    <div class="address-header">
                                        <strong>{{ $address->full_name }}</strong>
                                        @if($address->is_default)
                                            <span class="primary-badge">Default</span>
                                        @endif
                                    </div>
                                    <div class="address-details">
                                        <p>{{ $address->phone }}</p>
                                        <p>{{ $address->address_line_1 }}{{ $address->address_line_2 ? ', ' . $address->address_line_2 : '' }}</p>
                                        <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        @else
                            <div class="no-address">
                                <div class="no-address-icon">📍</div>
                                <h3>No Address Yet</h3>
                                <p>Click "Add New Address" to add your first address</p>
                            </div>
                        @endif
                    </div>

                    <!-- Add Address Dropdown -->
                    <div class="add-address-dropdown">
                        <div class="dropdown-header" id="addressDropdownHeader">
                            <h3>Add New Address</h3>
                            <button type="button" class="dropdown-toggle" id="dropdownToggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        
                        <div class="dropdown-content" id="addressDropdownContent">
                            <form id="addressForm" method="POST" action="{{ route('checkout.address.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="full_name">Full Name *</label>
                                    <input type="text" id="full_name" name="full_name" placeholder="John Doe" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" placeholder="012-3456789" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address_line_1">Address Line 1 *</label>
                                    <input type="text" id="address_line_1" name="address_line_1" placeholder="House no, Street name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address_line_2">Address Line 2 (Optional)</label>
                                    <input type="text" id="address_line_2" name="address_line_2" placeholder="Apartment, suite, unit, etc.">
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="city">City *</label>
                                        <input type="text" id="city" name="city" placeholder="Kuala Lumpur" required>
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
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="postal_code">Postal Code *</label>
                                        <input type="text" id="postal_code" name="postal_code" placeholder="50000" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" id="country" name="country" value="Malaysia" readonly>
                                    </div>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" id="is_default" name="is_default">
                                    <label for="is_default">Set as default address</label>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary cancel-btn">Cancel</button>
                                    <button type="submit" class="btn btn-primary save-btn">
                                        Save Address
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Payment Method -->
                <section class="checkout-section">
                    <h2>Payment Method</h2>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="online_banking" name="payment_method" value="online_banking" checked required>
                            <label for="online_banking" class="payment-label">
                                <div class="payment-method-info">
                                    <div class="payment-logo">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="payment-details">
                                        <span class="method-name">Online Banking</span>
                                        <span class="method-desc">Pay via online banking</span>
                                    </div>
                                </div>
                                <div class="radio-indicator"></div>
                            </label>
                        </div>

                        <div class="payment-method">
                            <input type="radio" id="credit_card" name="payment_method" value="credit_card" required>
                            <label for="credit_card" class="payment-label">
                                <div class="payment-method-info">
                                    <div class="payment-logo">
                                        <i class="far fa-credit-card"></i>
                                    </div>
                                    <div class="payment-details">
                                        <span class="method-name">Credit/Debit Card</span>
                                        <span class="method-desc">Pay with your card</span>
                                    </div>
                                </div>
                                <div class="radio-indicator"></div>
                            </label>
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
                                    $imageUrl = asset('images/default-product.png');
                                    if ($item->product && $item->product->images && $item->product->images->isNotEmpty()) {
                                        $firstImage = $item->product->images->first();
                                        if ($firstImage && $firstImage->image_path) {
                                            $imageUrl = asset('storage/' . $firstImage->image_path);
                                        }
                                    } elseif ($item->product && $item->product->image) {
                                        $imageUrl = asset('storage/' . $item->product->image);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $item->product->name ?? 'Product' }}">
                            </div>
                            <div class="item-details">
                                <h4>{{ $item->product->name ?? 'Product' }}</h4>
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
                            <span>RM5.99</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>RM{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Terms and Place Order -->
                    <div class="checkout-actions">
                        <div class="terms-agreement">
                            <input type="checkbox" id="agree_terms" required>
                            <label for="agree_terms">I agree to the Terms and Conditions</label>
                        </div>
                        <button type="button" id="placeOrderBtn" class="btn btn-primary btn-lg">Place Order</button>
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
    
    // Dropdown toggle functionality - FIXED VERSION
    const dropdownContent = document.getElementById('addressDropdownContent');
    const dropdownToggle = document.getElementById('dropdownToggle');
    const dropdownHeader = document.getElementById('addressDropdownHeader');

    console.log('Dropdown elements found:', {
        dropdownToggle: !!dropdownToggle,
        dropdownContent: !!dropdownContent,
        dropdownHeader: !!dropdownHeader
    });

    if (dropdownToggle && dropdownContent && dropdownHeader) {
        // Initialize dropdown state on page load
        updateToggleState(dropdownContent.classList.contains('show'));
        
        // Function to update toggle state
        function updateToggleState(isShowing) {
            if (isShowing) {
                dropdownToggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
                dropdownHeader.classList.add('active');
                dropdownContent.style.display = 'block';
            } else {
                dropdownToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
                dropdownHeader.classList.remove('active');
                dropdownContent.style.display = 'none';
            }
        }

        // Handle click on the dropdown toggle button
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isShowing = dropdownContent.style.display === 'block' || 
                             dropdownContent.classList.contains('show');
            
            if (isShowing) {
                // Hide dropdown
                dropdownContent.style.display = 'none';
                dropdownContent.classList.remove('show');
            } else {
                // Show dropdown
                dropdownContent.style.display = 'block';
                dropdownContent.classList.add('show');
            }
            
            updateToggleState(!isShowing);
        });
        
        // Handle click on the entire Header area
        dropdownHeader.addEventListener('click', function(e) {
            // Don't trigger if clicking on the button
            if (e.target === dropdownToggle || dropdownToggle.contains(e.target)) {
                return;
            }
            
            const isShowing = dropdownContent.style.display === 'block' || 
                             dropdownContent.classList.contains('show');
            
            if (isShowing) {
                // Hide dropdown
                dropdownContent.style.display = 'none';
                dropdownContent.classList.remove('show');
            } else {
                // Show dropdown
                dropdownContent.style.display = 'block';
                dropdownContent.classList.add('show');
            }
            
            updateToggleState(!isShowing);
        });
    }

    // Cancel button functionality
    const cancelBtn = document.querySelector('.cancel-btn');
    if (cancelBtn && dropdownContent) {
        cancelBtn.addEventListener('click', function() {
            dropdownContent.style.display = 'none';
            dropdownContent.classList.remove('show');
            
            if (dropdownToggle) {
                dropdownToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
            }
            if (dropdownHeader) {
                dropdownHeader.classList.remove('active');
            }
            
            const addressForm = document.getElementById('addressForm');
            if (addressForm) {
                addressForm.reset();
            }
        });
    }
    
    // Handle address form submission
    const addressForm = document.getElementById('addressForm');
    if (addressForm) {
        addressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const saveBtn = this.querySelector('.save-btn');
            const originalText = saveBtn.textContent;
            
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Address saved successfully!');
                    // Close dropdown and reset form
                    if (dropdownContent) {
                        dropdownContent.style.display = 'none';
                        dropdownContent.classList.remove('show');
                    }
                    if (dropdownToggle) {
                        dropdownToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    }
                    if (dropdownHeader) {
                        dropdownHeader.classList.remove('active');
                    }
                    this.reset();
                    // Reload page to show new address
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save address'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving address. Please try again.');
            })
            .finally(() => {
                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
            });
        });
    }
    
    // Handle place order button
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function() {
            const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
            const agreeTerms = document.getElementById('agree_terms');
            
            if (!selectedAddress) {
                alert('Please select a shipping address');
                return;
            }
            
            if (!agreeTerms.checked) {
                alert('Please agree to the terms and conditions');
                return;
            }
            
            const originalText = this.textContent;
            this.textContent = 'Processing...';
            this.disabled = true;
            
            const formData = new FormData();
            formData.append('address_id', selectedAddress.value);
            formData.append('payment_method', document.querySelector('input[name="payment_method"]:checked').value);
            
            fetch('{{ route("checkout.place-order") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + (data.message || 'Failed to place order'));
                    this.textContent = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error placing order. Please try again.');
                this.textContent = originalText;
                this.disabled = false;
            });
        });
    }
});
</script>
@endsection