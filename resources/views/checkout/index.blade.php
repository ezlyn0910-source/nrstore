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
                    @php
                        // DEFAULT FIRST
                        $sortedAddresses = $userAddresses->sortByDesc('is_default');
                        $hasDefaultAddress = $sortedAddresses->contains('is_default', true);
                    @endphp

                    <div id="addressList" class="address-list">
                        @if($sortedAddresses->count() > 0)
                            @foreach($sortedAddresses as $index => $address)
                                <div class="address-item">
                                    <div class="address-label">
                                        <div class="address-header">
                                            @if($address->is_default)
                                                <span class="primary-badge default-badge">Default</span>
                                            @endif

                                            <strong class="address-name">
                                                {{ $address->full_name }}
                                                <span class="address-phone">({{ $address->phone }})</span>
                                            </strong>
                                        </div>

                                        <div class="address-details">
                                            <p>
                                                {{ $address->address_line_1 }}
                                                {{ $address->address_line_2 ? ', ' . $address->address_line_2 : '' }}
                                            </p>
                                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                        </div>
                                    </div>

                                    <input 
                                        type="radio"
                                        name="selected_address"
                                        value="{{ $address->id }}"
                                        class="address-radio"
                                        @if($hasDefaultAddress)
                                            {{ $address->is_default ? 'checked' : '' }}
                                        @else
                                            {{ $loop->first ? 'checked' : '' }}
                                        @endif
                                        required
                                    >
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
                                
                                <div class="form-check default-check">
                                    <input 
                                        type="checkbox" 
                                        id="is_default" 
                                        name="is_default" 
                                        class="form-check-input"
                                    >
                                    <label for="is_default" class="form-check-label">Set as default address</label>
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

                <hr class="section-divider">

                <!-- Payment Method -->
                <section class="checkout-section">
                    <h2>Payment Method</h2>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="online_banking" name="payment_method" value="online_banking" required>
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

                            <div id="online_banking_banks" class="online-banking-dropdown" style="display:none; margin-top:10px;">
                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/maybank.png') }}" alt="Maybank" class="bank-logo">
                                        <span class="bank-name">Maybank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="maybank" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/cimb.png') }}" alt="CIMB Bank" class="bank-logo">
                                        <span class="bank-name">CIMB Bank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="cimb" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/public-bank.png') }}" alt="Public Bank" class="bank-logo">
                                        <span class="bank-name">Public Bank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="public_bank" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/rhb.png') }}" alt="RHB Bank" class="bank-logo">
                                        <span class="bank-name">RHB Bank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="rhb" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/hong-leong.png') }}" alt="Hong Leong Bank" class="bank-logo">
                                        <span class="bank-name">Hong Leong Bank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="hong_leong" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/bank-islam.png') }}" alt="Bank Islam" class="bank-logo">
                                        <span class="bank-name">Bank Islam</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="bank_islam" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/ambank.png') }}" alt="AmBank" class="bank-logo">
                                        <span class="bank-name">AmBank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="ambank" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/bank-rakyat.png') }}" alt="Bank Rakyat" class="bank-logo">
                                        <span class="bank-name">Bank Rakyat</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="bank_rakyat" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/hsbc.png') }}" alt="HSBC" class="bank-logo">
                                        <span class="bank-name">HSBC Bank Malaysia</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="hsbc" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/ocbc.png') }}" alt="OCBC" class="bank-logo">
                                        <span class="bank-name">OCBC Bank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="ocbc" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/uob.png') }}" alt="UOB" class="bank-logo">
                                        <span class="bank-name">UOB Bank</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="uob" class="bank-radio">
                                </div>

                                <div class="bank-option">
                                    <div class="bank-info">
                                        <img src="{{ asset('/images/banks/standard-chartered.png') }}" alt="Standard Chartered" class="bank-logo">
                                        <span class="bank-name">Standard Chartered</span>
                                    </div>
                                    <input type="radio" name="online_banking_bank" value="standard_chartered" class="bank-radio">
                                </div>
                            </div>

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
                                        <span class="method-desc">You will be redirected to a secure iPay88 card payment page</span>
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

                    <div class="checkout-actions">
                        <button type="button" id="placeOrderBtn" class="btn btn-primary btn-lg">Place Order</button>
                    </div>
                    {{-- Hidden form that actually posts to payment.process --}}
                    <form id="placeOrderForm" method="POST" action="{{ route('payment.process') }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="selected_address" id="po_selected_address">
                        <input type="hidden" name="payment_method" id="po_payment_method">
                        <input type="hidden" name="online_banking_bank" id="po_online_banking_bank">
                        <input type="hidden" name="amount" id="po_amount" value="{{ $total }}">
                    </form>

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
        const div = document.createElement('div');
        div.className = 'form-error-box';
        div.style.background = '#ffdddd';
        div.style.color = '#b30000';
        div.style.padding = '10px';
        div.style.border = '1px solid #b30000';
        div.style.borderRadius = '5px';
        div.style.marginBottom = '10px';
        div.textContent = message;
        return div;
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

        // ============================
        // FORM HANDLING – ADD ADDRESS
        // ============================
        const addressForm = document.getElementById('addressForm');

        if (addressForm) {
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

                    /* -----------------------------
                       SUCCESS RESPONSE
                    ----------------------------- */
                    if (res.ok && data && data.success) {
                        alert(data.message || 'Address saved successfully!');
                        if (typeof updateToggleState === 'function') updateToggleState(false);
                        this.reset();
                        window.location.reload();
                        return;
                    }

                    /* -----------------------------
                       VALIDATION ERRORS (422)
                    ----------------------------- */
                    if (res.status === 422 && data && data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            // Special handling for default checkbox
                            if (key === 'is_default') {
                                const cb = document.getElementById('is_default');
                                if (cb) {
                                    // Show browser-style tooltip like "Please fill out this field"
                                    cb.setCustomValidity('Already exist');
                                    cb.reportValidity();

                                    // Clear tooltip when user changes the checkbox
                                    cb.addEventListener('input', function () {
                                        cb.setCustomValidity('');
                                    }, { once: true });
                                }
                                return; // don't do anything else for this key
                            }

                            // Normal field errors (no red global box)
                            const field = this.querySelector('[name="' + key + '"]');
                            if (field) {
                                showFieldError(field, data.errors[key][0]);
                            } else {
                                // If some weird field name comes back, just log it
                                console.warn('Validation error on unknown field:', key, data.errors[key]);
                            }
                        });

                        return;
                    }

                    /* -----------------------------
                       OTHER ERRORS (400, 500, etc.)
                       No red box, simple alert only.
                    ----------------------------- */
                    console.error('Address save failed:', data);
                    alert('Failed to save address. Please try again.');
                    return;
                })
                .catch(err => {
                    console.error('Address save error:', err);
                    alert('Network error. Please try again.');
                })
                .finally(() => {
                    if (saveBtn) {
                        saveBtn.textContent = originalText;
                        saveBtn.disabled = false;
                    }
                });
            });
        }

        // ============================
        // PAYMENT METHODS – ONLINE BANKING BANK LIST
        // ============================
        const onlineRadio       = document.getElementById('online_banking');
        const paymentRadios     = document.querySelectorAll('input[name="payment_method"]');
        const bankDropdownBlock = document.getElementById('online_banking_banks');
        const bankOptions       = document.querySelectorAll('#online_banking_banks .bank-option');
        const bankRadios        = document.querySelectorAll('input[name="online_banking_bank"]');
        const bankToggleBtn     = document.getElementById('bankListToggle');

        // Track whether user has expanded the bank list to change selection
        let bankListExpanded = false;

        function applyBankSelectionUI() {
            if (!bankOptions || !bankOptions.length) return;

            let selectedRadio = null;
            bankRadios.forEach(r => {
                if (r.checked) selectedRadio = r;
            });

            const hasSelected = !!selectedRadio;

            if (!hasSelected) {
                // No bank selected: show all banks, hide toggle
                bankOptions.forEach(option => {
                    option.classList.remove('bank-selected', 'bank-hidden');
                });
                if (bankToggleBtn) {
                    bankToggleBtn.style.display = 'none';
                }
                return;
            }

            if (bankListExpanded) {
                // Expanded: show all banks, highlight the selected one
                bankOptions.forEach(option => {
                    const radio = option.querySelector('input[name="online_banking_bank"]');
                    if (!radio) return;

                    option.classList.remove('bank-hidden');

                    if (radio === selectedRadio) {
                        option.classList.add('bank-selected');
                    } else {
                        option.classList.remove('bank-selected');
                    }
                });

                if (bankToggleBtn) {
                    bankToggleBtn.style.display = 'inline-flex';
                    bankToggleBtn.innerHTML = 'Hide banks <i class="fas fa-chevron-up"></i>';
                }
            } else {
                // Collapsed: show only the selected bank row
                bankOptions.forEach(option => {
                    const radio = option.querySelector('input[name="online_banking_bank"]');
                    if (!radio) return;

                    if (radio === selectedRadio) {
                        option.classList.add('bank-selected');
                        option.classList.remove('bank-hidden');
                    } else {
                        option.classList.remove('bank-selected');
                        option.classList.add('bank-hidden');
                    }
                });

                if (bankToggleBtn) {
                    bankToggleBtn.style.display = 'inline-flex';
                    bankToggleBtn.innerHTML = 'Change bank <i class="fas fa-chevron-down"></i>';
                }
            }
        }

        function updateBankDropdown() {
            if (!onlineRadio || !bankDropdownBlock) return;

            if (onlineRadio.checked) {
                bankDropdownBlock.style.display = 'block';

                // Make bank selection required when using Online Banking
                if (bankRadios.length) {
                    bankRadios[0].setAttribute('required', 'required');
                }

                // Update UI based on whether a bank is already selected
                applyBankSelectionUI();
            } else {
                // Hide dropdown and reset
                bankDropdownBlock.style.display = 'none';
                bankListExpanded = false;

                bankRadios.forEach(radio => {
                    radio.checked = false;
                    radio.removeAttribute('required');
                });

                bankOptions.forEach(option => {
                    option.classList.remove('bank-selected', 'bank-hidden');
                });

                if (bankToggleBtn) {
                    bankToggleBtn.style.display = 'none';
                }
            }
        }

        // Make each bank row clickable
        if (bankOptions && bankOptions.length) {
            bankOptions.forEach(option => {
                option.addEventListener('click', function (e) {
                    const radio = this.querySelector('input[name="online_banking_bank"]');
                    if (!radio) return;

                    radio.checked = true;
                    bankListExpanded = false; // collapse after choosing
                    applyBankSelectionUI();
                    e.stopPropagation();
                });
            });
        }

        // Just in case user clicks directly on the small radio
        if (bankRadios && bankRadios.length) {
            bankRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    bankListExpanded = false; // collapse after choosing
                    applyBankSelectionUI();
                });
            });
        }

        // Toggle button: expand/collapse bank list
        if (bankToggleBtn) {
            bankToggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                bankListExpanded = !bankListExpanded;
                applyBankSelectionUI();
            });
        }

        // Payment method change: Online Banking vs Card
        if (paymentRadios && paymentRadios.length) {
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', updateBankDropdown);
            });
        }

        // Initial state: no payment selected → keep dropdown hidden
        if (bankDropdownBlock) {
            bankDropdownBlock.style.display = 'none';
        }

        // ============================
        // PAYMENT METHODS – CARD DETAILS TOGGLE
        // ============================
        const cardRadio     = document.getElementById('credit_card');
        const cardDetails   = document.getElementById('card_details');
        const cardRequiredInputs = cardDetails 
            ? cardDetails.querySelectorAll('[data-card-required]')
            : [];

        function updateCardDetailsUI() {
            if (!cardRadio || !cardDetails) return;

            if (cardRadio.checked) {
                // Show card form and make fields required
                cardDetails.style.display = 'block';
                cardRequiredInputs.forEach(input => {
                    input.setAttribute('required', 'required');
                });
            } else {
                // Hide card form and remove required
                cardDetails.style.display = 'none';
                cardRequiredInputs.forEach(input => {
                    input.removeAttribute('required');
                });
            }
        }

        // Use existing paymentRadios (already defined in your bank block)
        if (paymentRadios && paymentRadios.length) {
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', updateCardDetailsUI);
            });
        }

        // Initial state on load (no method selected → hide card details)
        updateCardDetailsUI();

        // ============================
        // PLACE ORDER → PAYMENT.PROCESS
        // ============================
        const placeOrderBtn = document.getElementById('placeOrderBtn');

        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function () {

                // 1. Check address selected
                const addressRadio = document.querySelector('input[name="selected_address"]:checked');
                if (!addressRadio) {
                    alert('Please select a shipping address.');
                    return;
                }

                // 2. Check payment method selected
                const paymentMethodRadio = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethodRadio) {
                    alert('Please select a payment method.');
                    return;
                }

                const methodValue = paymentMethodRadio.value; // 'online_banking' or 'credit_card'
                let onlineBankKey = null;

                if (methodValue === 'online_banking') {
                    // Find the checked bank
                    const selectedBank = document.querySelector('input.bank-radio:checked');
                    if (!selectedBank) {
                        alert('Please select your bank.');
                        return;
                    }
                    onlineBankKey = selectedBank.value; // e.g. 'maybank'
                }

                // 3. Fill the hidden Laravel form
                const form = document.getElementById('placeOrderForm');
                if (!form) {
                    console.error('placeOrderForm not found');
                    alert('Something went wrong. Please refresh and try again.');
                    return;
                }

                const addrInput   = document.getElementById('po_selected_address');
                const pmInput     = document.getElementById('po_payment_method');
                const bankInput   = document.getElementById('po_online_banking_bank');
                const amountInput = document.getElementById('po_amount');

                if (addrInput)   addrInput.value   = addressRadio.value;
                if (pmInput)     pmInput.value     = methodValue;
                if (bankInput)   bankInput.value   = onlineBankKey || '';
                if (amountInput) amountInput.value = "{{ $total }}"; // or recompute in backend

                // 4. Submit the real form (with @csrf)
                form.submit();
            });
        }

    }); // end load
})(); // IIFE
</script>
@endpush