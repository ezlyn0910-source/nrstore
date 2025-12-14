@extends('layouts.app')

@section('styles')
<style>
/* Add styles for bid detail page */
.bid-main-content {
    background-color: #f8f9fa;
    padding: 3rem 0;
}

.bid-content-box {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
}

.bid-detail-section {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e9ecef;
}

@media (max-width: 768px) {
    .bid-detail-section {
        grid-template-columns: 1fr;
    }
}

.bid-detail-image {
    border-radius: 1rem;
    overflow: hidden;
    background: #f8f9fa;
    height: 300px;
}

.bid-detail-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.bid-detail-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.bid-detail-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a2412;
    margin-bottom: 0.5rem;
}

.bid-detail-description {
    color: #6b7c72;
    line-height: 1.6;
}

.bid-detail-specs {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1rem;
}

.bid-spec-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.bid-spec-label {
    font-weight: 600;
    color: #1a2412;
}

.bid-spec-value {
    color: #6b7c72;
}

.bid-action-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e9ecef;
}

.bid-current-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.bid-info-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.75rem;
    text-align: center;
}

.bid-info-label {
    font-size: 0.875rem;
    color: #6b7c72;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.bid-info-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a2412;
}

.bid-info-value.highlight {
    color: #2d4a35;
}

.bid-form {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 1rem;
    max-width: 500px;
    margin: 0 auto;
}

.bid-input-group {
    margin-bottom: 1.5rem;
}

.bid-input-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #1a2412;
}

.bid-input {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.bid-input:focus {
    outline: none;
    border-color: #2d4a35;
}

.bid-btn {
    width: 100%;
    padding: 1rem;
    background: #2d4a35;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.bid-btn:hover {
    background: #253c2a;
    transform: translateY(-2px);
}

.bid-history-section {
    margin-top: 2rem;
}

.bid-history-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: #1a2412;
}

.bid-history-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-height: 400px;
    overflow-y: auto;
}

.bid-history-item {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    align-items: center;
}

.bid-history-bidder {
    font-weight: 600;
    color: #1a2412;
}

.bid-history-amount {
    font-weight: 700;
    color: #2d4a35;
}

.bid-history-time {
    color: #6b7c72;
    font-size: 0.875rem;
}

@media (max-width: 640px) {
    .bid-history-item {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 0.5rem;
    }
    
    .bid-history-bidder,
    .bid-history-amount,
    .bid-history-time {
        width: 100%;
    }
}
</style>
@endsection

