@extends('layouts.app')

@section('content')
<div class="checkout-result-page">
    <div class="checkout-result-card success">
        <h1>Payment Successful</h1>
        <p>Thank you! Your payment has been received and your order has been placed successfully.</p>

        @isset($order)
            <p><strong>Order Number:</strong> {{ $order->order_number ?? ('#' . $order->id) }}</p>
        @endisset

        <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 20px;">
            Back to Home
        </a>
    </div>
</div>
@endsection