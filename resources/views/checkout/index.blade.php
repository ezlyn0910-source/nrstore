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
                                                {{ $address->first_name }} {{ $address->last_name }}
                                                <span class="address-phone">({{ $address->phone}})</span>
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
                                            data-first-name="{{ e($address->first_name) }}"
                                            data-last-name="{{ e($address->last_name) }}"   
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

                    <!-- Add Address Section - Updated to Blue Link -->
                    <div class="add-address-section">
                        <button type="button" class="add-address-link" id="addAddressLink">
                            <i class="fas fa-plus"></i> Add New Address
                        </button>
                        
                        <div class="add-address-form" id="addAddressForm" style="display: none;">
                            <form id="addressForm" method="POST" action="{{ route('checkout.address.store') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="first_name">First Name *</label>
                                        <input type="text" id="first_name" name="first_name" placeholder="John" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last Name *</label>
                                        <input type="text" id="last_name" name="last_name" placeholder="Doe" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <div class="phone-input-group">
                                        <div class="country-code-selector">
                                            <select class="country-code" name="country_code" id="country_code">
                                                <option value="60" selected>+60</option>
                                                <option value="1">+1</option>
                                                <option value="44">+44</option>
                                                <option value="61">+61</option>
                                                <option value="65">+65</option>
                                                <option value="86">+86</option>
                                                <option value="81">+81</option>
                                                <option value="82">+82</option>
                                                <option value="91">+91</option>
                                                <option value="33">+33</option>
                                                <option value="49">+49</option>
                                                <option value="7">+7</option>
                                                <option value="55">+55</option>
                                            </select>
                                        </div>
                                        <input type="tel" id="phone" name="phone" placeholder="12-3456789" required>
                                    </div>
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
                                    <button type="button" class="btn btn-secondary cancel-btn" id="cancelAddressForm">Cancel</button>
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

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="edit_first_name">First Name <span class="required-star">*</span></label>
                                    <input type="text" id="edit_first_name" name="first_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_last_name">Last Name <span class="required-star">*</span></label>
                                    <input type="text" id="edit_last_name" name="last_name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="edit_phone">Phone Number <span class="required-star">*</span></label>
                                <input type="tel" id="edit_phone" name="phone" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_address_line_1">Address Line 1 <span class="required-star">*</span></label>
                                <input type="text" id="edit_address_line_1" name="address_line_1" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_address_line_2">Address Line 2 </label>
                                <input type="text" id="edit_address_line_2" name="address_line_2">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="edit_city">City <span class="required-star">*</span></label>
                                    <input type="text" id="edit_city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_state">State <span class="required-star">*</span></label>
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
                                    <label for="edit_postal_code">Postal Code <span class="required-star">*</span></label>
                                    <input type="text" id="edit_postal_code" name="postal_code" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_country">Country <span class="required-star">*</span></label>
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

                    <div class="payment-tabs">
                        <button class="pm-tab active" data-tab="credit">Credit Card</button>
                        <button class="pm-tab" data-tab="debit">Debit Card</button>
                        <button class="pm-tab" data-tab="online">Online Banking</button>
                    </div>

                    <!-- ===== CREDIT + DEBIT CARD PANEL ===== -->
                    <div class="payment-panel" id="panel-credit" style="display: block;">
                        <div class="card-grid">
                            {{-- If user has saved cards, show them --}}
                            @if(isset($savedCards) && $savedCards->count() > 0)
                                @foreach($savedCards as $card)
                                    <div class="saved-card" data-card-id="{{ $card->id }}">
                                        <div class="card-visual">
                                            <div class="cv-pattern"></div>
                                            <div class="cv-number">**** {{ substr($card->last4, -4) }}</div>
                                            <div class="cv-brand">{{ strtoupper($card->brand) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- ADD NEW CARD --}}
                            <div class="saved-card add-new-card" id="addNewCard">
                                <div class="card-visual add-card-visual">
                                    <span class="add-text">+ Add New</span>
                                </div>
                            </div>
                        </div>

                        {{-- SHOW CARD FORM ONLY IF "ADD NEW CARD" CLICKED --}}
                        <div class="new-card-form" id="newCardForm" style="display:none;">
                            <div class="form-group">
                                <label>Card Number</label>
                                <input type="text" placeholder="1234 5678 9012 3456">
                            </div>

                            <div class="form-group">
                                <label>Name on Card</label>
                                <input type="text" placeholder="John Doe">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Expiration Date</label>
                                    <input type="text" placeholder="MM / YY">
                                </div>
                                <div class="form-group">
                                    <label>CVV</label>
                                    <input type="text" placeholder="123">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== DEBIT CARD PANEL ===== -->
                    <div class="payment-panel" id="panel-debit" style="display: none;">
                        <!-- Debit card content similar to credit card -->
                        <p>Debit Card content would go here</p>
                    </div>

                    <!-- ===== ONLINE BANKING PANEL ===== -->
                    <div class="payment-panel" id="panel-online" style="display: none;">
                        <div class="bank-grid">
                            @foreach($banks as $index => $bank)
                                <div class="bank-box" data-bank="{{ $bank['name'] }}" data-bank-index="{{ $index }}">
                                    <img src="{{ asset('/images/banks/'.$bank['img']) }}" class="bank-icon" 
                                        alt="{{ $bank['name'] }} logo">
                                    <span class="bank-label">{{ $bank['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Hidden input to store selected bank -->
                        <input type="hidden" name="selected_bank" id="selectedBank" value="">
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
                                    
                                    // Check for product images via relationship
                                    if ($item->product && $item->product->images && $item->product->images->isNotEmpty()) {
                                        $firstImage = $item->product->images->first();
                                        if ($firstImage && $firstImage->image_path) {
                                            // Change from storage/ to proper public path
                                            $imagePath = $firstImage->image_path;
                                            // If it's just a filename, prepend the directory
                                            if (!str_contains($imagePath, '/') && !str_starts_with($imagePath, 'images/')) {
                                                $imagePath = 'images/products/' . $imagePath;
                                            }
                                            // If it doesn't start with 'images/', prepend it
                                            elseif (!str_starts_with($imagePath, 'images/')) {
                                                $imagePath = 'images/' . ltrim($imagePath, '/');
                                            }
                                            $imageUrl = asset($imagePath);
                                        }
                                    } 
                                    // Check for product's direct image field
                                    elseif ($item->product && $item->product->image) {
                                        $imagePath = $item->product->image;
                                        // If it's just a filename, prepend the directory
                                        if (!str_contains($imagePath, '/') && !str_starts_with($imagePath, 'images/')) {
                                            $imagePath = 'images/products/' . $imagePath;
                                        }
                                        // If it doesn't start with 'images/', prepend it
                                        elseif (!str_starts_with($imagePath, 'images/')) {
                                            $imagePath = 'images/' . ltrim($imagePath, '/');
                                        }
                                        $imageUrl = asset($imagePath);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $item->product->name ?? 'Product' }}" onerror="this.src='{{ asset('images/default-product.png') }}'">
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
        'first_name',
        'last_name',
        'phone',
        'address_line_1',
        // address_line_2 is optional
        'city',
        'state',
        'postal_code',
        'country',
        'country_code'
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
        inputEl.classList.add('input-error');
        inputEl.setAttribute('aria-invalid', 'true');

        let next = inputEl.nextElementSibling;
        let existing = null;

        if (next && next.classList && next.classList.contains('error-text')) {
            existing = next;
        } else {
            const parent = inputEl.parentElement;
            if (parent) {
                existing = parent.querySelector('.error-text');
            }
        }

        if (existing) {
            existing.textContent = message;
        } else {
            const err = createErrorEl(message);
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

        const parent = inputEl.parentElement;
        if (!parent) return;
        const existing = parent.querySelector('.error-text');
        if (existing) existing.remove();
    }

    function getVal(name, form) {
        const el = form.querySelector('[name="' + name + '"]');
        if (!el) return '';
        return (el.value || '').toString().trim();
    }

    function validatePhone(value) {
        const v = value.replace(/\s+/g, '');
        return /^[\d\+\-\s]{6,20}$/.test(value) && v.length >= 6;
    }

    function validatePostal(value) {
        return /^[A-Za-z0-9\- ]{3,10}$/.test(value);
    }

    function validateFormClient(form) {
        const errors = {};

        SELECTORS.forEach(name => {
            const val = getVal(name, form);
            if (name === 'country') {
                if (!val) errors[name] = 'Country is required.';
                return;
            }
            if (!val) {
                errors[name] = (name === 'postal_code')
                    ? 'Postal code is required.'
                    : (name === 'first_name' || name === 'last_name'
                        ? 'Name is required.'
                        : 'This field is required.');
                return;
            }

            if (name === 'phone' && !validatePhone(val)) {
                errors[name] = 'Please enter a valid phone number.';
            }
            if (name === 'postal_code' && !validatePostal(val)) {
                errors[name] = 'Please enter a valid postal code.';
            }
        });

        const stateVal = getVal('state', form);
        if (!stateVal) {
            errors['state'] = 'Please select a state.';
        }

        return { valid: Object.keys(errors).length === 0, errors };
    }

    function attachLiveClear(form) {
        SELECTORS.forEach(name => {
            const el = form.querySelector('[name="' + name + '"]');
            if (!el) return;
            const ev = (el.tagName.toLowerCase() === 'select' || el.type === 'checkbox' || el.type === 'radio')
                ? 'change'
                : 'input';
            el.addEventListener(ev, function () {
                clearFieldError(el);
            });
        });
    }

    // ADD ADDRESS LINK TOGGLE
    const addAddressLink = document.getElementById('addAddressLink');
    const addAddressForm = document.getElementById('addAddressForm');
    const cancelAddressForm = document.getElementById('cancelAddressForm');

    if (addAddressLink && addAddressForm) {
        addAddressLink.addEventListener('click', () => {
            addAddressForm.style.display = addAddressForm.style.display === 'none' ? 'block' : 'none';
        });
    }

    if (cancelAddressForm && addAddressForm) {
        cancelAddressForm.addEventListener('click', () => {
            addAddressForm.style.display = 'none';
        });
    }

    window.addEventListener('load', function () {
        // ============================
        // ADDRESS DROPDOWN TOGGLE
        // ============================
        const dropdownContent = document.getElementById('addressDropdownContent');
        const dropdownToggle  = document.getElementById('dropdownToggle');
        const dropdownHeader  = document.getElementById('addressDropdownHeader');

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

        // ===============FORM HANDLING – ADD ADDRESS==================
        const addressForm = document.getElementById('addressForm');

        if (addressForm) {
            attachLiveClear(addressForm);

            addressForm.addEventListener('submit', function (evt) {
                evt.preventDefault();

                const globalErr = document.querySelector('.form-global-error');
                if (globalErr) globalErr.remove();

                const saveBtn = this.querySelector('.save-btn');
                const originalText = saveBtn ? saveBtn.textContent : 'Save';

                const validated = validateFormClient(this);

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
                        alert(data.message || 'Address saved successfully!');
                        if (typeof updateToggleState === 'function') updateToggleState(false);
                        this.reset();
                        window.location.reload();
                        return;
                    }

                    if (res.status === 422 && data && data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            if (key === 'is_default') {
                                const cb = document.getElementById('is_default');
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
                                console.warn('Validation error on unknown field:', key, data.errors[key]);
                            }
                        });

                        return;
                    }

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

        // ================EDIT ADDRESS POPUP==================
        const editAddressModal  = document.getElementById('editAddressModal');
        const editAddressForm   = document.getElementById('editAddressForm');
        const editAddressCancel = document.getElementById('editAddressCancel');
        const editAddressClose  = document.getElementById('editAddressClose');

        function openEditAddressModal(trigger) {
            if (!editAddressModal || !editAddressForm) return;

            if (!trigger.classList.contains('address-edit-link')) {
                trigger = trigger.closest('.address-edit-link');
            }
            if (!trigger) return;

            const updateUrl = trigger.getAttribute('data-update-url') || '';
            editAddressForm.action = updateUrl;

            const map = {
                first_name:     'firstName',
                last_name:      'lastName',
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
                
                if (name === 'phone') {
                    // Parse phone number with country code
                    const phoneValue = trigger.dataset[dataKey] || '';
                    let countryCode = '60';
                    let phoneNumber = phoneValue;

                    // If phone starts with +, parse it
                    if (phoneValue.startsWith('+')) {
                        const match = phoneValue.match(/^\+(\d+)(.*)$/);
                        if (match) {
                            countryCode = match[1];
                            phoneNumber = match[2];
                        }
                    }

                    // Set country code and phone number
                    const countryCodeSelect = editAddressForm.querySelector('#edit_country_code');
                    if (countryCodeSelect) {
                        countryCodeSelect.value = countryCode;
                    }
                    input.value = phoneNumber;
                } else {
                    input.value = trigger.dataset[dataKey] || '';
                }
            });

            const isDefaultCb  = editAddressForm.querySelector('#edit_is_default');
            if (isDefaultCb) {
                const isDefaultAttr = trigger.getAttribute('data-is-default');
                const isDefault     = (isDefaultAttr === '1' || isDefaultAttr === 'true');

                isDefaultCb.checked = isDefault;
                isDefaultCb.setCustomValidity('');
            }

            SELECTORS.forEach(name => {
                const el = editAddressForm.querySelector('[name="' + name + '"]');
                if (el) clearFieldError(el);
            });
            const globalErr = editAddressForm.querySelector('.form-global-error');
            if (globalErr) globalErr.remove();

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
                    openEditAddressModal(this);
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

                const validated = validateFormClient(this);

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
                
                // Format phone number with country code
                const countryCode = getVal('country_code', this);
                const phoneValue = getVal('phone', this);
                const fullPhoneNumber = `+${countryCode}${phoneValue.replace(/\D/g, '')}`;
                formData.set('phone', fullPhoneNumber);

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(async res => {
                    // ... rest of the code remains the same
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

        // TAB SWITCHING
        document.querySelectorAll('.pm-tab').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.pm-tab').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const tab = btn.dataset.tab;

                document.querySelectorAll('.payment-panel').forEach(p => p.style.display = "none");
                document.getElementById('panel-' + tab).style.display = "block";
                
                // Clear bank selection when switching away from online banking
                if (tab !== 'online') {
                    document.querySelectorAll('.bank-box').forEach(b => b.classList.remove('selected'));
                    document.getElementById('selectedBank').value = '';
                }
            });
        });

        // ADD NEW CARD CLICK
        const addCardBtn = document.getElementById("addNewCard");
        const newCardForm = document.getElementById("newCardForm");

        if (addCardBtn && newCardForm) {
            addCardBtn.addEventListener("click", () => {
                newCardForm.style.display = "block";
            });
        }

        // ONLINE BANK SELECTION
        document.querySelectorAll('.bank-box').forEach(box => {
            box.addEventListener('click', () => {
                // Remove selected class from all banks
                document.querySelectorAll('.bank-box').forEach(b => b.classList.remove('selected'));
                
                // Add selected class to clicked bank
                box.classList.add('selected');
                
                // Get selected bank name
                const selectedBank = box.getAttribute('data-bank');
                
                // Update hidden input
                document.getElementById('selectedBank').value = selectedBank;
                
                console.log('Selected bank:', selectedBank);
            });
        });

        // ==================PLACE ORDER → PAYMENT.PROCESS=================
        const placeOrderBtn = document.getElementById('placeOrderBtn');

        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function () {
                // 1. Check address selected
                const addressRadio = document.querySelector('input[name="selected_address"]:checked');
                if (!addressRadio) {
                    alert('Please select a shipping address.');
                    return;
                }

                // 2. Check payment method
                const activeTab = document.querySelector('.pm-tab.active');
                const paymentMethod = activeTab ? activeTab.dataset.tab : '';
                
                if (!paymentMethod) {
                    alert('Please select a payment method.');
                    return;
                }
                
                // 3. If online banking is selected, check if a bank is chosen
                if (paymentMethod === 'online') {
                    const selectedBank = document.getElementById('selectedBank').value;
                    if (!selectedBank) {
                        alert('Please select a bank for online banking.');
                        return;
                    }
                    
                    // Update the hidden form field
                    const bankInput = document.getElementById('po_online_banking_bank');
                    if (bankInput) {
                        bankInput.value = selectedBank;
                    }
                }

                // 4. Fill the hidden Laravel form
                const form = document.getElementById('placeOrderForm');
                const addrInput = document.getElementById('po_selected_address');
                const pmInput = document.getElementById('po_payment_method');
                
                if (!form) {
                    console.error('placeOrderForm not found');
                    alert('Something went wrong. Please refresh and try again.');
                    return;
                }

                // Map tab names to payment method values
                const paymentMethodMap = {
                    'credit': 'credit_card',
                    'debit': 'debit_card',
                    'online': 'online_banking'
                };
                
                if (addrInput) addrInput.value = addressRadio.value;
                if (pmInput) pmInput.value = paymentMethodMap[paymentMethod] || paymentMethod;

                // 5. Submit the real form
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

.checkout-right {
    position: relative;
    height: 100%; /* Add this */
}

/* Update .checkout-content */
.checkout-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
    align-items: start; /* Ensure this is set */
    min-height: 600px; /* Add minimum height */
}

/* Section Styles */
.checkout-section {
    margin-bottom: 2rem;
}

.checkout-section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 2.5rem 0 1.5rem 0;
    padding-bottom: 0.75rem;
}

/* ===== ADD ADDRESS LINK STYLES ===== */
.add-address-section {
    margin-top: 1.5rem;
}

.add-address-link {
    background: none;
    border: none;
    color: #2563eb; /* Blue color */
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0 0;
    transition: color 0.2s ease;
}

.add-address-link:hover {
    color: #1d4ed8; /* Darker blue on hover */
    text-decoration: underline;
}

.add-address-link i {
    font-size: 0.875rem;
}

/* Add Address Form - Same as before but with margin */
.add-address-form {
    margin-top: 1.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    background: white;
    animation: fadeIn 0.3s ease;
}

/* Form styles inside add address form */
.add-address-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.add-address-form .form-group {
    margin-bottom: 1rem;
}

.add-address-form .form-group label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.add-address-form .form-group input,
.add-address-form .form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
}

