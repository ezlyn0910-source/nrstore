@extends('layouts.app')

@section('content')
<div class="checkout-page">
    <!-- Checkout Header with Back -->
    <div class="checkout-header">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('cart.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Checkout</h1>
            </div>
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
                                                <span class="address-phone">({{ $address->country_code }}{{ $address->phone }})</span>
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
                                            data-country-code="{{ $address->country_code }}"
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

                    <!-- Add Address Section -->
                    <div class="add-address-section">
                        <button type="button" class="add-address-link" id="addAddressLink">
                            <i class="fas fa-plus"></i> Add New Address
                        </button>
                        
                        <div class="add-address-form" id="addAddressForm" style="display: none;">
                            <form id="addressForm" method="POST" action="{{ route('checkout.address.store') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="required-star">*</span></label>
                                        <input type="text" id="first_name" name="first_name" placeholder="John" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="required-star">*</span></label>
                                        <input type="text" id="last_name" name="last_name" placeholder="Doe" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_phone">Phone Number <span class="required-star">*</span></label>
                                    <div class="phone-input-group">
                                        <div class="country-code-selector">
                                            <select class="country-code" name="country_code" id="edit_country_code">
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
                                        <input type="tel" id="edit_phone" name="phone" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address_line_1">Address Line 1 <span class="required-star">*</span></label>
                                    <input type="text" id="address_line_1" name="address_line_1" placeholder="House no, Street name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address_line_2">Address Line 2 (Optional)</label>
                                    <input type="text" id="address_line_2" name="address_line_2" placeholder="Apartment, suite, unit, etc.">
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="city">City <span class="required-star">*</span></label>
                                        <input type="text" id="city" name="city" placeholder="Kuala Lumpur" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State <span class="required-star">*</span></label>
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
                                        <label for="postal_code">Postal Code <span class="required-star">*</span></label>
                                        <input type="text" id="postal_code" name="postal_code" placeholder="50000" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country <span class="required-star">*</span></label>
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
                                <div class="phone-input-group">
                                    <div class="country-code-selector">
                                        <select class="country-code" name="country_code" id="edit_country_code">
                                            <option value="60">+60</option>
                                            <option value="1">+1</option>
                                            <option value="44">+44</option>
                                            <option value="61">+61</option>
                                            <option value="65">+65</option>
                                            <option value="86">+86</option>
                                            <option value="81">+81</option>
                                            <option value="82">+82</option>
                                            <option value="91">+91</option>
                                        </select>
                                    </div>

                                    <input
                                        type="tel"
                                        id="edit_phone"
                                        name="phone"
                                        required
                                    >
                                </div>
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

                    <div class="payment-methods-grid">
                        <!-- Credit/Debit Card Option -->
                        <div class="payment-method-box" data-method="card">
                            <div class="payment-method-content">
                                <div class="payment-method-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="payment-method-info">
                                    <h4>Credit / Debit Card</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Online Banking Option -->
                        <div class="payment-method-box" data-method="online">
                            <div class="payment-method-content">
                                <div class="payment-method-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="payment-method-info">
                                    <h4>Online Banking</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="checkout-right">
                @php
                    $discountAmount = $discount ?? 0;
                @endphp

                <div class="order-summary">
                    <h3>ORDER SUMMARY</h3>

                    <div class="order-items-scroll">
                        @foreach($cartItems as $item)
                            @php
                                // Handle both object and array access
                                $product = is_array($item) ? ($item['product'] ?? null) : $item->product;
                                
                                $imageUrl = asset('images/default-product.png');
                                if ($product && $product->images && $product->images->isNotEmpty()) {
                                    $firstImage = $product->images->first();
                                    if ($firstImage && $firstImage->image_path) {
                                        $imageUrl = asset('/' . $firstImage->image_path);
                                    }
                                } elseif ($product && $product->image) {
                                    $imageUrl = asset('/' . $product->image);
                                }

                                // Build short specs like your screenshot
                                $specParts = [];
                                if (!empty($product->processor)) $specParts[] = $product->processor;
                                if (!empty($product->ram)) $specParts[] = $product->ram;
                                if (!empty($product->storage)) $specParts[] = $product->storage;
                                $specText = implode(' • ', $specParts);
                                
                                // Handle both object and array access for other properties
                                $itemName = is_array($item) ? ($item['name'] ?? 'Product') : ($item->name ?? 'Product');
                                $itemPrice = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
                                $itemQuantity = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
                            @endphp

                            <div class="os-item">
                                <div class="os-img">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name ?? 'Product' }}">
                                </div>

                                <div class="os-info">
                                    <div class="os-title">{{ $product->name ?? 'Product' }}</div>
                                    @if(!empty($specText))
                                        <div class="os-spec">{{ $specText }}</div>
                                    @endif

                                    <div class="os-price-row">
                                        <div class="os-price">RM{{ number_format($itemPrice, 2) }}</div>
                                        <div class="os-qty">× {{ $itemQuantity }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="os-item-divider"></div>
                        @endforeach
                    </div>

                    <div class="os-voucher">
                        <input type="text" placeholder="Enter you voucher code here" aria-label="voucher">
                        <button type="button">Apply</button>
                    </div>

                    <div class="os-totals">
                        <div class="os-row">
                            <span class="os-label">SUBTOTAL</span>
                            <span class="os-value">RM{{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($discountAmount > 0)
                            <div class="os-row os-row-discount">
                                <span class="os-label">DISCOUNT</span>
                                <span class="os-value">- RM{{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif

                        <div class="os-row">
                            <span class="os-label">SHIPPING</span>
                            <span class="os-value">RM{{ number_format($shippingFee, 2) }}</span>
                        </div>

                        <div class="os-total">
                            <span class="os-total-label">TOTAL</span>
                            <span class="os-total-value">
                                RM{{ number_format(($subtotal - $discountAmount) + $shippingFee, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="os-footer">
                        <button type="button" id="placeOrderBtn" class="btn-place-order">Place Order</button>
                    </div>

                    {{-- Hidden payment form stays the same --}}
                    <form id="placeOrderForm" method="POST" action="{{ route('payment.process') }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="selected_address" id="po_selected_address">
                        <input type="hidden" name="payment_method" id="po_payment_method">
                        <input type="hidden" name="online_banking_bank" id="po_online_banking_bank">
                        <input type="hidden" name="amount" id="po_amount" value="{{ ($subtotal - $discountAmount) + $shippingFee }}">
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
        // ===== COMMON ELEMENTS =====
        const addressForm     = document.getElementById('addressForm');
        const editAddressModal  = document.getElementById('editAddressModal');
        const editAddressForm   = document.getElementById('editAddressForm');
        const editAddressClose  = document.getElementById('editAddressClose');
        const placeOrderBtn     = document.getElementById('placeOrderBtn');

        // ============================
        // FORM HANDLING – ADD ADDRESS
        // ============================
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
                const isDefaultCheckbox = this.querySelector('#is_default');
                if (isDefaultCheckbox) {
                    formData.set('is_default', isDefaultCheckbox.checked ? '1' : '0');
                }

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const contentType = res.headers.get('content-type') || '';
                    const isJson = contentType.includes('application/json');
                    
                    let data = null;
                        let rawText = null;

                        if (isJson) {
                            data = await res.json();
                        } else {
                            rawText = await res.text();
                            console.error('Non-JSON response:', rawText);
                        }

                    if (res.ok && data && data.success) {
                        alert(data.message || 'Address saved successfully!');
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
                            }
                        });
                        return;
                    }

                    alert((data && data.message) ? data.message : 'Failed to save address. Please try again.');

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
        // EDIT ADDRESS – OPEN + SUBMIT
        // ============================
        function openEditAddressModal(trigger) {
            if (!editAddressModal || !editAddressForm) return;

            editAddressForm.action = trigger.getAttribute('data-update-url') || '';

            editAddressForm.querySelector('#edit_first_name').value =
                trigger.dataset.firstName || '';
            editAddressForm.querySelector('#edit_last_name').value =
                trigger.dataset.lastName || '';
            editAddressForm.querySelector('#edit_phone').value =
                trigger.dataset.phone || '';
            editAddressForm.querySelector('#edit_address_line_1').value =
                trigger.dataset.line1 || '';
            editAddressForm.querySelector('#edit_address_line_2').value =
                trigger.dataset.line2 || '';
            editAddressForm.querySelector('#edit_city').value =
                trigger.dataset.city || '';
            editAddressForm.querySelector('#edit_postal_code').value =
                trigger.dataset.postal || '';
            editAddressForm.querySelector('#edit_country').value =
                trigger.dataset.country || 'Malaysia';
            editAddressForm.querySelector('#edit_country_code').value =
                trigger.dataset.countryCode || '60';

            const stateSelect = editAddressForm.querySelector('#edit_state');
            if (stateSelect) {
                stateSelect.value = trigger.dataset.state || '';
            }

            const isDefaultCb = editAddressForm.querySelector('#edit_is_default');
            if (isDefaultCb) {
                const isDefaultAttr = trigger.getAttribute('data-is-default');
                isDefaultCb.checked = (isDefaultAttr === '1' || isDefaultAttr === 'true');
            }

            SELECTORS.forEach(name => {
                const el = editAddressForm.querySelector('[name="' + name + '"]');
                if (el) clearFieldError(el);
            });

            editAddressModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeEditAddressModal() {
            if (!editAddressModal) return;
            editAddressModal.classList.remove('show');
            document.body.style.overflow = '';
        }

        const editButtons = document.querySelectorAll('.address-edit-link');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                openEditAddressModal(this);
            });
        });

        if (editAddressClose) {
            editAddressClose.addEventListener('click', function (e) {
                e.preventDefault();
                closeEditAddressModal();
            });
        }

        if (editAddressModal) {
            editAddressModal.addEventListener('click', function (e) {
                if (e.target.classList.contains('address-edit-modal')) {
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

                if (saveBtn) {
                    saveBtn.textContent = 'Saving...';
                    saveBtn.disabled = true;
                }

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST', // Laravel uses POST + _method=PUT
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const contentType = res.headers.get('content-type') || '';
                    const isJson = contentType.includes('application/json');
                    const data = isJson ? await res.json() : null;

                    if (res.ok && data && data.success) {
                        alert(data.message || 'Address updated successfully!');
                        closeEditAddressModal();
                        window.location.reload();
                        return;
                    }

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

                            const field = editAddressForm.querySelector('[name="' + key + '"]');
                            if (field) {
                                showFieldError(field, data.errors[key][0]);
                            }
                        });
                        return;
                    }

                    console.error('Address update failed:', data);
                    alert((data && data.message) ? data.message : 'Failed to save address. Please try again.');

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
        // PAYMENT METHOD SELECT (CARD / ONLINE)
        // ============================
        const paymentBoxes = document.querySelectorAll('.payment-method-box');
        paymentBoxes.forEach(box => {
            box.addEventListener('click', function () {
                paymentBoxes.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // ============================
        // PLACE ORDER → PAYMENT.PROCESS
        // ============================
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function () {
                const addressRadio = document.querySelector('input[name="selected_address"]:checked');
                if (!addressRadio) {
                    alert('Please select a shipping address.');
                    return;
                }

                const selectedBox = document.querySelector('.payment-method-box.selected');
                const paymentMethodKey = selectedBox ? selectedBox.dataset.method : '';

                if (!paymentMethodKey) {
                    alert('Please select a payment method.');
                    return;
                }

                const form      = document.getElementById('placeOrderForm');
                const addrInput = document.getElementById('po_selected_address');
                const pmInput   = document.getElementById('po_payment_method');

                if (!form || !addrInput || !pmInput) {
                    alert('Something went wrong. Please refresh and try again.');
                    return;
                }

                const paymentMethodMap = {
                    // card
                    card: 'credit_card',
                    credit_card: 'credit_card',
                    debit_card: 'debit_card',

                    // online banking
                    online: 'online_banking',
                    online_banking: 'online_banking',
                    fpx: 'online_banking',
                    fpx_toyyibpay: 'online_banking',
                    toyyibpay: 'online_banking',
                };

                addrInput.value = addressRadio.value;
                pmInput.value   = paymentMethodMap[paymentMethodKey] || paymentMethodKey;

                // If you later add bank selection for online banking, handle it safely:
                if (paymentMethodKey === 'online') {
                    const bankSelectEl = document.getElementById('selectedBank');
                    if (bankSelectEl) {
                        const selectedBank = bankSelectEl.value;
                        if (!selectedBank) {
                            alert('Please select a bank for online banking.');
                            return;
                        }
                        const bankInput = document.getElementById('po_online_banking_bank');
                        if (bankInput) bankInput.value = selectedBank;
                    }
                }

                form.submit();
            });
        }
    });
})(); // IIFE
</script>
@endpush

@section('styles')
<style>
.checkout-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #ffffff 0%, #f9fffb 50%, #f0fff8 100%);
    font-family: "Nunito", sans-serif;
    padding-bottom: 3rem;
}

