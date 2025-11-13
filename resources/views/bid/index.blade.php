@extends('layouts.app')

@section('styles')
    @vite('resources/css/bid.css')
@endsection

@section('content')
<div class="bid-page">
    <!-- Hero Section - DARK -->
    <section class="bid-hero-section">
        <img src="{{ asset('storage/images/bidbanner.png') }}" alt="Bid Banner" class="bid-hero-banner">
        <div class="hero-overlay-text">
            <h1>Auction Bidding Platform</h1>
            <p>Discover exclusive deals through competitive bidding</p>
        </div>
    </section>

    <!-- Auction Categories - LIGHT -->
    <section class="bid-section bid-section-light">
        <div class="container">
            <div class="section-title-container">
                <h2 class="section-title">
                    <span class="title-part-black">Auction</span>
                    <span class="title-part-green">Categories</span>
                </h2>
                <div class="title-underline"></div>
            </div>

            <div class="auction-categories-grid">
                @foreach($auctionCategories as $category)
                <div class="category-card">
                    <div class="category-logo">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4 class="category-name">{{ $category->name }}</h4>
                    <p class="category-count">{{ $category->count }} items</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Live Auctions - DARK -->
    <section class="bid-section bid-section-dark">
        <div class="container">
            <div class="section-title-container">
                <h2 class="section-title">
                    <span class="title-part-white">Live</span>
                    <span class="title-part-green">Auctions</span>
                </h2>
                <div class="title-underline title-underline-white"></div>
            </div>

            <div class="live-auctions-slider-container">
                <div class="live-auctions-track">
                    @foreach($liveAuctions as $auction)
                    <div class="live-auction-card">
                        <div class="live-auction-badge">
                            <i class="fas fa-bolt"></i> LIVE
                        </div>
                        <div class="live-auction-image">
                            <img src="{{ asset($auction->image) }}" alt="{{ $auction->product_name }}">
                        </div>
                        <div class="live-auction-content">
                            <h4 class="live-auction-name">{{ $auction->product_name }}</h4>
                            <p class="live-auction-condition">{{ $auction->condition }}</p>
                            <div class="live-auction-info">
                                <div class="live-auction-price">RM{{ number_format($auction->current_bid, 2) }}</div>
                                <div class="live-auction-bids">{{ $auction->bid_count }} bids</div>
                            </div>
                            <div class="live-auction-time">
                                <i class="fas fa-clock"></i>
                                {{ $auction->time_left }} left
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Duplicate for loop -->
                    @foreach($liveAuctions as $auction)
                    <div class="live-auction-card">
                        <div class="live-auction-badge">
                            <i class="fas fa-bolt"></i> LIVE
                        </div>
                        <div class="live-auction-image">
                            <img src="{{ asset($auction->image) }}" alt="{{ $auction->product_name }}">
                        </div>
                        <div class="live-auction-content">
                            <h4 class="live-auction-name">{{ $auction->product_name }}</h4>
                            <p class="live-auction-condition">{{ $auction->condition }}</p>
                            <div class="live-auction-info">
                                <div class="live-auction-price">RM{{ number_format($auction->current_bid, 2) }}</div>
                                <div class="live-auction-bids">{{ $auction->bid_count }} bids</div>
                            </div>
                            <div class="live-auction-time">
                                <i class="fas fa-clock"></i>
                                {{ $auction->time_left }} left
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Auctions - LIGHT -->
    <section class="bid-section bid-section-light">
        <div class="container">
            <div class="section-title-container">
                <h2 class="section-title">
                    <span class="title-part-black">Upcoming</span>
                    <span class="title-part-green">Auctions</span>
                </h2>
                <div class="title-underline title-underline-black"></div>
            </div>

            <div class="auction-categories-grid">
                <div class="category-card">
                    <div class="category-logo">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h4 class="category-name">Gaming PCs</h4>
                    <p class="category-count">Starts Tomorrow</p>
                </div>
                <div class="category-card">
                    <div class="category-logo">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4 class="category-name">Smartphones</h4>
                    <p class="category-count">Starts in 2 days</p>
                </div>
                <div class="category-card">
                    <div class="category-logo">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4 class="category-name">Laptops</h4>
                    <p class="category-count">Starts in 3 days</p>
                </div>
                <div class="category-card">
                    <div class="category-logo">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <h4 class="category-name">Accessories</h4>
                    <p class="category-count">Starts next week</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects
    const cards = document.querySelectorAll('.live-auction-card, .category-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection