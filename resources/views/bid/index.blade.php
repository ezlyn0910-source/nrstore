@extends('layouts.app')

@section('styles')
    @vite(['resources/css/bid.css'])
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

            <div class="brands-row">
                <a href="{{ route('brand.auctions', 'microsoft') }}" class="brand-card">
                    <div class="brand-logo-circle">
                        <img src="{{ asset('storage/images/microsoftlogo.png') }}" alt="Microsoft Logo">
                    </div>
                    <h4 class="brand-name">Microsoft</h4>
                </a>
                
                <a href="{{ route('brand.auctions', 'hp') }}" class="brand-card">
                    <div class="brand-logo-circle">
                        <img src="{{ asset('storage/images/hplogo.png') }}" alt="HP Logo">
                    </div>
                    <h4 class="brand-name">HP</h4>
                </a>
                
                <a href="{{ route('brand.auctions', 'dell') }}" class="brand-card">
                    <div class="brand-logo-circle">
                        <img src="{{ asset('storage/images/delllogo.png') }}" alt="Dell Logo">
                    </div>
                    <h4 class="brand-name">Dell</h4>
                </a>
                
                <a href="{{ route('brand.auctions', 'lenovo') }}" class="brand-card">
                    <div class="brand-logo-circle">
                        <img src="{{ asset('storage/images/lenovologo.png') }}" alt="Lenovo Logo">
                    </div>
                    <h4 class="brand-name">Lenovo</h4>
                </a>
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

            @if($liveAuctions->count() > 0)
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
                            <a href="{{ route('bid.show', $auction->id) }}" class="view-bid-btn" style="display: block; text-align: center; margin-top: 1rem; padding: 0.5rem; background: var(--accent-green); color: white; border-radius: 0.25rem; text-decoration: none;">
                                View Bid
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-white">No live auctions at the moment. Check back soon!</p>
            </div>
            @endif
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

            @if($upcomingAuctions->count() > 0)
            <div class="upcoming-auctions-container">
                <div class="upcoming-auctions-track">
                    @foreach($upcomingAuctions as $auction)
                    <div class="upcoming-auction-card">
                        <div class="upcoming-badge">
                            <i class="fas fa-clock"></i> UPCOMING
                        </div>
                        <div class="upcoming-auction-image">
                            <img src="{{ asset($auction->product->main_image_url ?? 'storage/images/placeholder.jpg') }}" alt="{{ $auction->product->name }}">
                        </div>
                        <div class="upcoming-auction-content">
                            <h3 class="upcoming-auction-name">{{ $auction->product->name }}</h3>
                            <div class="upcoming-auction-specs">
                                @if($auction->product->specifications && is_array($auction->product->specifications))
                                    @foreach(array_slice($auction->product->specifications, 0, 3) as $key => $value)
                                    <div class="upcoming-auction-spec">{{ $key }}: {{ $value }}</div>
                                    @endforeach
                                @else
                                    <div class="upcoming-auction-spec">Check product details for specifications</div>
                                @endif
                            </div>
                            <div class="upcoming-auction-price">
                                <div class="price-label">Starting Bid</div>
                                <div class="price-tba">RM{{ number_format($auction->starting_price, 2) }}</div>
                            </div>
                            <div class="upcoming-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Starts: {{ $auction->start_time->format('M d, Y - h:i A') }}</span>
                            </div>
                            <button class="reminder-btn">
                                <i class="fas fa-bell"></i>
                                Set Reminder
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <p>No upcoming auctions scheduled. Check back later for new auctions!</p>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.live-auction-card, .category-card');
    const reminderBtns = document.querySelectorAll('.reminder-btn');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    reminderBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const productName = this.closest('.upcoming-auction-card').querySelector('.upcoming-auction-name').textContent;
            
            if (this.classList.contains('reminder-set')) {
                this.classList.remove('reminder-set');
                this.innerHTML = '<i class="fas fa-bell"></i> Set Reminder';
                // Remove reminder logic here
            } else {
                this.classList.add('reminder-set');
                this.innerHTML = '<i class="fas fa-bell-slash"></i> Reminder Set';
                // Set reminder logic here
                alert(`Reminder set for ${productName}`);
            }
        });
    });
});
</script>
@endsection