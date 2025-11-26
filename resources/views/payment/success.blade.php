@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    Payment Successful
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="card-title">Thank You for Your Purchase!</h3>
                    <p class="card-text">{{ $message ?? 'Your payment was processed successfully.' }}</p>
                    <p class="text-muted">You will receive an email confirmation shortly.</p>
                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">View Orders</a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection