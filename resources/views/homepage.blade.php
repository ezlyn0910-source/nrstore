@extends('layouts.app')

@section('content')
<!-- Hero Slider Section -->
<section class="hero-slider">
    <div class="slider-container">
        <div class="slide active">
            <div class="slide-content">
                <div class="slide-buttons">
                    <button class="btn-shop-now">Shop Now</button>
                    <button class="btn-make-bid">Make a Bid</button>
                </div>
            </div>
        </div>
    </div>
    <div class="slider-indicators">
        <span class="indicator active"></span>
        <span class="indicator"></span>
        <span class="indicator"></span>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="categories-grid">
            <div class="category-card">
                <div class="category-image">
                    <div class="image-placeholder">Microsoft</div>
                </div>
                <h3 class="category-name">Microsoft</h3>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <div class="image-placeholder">Samsung</div>
                </div>
                <h3 class="category-name">Samsung</h3>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <div class="image-placeholder">HP</div>
                </div>
                <h3 class="category-name">HP</h3>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <div class="image-placeholder">Lenovo</div>
                </div>
                <h3 class="category-name">Lenovo</h3>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <div class="image-placeholder">Dell</div>
                </div>
                <h3 class="category-name">Dell</h3>
            </div>
        </div>
    </div>
</section>

<!-- Hot Selling Products Section -->
<section class="products-section">
    <div class="container">
        <h2 class="section-title">Hot Selling Products</h2>
        <div class="products-grid">
            <div class="product-card">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    <div class="image-placeholder">Product 1</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Wireless Headphones</h3>
                    <p class="product-brand">AudioTech</p>
                    <p class="product-price">RM129.99</p>
                </div>
            </div>
            <div class="product-card">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    <div class="image-placeholder">Product 2</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Smart Watch</h3>
                    <p class="product-brand">TechWear</p>
                    <p class="product-price">RM199.99</p>
                </div>
            </div>
            <div class="product-card">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    <div class="image-placeholder">Product 3</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Camera Lens</h3>
                    <p class="product-brand">PhotoPro</p>
                    <p class="product-price">RM299.99</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="products-section new-arrivals">
    <div class="container">
        <h2 class="section-title">New Arrivals</h2>
        <div class="products-grid four-column">
            <div class="product-card">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    <div class="image-placeholder">New 1</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Gaming Keyboard</h3>
                    <p class="product-brand">GameMaster</p>
                    <p class="product-price">RM89.99</p>
                </div>
            </div>
            <div class="product-card">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    <div class="image-placeholder">New 2</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Fitness Tracker</h3>
                    <p class="product-brand">FitLife</p>
                    <p class="product-price">RM79.99</p>
                </div>
            </div>
            <div class="product-card">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    <div class="image-placeholder">New 3</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Bluetooth Speaker</h3>
                    <p class="product-brand">SoundWave</p>
                    <p class="product-price">RM59.99</p>
                </div>
            </div>
            <div class="product-card">
                <button class="like-btn">
                    <i class="far fa-heart"></i>
                </button>
                <div class="product-image">
                    <div class="image-placeholder">New 4</div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Wireless Earbuds</h3>
                    <p class="product-brand">AudioPro</p>
                    <p class="product-price">RM149.99</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection