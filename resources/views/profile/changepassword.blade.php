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
        background: var(--accent-gold);
        color: #111827;
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

    /* Logout hidden form */
    #logoutForm {
        display: none;
    }

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
            <button id="logoutLink" class="account-nav-item logout">
                Logout
            </button>
        </aside>

        <section class="account-content">
            <h2 class="account-section-title">Change Password</h2>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')

                <div class="account-form-grid" style="grid-template-columns:1fr;">
                    <div class="account-form-group password-row">
                        <div style="display:flex; align-items:center;">
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

    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutLink = document.getElementById('logoutLink');
        const logoutForm = document.getElementById('logoutForm');

        if (logoutLink && logoutForm) {
            logoutLink.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    logoutForm.submit();
                }
            });
        }

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
</script>
@endsection