.add-address-form .form-group input:focus,
.add-address-form .form-group select:focus {
    outline: none;
    border-color: #1f2937;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

.add-address-form #country {
    background: #f3f4f6;
    cursor: not-allowed;
}

.add-address-form .form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1.5rem 0;
}

.add-address-form .form-check input {
    width: auto;
    margin: 0;
}

.add-address-form .form-check label {
    font-size: 0.875rem;
    color: #374151;
    margin: 0;
}

.add-address-form .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
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
    margin-bottom: 0;
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

/* Phone Input Group */
.phone-input-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    width: 100%;
}

.country-code-selector {
    flex-shrink: 0;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    height: 48px;
    display: flex;
    align-items: center;
}

.country-code {
    border: none;
    background: transparent;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    outline: none;
    padding: 0 0.75rem;
    height: 100%;
    width: 80px;
}

.country-code:focus {
    outline: none;
}

.phone-input-group input[type="tel"] {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
}

.phone-input-group input[type="tel"]:focus {
    outline: none;
    border-color: #1f2937;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
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

/* ===== PAYMENT TABS ===== */
.payment-tabs {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb; /* grey divider below all tabs */
    padding-bottom: 4px;
}

.pm-tab {
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    padding: 0.5rem 0;
    color: #6b7280; /* medium gray for inactive */
    position: relative;
    transition: color 0.2s ease;
    border: none;
    background: none;
}

.pm-tab.active {
    color: #111827; /* nearly black for active */
    font-weight: 600;
}

/* Active underline - appears above the grey divider */
.pm-tab.active::after {
    content: "";
    position: absolute;
    bottom: -4px; /* positions it right above the grey divider */
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #111827;
    border-radius: 2px;
}

/* Hover effect */
.pm-tab:hover {
    color: #374151; /* darker gray on hover */
}

/* ======CARD GRID======= */
.card-grid {
    display: grid;
    grid-template-columns: 180px 180px 180px;
    gap: 1rem;
}

.saved-card {
    cursor: pointer;
}

.card-visual {
    background: #e8eaf6;
    border-radius: 12px;
    padding: 15px;
    height: 120px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.add-card-visual {
    background: #d1fae5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-text {
    font-weight: 700;
    font-size: 1.1rem;
}

.cv-number {
    font-size: 1rem;
    font-weight: 700;
    color: #111;
}

.cv-brand {
    font-size: 0.85rem;
    opacity: 0.7;
}

/* ======NEW CARD FORM====== */
.new-card-form {
    margin-top: 1.5rem;
    padding: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    background: #f9fafb;
}

/* ===== ONLINE BANKING GRID ===== */
.bank-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 columns */
    gap: 1rem;
    margin-top: 1.5rem;
}

.bank-box {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: white;
}

.bank-box.selected {
    border-color: #1f2937 !important;
    background-color: #f3f4f6 !important;
    box-shadow: 0 0 0 2px rgba(31, 41, 55, 0.2) !important;
}

.bank-box:hover {
    border-color: #9ca3af !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}

.bank-icon {
    width: 36px;
    height: 36px;
    object-fit: contain;
}

.bank-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #1f2937;
}

/* ======= ONLINE BANKING – BANK LIST======== */

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
    position: -webkit-sticky; /* For Safari */
    position: sticky;
    top: 20px; /* Changed from 2rem to 20px */
    align-self: flex-start; /* Important for grid/flex children */
    margin-top: 50px;
    margin-bottom: 50px;
    max-height: calc(100vh - 100px); /* Increased from 4rem to 100px */
    overflow-y: auto;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    z-index: 10; /* Add z-index */
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
        width: 100%;
        max-height: none;
        margin-top: 2rem;
        margin-bottom: 2rem;
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