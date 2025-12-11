@extends('layouts.app')

@section('styles')
<style>
    :root {
        --primary-green: #2d4a35;
        --accent-gold: #f4b740;
        --accent-brown: #4b2a16;
        --border-light: #e5e7eb;
        --text-main: #1f2933;
        --text-muted: #6b7280;
        --bg-light: #f9fafb;
    }

    .account-page {
        min-height: 70vh;
        background: #ffffff;
        font-family: 'Nunito', sans-serif;
        padding: 0 0 60px;
        box-sizing: border-box;
    }

    .account-hero {
        background: #dce9df !important;
        padding: 40px 3rem;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .account-hero h1 {
        margin: 0;
        font-size: 2.4rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .account-breadcrumb {
        margin-top: 8px;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .account-breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
    }

    .account-breadcrumb span {
        margin: 0 4px;
    }

    .account-wrapper {
        max-width: 1200px;
        margin: 30px auto 0;
        padding: 0 3rem 0 3rem;
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 30px;
        box-sizing: border-box;
    }

    /* Sidebar */
    .account-sidebar {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid var(--border-light);
        padding: 6px;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        height: fit-content;
    }

    .account-nav-item {
        display: block;
        width: 100%;
        text-align: left;
        border: none;
        background: transparent;
        padding: 12px 16px;
        margin-bottom: 4px;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-main);
        cursor: pointer;
        text-decoration: none;
    }

    .account-nav-item:hover {
        background: #f3f4f6;
    }

    .account-nav-item.active {
        background: var(--primary-green);
        color: #ffffff;
    }

    .account-nav-item.logout {
        color: #b91c1c;
    }

    /* Content panel */
    .account-content {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid var(--border-light);
        padding: 24px 32px 32px;
        margin: 20px 0 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .account-section-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text-main);
    }

    /* Form */
    .account-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0,1fr));
        gap: 16px 24px;
        margin-top: 8px;
    }

    .account-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .account-form-group label {
        font-size: 0.9rem;
        color: var(--text-main);
        font-weight: 600;
    }

    .account-form-group span.required {
        color: #dc2626;
        margin-left: 2px;
    }

    .account-form-control,
    .account-form-select {
        border-radius: 6px;
        border: 1px solid var(--border-light);
        padding: 10px 12px;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .account-form-control:focus,
    .account-form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 1px rgba(45,74,53,0.2);
    }

    .account-form-select {
        background-color: #fff;
    }

    .account-actions {
        margin-top: 24px;
    }

    .btn-account-primary {
        background: var(--accent-brown);
        color: #fff;
        border-radius: 6px;
        border: none;
        padding: 10px 20px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.05s ease;
    }

    .btn-account-primary:hover {
        background: #3b1c0d;
        transform: translateY(-1px);
    }

    /* Address card */
    .address-card {
        border:1px solid var(--border-light);
        border-radius:8px;
        padding:14px 16px;
        margin-bottom:10px;
    }

    .address-card-header {
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:12px;
    }

    .address-actions {
        text-align:right;
        font-size:0.85rem;
        white-space:nowrap;
    }

    .link-button {
        background:none;
        border:none;
        padding:0;
        margin:0 0 0 12px;
        cursor:pointer;
        text-decoration:none;
        font-size:0.85rem;
    }

    .link-edit {
        color:var(--primary-green);
    }

    .link-delete {
        color:#b91c1c;
    }

    .link-edit:hover,
    .link-delete:hover {
        text-decoration:underline;
    }

    /* ===== Confirmation Modal ===== */
    .confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;                      /* hidden by default */
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .confirm-box {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        max-width: 360px;
        width: 90%;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.25);
        text-align: left;
        font-family: 'Nunito', sans-serif;
    }

    .confirm-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-main);
    }

    .confirm-message {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 18px;
    }

    .confirm-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .confirm-btn-cancel {
        padding: 8px 14px;
        border-radius: 999px;
        border: 1px solid var(--border-light);
        background: #ffffff;
        color: var(--text-main);
        font-size: 0.9rem;
        cursor: pointer;
    }

    .confirm-btn-ok {
        padding: 8px 16px;
        border-radius: 999px;
        border: none;
        background: var(--primary-green);
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
    }
    .confirm-btn-ok:hover {
        background: #223627;
    }

    /* Logout hidden form */
    #logoutForm {
        display: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .account-wrapper {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .account-hero {
            padding: 24px 1.5rem;
        }
        .account-wrapper {
            padding: 0 1.5rem;
        }
        .account-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="account-page">
    {{-- Banner --}}
    <div class="account-hero">
        <h1>My Account</h1>
        <div class="account-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span>My Account</span>
        </div>
    </div>

    <div class="account-wrapper">
        {{-- Sidebar --}}
        <aside class="account-sidebar">
            <a href="{{ route('profile.index') }}" class="account-nav-item">
                Personal Information
            </a>
            <a href="{{ route('profile.orders.index') }}" class="account-nav-item">
                My Orders
            </a>
            <a href="{{ route('profile.addresses.index') }}" class="account-nav-item active">
                Manage Address
            </a>
            <a href="{{ route('profile.payment.index') }}" class="account-nav-item">
                Payment Method
            </a>
            <a href="{{ route('profile.password.edit') }}" class="account-nav-item">
                Password Manager
            </a>
            <button id="logoutLink" class="account-nav-item logout">
                Logout
            </button>
        </aside>

        {{-- Content --}}
        <section class="account-content">
            <h2 class="account-section-title">Manage Address</h2>

            {{-- Existing addresses --}}
            <div style="margin-bottom:24px;">
                @forelse ($addresses as $address)
                    <div class="address-card">
                        <div class="address-card-header">
                            <div>
                                <div style="font-weight:600; color:var(--text-main); margin-bottom:4px;">
                                    {{ $address->full_name }}
                                    @if($address->is_default)
                                        <span style="font-size:0.7rem; padding:3px 8px; border-radius:999px; background:#dcfce7; color:#15803d; margin-left:8px;">
                                            Default
                                        </span>
                                    @endif
                                </div>
                                <div style="font-size:0.9rem; color:var(--text-muted);">
                                    {{ $address->street }}, {{ $address->city }}, {{ $address->state }} {{ $address->postcode }}, {{ $address->country }}
                                </div>
                                <div style="font-size:0.9rem; color:var(--text-muted); margin-top:4px;">
                                    Phone: {{ $address->phone }}
                                </div>
                            </div>

                            <div class="address-actions">
                                <button type="button"
                                        class="link-button link-edit"
                                        onclick="toggleEditAddress({{ $address->id }})">
                                    Edit
                                </button>

                                <form id="delete-address-{{ $address->id }}"
                                    method="POST"
                                    action="{{ route('profile.addresses.delete', $address) }}"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button type="button"
                                        class="link-button link-delete"
                                        onclick="openConfirmModal('Delete this address?', () => document.getElementById('delete-address-{{ $address->id }}').submit());">
                                    Delete
                                </button>
                            </div>
                        </div>

                        {{-- Inline EDIT form (hidden by default) --}}
                        <form id="editAddressForm-{{ $address->id }}"
                              method="POST"
                              action="{{ route('profile.addresses.update', $address) }}"
                              style="display:none; margin-top:16px; border-top:1px solid #f3f4f6; padding-top:12px;">
                            @csrf
                            @method('PUT')

                            <div class="account-form-grid">
                                <div class="account-form-group">
                                    <label>First Name <span class="required">*</span></label>
                                    <input type="text" name="first_name"
                                           class="account-form-control"
                                           value="{{ old('first_name', $address->first_name) }}" required>
                                </div>
                                <div class="account-form-group">
                                    <label>Last Name <span class="required">*</span></label>
                                    <input type="text" name="last_name"
                                           class="account-form-control"
                                           value="{{ old('last_name', $address->last_name) }}" required>
                                </div>
                                <div class="account-form-group">
                                    <label>Company Name (Optional)</label>
                                    <input type="text" name="company"
                                           class="account-form-control"
                                           value="{{ old('company', $address->company) }}">
                                </div>
                                <div class="account-form-group">
                                    <label>Country <span class="required">*</span></label>
                                    <select name="country" class="account-form-select" required>
                                        <option value="">Select Country</option>
                                        <option value="Malaysia" {{ old('country', $address->country) == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                    </select>
                                </div>
                                <div class="account-form-group">
                                    <label>Street Address <span class="required">*</span></label>
                                    <input type="text" name="street"
                                           class="account-form-control"
                                           value="{{ old('street', $address->street) }}" required>
                                </div>
                                <div class="account-form-group">
                                    <label>City <span class="required">*</span></label>
                                    <input type="text" name="city"
                                           class="account-form-control"
                                           value="{{ old('city', $address->city) }}" required>
                                </div>
                                <div class="account-form-group">
                                    <label>State <span class="required">*</span></label>
                                    <input type="text" name="state"
                                           class="account-form-control"
                                           value="{{ old('state', $address->state) }}" required>
                                </div>
                                <div class="account-form-group">
                                    <label>Zip Code <span class="required">*</span></label>
                                    <input type="text" name="postcode"
                                           class="account-form-control"
                                           value="{{ old('postcode', $address->postcode) }}" required>
                                </div>
                                <div class="account-form-group">
                                    <label>Phone <span class="required">*</span></label>
                                    <input type="text" name="phone"
                                           class="account-form-control"
                                           value="{{ old('phone', $address->phone) }}" required>
                                </div>
                                <div class="account-form-group">
                                    <label>Email <span class="required">*</span></label>
                                    <input type="email" name="email"
                                           class="account-form-control"
                                           value="{{ old('email', $address->email) }}" required>
                                </div>
                            </div>

                            <div style="margin-top:12px; display:flex; align-items:center; gap:8px;">
                                <input type="checkbox" id="is_default_{{ $address->id }}" name="is_default" value="1"
                                       style="width:16px;height:16px;"
                                       {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                <label for="is_default_{{ $address->id }}" style="font-size:0.9rem; color:var(--text-main);">
                                    Set as default address
                                </label>
                            </div>

                            <div class="account-actions">
                                <button type="submit" class="btn-account-primary">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <p style="font-size:0.95rem; color:var(--text-muted);">
                        You don’t have any addresses yet.
                    </p>
                @endforelse
            </div>

            {{-- ADD NEW ADDRESS (Hidden by default) --}}
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:8px; color:var(--text-main);">
                <a id="toggleAddAddress"
                   style="color:#2563eb; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
                    <span style="font-size:1.2rem;">+</span> Add New Address
                </a>
            </h3>

            <div id="addAddressWrapper" style="display:none; margin-top:10px;">

                <form method="POST" action="{{ route('profile.addresses.store') }}">
                    @csrf

                    <div class="account-form-grid">
                        <div class="account-form-group">
                            <label>First Name <span class="required">*</span></label>
                            <input type="text" name="first_name" class="account-form-control" required>
                        </div>
                        <div class="account-form-group">
                            <label>Last Name <span class="required">*</span></label>
                            <input type="text" name="last_name" class="account-form-control" required>
                        </div>
                        <div class="account-form-group">
                            <label>Company Name (Optional)</label>
                            <input type="text" name="company" class="account-form-control">
                        </div>
                        <div class="account-form-group">
                            <label>Country <span class="required">*</span></label>
                            <select name="country" class="account-form-select" required>
                                <option value="">Select Country</option>
                                <option value="Malaysia">Malaysia</option>
                            </select>
                        </div>
                        <div class="account-form-group">
                            <label>Street Address <span class="required">*</span></label>
                            <input type="text" name="street" class="account-form-control" required>
                        </div>
                        <div class="account-form-group">
                            <label>City <span class="required">*</span></label>
                            <input type="text" name="city" class="account-form-control" required>
                        </div>
                        <div class="account-form-group">
                            <label>State <span class="required">*</span></label>
                            <input type="text" name="state" class="account-form-control" required>
                        </div>
                        <div class="account-form-group">
                            <label>Zip Code <span class="required">*</span></label>
                            <input type="text" name="postcode" class="account-form-control" required>
                        </div>
                        <div class="account-form-group">
                            <label>Phone <span class="required">*</span></label>
                            <input type="text" name="phone" class="account-form-control" required>
                        </div>
                        <div class="account-form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" class="account-form-control" required>
                        </div>
                    </div>

                    <div style="margin-top:16px; display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" id="is_default" name="is_default" value="1"
                               style="width:16px;height:16px;">
                        <label for="is_default" style="font-size:0.9rem; color:var(--text-main);">
                            Set as default address
                        </label>
                    </div>

                    <div class="account-actions">
                        <button type="submit" class="btn-account-primary">
                            Add Address
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    {{-- Global confirmation modal --}}
    <div id="confirmOverlay" class="confirm-overlay">
        <div class="confirm-box">
            <div class="confirm-title">Are you sure?</div>
            <div id="confirmMessage" class="confirm-message">
                <!-- message goes here -->
            </div>
            <div class="confirm-actions">
                <button type="button"
                        class="confirm-btn-cancel"
                        onclick="closeConfirmModal()">
                    Cancel
                </button>
                <button type="button"
                        class="confirm-btn-ok"
                        id="confirmOkBtn">
                    Yes, continue
                </button>
            </div>
        </div>
    </div>

    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutLink  = document.getElementById('logoutLink');
        const logoutForm  = document.getElementById('logoutForm');
        const addBtn      = document.getElementById('toggleAddAddress');
        const addBox      = document.getElementById('addAddressWrapper');

        if (logoutLink && logoutForm) {
            logoutLink.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    logoutForm.submit();
                }
            });
        }

        if (addBtn && addBox) {
            addBtn.addEventListener('click', function () {
                const isHidden = (addBox.style.display === 'none' || addBox.style.display === '');
                addBox.style.display = isHidden ? 'block' : 'none';
            });
        }
    });

    // Toggle a single address edit form
    function toggleEditAddress(id) {
        const form = document.getElementById('editAddressForm-' + id);
        if (!form) return;
        const isHidden = (form.style.display === 'none' || form.style.display === '');
        form.style.display = isHidden ? 'block' : 'none';
    }

    // ===== Confirmation Modal Logic =====
    let confirmCallback = null;

    function openConfirmModal(message, callback) {
        const overlay  = document.getElementById('confirmOverlay');
        const msgEl    = document.getElementById('confirmMessage');
        const okButton = document.getElementById('confirmOkBtn');

        if (!overlay || !msgEl || !okButton) return;

        msgEl.textContent = message || 'Are you sure you want to continue?';
        confirmCallback = typeof callback === 'function' ? callback : null;

        overlay.style.display = 'flex';

        okButton.onclick = function () {
            overlay.style.display = 'none';
            if (confirmCallback) confirmCallback();
        };
    }

    function closeConfirmModal() {
        const overlay = document.getElementById('confirmOverlay');
        if (overlay) overlay.style.display = 'none';
        confirmCallback = null;
    }
</script>
@endpush
