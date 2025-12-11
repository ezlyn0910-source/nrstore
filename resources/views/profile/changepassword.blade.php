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
        background: #f7f5f2;
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
        background: var(--primary-green);
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

    /* Password fields */
    .password-row {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        cursor: pointer;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .password-toggle.visible {
        color: var(--primary-green);
    }

    .forgot-link {
        font-size:0.85rem;
        color:var(--primary-green);
        text-decoration:none;
        margin-left:auto;
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
            <a href="{{ route('profile.addresses.index') }}" class="account-nav-item">
                Manage Address
            </a>
            <a href="{{ route('profile.payment.index') }}" class="account-nav-item">
                Payment Method
            </a>
            <a href="{{ route('profile.password.edit') }}" class="account-nav-item active">
                Change Password
            </a>

            {{-- Logout button using same confirmation modal style --}}
            <button type="button"
                    id="logoutLink"
                    class="account-nav-item logout">
                Logout
            </button>
        </aside>

        {{-- Content --}}
        <section class="account-content">
            <h2 class="account-section-title">Change Password</h2>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')

                <div class="account-form-grid" style="grid-template-columns:1fr;">
                    <div class="account-form-group password-row">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label>Password <span class="required">*</span></label>
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                        </div>
                        <input type="password" id="current_password" name="current_password"
                               class="account-form-control" required>
                        <button type="button"
                                class="password-toggle"
                                data-target="current_password">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div class="account-form-group password-row">
                        <label>New Password <span class="required">*</span></label>
                        <input type="password" id="new_password" name="password"
                               class="account-form-control" required>
                        <button type="button"
                                class="password-toggle"
                                data-target="new_password">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div class="account-form-group password-row">
                        <label>Confirm New Password <span class="required">*</span></label>
                        <input type="password" id="new_password_confirmation" name="password_confirmation"
                               class="account-form-control" required>
                        <button type="button"
                                class="password-toggle"
                                data-target="new_password_confirmation">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="account-actions">
                    <button type="submit" class="btn-account-primary">
                        Update Password
                    </button>
                </div>
            </form>
        </section>
    </div>

    {{-- Hidden logout form --}}
    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>

    {{-- Global confirmation modal (same as address page) --}}
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
        const logoutLink = document.getElementById('logoutLink');
        const logoutForm = document.getElementById('logoutForm');

        // Logout with confirmation modal (same behaviour as address page)
        if (logoutLink && logoutForm) {
            logoutLink.addEventListener('click', function () {
                openConfirmModal('Are you sure you want to logout?', function () {
                    logoutForm.submit();
                });
            });
        }

        // Show / hide passwords
        document.querySelectorAll('.password-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.add('visible');
                } else {
                    input.type = 'password';
                    this.classList.remove('visible');
                }
            });
        });
    });

    // ===== Confirmation Modal Logic (same as editaddress) =====
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
