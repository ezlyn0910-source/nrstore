@extends('layouts.app')

@section('styles')
    @vite(['resources/css/home.css', 'resources/css/bid.css'])
@endsection

@section('content')
<div class="bid-page">
    <!-- Hero Section -->
    <section class="brand-hero-section">
        <div class="brand-hero-banner" style="background: linear-gradient(135deg, #316534 0%, #2d4a35 50%, #1a2412 100%);"></div>
        <div class="brand-hero-overlay">
            <h1 class="brand-hero-title">Auction Categories</h1>
            <p class="brand-hero-subtitle">
                Discover amazing {{ $brandData['name'] ?? 'Brand' }} products through competitive bidding
            </p>
            <div class="brand-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">></span>
                <a href="{{ route('bid.index') }}">Bid</a>
                <span class="separator">></span>
                <a href="{{ route('bid.index') }}#brands">Categories</a>
                <span class="separator">></span>
                <span>{{ $brandData['name'] ?? 'Brand' }}</span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="brand-main-content">
        <div class="container">
            <div class="brand-auctions-layout">
                <!-- Filters Sidebar -->
                <div class="brand-filters">
                    <div class="filter-section">
                        <h3 class="filter-title">Price Range</h3>
                        <div class="price-range">
                            <div class="price-inputs">
                                <div class="price-input-group">
                                    <label class="price-label" for="minPrice">Minimum Price (RM)</label>
                                    <input type="number" class="price-input" placeholder="0" id="minPrice" min="0">
                                </div>
                                <div class="price-input-group">
                                    <label class="price-label" for="maxPrice">Maximum Price (RM)</label>
                                    <input type="number" class="price-input" placeholder="10000" id="maxPrice" min="0">
                                </div>
                            </div>
                            <button class="filter-btn">Apply Price Filter</button>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h3 class="filter-title">Product Type</h3>
                        <div class="filter-options">
                            <div class="filter-option">
                                <input type="checkbox" id="laptops" name="productType">
                                <label for="laptops">Laptops</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="desktops" name="productType">
                                <label for="desktops">Desktops</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="aio" name="productType">
                                <label for="aio">All-in-One PCs</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="accessories" name="productType">
                                <label for="accessories">Accessories</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h3 class="filter-title">Condition</h3>
                        <div class="filter-options">
                            <div class="filter-option">
                                <input type="checkbox" id="new" name="condition">
                                <label for="new">New</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="refurbished" name="condition">
                                <label for="refurbished">Refurbished</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="used" name="condition">
                                <label for="used">Used - Good</label>
                            </div>
                        </div>
                    </div>

                    <button class="filter-btn">Apply All Filters</button>
                </div>

                <!-- Products List -->
                <div class="brand-products">
                    <div class="brand-products-header">
                        <div class="products-count">
                            {{ $auctions->count() }} {{ $brandData['name'] ?? 'Brand' }} Products Available
                        </div>
                        <select class="sort-select">
                            <option>Sort by: Latest</option>
                            <option>Sort by: Price Low to High</option>
                            <option>Sort by: Price High to Low</option>
                            <option>Sort by: Ending Soonest</option>
                        </select>
                    </div>

                    <div class="brand-products-grid">
                        @forelse($auctions as $auction)
                            <div class="brand-product-card">
                                <div class="product-image">
                                    <img src="{{ asset($auction->image) }}" alt="{{ $auction->product_name }}">
                                </div>

                                <div class="product-info">
                                    <h3 class="product-name">{{ $auction->product_name }}</h3>

                                    <div class="product-specs">
                                        @if(!empty($auction->short_description))
                                            <div class="product-spec">{{ $auction->short_description }}</div>
                                        @elseif(!empty($auction->spec))
                                            <div class="product-spec">{{ $auction->spec }}</div>
                                        @endif

                                        @if(!empty($auction->processor))
                                            <div class="product-spec">{{ $auction->processor }}</div>
                                        @endif

                                        @if(!empty($auction->memory) || !empty($auction->storage))
                                            <div class="product-spec">
                                                @if(!empty($auction->memory))
                                                    {{ $auction->memory }}
                                                @endif
                                                @if(!empty($auction->memory) && !empty($auction->storage))
                                                    ,
                                                @endif
                                                @if(!empty($auction->storage))
                                                    {{ $auction->storage }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Countdown Timer -->
                                    <div class="countdown-timer">
                                        <div class="countdown-label">Auction Ends In:</div>
                                        <div
                                            class="countdown-display"
                                            @if(!empty($auction->end_time))
                                                data-end-date="{{ \Carbon\Carbon::parse($auction->end_time)->format('Y-m-d\TH:i:s') }}"
                                            @endif
                                        >
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-months>0</span>
                                                </div>
                                                <span class="countdown-label-small">Months</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-weeks>0</span>
                                                </div>
                                                <span class="countdown-label-small">Weeks</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-days>0</span>
                                                </div>
                                                <span class="countdown-label-small">Days</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-hours>00</span>
                                                </div>
                                                <span class="countdown-label-small">Hours</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-minutes>00</span>
                                                </div>
                                                <span class="countdown-label-small">Minutes</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-seconds>00</span>
                                                </div>
                                                <span class="countdown-label-small">Seconds</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-price-info">
                                    <div class="current-bid">
                                        <div class="bid-label">Current Bid</div>
                                        <div class="bid-amount">
                                            <span class="exclamation-mark">!</span>
                                            RM{{ number_format($auction->current_bid ?? $auction->starting_price ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="auction-time">
                                        <i class="fas fa-clock"></i>
                                        @if(!empty($auction->end_time))
                                            <span>
                                                {{-- You can replace this with a helper for "x days left" if you have one --}}
                                                Ends {{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}
                                            </span>
                                        @else
                                            <span>Ongoing</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('bid.show', $auction->id) }}" class="buy-now-btn">
                                        Place Bid
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center mt-4" style="width: 100%;">
                                No auctions available for {{ $brandData['name'] ?? 'this brand' }} at the moment.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Countdown Timer Function
function updateCountdownTimers() {
    const countdowns = document.querySelectorAll('.countdown-display');
    
    countdowns.forEach(countdown => {
        const endDateAttr = countdown.dataset.endDate;
        if (!endDateAttr) return;

        const endDate = new Date(endDateAttr).getTime();
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            countdown.innerHTML = '<div style="color: #dc2626; font-weight: 600;">Auction Ended</div>';
            return;
        }
        
        // Calculate time units
        const months = Math.floor(distance / (1000 * 60 * 60 * 24 * 30));
        const weeks = Math.floor((distance % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24 * 7));
        const days = Math.floor((distance % (1000 * 60 * 60 * 24 * 7)) / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Update display
        const monthsEl = countdown.querySelector('[data-months]');
        const weeksEl = countdown.querySelector('[data-weeks]');
        const daysEl = countdown.querySelector('[data-days]');
        const hoursEl = countdown.querySelector('[data-hours]');
        const minutesEl = countdown.querySelector('[data-minutes]');
        const secondsEl = countdown.querySelector('[data-seconds]');
        
        if (monthsEl) monthsEl.textContent = months;
        if (weeksEl) weeksEl.textContent = weeks;
        if (daysEl) daysEl.textContent = days;
        if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
        if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
        if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
    });
}

// Initialize countdown timers
document.addEventListener('DOMContentLoaded', function() {
    updateCountdownTimers();
    setInterval(updateCountdownTimers, 1000);
});
</script>
@endpush