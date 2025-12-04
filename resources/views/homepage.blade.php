@extends('layouts.app')

@section('content')
<div class="homepage">
    <!-- Hero Slider Section -->
    <section class="hero-slider-section">
        <div class="hero-slider">
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
        </div>
    </section>

    <!-- Categories Section -->
    <section class="main-content-section">
        <div class="container">
            <!-- Categories Section -->
            <div class="categories-section">
                <h2 class="section-title">Top Categories</h2>
                <div class="categories-grid">
                    <div class="category-card">
                        <div class="category-content">
                            <h3 class="category-name">HP</h3>
                            <p class="category-description">Spectre, Omen gaming, and professional workstations</p>
                            <a href="#" class="explore-link">
                                Explore category <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="category-card">
                        <div class="category-content">
                            <h3 class="category-name">Dell</h3>
                            <p class="category-description">XPS, Alienware, and reliable business computers</p>
                            <a href="#" class="explore-link">
                                Explore category <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="category-card">
                        <div class="category-content">
                            <h3 class="category-name">Microsoft</h3>
                            <p class="category-description">Surface devices, software, and enterprise solutions</p>
                            <a href="#" class="explore-link">
                                Explore category <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="category-card">
                        <div class="category-content">
                            <h3 class="category-name">Lenovo</h3>
                            <p class="category-description">ThinkPad, Yoga, and Legion gaming series</p>
                            <a href="#" class="explore-link">
                                Explore category <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Products Section -->
            <div class="products-section">
                <h2 class="section-title">Featured Products</h2>
                <div class="products-grid">
                    @forelse($hotProducts as $product)
                    <div class="product-card" data-product-id="{{ $product->id }}">
                        <div class="product-image">
                            @if($product->main_image_url)
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}">
                            @else
                                <div class="image-placeholder">No Image</div>
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <p class="product-brand">{{ $product->brand }}</p>
                            <p class="product-price">{{ $product->formatted_price }}</p>
                            @if($product->has_variations)
                                <small class="price-from">From {{ $product->formatted_price }}</small>
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

            <!-- New Arrivals Section -->
            <div class="products-section new-arrivals" style="background: transparent; box-shadow: none; padding: 0; border-radius: 0;">
                <h2 class="section-title" style="margin-bottom: 1.5rem;">New Arrivals</h2>

                <div class="products-grid four-column">
                    @forelse($newArrivals as $product)
                    <div class="product-card" data-product-id="{{ $product->id }}">
                        <div class="product-image">
                            @if($product->main_image_url)
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}">
                            @else
                                <div class="image-placeholder">No Image</div>
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <p class="product-brand">{{ $product->brand }}</p>
                            <p class="product-price">{{ $product->formatted_price }}</p>

                            @if($product->has_variations)
                                <small class="price-from">From {{ $product->formatted_price }}</small>
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
        </div>
    </section>
</div>

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

@section('styles')
<style>
    :root {
        --primary-dark: #1a2412;
        --primary-green: #2d4a35;
        --accent-gold: #daa112;
        --light-bone: #f8f9fa;
        --dark-text: #1a2412;
        --light-text: #6b7c72;
        --white: #ffffff;
        --border-light: #e9ecef;
        --grey-bg: #f5f5f5;
        --grey-text: #6b7280;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* FIXED: Remove all default margins and padding from homepage */
    .homepage {
        background: #f8f9fa;
        min-height: 100vh;
        margin: 0 !important;
        padding: 0 !important;
    }

    .main-content-section {
        padding: 0;
        position: relative;
        margin-top: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* FIXED: Hero Slider Section - Completely remove all spacing */
    .hero-slider-section {
        width: 100%;
        padding: 0 !important;
        margin: 0 !important;
        position: relative;
    }

    .hero-slider {
        position: relative;
        color: var(--white);
        overflow: hidden;
        height: 86vh;
        min-height: 600px;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }

    .slider-container {
        height: 100%;
        width: 100%;
        position: relative;
        margin: 0;
        padding: 0;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        z-index: 1;
        margin: 0;
        padding: 0;
    }

    .slide.active {
        opacity: 1;
        z-index: 2;
    }

    .slider-indicators {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.75rem;
        z-index: 100;
    }

    .indicator {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        pointer-events: auto;
    }

    .indicator.active {
        background: var(--accent-gold);
        transform: scale(1.2);
        border-color: var(--white);
    }

    .indicator:hover {
        background: var(--accent-gold);
        transform: scale(1.1);
    }

    /* FIXED: Categories Section - Remove top spacing */
    .categories-section {
        padding: 2rem 0;
        margin: 0 auto;
        max-width: 1200px;
        margin-top: 3rem;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0 0 2rem 0;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin: 0;
        padding: 0;
    }

    .category-card {
        background: var(--white);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-light);
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        position: relative;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-green);
    }

    .category-content {
        flex: 1;
        z-index: 2;
        position: relative;
        text-align: center !important;
    }

    .category-name {
        font-size: 1.375rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.75rem;
    }

    .category-description {
        color: var(--light-text);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .explore-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--accent-gold);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .explore-link:hover {
        gap: 0.75rem;
        color: var(--primary-green);
    }

    .category-image {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        width: 70px;
        height: 70px;
        background: transparent;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .products-section {
        padding: 2rem 0;
        margin: 0 auto;
        max-width: 1200px;
    }

    .products-section.new-arrivals {
        background: transparent !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        margin-bottom: 3rem;
        margin-top: 2rem;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin: 0;
        padding: 0;
    }

    .products-grid.four-column {
        grid-template-columns: repeat(4, 1fr);
    }

    .product-card {
        background: var(--white);
        border-radius: 1rem;
        padding: 1.5rem;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-light);
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-green);
    }

    .product-image {
        width: 100%;
        height: 180px;
        background: var(--light-bone);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-image .image-placeholder {
        color: var(--light-text);
        font-size: 1rem;
    }

    .product-info {
        text-align: center;
    }

    .product-name {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark-text);
    }

    .product-brand {
        font-size: 0.875rem;
        color: var(--light-text);
        margin-bottom: 0.5rem;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--accent-gold);
        margin: 0;
    }

    .price-from {
        color: var(--light-text);
        font-size: 0.875rem;
    }

    .no-products {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem 2rem;
        color: var(--light-text);
        background: var(--white);
        border-radius: 1rem;
        border: 2px dashed var(--border-light);
    }

    .no-products p {
        margin: 0;
        font-size: 1.125rem;
    }

    /* FIXED: Responsive Design */
    @media (max-width: 1024px) {
        .categories-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .products-grid.four-column {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 1.5rem;
        }

        .categories-section,
        .products-section {
            margin: 0 auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .categories-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .category-card {
            padding: 1.25rem;
            min-height: 180px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .products-grid.four-column {
            grid-template-columns: 1fr;
        }

        .product-card {
            padding: 1.25rem;
        }

        .product-image {
            height: 160px;
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 0 1rem;
        }

        .main-content-section,
        .categories-section,
        .products-section {
            margin: 0 auto;
            padding-left: 1rem;
            padding-right: 1rem;
            text-align: center !important;
        }

        .section-title {
            font-size: 1.75rem;
        }

        .category-name {
            font-size: 1.25rem;
        }

        .product-name {
            font-size: 1rem;
        }

        .product-price {
            font-size: 1.125rem;
        }

        .no-products {
            padding: 2rem 1.5rem;
        }
    }

    /* Homepage Specific Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .category-card,
    .product-card {
        animation: slideInUp 0.6s ease-out;
    }

    /* Smooth transitions */
    .product-card,
    .category-card,
    .explore-link,
</style>
@endsection