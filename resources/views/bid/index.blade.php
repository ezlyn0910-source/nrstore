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

            <div class="upcoming-auctions-container">
                <div class="upcoming-auctions-track">
                    <!-- Product 1 -->
                    <div class="upcoming-auction-card">
                        <div class="upcoming-badge">
                            <i class="fas fa-clock"></i> UPCOMING
                        </div>
                        <div class="upcoming-auction-image">
                            <img src="{{ asset('storage/images/gaming-pc.png') }}" alt="Gaming PC">
                        </div>
                        <div class="upcoming-auction-content">
                            <h3 class="upcoming-auction-name">Gaming PC RTX 4080</h3>
                            <div class="upcoming-auction-specs">
                                <div class="upcoming-auction-spec">Intel i9-13900K, 32GB RAM</div>
                                <div class="upcoming-auction-spec">NVIDIA RTX 4080 16GB</div>
                                <div class="upcoming-auction-spec">2TB NVMe SSD + 4TB HDD</div>
                            </div>
                            <div class="upcoming-auction-price">
                                <div class="price-label">Starting Bid</div>
                                <div class="price-tba">TBA</div>
                            </div>
                            <div class="upcoming-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Starts: Dec 15, 2024 - 10:00 AM</span>
                            </div>
                            <button class="reminder-btn">
                                <i class="fas fa-bell"></i>
                                Set Reminder
                            </button>
                        </div>
                    </div>

                    <!-- Product 2 -->
                    <div class="upcoming-auction-card">
                        <div class="upcoming-badge">
                            <i class="fas fa-clock"></i> UPCOMING
                        </div>
                        <div class="upcoming-auction-image">
                            <img src="{{ asset('storage/images/iphone-15.png') }}" alt="iPhone 15 Pro">
                        </div>
                        <div class="upcoming-auction-content">
                            <h3 class="upcoming-auction-name">iPhone 15 Pro Max</h3>
                            <div class="upcoming-auction-specs">
                                <div class="upcoming-auction-spec">6.7" Super Retina XDR</div>
                                <div class="upcoming-auction-spec">A17 Pro Chip, 1TB Storage</div>
                                <div class="upcoming-auction-spec">Titanium Design</div>
                            </div>
                            <div class="upcoming-auction-price">
                                <div class="price-label">Starting Bid</div>
                                <div class="price-tba">TBA</div>
                            </div>
                            <div class="upcoming-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Starts: Dec 18, 2024 - 2:00 PM</span>
                            </div>
                            <button class="reminder-btn">
                                <i class="fas fa-bell"></i>
                                Set Reminder
                            </button>
                        </div>
                    </div>

                    <!-- Product 3 -->
                    <div class="upcoming-auction-card">
                        <div class="upcoming-badge">
                            <i class="fas fa-clock"></i> UPCOMING
                        </div>
                        <div class="upcoming-auction-image">
                            <img src="{{ asset('storage/images/macbook-pro.png') }}" alt="MacBook Pro">
                        </div>
                        <div class="upcoming-auction-content">
                            <h3 class="upcoming-auction-name">MacBook Pro 16"</h3>
                            <div class="upcoming-auction-specs">
                                <div class="upcoming-auction-spec">M3 Max, 48GB RAM, 4TB SSD</div>
                                <div class="upcoming-auction-spec">16.2" Liquid Retina XDR</div>
                                <div class="upcoming-auction-spec">Space Black Finish</div>
                            </div>
                            <div class="upcoming-auction-price">
                                <div class="price-label">Starting Bid</div>
                                <div class="price-tba">TBA</div>
                            </div>
                            <div class="upcoming-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Starts: Dec 20, 2024 - 9:00 AM</span>
                            </div>
                            <button class="reminder-btn">
                                <i class="fas fa-bell"></i>
                                Set Reminder
                            </button>
                        </div>
                    </div>

                    <!-- Product 4 -->
                    <div class="upcoming-auction-card">
                        <div class="upcoming-badge">
                            <i class="fas fa-clock"></i> UPCOMING
                        </div>
                        <div class="upcoming-auction-image">
                            <img src="{{ asset('storage/images/camera.png') }}" alt="Professional Camera">
                        </div>
                        <div class="upcoming-auction-content">
                            <h3 class="upcoming-auction-name">Sony A7IV Camera</h3>
                            <div class="upcoming-auction-specs">
                                <div class="upcoming-auction-spec">33MP Full-frame Sensor</div>
                                <div class="upcoming-auction-spec">4K 60p Video Recording</div>
                                <div class="upcoming-auction-spec">With 24-70mm Lens</div>
                            </div>
                            <div class="upcoming-auction-price">
                                <div class="price-label">Starting Bid</div>
                                <div class="price-tba">TBA</div>
                            </div>
                            <div class="upcoming-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Starts: Dec 22, 2024 - 11:00 AM</span>
                            </div>
                            <button class="reminder-btn">
                                <i class="fas fa-bell"></i>
                                Set Reminder
                            </button>
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