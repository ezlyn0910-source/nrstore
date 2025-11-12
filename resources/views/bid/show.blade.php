@extends('layouts.app')

@section('styles')
    @vite('resources/css/bid.css')
@endsection

@section('content')
<div class="bid-page">
    <!-- Hero Section -->
    <section class="bid-hero-section">
        <div class="bid-hero-title">
            <h1>Bid</h1>
        </div>
    </section>

    <!-- Main Content -->
    <section class="bid-main-content">
        <div class="container">
            <div class="bid-content-box">
                <!-- Bid Detail Section -->
                <div class="bid-detail-section">
                    <!-- Product Image -->
                    <div class="bid-detail-image">
                        <img src="{{ asset('storage/' . $bid->image) }}" alt="{{ $bid->product_name }}">
                    </div>

                    <!-- Product Info -->
                    <div class="bid-detail-info">
                        <h2 class="bid-detail-title">{{ $bid->product_name }}</h2>
                        <p class="bid-detail-description">{{ $bid->description }}</p>
                        
                        <!-- Specifications -->
                        <div class="bid-detail-specs">
                            <h4 style="margin-bottom: 1rem; color: var(--dark-text);">Specifications</h4>
                            @foreach($bid->specifications as $key => $value)
                            <div class="bid-spec-item">
                                <span class="bid-spec-label">{{ $key }}</span>
                                <span class="bid-spec-value">{{ $value }}</span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Condition -->
                        <div style="background: var(--light-bone); padding: 1rem; border-radius: 0.5rem; margin-top: 2rem;">
                            <strong>Condition:</strong> {{ $bid->condition }}
                        </div>
                    </div>
                </div>

                <!-- Bid Action Section -->
                <div class="bid-action-section">
                    <div class="bid-current-info">
                        <div class="bid-info-card">
                            <div class="bid-info-label">Current Bid</div>
                            <div class="bid-info-value highlight">RM{{ number_format($bid->current_bid, 2) }}</div>
                        </div>
                        <div class="bid-info-card">
                            <div class="bid-info-label">Starting Bid</div>
                            <div class="bid-info-value">RM{{ number_format($bid->starting_bid, 2) }}</div>
                        </div>
                        <div class="bid-info-card">
                            <div class="bid-info-label">Time Left</div>
                            <div class="bid-info-value">{{ $bid->time_left }}</div>
                        </div>
                    </div>

                    <!-- Bid Form -->
                    <form class="bid-form" id="bidForm">
                        @csrf
                        <div class="bid-input-group">
                            <label class="bid-input-label" for="bidAmount">Your Bid Amount</label>
                            <input 
                                type="number" 
                                id="bidAmount" 
                                name="amount"
                                class="bid-input"
                                min="{{ $bid->current_bid + $bid->bid_increment }}"
                                step="{{ $bid->bid_increment }}"
                                placeholder="Enter your bid..."
                                required
                            >
                            <small style="color: var(--light-text); margin-top: 0.5rem; display: block;">
                                Minimum bid: RM{{ number_format($bid->current_bid + $bid->bid_increment, 2) }}
                            </small>
                        </div>
                        <button type="submit" class="bid-btn">
                            <i class="fas fa-gavel"></i>
                            Place Bid
                        </button>
                    </form>
                </div>

                <!-- Bid History -->
                <div class="bid-history-section">
                    <h3 class="bid-history-title">Bid History</h3>
                    <div class="bid-history-list">
                        @foreach($bid->bid_history as $history)
                        <div class="bid-history-item">
                            <span class="bid-history-bidder">{{ $history->bidder }}</span>
                            <span class="bid-history-amount">RM{{ number_format($history->amount, 2) }}</span>
                            <span class="bid-history-time">{{ $history->time }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidForm = document.getElementById('bidForm');
    
    bidForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const bidAmount = document.getElementById('bidAmount').value;
        
        // Simple validation
        if (!bidAmount || bidAmount < {{ $bid->current_bid + $bid->bid_increment }}) {
            alert('Please enter a valid bid amount. Minimum bid is RM{{ number_format($bid->current_bid + $bid->bid_increment, 2) }}');
            return;
        }
        
        // Simulate bid placement
        fetch('{{ route("bid.place", $bid->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Bid placed successfully!');
                // In a real app, you'd update the UI with the new bid
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error placing bid. Please try again.');
        });
    });
});
</script>
@endsection