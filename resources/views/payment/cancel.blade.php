@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    Payment Cancelled
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-times-circle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="card-title">Payment Cancelled</h3>
                    <p class="card-text">{{ $message ?? 'Your payment was cancelled.' }}</p>
                    <p class="text-muted">No charges were made to your account.</p>
                    <div class="mt-4">
                        <a href="{{ route('cart.index') }}" class="btn btn-primary">Return to Cart</a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection