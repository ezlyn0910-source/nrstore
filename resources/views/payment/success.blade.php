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
                    
                    @if(isset($order))
                        <div class="alert alert-info">
                            <strong>Order #:</strong> {{ $order->order_number }}<br>
                            <strong>Total Amount:</strong> RM{{ number_format($order->total_amount, 2) }}<br>
                            <strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>
                    @endif
                    
                    <p class="card-text">{{ $message ?? 'Your payment was processed successfully.' }}</p>
                    <p class="text-muted">You will receive an email confirmation shortly.</p>
                    
                    <div class="mt-4">
                        @auth
                            <a href="{{ route('orders.index') }}" class="btn btn-primary">View Orders</a>
                            <a href="{{ route('home') }}" class="btn btn-secondary">Continue Shopping</a>
                        @else
                            <p class="text-muted mb-3">
                                <a href="{{ route('login') }}">Log in</a> to view all your orders
                            </p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
                        @endauth
                    </div>
                    
                    @if(!auth()->check() && isset($order))
                        <div class="mt-3 p-3 border rounded bg-light">
                            <small class="text-muted">
                                <strong>Note:</strong> Save your order number (#{{ $order->order_number }}) 
                                for future reference.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection