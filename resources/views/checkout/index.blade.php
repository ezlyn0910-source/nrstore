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

@push('scripts')
<script>
(function () {
    // Helpers
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const SELECTORS = [
        'full_name',
        'phone',
        'address_line_1',
        // address_line_2 is optional
        'city',
        'state',
        'postal_code',
        'country'
    ];

    function createErrorEl(message) {
        const el = document.createElement('div');
        el.className = 'error-text';
        el.setAttribute('role', 'alert');
        el.style.color = '#dc2626'; // red
        el.style.fontSize = '0.875rem';
        el.style.marginTop = '0.375rem';
        return el;
    }

    function showFieldError(inputEl, message) {
        if (!inputEl) return;
        // add error class
        inputEl.classList.add('input-error');
        inputEl.setAttribute('aria-invalid', 'true');

        // if existing error text, update it, else create
        let next = inputEl.nextElementSibling;
        // if next is label (in some structures) find below container
        // remove any existing .error-text right after input or inside parent
        let existing = null;

        if (next && next.classList && next.classList.contains('error-text')) {
            existing = next;
        } else {
            // try parent's last-child
            const parent = inputEl.parentElement;
            if (parent) {
                existing = parent.querySelector('.error-text');
            }
        }

        if (existing) {
            existing.textContent = message;
        } else {
            const err = createErrorEl(message);
            // try to insert after input
            if (inputEl.nextSibling) {
                inputEl.parentNode.insertBefore(err, inputEl.nextSibling);
            } else {
                inputEl.parentNode.appendChild(err);
            }
        }
    }

    function clearFieldError(inputEl) {
        if (!inputEl) return;
        inputEl.classList.remove('input-error');
        inputEl.removeAttribute('aria-invalid');

        // remove any error-text in parent
        const parent = inputEl.parentElement;
        if (!parent) return;
        const existing = parent.querySelector('.error-text');
        if (existing) existing.remove();
    }

    // Small utility to trim and get value (works for select too)
    function getVal(name, form) {
        const el = form.querySelector('[name="' + name + '"]');
        if (!el) return '';
        return (el.value || '').toString().trim();
    }

    // validate phone basic
    function validatePhone(value) {
        // allow digits, spaces, + and hyphens; but ensure at least 6 chars
        const v = value.replace(/\s+/g, '');
        return /^[\d\+\-\s]{6,20}$/.test(value) && v.length >= 6;
    }

    // validate postal code basic (Malaysia: 5 digits) but we'll allow 3-10 chars
    function validatePostal(value) {
        return /^[A-Za-z0-9\- ]{3,10}$/.test(value);
    }

    // validate all required fields: returns object {valid: bool, errors: {name:message}}
    function validateFormClient(form) {
        const errors = {};

        // check required selectors
        SELECTORS.forEach(name => {
            // address_line_2 is not in SELECTORS -> optional
            const val = getVal(name, form);
            if (name === 'country') {
                // country is readonly but still required — ensure value present
                if (!val) errors[name] = 'Country is required.';
                return;
            }
            if (!val) {
                errors[name] = (name === 'postal_code') ? 'Postal code is required.' : (name === 'full_name' ? 'Full name is required.' : 'This field is required.');
                return;
            }

            // extra checks
            if (name === 'phone' && !validatePhone(val)) {
                errors[name] = 'Please enter a valid phone number.';
            }
            if (name === 'postal_code' && !validatePostal(val)) {
                errors[name] = 'Please enter a valid postal code.';
            }
        });

        // check state select explicitly (empty string not allowed)
        const stateVal = getVal('state', form);
        if (!stateVal) {
            errors['state'] = 'Please select a state.';
        }

        return { valid: Object.keys(errors).length === 0, errors };
    }

    // Add input/change listeners to remove errors when user types/selects
    function attachLiveClear(form) {
        SELECTORS.forEach(name => {
            const el = form.querySelector('[name="' + name + '"]');
            if (!el) return;
            const ev = (el.tagName.toLowerCase() === 'select' || el.type === 'checkbox' || el.type === 'radio') ? 'change' : 'input';
            el.addEventListener(ev, function () {
                clearFieldError(el);
            });
        });
    }

    // Main script hooking
    window.addEventListener('load', function () {
        // Dropdown elements (safe to keep)
        const dropdownContent = document.getElementById('addressDropdownContent');
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownHeader = document.getElementById('addressDropdownHeader');

        // Toggle behavior (keeps simple)
        function updateToggleState(isShowing) {
            if (!dropdownToggle || !dropdownHeader || !dropdownContent) return;
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
        if (dropdownToggle) {
            dropdownToggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = dropdownContent && dropdownContent.style.display === 'block';
                updateToggleState(!isOpen);
            });
        }
        if (dropdownHeader) {
            dropdownHeader.addEventListener('click', function (e) {
                if (e.target === dropdownToggle || (dropdownToggle && dropdownToggle.contains(e.target))) return;
                const isOpen = dropdownContent && dropdownContent.style.display === 'block';
                updateToggleState(!isOpen);
            });
        }

        // form handling
        const addressForm = document.getElementById('addressForm');
        if (!addressForm) return;

        attachLiveClear(addressForm);

        addressForm.addEventListener('submit', function (evt) {
            evt.preventDefault();

            // remove any previous server-side error list
            const globalErr = document.querySelector('.form-global-error');
            if (globalErr) globalErr.remove();

            const saveBtn = this.querySelector('.save-btn');
            const originalText = saveBtn ? saveBtn.textContent : 'Save';

            // Client-side validation
            const validated = validateFormClient(this);
            // Clear all prior field errors first
            SELECTORS.forEach(name => {
                const el = this.querySelector('[name="' + name + '"]');
                if (el) clearFieldError(el);
            });

            if (!validated.valid) {
                // show inline errors
                Object.keys(validated.errors).forEach(key => {
                    const el = this.querySelector('[name="' + key + '"]');
                    if (el) {
                        showFieldError(el, validated.errors[key]);
                        // scroll first invalid into view
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
                return; // do not submit
            }

            // everything ok client-side, submit via AJAX
            if (saveBtn) {
                saveBtn.textContent = 'Saving...';
                saveBtn.disabled = true;
            }

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(async res => {
                const contentType = res.headers.get('content-type') || '';
                const isJson = contentType.includes('application/json');
                const data = isJson ? await res.json() : null;

                if (res.ok && data && data.success) {
                    // success
                    alert(data.message || 'Address saved successfully!');
                    // close dropdown if present
                    if (typeof updateToggleState === 'function') updateToggleState(false);
                    this.reset();
                    // reload to show saved address
                    window.location.reload();
                    return;
                }

                // handle validation 422
                if (res.status === 422 && data && data.errors) {
                    // data.errors is an object from Laravel validation
                    Object.keys(data.errors).forEach(key => {
                        const el = this.querySelector('[name="' + key + '"]');
                        if (el) {
                            showFieldError(el, data.errors[key][0] || data.errors[key]);
                        } else {
                            // if unknown field, show global
                            const g = createErrorEl(data.errors[key][0] || data.errors[key]);
                            g.classList.add('form-global-error');
                            this.prepend(g);
                        }
                    });
                } else {
                    // other errors: show message from response or generic message
                    const msg = (data && data.message) ? data.message : 'Failed to save address. Please try again.';
                    const g = createErrorEl(msg);
                    g.classList.add('form-global-error');
                    this.prepend(g);
                }
            })
            .catch(err => {
                console.error('Address save error:', err);
                const g = createErrorEl('Network error. Please try again.');
                g.classList.add('form-global-error');
                this.prepend(g);
            })
            .finally(() => {
                if (saveBtn) {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                }
            });
        });
    }); // end load
})(); // IIFE
</script>
@endpush