/* ===== CHECKOUT HEADER ===== */
.checkout-header {
    background: transparent;
    padding: 5rem 0 0.5rem 0;
    margin-bottom: 0.5rem;
}

.checkout-header .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 3rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.back-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: 1px solid #dee2e6;
    color: #495057;
    text-decoration: none;
    transition: all 0.2s ease;
}

.back-link:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    color: #212529;
}

.back-link i {
    font-size: 1rem;
}

.checkout-header h1 {
    font-size: 2.5rem;
    font-weight: 600;
    color: #212529;
    margin: 0;
}

/* Container */
.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 3rem;
    margin-top: 0;
}

.checkout-right {
    position: relative;
    height: 100%;
}

.checkout-content {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 3rem;
    margin-top: 1rem;
    align-items: start;
    min-height: 600px; 
}

/* Section Styles */
.checkout-section {
    margin-bottom: 1rem;
}

.checkout-section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 1rem 0 1rem 0;
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

.add-address-form .btn{
    padding: 0.75rem 1.25rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.95rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
}

.add-address-form .btn-primary{
    background: #1f2937;
    color: #fff;
}

.add-address-form .btn-primary:hover{
    background: #374151;
    transform: translateY(-1px);
}

.add-address-form .btn-secondary{
    background: #fff;
    color: #1f2937;
    border-color: #d1d5db;
}

