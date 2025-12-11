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
        background: #dce9df !important;
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
        {{-- Sidebar --}}
        <aside class="account-sidebar">
            <a href="{{ route('profile.personal.edit') }}"
               class="account-nav-item">
                Personal Information
            </a>
            <a href="{{ route('profile.orders.index') }}"
               class="account-nav-item active">
                My Orders
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
                Password Manager
            </a>
            <button id="logoutLink"
                    class="account-nav-item logout">
                Logout
            </button>
        </aside>

        {{-- Content --}}
        <section class="account-content">
            <h2 class="account-section-title">My Orders</h2>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <p style="margin:0; font-size:0.95rem; color:var(--text-muted);">
                    Orders ({{ $orders->count() }})
                </p>
                <div style="display:flex; align-items:center; gap:8px; font-size:0.9rem;">
                    <span style="color:var(--text-muted);">Sort by :</span>
                    <select class="account-form-select" style="width:160px;">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="price-low">Price: Low to High</option>
                    </select>
                </div>
            </div>

            @forelse ($orders as $order)
                {{-- Past orders only (make sure controller filters) --}}
                <div style="border-radius:8px; overflow:hidden; border:1px solid var(--border-light); margin-bottom:18px;">
                    {{-- Order summary header --}}
                    <div style="background:var(--primary-green); padding:12px 16px; display:grid; grid-template-columns:2fr 1.2fr 1.2fr 1.5fr; gap:12px; font-size:0.9rem; font-weight:600;">
                        <div>
                            <div style="font-size:0.8rem; color:#E5E4E2;">Order ID</div>
                            <div>#{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; color:#E5E4E2;">Total Payment</div>
                            <div>RM {{ number_format($order->total_amount, 2) }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; color:#E5E4E2;">Payment Method</div>
                            <div>{{ $order->payment_method_label ?? ucfirst($order->payment_method) }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; color:#E5E4E2;">
                                {{ $order->status === 'shipped' || $order->status === 'delivered' ? 'Delivered / Shipped Date' : 'Updated At' }}
                            </div>
                            <div>{{ $order->shipped_at?->format('d F Y') ?? $order->updated_at->format('d F Y') }}</div>
                        </div>
                    </div>

                    {{-- Order items --}}
                    <div style="padding:16px;">
                        @foreach ($order->items as $item)
                            <div style="display:grid; grid-template-columns:80px 1fr; gap:16px; padding:10px 0; border-bottom:1px solid #f3f4f6;">
                                <div>
                                    <img src="{{ $item->product->thumbnail_url ?? asset('images/default-product.jpg') }}"
                                         alt="{{ $item->product_name }}"
                                         style="width:80px; height:80px; object-fit:cover; border-radius:4px;">
                                </div>
                                <div style="display:flex; flex-direction:column; justify-content:center;">
                                    <div style="font-weight:600; margin-bottom:4px; color:var(--text-main);">
                                        {{ $item->product_name }}
                                    </div>
                                    <div style="font-size:0.85rem; color:var(--text-muted);">
                                        {{ $item->variation_summary ?? '' }}
                                        @if($item->variation_summary)
                                            |
                                        @endif
                                        Qty: {{ $item->quantity }}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Status row --}}
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-top:12px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                @if($order->status === 'shipped' || $order->status === 'delivered')
                                    <span style="font-size:0.8rem; padding:4px 10px; border-radius:999px; background:#dcfce7; color:#15803d; border:1px solid #22c55e;">{{ ucfirst($order->status) }}</span>
                                @elseif($order->status === 'cancelled')
                                    <span style="font-size:0.8rem; padding:4px 10px; border-radius:999px; background:#fee2e2; color:#b91c1c; border:1px solid #f87171;">Cancelled</span>
                                @else
                                    <span style="font-size:0.8rem; padding:4px 10px; border-radius:999px; background:#e5e7eb; color:#374151; border:1px solid #d1d5db;">{{ ucfirst($order->status) }}</span>
                                @endif

                                <span style="font-size:0.9rem; color:var(--text-muted);">
                                    {{ $order->status_label ?? ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p style="font-size:0.95rem; color:var(--text-muted);">
                    You don't have any past orders yet.
                </p>
            @endforelse
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
