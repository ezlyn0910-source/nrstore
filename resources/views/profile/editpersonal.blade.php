@extends('layouts.app')

@section('styles')
<style>
    :root {
        --primary-green: #2d4a35;
        --accent-gold: #f4b740;
        --accent-brown: #4b2a16;
        --border-light: #C1E1C1;
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
        background: #f7f5f2;
        padding: 40px 3rem;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .account-hero h1 {
        margin: 0;
        font-size: 2.4rem;
        font-weight: 700;
        color: var(--primary-green);
    }

    .account-breadcrumb {
        margin-top: 8px;
        font-size: 0.9rem;
        color: var(--primary-green);
    }

    .account-breadcrumb a {
        color: var(--primary-green);
        text-decoration: none;
    }

    .account-breadcrumb span {
        margin: 0 4px;
    }

    .account-wrapper {
        max-width: 1200px;
        margin: 30px auto 60px;
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
        background: var(--primary-green) !important;
        color: #ffffff !important;
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

    /* Personal info layout */
    .profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 96px;
        height: 96px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--accent-gold);
        flex-shrink: 0;
    }

    .profile-avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-upload-btn {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: var(--accent-brown);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    }

    .profile-name-email h2 {
        margin: 0 0 4px;
        font-size: 1.2rem;
        color: var(--text-main);
    }

    .profile-name-email p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.9rem;
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
        background: var(--primary-green) !important;
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
        background: #1f3627 !important;
        transform: translateY(-1px);
    }

    /* Logout hidden form */
    #logoutForm {
        display: none;
    }

    /* ===== Confirmation Modal (same style as address page) ===== */
    .confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;
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

    .confirm-message {
        font-size: 1rem;
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

    {{-- Hero --}}
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
            <a href="{{ route('profile.personal.edit') }}" class="account-nav-item active">
                Personal Information
            </a>
            <a href="{{ route('profile.orders.index') }}" class="account-nav-item">
                My Orders
            </a>
            <a href="{{ route('profile.addresses.index') }}" class="account-nav-item">
                Manage Address
            </a>
            <a href="{{ route('profile.payment.index') }}" class="account-nav-item">
                Payment Method
            </a>
            <a href="{{ route('profile.password.edit') }}" class="account-nav-item">
                Change Password
            </a>

            {{-- Logout button (uses confirmation modal) --}}
            <button type="button"
                    id="logoutLink"
                    class="account-nav-item logout">
                Logout
            </button>
        </aside>

        {{-- Content --}}
        <section class="account-content">
            <h2 class="account-section-title">Personal Information</h2>

            <form method="POST"
                  action="{{ route('profile.personal.update') }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="account-form-grid">
                    <div class="account-form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" name="first_name"
                               class="account-form-control"
                               value="{{ old('first_name', $user->first_name) }}" required>
                    </div>

                    <div class="account-form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" name="last_name"
                               class="account-form-control"
                               value="{{ old('last_name', $user->last_name) }}" required>
                    </div>

                    <div class="account-form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email"
                               class="account-form-control"
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="account-form-group">
                        <label>Phone <span class="required">*</span></label>
                        <input type="text" name="phone"
                               class="account-form-control"
                               value="{{ old('phone', $user->phone) }}" required>
                    </div>

                    <div class="account-form-group">
                        <label>Gender</label>
                        <select name="gender" class="account-form-select">
                            <option value="">Select</option>
                            <option value="male"   {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other"  {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="account-actions">
                    <button type="submit" class="btn-account-primary">
                        Update Changes
                    </button>
                </div>

            </form>
        </section>
    </div>

    {{-- Hidden logout form --}}
    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>

    {{-- Global confirmation modal (same as other account pages) --}}
    <div id="confirmOverlay" class="confirm-overlay">
        <div class="confirm-box">
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

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutLink  = document.getElementById('logoutLink');
        const logoutForm  = document.getElementById('logoutForm');

        // Logout with confirmation modal
        if (logoutLink && logoutForm) {
            logoutLink.addEventListener('click', function () {
                openConfirmModal('Are you sure you want to logout?', function () {
                    logoutForm.submit();
                });
            });
        }
    });

    // ===== Confirmation Modal Logic (reused) =====
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
