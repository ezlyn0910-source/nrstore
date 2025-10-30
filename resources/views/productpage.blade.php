@extends('layouts.app')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/productpage.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="product-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <img src="{{ asset('storage/images/productbanner.png') }}" alt="Products Banner" class="hero-image">
        <div class="hero-content">
            <h1 class="hero-title">Product</h1>
        </div>
    </section>

    <!-- White Box Container -->
    <section class="main-content">
        <div class="container">
            <div class="main-content-box">
                
                <!-- Header Row -->
                <div class="header-row">
                    <h2 class="page-title">Give All You Need</h2>
                    <div class="search-container">
                        <input type="text" placeholder="Search products..." class="search-box">
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="two-column-layout">
                    <!-- Filters Sidebar -->
                    <div class="filters-sidebar">
                        <div class="filter-section">
                            <h3 class="filter-title">Category</h3>
                            <div class="filter-options">
                                @foreach($categories as $category)
                                <label class="filter-option">
                                    <input type="checkbox" name="category" value="{{ $category->slug }}">
                                    <span>{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="filter-section">
                            <h3 class="filter-title">Price</h3>
                            <select class="filter-select">
                                <option>Default Sorting</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                            </select>
                        </div>

                        <div class="filter-section">
                            <h3 class="filter-title">Brand</h3>
                            <select class="filter-select">
                                <option>All Brands</option>
                                <option>HP</option>
                                <option>Dell</option>
                                <option>Microsoft</option>
                                <option>Lenovo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Main -->
                    <div class="products-main">
                        <div class="products-grid">
                            @foreach($products as $product)
                            <div class="product-card">
                                <div class="product-image-container">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                        alt="{{ $product->name }}" 
                                        class="product-image">
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name line-clamp-2">{{ $product->name }}</h3>
                                    <p class="product-price">${{ number_format($product->price, 2) }}</p>
                                    <div class="product-buttons">
                                        <button class="btn-cart">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Cart
                                        </button>
                                        <button class="btn-buy">Buy Now</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="divider"></div>

                        <!-- Pagination -->
                        <div class="pagination">
                            <button class="pagination-btn">Previous</button>
                            <div class="pagination-numbers">
                                @for($i = 1; $i <= 6; $i++)
                                <button class="pagination-btn {{ $i == 1 ? 'active' : '' }}">{{ $i }}</button>
                                @endfor
                            </div>
                            <button class="pagination-btn">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div class="recommendations-section-boxed">
                    <div class="recommendations-header">
                        <h2 class="recommendations-title">Explore our recommendations</h2>
                        <div class="recommendations-controls">
                            <button class="slider-btn recommendations-prev">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button class="slider-btn recommendations-next">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="recommendations-slider scrollbar-hide" id="recommendations-slider">
                        @foreach($recommendedProducts as $product)
                        <div class="recommendation-card">
                            <div class="recommendation-image-container">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}" 
                                    class="recommendation-image">
                            </div>
                            <div class="recommendation-info">
                                <h3 class="recommendation-name line-clamp-2">{{ $product->name }}</h3>
                                <p class="recommendation-price">${{ number_format($product->price, 2) }}</p>
                                <div class="recommendation-buttons">
                                    <button class="btn-recommendation-cart">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Cart
                                    </button>
                                    <button class="btn-recommendation-buy">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-column">
                    <h3>About Us</h3>
                    <p>Your trusted partner for quality laptops and computing solutions.</p>
                </div>
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Products</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact Info</h3>
                    <ul>
                        <li>Email: info@nrstore.com</li>
                        <li>Phone: +1 234 567 890</li>
                        <li>Address: 123 Tech Street, Digital City</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 NRStore. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('recommendations-slider');
    const prevBtn = document.querySelector('.recommendations-prev');
    const nextBtn = document.querySelector('.recommendations-next');
    
    if (prevBtn && nextBtn && slider) {
        prevBtn.addEventListener('click', () => {
            slider.scrollBy({ left: -200, behavior: 'smooth' });
        });
        
        nextBtn.addEventListener('click', () => {
            slider.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }

    let isDown = false;
    let startX;
    let scrollLeft;

    if (slider) {
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    }
});
</script>
@endsection