@section('content')
<div class="bid-page">
    <!-- Hero Section -->
    <section class="bid-hero-section">
        <div class="bid-hero-title">
            <h1>Auction Details</h1>
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
                        @if($bid->product)
                            <img src="{{ $bid->product->main_image_url }}" alt="{{ $bid->product->name }}">
                        @else
                            <img src="{{ asset('images/default-product.png') }}" alt="Product Image">
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="bid-detail-info">
                        <h2 class="bid-detail-title">
                            {{ $bid->product->name ?? 'Unknown Product' }}
                        </h2>
                        
                        @if($bid->product && $bid->product->description)
                            <p class="bid-detail-description">{{ $bid->product->description }}</p>
                        @endif
                        
                        <!-- Specifications -->
                        <div class="bid-detail-specs">
                            <h4 style="margin-bottom: 1rem; color: #1a2412;">Specifications</h4>
                            
                            @if($bid->product)
                                @if($bid->variation && !empty($bid->variation->specifications))
                                    @foreach($bid->variation->specifications as $key => $value)
                                        @if(!empty($value))
                                            <div class="bid-spec-item">
                                                <span class="bid-spec-label">{{ $key }}:</span>
                                                <span class="bid-spec-value">{{ $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @elseif($bid->product)
                                    <!-- Display product specifications -->
                                    @php
                                        $specs = [];
                                        if($bid->product->processor) $specs['Processor'] = $bid->product->processor;
                                        if($bid->product->ram) $specs['RAM'] = $bid->product->ram;
                                        if($bid->product->storage) $specs['Storage'] = $bid->product->storage;
                                        if($bid->product->graphics_card) $specs['Graphics Card'] = $bid->product->graphics_card;
                                        if($bid->product->screen_size) $specs['Screen Size'] = $bid->product->screen_size;
                                        if($bid->product->os) $specs['Operating System'] = $bid->product->os;
                                        if($bid->product->warranty) $specs['Warranty'] = $bid->product->warranty;
                                    @endphp
                                    
                                    @foreach($specs as $key => $value)
                                        @if(!empty($value))
                                            <div class="bid-spec-item">
                                                <span class="bid-spec-label">{{ $key }}:</span>
                                                <span class="bid-spec-value">{{ $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        </div>

                        <!-- Product Type/Variation -->
                        @if($bid->variation)
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.5rem;">
                                <strong>Variation:</strong> {{ $bid->variation->model ?? $bid->variation->sku }}
                            </div>
                        @endif

                        <!-- Brand -->
                        @if($bid->product && $bid->product->brand)
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.5rem;">
                                <strong>Brand:</strong> {{ ucfirst($bid->product->brand) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bid Action Section -->
                <div class="bid-action-section">
                    <div class="bid-current-info">
                        <div class="bid-info-card">
                            <div class="bid-info-label">Current Bid</div>
                            <div class="bid-info-value highlight">
                                RM {{ number_format($bid->current_price, 2) }}
                            </div>
                        </div>
                        <div class="bid-info-card">
                            <div class="bid-info-label">Starting Bid</div>
                            <div class="bid-info-value">
                                RM {{ number_format($bid->starting_price, 2) }}
                            </div>
                        </div>
                        <div class="bid-info-card">
                            <div class="bid-info-label">Time Left</div>
                            <div class="bid-info-value">
                                @if($bid->is_active)
                                    @php
                                        $timeRemaining = $bid->time_remaining;
                                        if($timeRemaining) {
                                            if($timeRemaining->d > 0) {
                                                echo $timeRemaining->d . 'd ' . $timeRemaining->h . 'h';
                                            } elseif($timeRemaining->h > 0) {
                                                echo $timeRemaining->h . 'h ' . $timeRemaining->i . 'm';
                                            } else {
                                                echo $timeRemaining->i . 'm';
                                            }
                                        } else {
                                            echo 'Ended';
                                        }
                                    @endphp
                                @else
                                    {{ ucfirst($bid->status) }}
                                @endif
                            </div>
                        </div>
                        @if($bid->reserve_price)
                            <div class="bid-info-card">
                                <div class="bid-info-label">Reserve Price</div>
                                <div class="bid-info-value">
                                    RM {{ number_format($bid->reserve_price, 2) }}
                                    @if($bid->reserve_met)
                                        <span style="color: #2d4a35; font-size: 0.75rem; display: block;">(Met ✓)</span>
                                    @else
                                        <span style="color: #dc2626; font-size: 0.75rem; display: block;">(Not Met)</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Bid Form -->
                    @auth
                        @if($bid->is_active)
                            <form class="bid-form" id="bidForm" method="POST" action="{{ route('bid.place', $bid->id) }}">
                                @csrf
                                <div class="bid-input-group">
                                    <label class="bid-input-label" for="bidAmount">Your Bid Amount</label>
                                    <input 
                                        type="number" 
                                        id="bidAmount" 
                                        name="amount"
                                        class="bid-input"
                                        min="{{ number_format($bid->current_price + $bid->bid_increment, 2, '.', '') }}"
                                        step="{{ number_format($bid->bid_increment, 2, '.', '') }}"
                                        placeholder="Enter your bid..."
                                        required
                                    >
                                    <small style="color: #6b7c72; margin-top: 0.5rem; display: block;">
                                        Minimum bid: RM{{ number_format($bid->current_price + $bid->bid_increment, 2) }}
                                    </small>
                                </div>
                                <button type="submit" class="bid-btn">
                                    <i class="fas fa-gavel"></i>
                                    Place Bid
                                </button>
                            </form>
                        @else
                            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 1rem;">
                                <h3 style="color: #dc2626; margin-bottom: 1rem;">
                                    @if($bid->has_ended)
                                        Auction Has Ended
                                    @elseif($bid->status == 'upcoming')
                                        Auction Starts Soon
                                    @else
                                        Auction is {{ ucfirst($bid->status) }}
                                    @endif
                                </h3>
                                @if($bid->winner && $bid->status == 'completed')
                                    <p>Winner: {{ $bid->winner->name ?? 'Unknown' }}</p>
                                    <p>Winning Bid: RM{{ number_format($bid->winning_bid_amount ?? $bid->current_price, 2) }}</p>
                                @endif
                            </div>
                        @endif
                    @else
                        <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 1rem;">
                            <h3 style="color: #2d4a35; margin-bottom: 1rem;">Login to Place a Bid</h3>
                            <a href="{{ route('login') }}" class="bid-btn" style="width: auto; display: inline-flex;">
                                <i class="fas fa-sign-in-alt"></i>
                                Login to Bid
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Bid History -->
                <div class="bid-history-section">
                    <h3 class="bid-history-title">Bid History</h3>
                    <div class="bid-history-list">
                        @forelse($bid->bids ?? [] as $bidHistory)
                            <div class="bid-history-item">
                                <span class="bid-history-bidder">
                                    {{ $bidHistory->user->name ?? 'Anonymous' }}
                                    @if($bidHistory->is_auto_bid)
                                        <span style="color: #2d4a35; font-size: 0.75rem;">(Auto)</span>
                                    @endif
                                </span>
                                <span class="bid-history-amount">
                                    RM{{ number_format($bidHistory->amount, 2) }}
                                </span>
                                <span class="bid-history-time">
                                    {{ $bidHistory->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                        @empty
                            <p style="text-align: center; color: #6b7c72; padding: 2rem;">No bids placed yet.</p>
                        @endforelse
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
    
    if (bidForm) {
        bidForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const bidAmount = document.getElementById('bidAmount').value;
            const minBid = {{ number_format($bid->current_price + $bid->bid_increment, 2, '.', '') }};
            
            // Simple validation
            if (!bidAmount || parseFloat(bidAmount) < minBid) {
                alert('Please enter a valid bid amount. Minimum bid is RM' + minBid.toFixed(2));
                return;
            }
            
            // Submit the form
            fetch(this.action, {
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
                    location.reload();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error placing bid. Please try again.');
            });
        });
    }
});
</script>
@endsection