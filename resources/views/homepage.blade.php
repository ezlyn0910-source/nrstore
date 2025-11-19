@extends('layouts.app')

@section('styles')
    @vite('resources/css/homepage.css')
@endsection

@section('content')
<!-- Hero Slider Section -->
<section class="hero-slider">
    <div class="slider-container">
        <div class="slide active" style="background-image: url('/storage/images/banner1.png')"></div>
        <div class="slide" style="background-image: url('/storage/images/banner2.jpg')"></div>
        <div class="slide" style="background-image: url('/storage/images/banner3.jpg')"></div>
    </div>
    <div class="slider-indicators">
        <span class="indicator active" data-slide="0"></span>
        <span class="indicator" data-slide="1"></span>
        <span class="indicator" data-slide="2"></span>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Top Categories</h2>
        <div class="categories-grid">
            <div class="category-card">
                <div class="category-content">
                    <h3 class="category-name">HP</h3>
                    <p class="category-description" style="margin-bottom: 5px;">Spectre, Omen gaming, and professional workstations</p>
                    <a href="#" class="explore-link">
                        Explore category <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="category-image" style="background: transparent; padding-left: 15px;">
                    <img src="/storage/images/hp.png" alt="HP Products" class="category-img" style="width: 200px; height: 200px; object-fit: contain; background: transparent;">
                </div>
            </div>

            <div class="category-card">
                <div class="category-content">
                    <h3 class="category-name">Dell</h3>
                    <p class="category-description" style="margin-bottom: 5px;">XPS, Alienware, and reliable business computers</p>
                    <a href="#" class="explore-link">
                        Explore category <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="category-image" style="background: transparent;">
                    <img src="/storage/images/dell.png" alt="Dell Products" class="category-img" style="width: 150px; height: 150px; object-fit: contain; background: transparent;">
                </div>
            </div>

            <div class="category-card">
                <div class="category-content">
                    <h3 class="category-name">Microsoft</h3>
                    <p class="category-description" style="margin-bottom: 5px;">Surface devices, software, and enterprise solutions</p>
                    <a href="#" class="explore-link">
                        Explore category <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="category-image" style="background: transparent;">
                    <img src="/storage/images/microsoft.png" alt="Microsoft Products" class="category-img" style="width: 250px; height: 250px; object-fit: contain; background: transparent;">
                </div>
            </div>

            <div class="category-card">
                <div class="category-content">
                    <h3 class="category-name">Lenovo</h3>
                    <p class="category-description" style="margin-bottom: 5px;">ThinkPad, Yoga, and Legion gaming series</p>
                    <a href="#" class="explore-link">
                        Explore category <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="category-image" style="background: transparent;">
                    <img src="/storage/images/lenovo.png" alt="Lenovo Products" class="category-img" style="width: 200px; height: 200px; object-fit: contain; background: transparent;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="products-section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="products-grid">
            @forelse($hotProducts as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    @if($product->main_image_url)
                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="image-placeholder">No Image</div>
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-brand">{{ $product->brand }}</p>
                    <p class="product-price">{{ $product->formatted_price }}</p>
                    @if($product->has_variations)
                        <small style="color: var(--light-text);">From {{ $product->formatted_price }}</small>
                    @endif
                </div>
            </div>
            @empty
            <div class="no-products">
                <p>No hot selling products found.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="products-section new-arrivals">
    <div class="container">
        <h2 class="section-title">New Arrivals</h2>
        <div class="products-grid four-column">
            @forelse($newArrivals as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    @if($product->main_image_url)
                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="image-placeholder">No Image</div>
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-brand">{{ $product->brand }}</p>
                    <p class="product-price">{{ $product->formatted_price }}</p>
                    @if($product->has_variations)
                        <small style="color: var(--light-text);">From {{ $product->formatted_price }}</small>
                    @endif
                </div>
            </div>
            @empty
            <div class="no-products">
                <p>No new arrivals found.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.indicator');
    let currentSlide = 0;
    let slideInterval;
    
    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => {
            slide.style.opacity = '0';
            slide.style.zIndex = '1';
        });
        
        // Remove active from all indicators
        indicators.forEach(indicator => {
            indicator.classList.remove('active');
        });
        
        // Show current slide
        if (slides[index]) {
            slides[index].style.opacity = '1';
            slides[index].style.zIndex = '2';
        }
        
        // Activate current indicator
        if (indicators[index]) {
            indicators[index].classList.add('active');
        }
        
        currentSlide = index;
    }
    
    function nextSlide() {
        let nextSlideIndex = (currentSlide + 1) % slides.length;
        showSlide(nextSlideIndex);
    }
    
    // Add click events to indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function() {
            clearInterval(slideInterval);
            showSlide(index);
            startAutoSlide();
        });
    });
    
    function startAutoSlide() {
        slideInterval = setInterval(nextSlide, 5000);
    }
    
    // Initialize - show first slide
    showSlide(0);
    startAutoSlide();

    // Product card interactions
    const productCards = document.querySelectorAll('.product-card');
    const likeButtons = document.querySelectorAll('.like-btn');

    // Product card hover effects
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.12)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.08)';
        });
    });

    // Like button functionality
    likeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.background = '#fff5f5';
                this.style.borderColor = '#ff6b6b';
                this.style.color = '#ff6b6b';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.background = 'var(--white)';
                this.style.borderColor = 'var(--border-light)';
                this.style.color = 'inherit';
            }
        });
    });

    // Add click events to product cards for navigation
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if (productId) {
                window.location.href = `/products/${productId}`;
            }
        });
    });

    // Explore link interactions
    const exploreLinks = document.querySelectorAll('.explore-link');
    exploreLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryName = this.closest('.category-card').querySelector('.category-name').textContent;
            window.location.href = `/products?category=${encodeURIComponent(categoryName)}`;
        });
    });
});
</script>
@endsection