.add-address-form .btn-secondary:hover{
    background: #f9fafb;
    border-color: #9ca3af;
}

/* ===== EDIT ADDRESS SAVE BUTTON ===== */
#editAddressSave {
    width: 100%;
    padding: 12px 24px;
    font-size: 1rem;
    font-weight: 600;
    background: #1f2937;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-top: 1rem;
}

#editAddressSave:hover {
    background: #374151;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

#editAddressSave:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

#editAddressSave:disabled {
    background: #9ca3af;
    color: #6b7280;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Button loading state */
#editAddressSave.saving {
    position: relative;
    color: transparent;
}

#editAddressSave.saving::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Edit form actions container */
.edit-form-actions {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

/* Make sure the button is properly aligned */
.address-edit-dialog .edit-form-actions {
    width: 100%;
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
    background: #ffffff;
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
    margin: 0 0 2rem 0;
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

/* Make country code box match other edit modal inputs */
.address-edit-dialog .country-code-selector {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    height: auto;
}

.address-edit-dialog .country-code {
    width: 80px;
    height: auto;           /* match select default height */
    padding: 10px 12px;     /* match .address-edit-dialog select padding */
    font-size: 0.9rem;      /* match .address-edit-dialog select font-size */
}

/* Optional: make focus same as other edit modal fields */
.address-edit-dialog .phone-input-group:focus-within .country-code-selector {
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

/* ===== PAYMENT METHODS GRID ===== */
.payment-methods-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.payment-method-box {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    width: 50%;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.payment-method-box:hover {
    border-color: #9ca3af;
    background-color: #f9fafb;
}

.payment-method-box.selected {
    border-color: #6b7280;
    background-color: #f3f4f6;
    box-shadow: 0 0 0 1px rgba(107, 114, 128, 0.2);
}

.payment-method-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.payment-method-icon {
    width: 36px;
    height: 36px;
    background: #f3f4f6;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #374151;
}

.payment-method-box.selected .payment-method-icon {
    background: #9ca3af;
    color: white;
}

.payment-method-info {
    flex: 1;
}

.payment-method-info h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

/* Payment Details (shown when method is selected) */
.payment-details {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
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

/* Card Grid adjustments for new layout */
.payment-details .card-grid {
    margin-top: 0;
}

/* Online Banking Grid adjustments */
.payment-details .bank-grid {
    margin-top: 0;
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

/* ===== ORDER SUMMARY (Redesign like screenshot) ===== */
.order-summary{
    background: #fff;
    border-radius: 12px;
    padding: 1.75rem;
    border: 1px solid #e5e7eb;
    position: sticky;
    top: 20px;
    margin-top: 2rem;
    height: calc(100vh - 40px);
    max-height: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    z-index: 10;

    /* ✅ make internal sections layout properly */
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.order-summary h3{
    font-size: 1.25rem;
    font-weight: 800;
    color: #1f2937;
    margin: 0 0 2rem 0;
    text-align: center;
    letter-spacing: 0.5px;
}

/* ✅ Scroll area for products only */
.order-items-scroll{
    flex: 1;
    overflow-y: auto;
    padding-right: 6px; /* space for scrollbar */
}

/* Product rows */
.os-item{
    display: grid;
    grid-template-columns: 64px 1fr;
    gap: 12px;
    align-items: start;
    padding: 8px 0;
}

.os-img img{
    width: 64px;
    height: 44px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #fff;
}

.os-title{
    font-weight: 700;
    color: #1f2937;
    font-size: 0.95rem;
    line-height: 1.2;
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.os-spec{
    margin-top: 4px;
    font-size: 0.82rem;
    color: #6b7280;
    line-height: 1.2;
}

.os-price-row{
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.os-price{
    font-weight: 800;
    color: #111827;
    font-size: 0.95rem;
}

.os-qty{
    font-size: 0.85rem;
    color: #6b7280;
}

.os-item-divider{
    height: 1px;
    background: #e5e7eb;
    margin: 10px 0;
}

/* Voucher */
.os-voucher{
    display: flex;
    gap: 10px;
    padding: 14px 0 10px;
}

.os-voucher input{
    flex: 1;
    height: 44px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    padding: 0 12px;
    font-size: 0.9rem;
    outline: none;
}

.os-voucher input:focus{
    border-color: #1f2937;
    box-shadow: 0 0 0 3px rgba(31,41,55,0.1);
}

.os-voucher button{
    height: 44px;
    padding: 0 14px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    font-weight: 700;
    cursor: pointer;
}

.os-voucher button:hover{
    background: #f3f4f6;
    border-color: #9ca3af;
}

/* Totals */
.os-totals{
    padding-top: 6px;
}

.os-row{
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    font-size: 0.9rem;
}

.os-label{
    color: #6b7280;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.os-value{
    color: #111827;
    font-weight: 700;
}

.os-row-discount .os-value{
    color: #111827;
}

/* Total row */
.os-total{
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    padding-top: 14px;
}

.os-total-label{
    font-size: 1.4rem;
    font-weight: 900;
    color: #111827;
}

.os-total-value{
    font-size: 1.4rem;
    font-weight: 900;
    color: #111827;
}

/* ✅ Footer pinned bottom inside the box (no divider above button) */
.os-footer{
    margin-top: 0.65rem;
    padding-top: 14px;
}

/* Keep your existing button style */
.btn-place-order{
    padding: 1rem;
    font-size: 1.05rem;
    width: 100%;
    font-weight: 700;
    background: #1f2937;
    color: white;
    border: none;
    border-radius: 3rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-place-order:hover{
    background: #374151;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .checkout-container {
        padding: 0 1rem;
    }

    .order-summary {
        position: static;
        width: 100%;
        max-height: none;
        margin-top: 2rem;
        height: auto;
        overflow: visible;
    }
    
    .product-image-center {
        max-width: 140px;
    }
    
    .product-image-center img {
        width: 120px;
        height: 120px;
    }
    
    .product-name-large {
        font-size: 1.2rem;
    }
    
    .product-price {
        font-size: 1.2rem;
    }
    
    .total-value {
        font-size: 1.3rem;
    }
    
    .product-image {
        width: 70px;
        height: 70px;
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

    .payment-method-content {
        gap: 0.75rem;
    }
    
    .payment-method-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .payment-method-info h4 {
        font-size: 0.9rem;
    }
    
    .payment-method-info p {
        font-size: 0.8rem;
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
    #editAddressSave {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
}
</style>
@endsection