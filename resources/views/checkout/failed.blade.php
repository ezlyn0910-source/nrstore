@extends('layouts.app')

@section('content')
<div class="checkout-result-page">
    <div class="checkout-result-card failed">
        <h1>Payment Failed</h1>
        <p>Unfortunately, your payment could not be completed.</p>

        @if(session('error'))
            <p style="color:#b91c1c; margin-top:10px;">{{ session('error') }}</p>
        @endif

        <div style="margin-top:20px;">
            <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="margin-right:10px;">
                Try Again
            </a>
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                Back to Cart
            </a>
        </div>
    </div>
</div>
@endsection