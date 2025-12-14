@extends('layouts.app')

@section('content')
<div class="checkout-result-page">
    <div class="checkout-result-card success">

        <!-- Success Icon -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1>Payment Successful</h1>

        <p class="success-message">
            Your payment has been successfully made.
            <br>
            Go to the order page to view your order details.
        </p>

        @isset($order)
            <p class="order-number">
                <strong>Order Number:</strong>
                {{ $order->order_number ?? ('#' . $order->id) }}
            </p>
        @endisset

        <a href="{{ route('orders.show', $order->id ?? null) }}" class="btn-go-order">
            Go to Order
        </a>

    </div>
</div>
@endsection

@section('styles')
<style>
.checkout-result-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    padding: 2rem;
}

.checkout-result-card {
    background: white;
    border-radius: 14px;
    padding: 3rem 2.5rem;
    max-width: 480px;
    width: 100%;
    text-align: center;
    border: 1px solid #e5e7eb;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
}

.success-icon {
    width: 90px;
    height: 90px;
    background: #ecfdf5;
    color: #10b981;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.8rem;
    margin: 0 auto 1.5rem;
}

.checkout-result-card h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.75rem;
}

.success-message {
    font-size: 1rem;
    color: #6b7280;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.order-number {
    font-size: 0.95rem;
    color: #374151;
    margin-bottom: 2rem;
}

.btn-go-order {
    display: inline-block;
    padding: 0.9rem 2.2rem;
    background: #1f2937;
    color: white;
    border-radius: 999px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-go-order:hover {
    background: #374151;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}
</style>
@endsection