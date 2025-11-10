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
                    <h3 class="category-name">Asus</h3>
                    <p class="category-description">Premium gaming laptops, ROG series, and innovative computing solutions</p>
                    <a href="#" class="explore-link">
                        Explore category <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="category-image">
                    <div class="image-placeholder">Asus Product</div>
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
                <div class="category-image">
                    <div class="image-placeholder">Microsoft Product</div>
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
                <div class="category-image">
                    <div class="image-placeholder">Dell Product</div>
                </div>
            </div>

            <div class="category-card">
                <div class="category-content">
                    <h3 class="category-name">HP</h3>
                    <p class="category-description">Spectre, Omen gaming, and professional workstations</p>
                    <a href="#" class="explore-link">
                        Explore category <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="category-image">
                    <div class="image-placeholder">HP Product</div>
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
                <div class="category-image">
                    <div class="image-placeholder">Lenovo Product</div>
                </div>
            </div>

            <div class="category-card">
                <div class="category-content">
                    <h3 class="category-name">Acer</h3>
                    <p class="category-description">Predator gaming, Swift ultrabooks, and affordable computing</p>
                    <a href="#" class="explore-link">
                        Explore category <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="category-image">
                    <div class="image-placeholder">Acer Product</div>
                </div>
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

<!-- Footer Section -->
<footer class="footer-dark">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="footer-heading">About NR Store</h3>
                <p class="footer-text">Your trusted partner for quality laptops and computing solutions. We provide the latest technology with exceptional service.</p>
                <div class="footer-social">
                    <a href="#" class="social-link">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-heading">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}" class="footer-link">Home</a></li>
                    <li><a href="{{ url('/products') }}" class="footer-link">Products</a></li>
                    <li><a href="{{ url('/bid') }}" class="footer-link">Bid</a></li>
                    <li><a href="{{ url('/orders') }}" class="footer-link">Orders</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-heading">Customer Service</h3>
                <ul class="footer-links">
                    <li><a href="#" class="footer-link">Shipping Information</a></li>
                    <li><a href="#" class="footer-link">Returns & Refunds</a></li>
                    <li><a href="#" class="footer-link">Privacy Policy</a></li>
                    <li><a href="#" class="footer-link">Terms & Conditions</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-heading">Contact Info</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@nrstore.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+1 234 567 890</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Tech Street, Digital City</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-divider"></div>
            <div class="footer-copyright">
                <p>&copy; 2024 NR Store. All rights reserved.</p>
                <div class="footer-payment">
                    <span>We accept:</span>
                    <div class="payment-methods">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-paypal"></i>
                        <i class="fab fa-cc-apple-pay"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.indicator');
    let currentSlide = 0;
    let slideInterval;
    
    console.log('Slides found:', slides.length);
    
    function showSlide(index) {
        console.log('Changing to slide:', index);
        
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
        console.log('Auto advancing to slide:', nextSlideIndex);
        showSlide(nextSlideIndex);
    }
    
    // Add click events to indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function() {
            console.log('Indicator clicked, going to slide:', index);
            clearInterval(slideInterval);
            showSlide(index);
            startAutoSlide();
        });
    });
    
    function startAutoSlide() {
        slideInterval = setInterval(nextSlide, 5000);
        console.log('Auto slide started');
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
            e.stopPropagation(); // Prevent card click event
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

    // Add click events to product cards for navigation (example)
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            // Navigate to product detail page or show quick view
            const productName = this.querySelector('.product-name').textContent;
            console.log('Product clicked:', productName);
            // You can add navigation logic here
            // window.location.href = '/products/' + productId;
        });
    });

    // Explore link interactions
    const exploreLinks = document.querySelectorAll('.explore-link');
    exploreLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryName = this.closest('.category-card').querySelector('.category-name').textContent;
            console.log('Exploring category:', categoryName);
            // Navigate to category page
            // window.location.href = '/products?category=' + categoryName;
        });
    });
});
</script>
@endsection