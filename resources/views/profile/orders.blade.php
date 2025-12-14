@extends('layouts.app')

@section('styles')
<style>
    :root {
        --primary-green: #2d4a35;
        --accent-gold: #f4b740;
        --accent-brown: #4b2a16;
        --border-light: #dce9df;
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

    /* Logout hidden form */
    #logoutForm {
        display: none;
    }

    /* ===== Confirmation Modal (same style as other pages) ===== */
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
        font-size: 0.95rem;
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
            <a href="{{ route('profile.personal.edit') }}"
               class="account-nav-item">
                Personal Information
            </a>
            <a href="{{ route('profile.addresses.index') }}"
               class="account-nav-item">
                Manage Address
            </a>
            <a href="{{ route('profile.payment.index') }}"
               class="account-nav-item">
                Payment Method
            </a>
            <a href="{{ route('profile.password.edit') }}"
               class="account-nav-item">
                Change Password
            </a>

            {{-- Logout button using custom confirmation --}}
            <button type="button"
                    id="logoutLink"
                    class="account-nav-item logout"
                    style="width:100%; text-align:left;">
                Logout
            </button>
        </aside>

        {{-- CONTENT SECTION (unchanged layout) --}}
        <section class="account-content">
            <h2 class="account-section-title">My Orders</h2>

            @forelse($orders as $order)
                <div style="border-radius:8px; overflow:hidden; border:1px solid var(--border-light); margin-bottom:18px;">

                    <div style="background:var(--primary-green);
                                padding:12px 18px;
                                display:grid;
                                grid-template-columns:2fr 1fr 1fr 1.4fr;
                                gap:16px;
                                font-size:0.9rem;
                                font-weight:600;
                                color:#ffffff;">
                        <div>
                            <div style="font-size:0.8rem; opacity:0.85;">Order ID</div>
                            <div>#{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; opacity:0.85;">Total Payment</div>
                            <div>RM {{ number_format($order->total_amount, 2) }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; opacity:0.85;">Payment Method</div>
                            <div>{{ $order->payment_method_label ?? ucfirst($order->payment_method) }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; opacity:0.85;">
                                {{ in_array($order->status, ['delivered','shipped']) ? 'Delivered Date' : 'Updated At' }}
                            </div>
                            <div>
                                {{ $order->delivered_at?->format('d F Y') ?? $order->updated_at->format('d F Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div style="padding:16px 18px 18px;">
                        @foreach($order->items as $item)
                            <div style="display:grid;
                                        grid-template-columns:70px 1fr;
                                        gap:14px;
                                        padding:10px 0;
                                        border-bottom:1px solid #f3f4f6;">
                                <div>
                                    <img src="{{ $item->product->thumbnail_url ?? asset('images/default-product.jpg') }}"
                                         alt="{{ $item->product_name }}"
                                         style="width:70px; height:70px; object-fit:cover; border-radius:4px;">
                                </div>
                                <div style="display:flex; flex-direction:column; justify-content:center;">
                                    <div style="font-weight:600; margin-bottom:4px; color:var(--text-main);">
                                        {{ $item->product_name }}
                                    </div>
                                    <div style="font-size:0.85rem; color:var(--text-muted);">
                                        {{ $item->variation_summary ?? '' }}
                                        @if($item->variation_summary)
                                            &nbsp;|&nbsp;
                                        @endif
                                        Qty: {{ $item->quantity }}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Status + message row --}}
                        <div style="display:flex; align-items:center; gap:12px; padding-top:12px; margin-bottom:16px;">
                            @php
                                $status = $order->status;
                            @endphp

                            @if($status === 'delivered')
                                <span style="font-size:0.8rem;
                                             padding:4px 12px;
                                             border-radius:999px;
                                             background:#dcfce7;
                                             color:#15803d;
                                             border:1px solid #22c55e;">
                                    Delivered
                                </span>
                                <span style="font-size:0.9rem; color:var(--text-main);">
                                    Your order has been delivered
                                </span>
                            @elseif($status === 'shipped')
                                <span style="font-size:0.8rem;
                                             padding:4px 12px;
                                             border-radius:999px;
                                             background:#dbeafe;
                                             color:#1d4ed8;
                                             border:1px solid #3b82f6;">
                                    Shipped
                                </span>
                                <span style="font-size:0.9rem; color:var(--text-main);">
                                    Your order is on the way
                                </span>
                            @elseif($status === 'cancelled')
                                <span style="font-size:0.8rem;
                                             padding:4px 12px;
                                             border-radius:999px;
                                             background:#fee2e2;
                                             color:#b91c1c;
                                             border:1px solid #f87171;">
                                    Cancelled
                                </span>
                                <span style="font-size:0.9rem; color:var(--text-main);">
                                    Your order has been cancelled
                                </span>
                            @else
                                <span style="font-size:0.8rem;
                                             padding:4px 12px;
                                             border-radius:999px;
                                             background:#e5e7eb;
                                             color:#374151;
                                             border:1px solid #d1d5db;">
                                    {{ ucfirst($status) }}
                                </span>
                                <span style="font-size:0.9rem; color:var(--text-main);">
                                    Your order is being processed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p style="font-size:0.95rem; color:var(--text-muted); margin-top:8px;">
                    You don't have any past orders yet.
                </p>
            @endforelse
        </section>
    </div>

    {{-- Hidden logout form --}}
    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>

    {{-- Global confirmation modal --}}
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

        if (logoutLink && logoutForm) {
            logoutLink.addEventListener('click', function (e) {
                e.preventDefault();
                openConfirmModal('Are you sure you want to logout?', function () {
                    logoutForm.submit();
                });
            });
        }
    });

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
