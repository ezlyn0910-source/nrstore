@extends('layouts.app')

@section('styles')
    @vite('resources/css/bid.css')
@endsection

@section('content')
<div class="bid-page">
    <!-- Hero Section with Overlay Text -->
    <section class="bid-hero-section">
        <img src="{{ asset('storage/images/bidbanner.png') }}" alt="Bid Banner" class="bid-hero-banner">
        <div class="hero-overlay-text">
            <h1>Auction Bidding Platform</h1>
            <p>Discover exclusive deals through competitive bidding</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="bid-main-content">
        <div class="container">
            <div class="bid-content-box">
                <!-- Auction Categories Section -->
                <div class="auction-categories-section">
                    <div class="section-title-container">
                        <h2 class="section-title">
                            <span class="title-part-black">Auction</span>
                            <span class="title-part-green">Categories</span>
                        </h2>
                        <div class="title-underline"></div>
                    </div>

                    <div class="auction-categories-grid">
                        @foreach($auctionCategories as $category)
                        <div class="category-card" onclick="window.location.href='#'">
                            <div class="category-logo">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <h4 class="category-name">{{ $category->name }}</h4>
                            <p class="category-count">{{ $category->count }} items</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Live Auctions Section -->
                <div class="live-auctions-section">
                    <div class="section-title-container">
                        <h2 class="section-title">
                            <span class="title-part-black">Live</span>
                            <span class="title-part-green">Auction</span>
                        </h2>
                        <div class="title-underline"></div>
                    </div>

                    <div class="live-auctions-slider-container">
                        <div class="live-auctions-track">
                            @foreach($liveAuctions as $auction)
                            <div class="live-auction-card" onclick="window.location.href='{{ route('bid.show', $auction->id) }}'">
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
                            
                            <!-- Duplicate items for seamless loop -->
                            @foreach($liveAuctions as $auction)
                            <div class="live-auction-card" onclick="window.location.href='{{ route('bid.show', $auction->id) }}'">
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
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-sliding animation is handled by CSS
    console.log('Bid page loaded with auto-slider');
    
    // Add hover effect pause for better UX
    const track = document.querySelector('.live-auctions-track');
    const cards = document.querySelectorAll('.live-auction-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endsection