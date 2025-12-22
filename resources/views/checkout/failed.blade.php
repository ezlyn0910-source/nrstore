@extends('layouts.app')

@section('content')
<div class="payment-status-page">
    <div class="payment-status-container">
        <div class="status-icon failed">
            <i class="fas fa-times-circle"></i>
        </div>

        <h1>Payment Failed</h1>

        <p class="status-message">
            {{ session('error') ?? 'Unfortunately, your payment could not be completed.' }}
        </p>

        @if(session('info'))
            <p class="status-message">{{ session('info') }}</p>
        @endif

        <a href="{{ route('home') }}" class="btn-home">
            Go to Homepage
        </a>
    </div>
</div>
@endsection

@section('styles')
<style>
/* ===== PAYMENT STATUS PAGE ===== */
.payment-status-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    padding: 2rem;
    font-family: "Nunito", sans-serif;
}

.payment-status-container {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 3rem 3.5rem;
    max-width: 480px;
    width: 100%;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
}

/* Icon */
.status-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.status-icon.failed {
    background: #fee2e2;
    color: #b91c1c;
}

.status-icon i {
    font-size: 2.5rem;
}

/* Text */
.payment-status-container h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.75rem;
}

.status-message {
    font-size: 1rem;
    color: #374151;
    margin-bottom: 2rem;
}

/* Button */
.btn-home {
    display: inline-block;
    padding: 0.9rem 2.5rem;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    background: #1f2937;
    border-radius: 999px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-home:hover {
    background: #374151;
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
}
</style>
@endsection
