@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    Payment Failed
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="card-title">Payment Unsuccessful</h3>
                    <p class="card-text">{{ $error ?? 'Your payment could not be processed.' }}</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('cart.index') }}" class="btn btn-primary">Return to Cart</a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Continue Shopping</a>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-muted">
                            Need help? <a href="{{ route('contact') }}">Contact our support team</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection