@extends('layouts.app')

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
                                    <label class="address-left">
                                        <div class="address-header">
                                            @if($address->is_default)
                                                <span class="primary-badge default-badge">Default</span>
                                            @endif

                                            <span class="address-name-line">
                                                {{ $address->full_name }}
                                                <span class="address-phone">({{ $address->phone }})</span>
                                            </span>
                                        </div>

                                        <div class="address-details">
                                            <p>{{ $address->address_line_1 }}</p>
                                            @if($address->address_line_2)
                                                <p>{{ $address->address_line_2 }}</p>
                                            @endif
                                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                            <p>{{ $address->country ?? 'Malaysia' }}</p>
                                        </div>
                                    </label>

                                    <div class="address-right">
                                        <button 
                                            type="button"
                                            class="address-edit-link"
                                            data-id="{{ $address->id }}"
                                            data-full-name="{{ e($address->full_name) }}"
                                            data-phone="{{ e($address->phone) }}"
                                            data-line1="{{ e($address->address_line_1) }}"
                                            data-line2="{{ e($address->address_line_2) }}"
                                            data-city="{{ e($address->city) }}"
                                            data-state="{{ e($address->state) }}"
                                            data-postal="{{ e($address->postal_code) }}"
                                            data-country="{{ e($address->country ?? 'Malaysia') }}"
                                            data-is-default="{{ $address->is_default ? '1' : '0' }}"
                                            data-update-url="{{ route('checkout.address.update', $address->id) }}"
                                        >
                                            Edit
                                        </button>

                                        <input
                                            type="radio"
                                            name="selected_address"
                                            class="address-radio"
                                            value="{{ $address->id }}"
                                            {{ $address->is_default ? 'checked' : '' }}
                                        >
                                    </div>
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
                                        id="edit_is_default" 
                                        name="is_default"
                                        class="form-check-input"
                                    >
                                    <label for="edit_is_default" class="form-check-label">Set as default address</label>
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

                <!-- Edit Address Modal -->
                <div id="editAddressModal" class="address-edit-modal">
                    <div class="address-edit-backdrop"></div>

                    <div class="address-edit-dialog">
                        <button type="button" class="address-edit-close" id="editAddressClose">&times;</button>
                        <h3 class="address-edit-title">Edit Address</h3>

                        <form id="editAddressForm" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="edit_full_name">Full Name <span class="required-star">*</span></label>
                                <input type="text" id="edit_full_name" name="full_name" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_full_name">Phone Number <span class="required-star">*</span></label>
                                <input type="tel" id="edit_phone" name="phone" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_full_name">Address Line 1 <span class="required-star">*</span></label>
                                <input type="text" id="edit_address_line_1" name="address_line_1" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_address_line_2">Address Line 2 </label>
                                <input type="text" id="edit_address_line_2" name="address_line_2">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="edit_full_name">City <span class="required-star">*</span></label>
                                    <input type="text" id="edit_city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_full_name">State <span class="required-star">*</span></label>
                                    <select id="edit_state" name="state" required>
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
                                    <label for="edit_full_name">Postal Code <span class="required-star">*</span></label>
                                    <input type="text" id="edit_postal_code" name="postal_code" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_full_name">Country <span class="required-star">*</span></label>
                                    <input type="text" id="edit_country" name="country" value="Malaysia" readonly>
                                </div>
                            </div>

                            <div class="form-check default-check">
                                <input 
                                    type="checkbox" 
                                    id="edit_is_default" 
                                    name="is_default" 
                                    class="form-check-input"
                                >
                                <label for="edit_is_default" class="form-check-label">Set as default address</label>
                            </div>

                            <div class="form-actions edit-form-actions">
                                <button type="submit" class="btn btn-primary save-btn" id="editAddressSave">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

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
        // EDIT ADDRESS POPUP
        // ============================
        const editAddressModal  = document.getElementById('editAddressModal');
        const editAddressForm   = document.getElementById('editAddressForm');
        const editAddressCancel = document.getElementById('editAddressCancel');
        const editAddressClose  = document.getElementById('editAddressClose');

        function openEditAddressModal(trigger) {
            if (!editAddressModal || !editAddressForm) return;

            // SAFETY: if trigger is not the button, climb up to the closest button
            if (!trigger.classList.contains('address-edit-link')) {
                trigger = trigger.closest('.address-edit-link');
            }
            if (!trigger) return;

            // 1) Set form action
            const updateUrl = trigger.getAttribute('data-update-url') || '';
            editAddressForm.action = updateUrl;

            // 2) Fill text fields from data- attributes
            const map = {
                full_name:      'fullName',
                phone:          'phone',
                address_line_1: 'line1',
                address_line_2: 'line2',
                city:           'city',
                state:          'state',
                postal_code:    'postal',
                country:        'country'
            };

            Object.keys(map).forEach(name => {
                const input = editAddressForm.querySelector('[name="' + name + '"]');
                if (!input) return;
                const dataKey = map[name];
                input.value = trigger.dataset[dataKey] || '';
            });

            // 3) Set "Set as default" checkbox based on data-is-default
            const isDefaultCb  = editAddressForm.querySelector('#edit_is_default');
            if (isDefaultCb) {
                const isDefaultAttr = trigger.getAttribute('data-is-default');  // "1" or "0"
                const isDefault     = (isDefaultAttr === '1' || isDefaultAttr === 'true');

                isDefaultCb.checked = isDefault;    // ✅ tick only if default
                isDefaultCb.setCustomValidity('');
            }

            // 4) Clear old field errors
            SELECTORS.forEach(name => {
                const el = editAddressForm.querySelector('[name="' + name + '"]');
                if (el) clearFieldError(el);
            });
            const globalErr = editAddressForm.querySelector('.form-global-error');
            if (globalErr) globalErr.remove();

            // 5) Show modal
            editAddressModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeEditAddressModal() {
            if (!editAddressModal) return;
            editAddressModal.classList.remove('show');
            document.body.style.overflow = '';
        }

        function bindEditButtons() {
            const editButtons = document.querySelectorAll('.address-edit-link');
            editButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    openEditAddressModal(this);   // <— use "this", not e.target
                });
            });
        }

        bindEditButtons();

        if (editAddressCancel) {
            editAddressCancel.addEventListener('click', function (e) {
                e.preventDefault();
                closeEditAddressModal();
            });
        }

        if (editAddressClose) {
            editAddressClose.addEventListener('click', function (e) {
                e.preventDefault();
                closeEditAddressModal();
            });
        }

        if (editAddressModal) {
            // Click backdrop to close
            editAddressModal.addEventListener('click', function (e) {
                if (e.target === editAddressModal) {
                    closeEditAddressModal();
                }
            });
        }

        if (editAddressForm) {
            attachLiveClear(editAddressForm);

            editAddressForm.addEventListener('submit', function (evt) {
                evt.preventDefault();

                const saveBtn = this.querySelector('.save-btn');
                const originalText = saveBtn ? saveBtn.textContent : 'Save';

                // Client-side validation
                const validated = validateFormClient(this);

                // Clear field errors first
                SELECTORS.forEach(name => {
                    const el = this.querySelector('[name="' + name + '"]');
                    if (el) clearFieldError(el);
                });

                if (!validated.valid) {
                    Object.keys(validated.errors).forEach(key => {
                        const el = this.querySelector('[name="' + key + '"]');
                        if (el) {
                            showFieldError(el, validated.errors[key]);
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                    return;
                }

                if (saveBtn) {
                    saveBtn.textContent = 'Saving...';
                    saveBtn.disabled = true;
                }

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST', // Laravel will read _method=PUT
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

                    // SUCCESS
                    if (res.ok && data && data.success) {
                        alert(data.message || 'Address updated successfully!');
                        closeEditAddressModal();
                        window.location.reload();
                        return;
                    }

                    // VALIDATION ERRORS (422)
                    if (res.status === 422 && data && data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            if (key === 'is_default') {
                                const cb = document.getElementById('edit_is_default');
                                if (cb) {
                                    cb.setCustomValidity('Already exist');
                                    cb.reportValidity();
                                    cb.addEventListener('input', function () {
                                        cb.setCustomValidity('');
                                    }, { once: true });
                                }
                                return;
                            }

                            const field = this.querySelector('[name="' + key + '"]');
                            if (field) {
                                showFieldError(field, data.errors[key][0]);
                            } else {
                                console.warn('Validation error on unknown field (edit):', key, data.errors[key]);
                            }
                        });

                        return;
                    }

                    // OTHER ERRORS
                    console.error('Address update failed:', data);
                    alert('Failed to update address. Please try again.');
                })
                .catch(err => {
                    console.error('Address update error:', err);
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

@section('styles')
<style>
.checkout-page {
    min-height: 70vh;
    background: white;
    font-family: "Nunito", sans-serif;
}

/* Banner */
.checkout-banner {
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
.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 3rem;
}

.checkout-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

/* Section Styles */
.checkout-section {
    margin-bottom: 2rem;
}

.checkout-section h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

/* ===== ADDRESS DROPDOWN STYLES ===== */
.add-address-dropdown {
    margin-top: 2rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    overflow: hidden;
    background: white;
}

.dropdown-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    background: #f8fafc;
    cursor: pointer;
    transition: background 0.2s ease;
    border-bottom: 1px solid transparent;
}

.dropdown-header:hover {
    background: #f1f5f9;
}

.dropdown-header.active {
    border-bottom-color: #e5e7eb;
    background: #f1f5f9;
}

.dropdown-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
}

.dropdown-toggle {
    background: none;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #6b7280;
    font-size: 1.1rem;
}

.dropdown-toggle:hover {
    background: #e5e7eb;
    color: #1f2937;
}

/* Dropdown content */
.dropdown-content {
    display: none;
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.dropdown-content.show {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form styles inside dropdown */
.dropdown-content .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.dropdown-content .form-group {
    margin-bottom: 1rem;
}

.dropdown-content .form-group label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.dropdown-content .form-group input,
.dropdown-content .form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
}

.dropdown-content .form-group input:focus,
.dropdown-content .form-group select:focus {
    outline: none;
    border-color: #1f2937;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

.dropdown-content #country {
    background: #f3f4f6;
    cursor: not-allowed;
}

.dropdown-content .form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1.5rem 0;
}

.dropdown-content .form-check input {
    width: auto;
    margin: 0;
}

.dropdown-content .form-check label {
    font-size: 0.875rem;
    color: #374151;
    margin: 0;
}

.dropdown-content .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

/* visible red border for invalid fields */
.input-error {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.06) !important;
}

/* small red inline text already created by the script */
.error-text {
    /* style is also set in JS, this is fallback */
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.375rem;
}

/* optional: global form error on top */
.form-global-error {
    background: #fff0f0;
    border: 1px solid #fccaca;
    padding: 0.6rem 0.8rem;
    margin-bottom: 0.75rem;
    color: #7f1d1d;
    border-radius: 0.375rem;
}

/* Address List */
.address-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.address-item {
    display: flex;
    justify-content: space-between; 
    align-items: flex-start;
    padding: 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    margin-bottom: 12px;
    position: relative;
}

.address-item:hover {
    border-color: #1f2937;
}

/* LEFT SIDE column */
.address-left {
    flex: 1;
    display: block;
    padding-right: 10px;
}

/* RIGHT SIDE: Edit + Radio in same vertical column */
.address-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
    margin-left: 20px;
    margin-right: 5px;
    min-width: 70px;
}

.address-label {
    flex: 1;
    cursor: pointer;
    display: block;
}

/* SHOW the radio button */
.address-radio {
    width: 20px;
    height: 20px;
    accent-color: #1f2937;
    cursor: pointer;
    align-self: flex-end;
    margin-top: 10px;
}

.address-radio:checked + .address-label {
    border-color: #1f2937;
}

.address-header {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 4px;
}

.address-name-line {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
}

.address-header-main {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.address-edit-link {
    background: transparent;
    border: none;
    padding: 0;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: underline;
    color: #1f2937;
    cursor: pointer;
    margin-bottom: 12px;
}

.address-edit-link:hover {
    color: #111827;
}

/* ===== Edit Address Modal ===== */
.address-edit-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.address-edit-modal.show {
    display: flex;
}

.address-edit-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.35);
}

.address-edit-dialog {
    position: relative;
    background: #ffffff;
    border-radius: 0.75rem;
    padding: 1.25rem 1.5rem;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
    z-index: 1;
}

.address-edit-dialog form {
    width: 100%;
    text-align: left;
}

.address-edit-title {
    margin: 0 0 1rem 0;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    color: #111827;
}

.address-edit-close {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    background: transparent;
    border: none;
    font-size: 1.1rem;
    cursor: pointer;
    color: #6b7280;
}

