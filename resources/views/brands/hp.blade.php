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
            <p class="brand-hero-subtitle">Discover amazing HP products through competitive bidding</p>
            <div class="brand-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">></span>
                <a href="{{ route('bid.index') }}">Bid</a>
                <span class="separator">></span>
                <a href="{{ route('bid.index') }}#brands">Categories</a>
                <span class="separator">></span>
                <span>HP</span>
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
                                <input type="checkbox" id="surface" name="productType">
                                <label for="surface">Surface Devices</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="xbox" name="productType">
                                <label for="xbox">Xbox Consoles</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="accessories" name="productType">
                                <label for="accessories">Accessories</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="software" name="productType">
                                <label for="software">Software</label>
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
                        <div class="products-count">12 Microsoft Products Available</div>
                        <select class="sort-select">
                            <option>Sort by: Latest</option>
                            <option>Sort by: Price Low to High</option>
                            <option>Sort by: Price High to Low</option>
                            <option>Sort by: Ending Soonest</option>
                        </select>
                    </div>

                    <div class="brand-products-grid">
                        <!-- Product 1 -->
                        <div class="brand-product-card">
                            <div class="product-image">
                                <img src="{{ asset('storage/products/hp1.png') }}" alt="Surface Pro 9">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">Microsoft Surface Pro 9</h3>
                                <div class="product-specs">
                                    <div class="product-spec">Intel Core i7, 16GB RAM, 512GB SSD</div>
                                    <div class="product-spec">13" PixelSense Touchscreen</div>
                                    <div class="product-spec">Windows 11 Pro</div>
                                </div>
                                <!-- Countdown Timer -->
                                <div class="countdown-timer">
                                    <div class="countdown-label">Auction Ends In:</div>
                                    <div class="countdown-display" data-end-date="2026-06-31T23:59:59">
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-months>2</span>
                                            </div>
                                            <span class="countdown-label-small">Months</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-weeks>2</span>
                                            </div>
                                            <span class="countdown-label-small">Weeks</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-days>3</span>
                                            </div>
                                            <span class="countdown-label-small">Days</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-hours>08</span>
                                            </div>
                                            <span class="countdown-label-small">Hours</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-minutes>45</span>
                                            </div>
                                            <span class="countdown-label-small">Minutes</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-seconds>30</span>
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
                                        RM3,299
                                    </div>
                                </div>
                                <div class="auction-time">
                                    <i class="fas fa-clock"></i>
                                    <span>2 days left</span>
                                </div>
                                <a href="{{ route('bid.show', 1) }}" class="buy-now-btn">Place Bid</a>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="brand-product-card">
                            <div class="product-image">
                                <img src="{{ asset('storage/products/hp2.png') }}" alt="Xbox Series X">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">Xbox Series X 1TB</h3>
                                <div class="product-specs">
                                    <div class="product-spec">4K Gaming at 120fps</div>
                                    <div class="product-spec">1TB Custom SSD</div>
                                    <div class="product-spec">Includes Wireless Controller</div>
                                </div>
                                <!-- Countdown Timer -->
                                <div class="countdown-timer">
                                    <div class="countdown-label">Auction Ends In:</div>
                                    <div class="countdown-display" data-end-date="2026-02-15T23:59:59">
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-months>2</span>
                                            </div>
                                            <span class="countdown-label-small">Months</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-weeks>2</span>
                                            </div>
                                            <span class="countdown-label-small">Weeks</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-days>3</span>
                                            </div>
                                            <span class="countdown-label-small">Days</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-hours>08</span>
                                            </div>
                                            <span class="countdown-label-small">Hours</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-minutes>45</span>
                                            </div>
                                            <span class="countdown-label-small">Minutes</span>
                                        </div>
                                        <div class="countdown-unit">
                                            <div class="countdown-box">
                                                <span class="countdown-value" data-seconds>30</span>
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
                                        RM1,899
                                    </div>
                                </div>
                                <div class="auction-time">
                                    <i class="fas fa-clock"></i>
                                    <span>1 day left</span>
                                </div>
                                <a href="{{ route('bid.show', 2) }}" class="buy-now-btn">Place Bid</a>
                            </div>
                        </div>

                        <!-- Add more Microsoft products as needed -->
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
        const endDate = new Date(countdown.dataset.endDate).getTime();
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