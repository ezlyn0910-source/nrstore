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
            <a href="{{ route('profile.payment.index') }}" class="account-nav-item active">
                Payment Method
            </a>
            <a href="{{ route('profile.password.edit') }}" class="account-nav-item">
                Password Manager
            </a>
            <button id="logoutLink" class="account-nav-item logout">
                Logout
            </button>
        </aside>

        <section class="account-content">
            <h2 class="account-section-title">Payment Method</h2>

            {{-- Existing methods --}}
            <div style="margin-bottom:24px;">
                <p style="font-size:0.95rem; color:var(--text-muted);">
                    We use Stripe to process your payments securely. Your card details are
                    <strong>not stored</strong> on NR Store – they are encrypted and stored by Stripe.
                </p>
            </div>

            {{-- Add new card --}}
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:16px; color:var(--text-main);">
                Add New Credit/Debit Card
            </h3>

            <form method="POST" action="{{ route('profile.payment.cards.store') }}">
                @csrf

                <div class="account-form-grid">
                    <div class="account-form-group" style="grid-column:1 / -1;">
                        <label>Card Holder Name <span class="required">*</span></label>
                        <input type="text" name="holder_name" class="account-form-control" required>
                    </div>
                    <div class="account-form-group" style="grid-column:1 / -1;">
                        <label>Card Number <span class="required">*</span></label>
                        <input type="text" name="number" class="account-form-control" placeholder="XXXX XXXX XXXX XXXX" required>
                    </div>
                    <div class="account-form-group">
                        <label>Expiry Date <span class="required">*</span></label>
                        <input type="text" name="expiry" class="account-form-control" placeholder="MM/YY" required>
                    </div>
                    <div class="account-form-group">
                        <label>CVV <span class="required">*</span></label>
                        <input type="password" name="cvv" class="account-form-control" maxlength="4" required>
                    </div>
                </div>

                <div style="margin-top:16px; display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" id="save_card" name="save_card" value="1"
                           style="width:16px;height:16px;">
                    <label for="save_card" style="font-size:0.9rem; color:var(--text-main);">
                        Save card for future payments
                    </label>
                </div>

                <div class="account-actions">
                    <button type="submit" class="btn-account-primary">
                        Add Card
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
    });
</script>
@endsection