.address-edit-dialog .form-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    display: block;
    margin-bottom: 6px;
}

.address-edit-dialog .form-group label::after {
    content: " :";
}

.address-edit-dialog .form-group {
    margin-bottom: 14px;
}

.address-edit-dialog .form-group input,
.address-edit-dialog .form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.9rem;
    background: white;
}

.address-edit-dialog .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 14px;
}

.required-star {
    color: #dc2626;
    font-weight: 700;
    margin-left: 2px;
}

.edit-form-actions {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

#editAddressSave {
    border-radius: 20px !important;
}

.address-header strong {
    font-size: 1rem;
    color: #1f2937;
}

.default-badge {
    font-size: 12px;
    padding: 3px 8px;
    margin-bottom: 4px;
}

.primary-badge {
    background: #10b981;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.address-details {
    font-size: 0.875rem;
    color: #6b7280;
}

.address-details p {
    margin: 0.25rem 0;
}

.address-phone {
    color: #6b7280;
    font-weight: normal;
    font-size: 14px;
}

/* No Address State */
.no-address {
    text-align: center;
    padding: 2rem;
    background: #f9fafb;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e5e7eb;
}

.no-address-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.no-address h3 {
    font-size: 1.25rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.no-address p {
    color: #6b7280;
    margin: 0;
}

.section-divider {
    border: 0;
    border-top: 2px solid #d1d5db;
    margin: 35px 0;
}

/* ===== PAYMENT METHOD STYLES ===== */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.payment-method {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
    transition: all 0.2s ease;
    background: white;
}

.payment-method:hover {
    border-color: #d1d5db;
}

.payment-method input {
    display: none;
}

.payment-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.payment-method input:checked + .payment-label {
    background: #f8fafc;
    border-color: #1f2937;
}

.payment-method input:checked + .payment-label .radio-indicator {
    background: #1f2937;
    border-color: #1f2937;
}

.payment-method input:checked + .payment-label .radio-indicator::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
}

