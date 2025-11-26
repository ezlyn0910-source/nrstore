<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NR Store') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite([
        'resources/sass/app.scss', 
        'resources/css/home.css',  {{-- Global header/footer styles for ALL pages --}}
        'resources/js/app.js'
    ])

    @yield('styles') {{-- Page-specific styles only --}}
</head>
<body>
    <div id="app">
        @if (!request()->is('login') && !request()->is('register'))
            <!-- Global Header (from home.css) -->
            <!-- First Header -->
            <div class="header-top">
                <div class="container">
                    <div class="header-top-content">
                        <div class="header-left">
                            <div class="language-dropdown">
                                <button class="language-btn">
                                    EN <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-content">
                                    <a href="#" class="dropdown-item">English</a>
                                    <a href="#" class="dropdown-item">Malay</a>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="warranty-text">3 Months Warranty For All Products</div>
                        </div>
                        <div class="header-right">
                            <div class="auth-links">
                                @auth
                                    <!-- Show user menu when logged in -->
                                    <div class="user-menu">
                                        <a href="#" class="header-link">
                                            <i class="fas fa-user-circle"></i>
                                            <span>{{ Auth::user()->name }}</span>
                                        </a>
                                        <div class="auth-divider"></div>
                                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                            @csrf
                                            <a href="{{ route('logout') }}" 
                                            class="header-link"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>Logout</span>
                                            </a>
                                        </form>
                                    </div>
                                @else
                                    <!-- Show login/register when not logged in -->
                                    <a href="{{ route('login') }}" class="auth-link">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Sign In</span>
                                    </a>
                                    <div class="auth-divider"></div>
                                    <a href="{{ route('register') }}" class="auth-link">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Register</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Header -->
            <div class="header-bottom">
                <div class="container">
                    <div class="header-bottom-content">
                        <div class="logo-section-wrapper">
                            <a href="/" class="logo-link">NR INTELLITECH</a>
                        </div>

                        <div class="nav-section-wrapper">
                            <nav class="main-nav">
                                <a href="/" class="nav-link">Home</a>
                                <a href="/products" class="nav-link">Products</a>
                                <a href="/orders" class="nav-link">Order</a>
                                <a href="/bid" class="nav-link bid-link">
                                    <i class="fas fa-gavel"></i>
                                    <span>Bid Now</span>
                                </a>
                            </nav>
                        </div>

                        <div class="actions-section">
                            <div class="search-actions-container">
                                <div class="search-container">
                                    <form class="search-form">
                                        <input type="text" class="search-input" placeholder="Search products...">
                                        <button type="submit" class="search-btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="action-icons">
                                    <a href="/favorites" class="action-icon">
                                        <i class="far fa-heart"></i>
                                        <span class="action-badge" data-count="0"></span>
                                    </a>
                                    
                                    <a href="{{ route('cart.index') }}" class="action-icon" id="cart-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="action-badge" id="cart-badge" style="display: none;"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <main>
            @yield('content')
        </main>

        <!-- Global Footer -->
        @if (!request()->is('login') && !request()->is('register'))
            <footer class="footer-dark">
                <div class="container">
                    <div class="footer-content">
                        <div class="footer-section">
                            <h3 class="footer-heading">NR INTELLITECH</h3>
                            <p class="footer-text">
                                Your trusted partner for premium tech products. We offer the latest in technology with guaranteed quality and exceptional customer service.
                            </p>
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
                                <li><a href="/" class="footer-link">Home</a></li>
                                <li><a href="/products" class="footer-link">Products</a></li>
                                <li><a href="/orders" class="footer-link">Order</a></li>
                                <li><a href="/bid" class="footer-link">Bid Now</a></li>
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
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Lot 5-34, Imbi Plaza, 28, Jalan Imbi, Bukit Bintang, 55100 Kuala Lumpur</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>+60 12 316 2006</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>nrintellitech@gmail.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer-divider"></div>
                    
                    <div class="footer-bottom">
                        <div class="footer-copyright">
                            <p>&copy; 2024 NR INTELLITECH. All rights reserved.</p>
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
        @endif
    </div>

    @stack('scripts')
    <!-- Global Cart Count Script -->
    <script>
    // Global function to update cart count (can be called from any page)
    function updateHeaderCartCount(count) {
        const cartBadge = document.querySelector('#cart-icon .action-badge');
        if (cartBadge) {
            if (count > 0) {
                cartBadge.textContent = count;
                cartBadge.style.display = 'flex';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    }

    // Load cart count on page load for ALL pages
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch the current cart count via AJAX on page load
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                updateHeaderCartCount(data.count);
            })
            .catch(error => {
                console.error('Error fetching cart count:', error);
            });
    });

    // Make the function globally available
    window.updateHeaderCartCount = updateHeaderCartCount;
    </script>
</body>
</html>