.payment-method-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.payment-logo {
    width: 40px;
    height: 40px;
    background: #f3f4f6;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #2c3e50;
}

.payment-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

/* ============================
   ONLINE BANKING – BANK LIST
   ============================ */

/* Wrapper panel under Online Banking */
.online-banking-dropdown {
    margin-top: 10px; /* already added inline, but safe to keep here */
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e5e7eb; /* light grey border */
    background-color: #f9fafb; /* very light background */
    display: none; /* controlled by JS */
}

/* Each bank row */
.bank-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 8px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background-color: #ffffff;
    margin-bottom: 6px;
    transition: background-color 0.15s ease, border-color 0.15s ease;
}

.bank-option:hover {
    border-color: #d1d5db;
    background-color: #f3f4f6;
    cursor: pointer;
}

.bank-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.bank-logo {
    height: 40px;
    width: auto;
    object-fit: contain;
    display: block;
}

.bank-name {
    font-size: 14px;
    color: #111827;
}

.bank-radio {
    width: 16px;
    height: 16px;
    accent-color: #111827;
    cursor: pointer;
}

/* Selected bank row */
.bank-option.bank-selected {
    border-color: #111827;
    background-color: #e5e7eb;
}

/* Hidden banks */
.bank-option.bank-hidden {
    display: none;
}

/* Change bank button */
.bank-list-toggle {
    margin-top: 6px;
    background: transparent;
    border: none;
    color: #111827;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    padding: 0;
}

.bank-list-toggle i {
    font-size: 10px;
}

/* Card details panel under Credit/Debit Card */
.card-details {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background-color: #f9fafb;
    margin-top: 10px;
}

.card-details .form-group label {
    font-size: 13px;
    font-weight: 500;
    display: block;
    margin-bottom: 4px;
}

.card-details .form-group input {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
}

.card-details .form-group input:focus {
    outline: none;
    border-color: #111827;
}

.method-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.95rem;
}

.method-desc {
    font-size: 0.8rem;
    color: #6b7280;
}

.radio-indicator {
    width: 18px;
    height: 18px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

/* Order Summary */
.order-summary {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    position: sticky;
    top: 2rem;
    margin-top: 50px;
    margin-bottom: 50px;
}

.order-summary h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
    max-height: 300px;
    overflow-y: auto;
}

.order-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 0.5rem;
}

.item-image {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 0.375rem;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid #e5e7eb;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
}

.item-details h4 {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.25rem 0;
    line-height: 1.2;
}

.item-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.item-quantity {
    font-size: 0.75rem;
    color: #6b7280;
}

.item-price {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

.summary-totals {
    margin-bottom: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.summary-row.total {
    font-weight: 600;
    font-size: 1.125rem;
    color: #1f2937;
}

.summary-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 0.75rem 0;
}

.checkout-actions {
    text-align: center;
}

.terms-agreement {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-bottom: 1rem;
    text-align: left;
    font-size: 0.875rem;
}

.terms-agreement input {
    margin-top: 0.25rem;
    flex-shrink: 0;
}

/* Button Styles */
.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
    font-size: 0.875rem;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.125rem;
    width: 100%;
}

.btn-primary {
    background: #1f2937;
    color: white;
}

.btn-primary:hover {
    background: #374151;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

/* Responsive Design */
@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .checkout-banner {
        padding: 40px 1rem;
    }

    .banner-content h1 {
        font-size: 2rem;
    }

    .checkout-container {
        padding: 0 1rem;
    }

    .order-summary {
        position: static;
        margin-top: 0;
    }

    .dropdown-content .form-row {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .payment-method-info {
        gap: 0.75rem;
    }

    .payment-logo {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }

    .method-name {
        font-size: 0.9rem;
    }

    .method-desc {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .banner-content h1 {
        font-size: 1.75rem;
    }

    .payment-label {
        padding: 0.75rem;
    }

    .payment-details {
        gap: 0.125rem;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-actions .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .bank-option {
        padding: 6px;
    }

    .bank-name {
        font-size: 13px;
    }

    .bank-logo {
        height: 18px;
    }
}
</style>
@endsection