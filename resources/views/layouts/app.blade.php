<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NR Store') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/tablogo.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 for beautiful dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('styles') {{-- Page-specific styles only --}}
</head>
<body>
    <div id="app">
        <!-- Global Header (ALWAYS show on all pages except auth pages) -->
        @if (!in_array(Route::currentRouteName(), ['login', 'register', 'password.request', 'password.reset']))
            <!-- First Header -->
            <div class="header-top">
                <div class="container">
                    <div class="header-top-content">
                        <!-- Left Column - Aligns with logo in bottom header -->
                        <div class="header-top-left">
                            <div class="warranty-text">3 Months Warranty For All Products</div>
                        </div>
                        
                        <!-- Right Column - Aligns with cart icon in bottom header -->
                        <div class="header-top-right">
                            <div class="auth-links">
                                @auth
                                    @php
                                        $fullName  = trim(Auth::user()->name ?? '');
                                        // If there is a space, take only the first word, else keep full name
                                        $firstName = $fullName !== '' ? strtok($fullName, ' ') : '';
                                    @endphp

                                    <!-- Show user menu when logged in -->
                                    <div class="user-menu">
                                    <a href="{{ route('profile.index') }}" class="header-link">
                                        <i class="fas fa-user-circle"></i>
                                        <span>{{ $firstName }}</span>
                                    </a>
                                    <div class="auth-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:inline;">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                        class="header-link"
                                        onclick="event.preventDefault(); confirmLogout();">
                                            <span>Logout</span>
                                        </a>
                                    </form>
                                </div>
                                @else
                                    <!-- Show login/register when not logged in -->
                                    <a href="{{ route('login') }}" class="auth-link">
                                        <span>Sign In</span>
                                    </a>
                                    <div class="auth-divider"></div>
                                    <a href="{{ route('register') }}" class="auth-link">
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
                        <!-- Left Column - Logo -->
                        <div class="logo-section-wrapper">
                            <a href="/" class="logo-link">NR INTELLITECH</a>
                        </div>

                        <!-- Center Column - Navigation -->
                        <div class="nav-section-wrapper">
                            <nav class="main-nav">
                                <a href="/"
                                class="nav-link nav-link-indicator {{ request()->is('/') ? 'nav-link-active' : '' }}">
                                    Home
                                </a>

                                <a href="/products"
                                class="nav-link nav-link-indicator {{ request()->is('products*') ? 'nav-link-active' : '' }}">
                                    Products
                                </a>

                                <a href="/orders"
                                class="nav-link nav-link-indicator {{ request()->is('orders*') ? 'nav-link-active' : '' }}">
                                    Order
                                </a>

                                <a href="/bid"
                                class="nav-link nav-link-indicator {{ request()->is('bid*') ? 'nav-link-active' : '' }}">
                                    Bid Now
                                </a>

                                <a href="{{ route('about') }}"
                                class="nav-link nav-link-indicator {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">
                                    About Us
                                </a>

                            </nav>
                        </div>

                        <!-- Right Column - Search and Cart -->
                        <div class="actions-section">
                            <div class="search-actions-container">
                                <div class="search-container">
                                    <form class="search-form" method="GET" action="{{ url('/products') }}">
                                        {{-- use the SAME query name that your product page uses (change "search" if needed) --}}
                                        <input
                                            type="text"
                                            name="search"
                                            class="search-input"
                                            placeholder="Search products..."
                                            value="{{ request('search') }}"
                                        >
                                        <button type="submit" class="search-btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Cart Icon Only -->
                                <a href="{{ route('cart.index') }}" class="action-icon" id="cart-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="action-badge" id="cart-badge" style="display: none;"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <main>
            @yield('content')
        </main>

        <!-- Global Footer (ALWAYS show on all pages except auth pages) -->
        @if (!in_array(Route::currentRouteName(), ['login', 'register', 'password.request', 'password.reset']))
            <footer class="footer-dark">
                <div class="container">
                    <div class="footer-content">
                        <div class="footer-section">
                            <h3 class="footer-heading">NR INTELLITECH</h3>
                            <p class="footer-text">
                                Your trusted partner for premium tech products. We offer the latest in technology with guaranteed quality and exceptional customer service.
                            </p>
                            <div class="footer-social">
                                <a href="https://shopee.com.my/nr_intellitech_sdn_bhd"
                                class="social-link social-icon-box"
                                target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('images/social/shopee.png') }}" class="social-logo" alt="Shopee">
                                </a>

                                <a href="https://www.lazada.com.my/shop/nr-intellitech-sdn-bhd"
                                class="social-link social-icon-box"
                                target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('images/social/lazada.png') }}" class="social-logo" alt="Lazada">
                                </a>

                                <a href="https://carousell.app.link/M5HhdCw2WYb"
                                class="social-link social-icon-box"
                                target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('images/social/carousell.png') }}" class="social-logo" alt="Carousell">
                                </a>

                                <a href="https://www.tiktok.com/@nr.intellitech?_r=1&_t=ZS-924SNipKiyA"
                                class="social-link social-icon-box"
                                target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('images/social/tiktok.png') }}" class="social-logo" alt="TikTok">
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
                                <li><a href="{{ route('shipping.info') }}" class="footer-link">Shipping Information</a></li>
                                <li><a href="{{ route('return.policy') }}" class="footer-link">Returns & Refunds</a></li>
                                <li><a href="{{ route('privacy.policy') }}" class="footer-link">Privacy Policy</a></li>
                                <li><a href="{{ route('terms.conditions') }}" class="footer-link">Terms & Conditions</a></li>
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
                                    <img src="{{ asset('images/payment/visa.png') }}" alt="Visa" class="payment-logo">
                                    <img src="{{ asset('images/payment/mastercard.png') }}" alt="Mastercard" class="payment-logo">
                                    <img src="{{ asset('images/payment/fpx.png') }}" alt="FPX" class="payment-logo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        @endif
    </div>

    <script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - initializing cart badge');
        updateCartBadge();
        
        // Update every 5 seconds to keep badge fresh
        setInterval(updateCartBadge, 5000);
        
        // Make function available globally
        window.updateCartBadge = updateCartBadge;
    });

    // Cart badge update function
    async function updateCartBadge() {
        try {
            const response = await fetch('{{ route("cart.count") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: 'no-cache'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            const cartBadge = document.getElementById('cart-badge');
            
            if (cartBadge) {
                const count = data.count || 0;
                
                if (count > 0) {
                    cartBadge.textContent = count > 9 ? '9+' : count.toString();
                    cartBadge.style.display = 'flex';
                    console.log(`Cart badge updated: ${count} items`);
                } else {
                    cartBadge.style.display = 'none';
                    console.log('Cart badge hidden (empty cart)');
                }
            }
        } catch (error) {
            console.error('Error updating cart badge:', error);
        }
    }

    // Event listeners for cart updates
    window.addEventListener('storage', function(event) {
        if (event.key === 'cart_updated') {
            updateCartBadge();
        }
    });

    // Custom event for cart updates
    document.addEventListener('cartUpdated', updateCartBadge);

    // Logout confirmation function
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out from your account.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#2d4a35',
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the logout form
                document.getElementById('logout-form').submit();
            }
        });
    }
    </script>

    <style>
        :root {
            --primary-dark: #1a2412;
            --primary-green: #2d4a35;
            --light-green: #4caf50;
            --light-bone: #f8f9fa;
            --dark-text: #1a2412;
            --light-text: #6b7c72;
            --white: #ffffff;
            --border-light: #e9ecef;
            --shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        /* Global Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Nunito", sans-serif;
            background-color: var(--primary-dark) !important;
        }

        /* Badge Animation */
        .action-badge.badge-pulse {
            animation: badgePulse 0.3s ease-in-out;
        }

        @keyframes badgePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }

        /* Cart Toast Notification */
        .cart-toast {
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--light-green);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: var(--shadow-hover);
            transform: translateX(120%);
            transition: transform 0.3s ease;
            z-index: 9999;
            min-width: 280px;
            max-width: 350px;
        }

        .cart-toast.show {
            transform: translateX(0);
        }

        .cart-toast i {
            font-size: 1.25rem;
        }

        .cart-toast.error {
            background: #dc3545;
        }

        .cart-toast.warning {
            background: #ffc107;
            color: #212529;
        }

        /* Only control containers inside header/footer, not the page content */
        .header-top > .container,
        .header-bottom > .container,
        .footer-dark > .container {
            margin: 0 auto;
            padding: 0 3rem !important;
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 3rem;
        }

        /* Global Heading Margins */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-bottom: 1rem;
        }

        /* Global Paragraph Margins */
        p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        /* ===== HEADER STYLES ===== */
        /* First Header */
        .header-top {
            background: #1a2412 !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
            padding: 0.25rem 0 !important;
            height: 40px !important;
            min-height: 40px !important;
            max-height: 40px !important;
            display: flex !important;
            align-items: center !important;
            position: relative;
            z-index: 1000;
        }

        .header-top .container {
            margin: 0 auto;
            max-width: 1500px;
            padding: 0 3rem !important;
        }

        .header-top-content {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            font-size: 0.75rem !important;
            width: 100% !important;
            height: 100% !important;
            margin: -1rem;
            padding: 0 !important;
        }

        /* Left Column - Aligns with logo in bottom header */
        .header-top-left {
            flex: 0 0 600px;
            display: flex;
            align-items: center;
            height: 100%;
        }

        /* Right Column - Aligns with cart icon in bottom header */
        .header-top-right {
            flex: 0 0 600px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
        }

        .warranty-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .auth-links {
            display: flex;
            align-items: center;
            gap: 0;
            height: 100%;
        }

        .auth-link {
            display: flex;
            align-items: center;
            gap: 0 rem;
            color: var(--white);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.8rem;
            font-weight: 600;
            height: 24px;
            padding: 0.25rem 0.25rem !important;
            margin: 0 !important;
            white-space: nowrap;
        }

        .auth-link:hover {
            color: var(--light-green);
        }

        .auth-divider {
            width: 1.5px;
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            margin: 10px 10px !important;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0;
            height: 100%;
        }

        .header-link {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            color: var(--white);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.8rem;
            font-weight: 600;
            height: 24px;
            padding: 0.25rem 0.25rem !important;
            margin: 0 !important;
            white-space: nowrap;
        }

        .header-link:hover {
            color: var(--light-green);
        }

        /* Second Header */
        .header-bottom {
            padding: 1.5rem 0 !important;
            background: #1a2412 !important;
            height: 70px !important;
            min-height: 70px !important;
            max-height: 70px !important;
            display: flex !important;
            align-items: center !important;
            position: relative;
            z-index: 999;
        }

        .header-bottom .container {
            margin: 0 auto;
            max-width: 100%;
            padding: 0 4rem !important;
        }

        .header-bottom-content {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            height: 100% !important;
            gap: 6.5rem;
            margin: 0;
            padding: 0 !important;
        }

        .logo-section-wrapper {
            flex: 0 0 auto;
            margin-right: auto;
        }

        .logo-link {
            font-size: 2rem;
            font-weight: 700;
            color: var(--white);
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
        }

        .logo-link:hover {
            color: var(--light-green);
        }

        /* Navigation in the center */
        .nav-section-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-nav {
            display: flex;
            align-items: center;
            gap: 2.2rem;
            margin: 0 auto;
        }

        .nav-link {
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            padding: 0.75rem 0;
            font-size: 1.2rem;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--light-green);
        }

        /* Base wrapper so we can draw fancy indicator without breaking layout */
        .nav-link-indicator {
            position: relative;
            overflow: visible;
        }

        /* ACTIVE STATE – soft pill + diamond + glow */
        .nav-link-active {
            color: #AFE1AF !important;
        }

        /* glowing pill behind text */
        .nav-link-active::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 130%;
            height: 2.1rem;
            background: radial-gradient(circle at 0% 0%, rgba(175,225,175,0.55), transparent 55%),
                        radial-gradient(circle at 100% 100%, rgba(175,225,175,0.45), transparent 55%);
            border-radius: 999px;
            opacity: 1;
            filter: blur(0.3px);
            z-index: -1;
        }

        /* small floating diamond under the text */
        .nav-link-active::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: -0.4rem;
            transform: translateX(-50%) rotate(45deg);
            width: 7px;
            height: 7px;
            background: #AFE1AF;
            box-shadow: 0 0 8px rgba(175, 225, 175, 0.8);
            border-radius: 2px;
        }

        .bid-link {
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            padding: 0.75rem 0;
            position: relative;
            font-size: 1.25rem;
            letter-spacing: 0.3px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .bid-link:hover {
            color: var(--light-green);
        }

        /* Search and actions on the right */
        .actions-section {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            margin-left: auto;
            margin-right: auto;
        }

        .search-actions-container {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .search-container {
            width: 240px !important;
        }

        .search-form {
            position: relative;
            display: flex;
        }

        .search-input {
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 25px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--white);
            font-size: 0.85rem;
            outline: none;
            backdrop-filter: blur(10px);
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .search-input:focus {
            background: rgba(255, 255, 255, 0.15) !important;
        }

        .search-btn {
            position: absolute;
            right: 1.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: var(--white);
            border: none;
            border-radius: 50% !important;
            width: 35px;
            height: 35px;
            color: var(--primary-dark);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: #f8f9fa;
            transform: translateY(-50%) scale(1.05);
        }

        /* Cart Icon Only */
        .action-icon {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-left: -30px;
        }

        .action-icon:hover {
            color: var(--light-green);
            transform: translateY(-2px);
        }

        .action-icon i {
            font-size: 1.25rem;
        }

        /* Badge for cart count */
        .action-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--light-green);
            color: var(--white);
            border-radius: 50%;
            min-width: 16px;
            height: 16px;
            padding: 0 5px;
            font-size: 0.65rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            display: none;
        }

        .action-badge:not(:empty) {
            display: flex;
        }

        .action-badge[data-count="0"] {
            display: none !important;
        }

        /* ===== FOOTER STYLES ===== */
        .footer-dark {
            background: var(--primary-dark) !important;
            color: var(--light-bone) !important;
            padding: 3rem 0 1rem !important;
            margin-top: 0 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            width: 100% !important;
            position: relative;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 2rem;
            margin-bottom: 2rem;
            max-width: 1200px;
            margin: 0 auto 2rem auto;
            padding: 0 0.5rem !important;
        }

        .footer-section {
            display: flex;
            flex-direction: column;
        }

        .footer-heading {
            color: var(--white);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .footer-social {
            display: flex;
            gap: 0.75rem;
        }

        /* White background for social icons */
        .social-icon-box {
            background: #ffffff !important;
            border: none !important;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 10px;   /* rounded box */
            overflow: hidden;      /* crop anything outside */
        }

        /* Make logo slightly bigger than the box so any transparent edge is hidden */
        .social-logo {
            width: 100%;
            height: 100%;
            object-fit: cover;     /* fill the box & crop edges if needed */
            display: block;
        }

        /* Hover effect */
        .social-icon-box:hover {
            background: #f1f1f1 !important;     /* Light grey hover */
            transform: translateY(-2px);
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--light-green);
            border-color: var(--light-green);
            color: var(--white);
            transform: translateY(-2px);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .footer-link:hover {
            color: var(--light-green);
            transform: translateX(5px);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .contact-item i {
            color: var(--light-green);
            width: 16px;
        }

        .footer-divider {
            height: 1.5px;
            background: rgba(255, 255, 255, 0.2);
            margin: 2rem 0 1.5rem;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 0.5rem !important;
        }

        .footer-copyright {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .footer-copyright p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            margin: 0;
        }

        .footer-payment {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }

        .payment-methods {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .payment-methods img {
            width: 35px;
            height: 22px;
            display: block;
        }

        .payment-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #ffffff;
            border-radius: 6px;
        }

        .payment-methods i {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.2s ease;
        }

        .payment-methods i:hover {
            color: var(--light-green);
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 968px) {
            .header-top-content {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
                height: auto !important;
            }

            .header-top {
                height: auto !important;
                min-height: 60px !important;
                padding: 0.5rem 0 !important;
            }

            .header-top-left,
            .header-top-right {
                justify-content: center;
                width: 100%;
            }

            .header-bottom-content {
                flex-direction: column;
                gap: 1rem;

            }

            .header-bottom {
                height: auto !important;
                min-height: auto !important;
                padding: 1.5rem 0 !important;
            }

            .nav-section-wrapper {
                order: 3;
                width: 100%;
                margin-top: 1rem;
            }

            .main-nav {
                gap: 1.5rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .search-actions-container {
                gap: 1rem;
            }

            .search-container {
                width: 250px !important;
            }

            .action-icon i {
                font-size: 1.2rem;
            }

            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .header-top .container,
            .header-bottom .container {
                padding: 0 1.5rem !important;
            }
            
            .header-bottom-content {
                flex-direction: column;
                gap: 1.5rem;
            }

            .header-bottom {
                height: auto !important;
                min-height: auto !important;
                padding: 1.5rem 0 !important;
            }

            .nav-section-wrapper {
                order: 3;
                width: 100%;
                margin-top: 1rem;
            }

            .main-nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .search-actions-container {
                width: 100%;
                justify-content: center;
            }

            .search-container {
                width: 100% !important;
                max-width: 300px !important;
            }

            .user-menu {
                gap: 0;
            }

            .warranty-text {
                font-size: 0.8rem;
            }

            .footer-dark {
                padding: 2rem 0 1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-copyright {
                flex-direction: column;
                gap: 1rem;
            }

            .footer-payment {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1.5rem;
            }

            .page-container {
                padding: 0 1.5rem;
            }

            .header-top {
                padding: 0.375rem 0 !important;
            }

            .main-nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.75rem;
            }

            .nav-link {
                font-size: 0.9rem;
            }

            .search-actions-container {
                flex-direction: column;
                gap: 0.75rem;
            }

            .user-menu {
                flex-direction: column;
                gap: 0.75rem;
            }

            .header-link span,
            .auth-link span {
                display: none;
            }

            .footer-social {
                justify-content: center;
            }

            .footer-heading {
                text-align: center;
            }

            .footer-text {
                text-align: center;
            }
        }

        /* ===== UTILITY CLASSES ===== */
        .text-minimal {
            color: var(--light-text);
        }

        .bg-minimal {
            background: var(--light-bone);
        }

        .border-minimal {
            border-color: var(--border-light);
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Focus States for Accessibility */
        .nav-link:focus,
        .action-icon:focus {
            outline: 2px solid var(--light-green);
            outline-offset: 2px;
        }

        /* Smooth transitions */
        .nav-link,
        .social-link,
        .footer-link,
        .action-icon {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== FORCE HEADER CONSISTENCY ACROSS ALL PAGES ===== */
        #app {
            background-color: var(--light-bone);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            background-color: var(--light-bone);
        }

        #app .header-top,
        #app .header-bottom {
            position: relative !important;
            z-index: 1000 !important;
        }

        #app .header-top {
            background: #1a2412 !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
            padding: 0.25rem 0 !important;
            height: 40px !important;
            min-height: 40px !important;
            max-height: 40px !important;
            display: flex !important;
            align-items: center !important;
        }

        #app .header-bottom {
            padding: 1.5rem 0 !important;
            background: #1a2412 !important;
            height: 80px !important;
            min-height: 80px !important;
            max-height: 80px !important;
            display: flex !important;
            align-items: center !important;
        }

        /* Ensure header content stays consistent */
        #app .header-top-content {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            font-size: 0.75rem !important;
            width: 100% !important;
            height: 100% !important;
        }

        #app .header-bottom-content {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            height: 100% !important;
        }

        /* Force consistent search and action icons */
        #app .search-actions-container {
            display: flex !important;
            align-items: center !important;
            gap: 1.5rem !important;
        }

        #app .search-container {
            width: 240px !important;
        }

        #app .search-input {
            border-radius: 25px !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }

        #app .search-btn {
            border-radius: 50% !important;
        }

        #app .action-icon {
            display: flex !important;
            align-items: center !important;
        }

        /* ===== CART PAGE STYLES ===== */
        .cart-page {
            padding: 2rem 0;
            background: var(--light-bone);
            min-height: 70vh;
        }

        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 3rem;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .cart-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .cart-subtitle {
            color: var(--light-text);
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* Cart Content Layout */
        .cart-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            align-items: start;
        }

        /* Cart Items Section */
        .cart-items {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto auto auto;
            gap: 1.5rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--border-light);
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            background: var(--light-bone);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-details {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .cart-item-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-dark);
            text-decoration: none;
            transition: color 0.3s ease;
            line-height: 1.3;
        }

        .cart-item-name:hover {
            color: var(--light-green);
        }

        .cart-item-specs {
            color: var(--light-text);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .cart-item-specs div {
            margin-bottom: 0.25rem;
        }

        .cart-item-sku {
            font-size: 0.8rem;
            color: var(--light-text);
            background: var(--light-bone);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
            font-weight: 500;
        }

        /* Quantity Controls */
        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .quantity-btn {
            width: 36px;
            height: 36px;
            border: 1.5px solid var(--border-light);
            background: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .quantity-btn:hover {
            background: var(--light-bone);
            border-color: var(--light-green);
            transform: translateY(-1px);
        }

        .quantity-input {
            width: 60px;
            height: 36px;
            border: 1.5px solid var(--border-light);
            border-radius: 8px;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--primary-dark);
            background: var(--white);
        }

        .quantity-input:focus {
            outline: none;
            border-color: var(--light-green);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        /* Price Section */
        .cart-item-price {
            text-align: right;
            min-width: 120px;
        }

        .item-price {
            font-size: 0.95rem;
            color: var(--light-text);
            margin-bottom: 0.5rem;
        }

        .item-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-dark);
        }

        /* Remove Button */
        .cart-item-remove {
            background: none;
            border: none;
            color: var(--light-text);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-remove:hover {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
            transform: scale(1.05);
        }

        .cart-item-remove i {
            font-size: 1.1rem;
        }

        /* Cart Summary */
        .cart-summary {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
            position: sticky;
            top: 2rem;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-light);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem 0;
        }

        .summary-label {
            color: var(--light-text);
            font-size: 1rem;
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 1rem;
        }

        .summary-divider {
            height: 1px;
            background: var(--border-light);
            margin: 1.5rem 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-dark);
            padding: 1.5rem 0;
            border-top: 2px solid var(--border-light);
        }

        /* Buttons */
        .checkout-btn {
            width: 100%;
            padding: 1.25rem 2rem;
            background: var(--light-green);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .checkout-btn:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .continue-shopping {
            display: block;
            text-align: center;
            color: var(--light-text);
            text-decoration: none;
            margin-top: 1.5rem;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 0.5rem;
        }

        .continue-shopping:hover {
            color: var(--light-green);
        }

        /* Empty Cart State */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-cart-icon {
            font-size: 5rem;
            color: var(--border-light);
            margin-bottom: 2rem;
            opacity: 0.7;
        }

        .empty-cart-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }

        .empty-cart-text {
            color: var(--light-text);
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .shop-now-btn {
            display: inline-block;
            padding: 1.25rem 2.5rem;
            background: var(--light-green);
            color: var(--white);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .shop-now-btn:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            color: var(--white);
        }

        /* Loading States */
        .cart-item.updating {
            opacity: 0.6;
            pointer-events: none;
        }

        .cart-item.updating .quantity-btn,
        .cart-item.updating .cart-item-remove {
            cursor: not-allowed;
        }

        /* Animation for cart updates */
        @keyframes cartUpdate {
            0% {
                background-color: transparent;
            }
            50% {
                background-color: rgba(76, 175, 80, 0.1);
            }
            100% {
                background-color: transparent;
            }
        }

        .cart-item.updated {
            animation: cartUpdate 0.6s ease;
        }

        /* Cart Page Responsive Design */
        @media (max-width: 1024px) {
            .cart-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .cart-summary {
                position: static;
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .cart-page {
                padding: 1rem 0;
            }

            .cart-container {
                padding: 0 1.5rem;
            }

            .cart-header {
                margin-bottom: 2rem;
            }

            .cart-title {
                font-size: 2rem;
            }

            .cart-items {
                padding: 1.5rem;
            }

            .cart-item {
                grid-template-columns: 80px 1fr auto;
                gap: 1rem;
                padding: 1.25rem 0;
                position: relative;
            }

            .cart-item-image {
                width: 80px;
                height: 80px;
            }

            .cart-item-quantity {
                grid-column: 1 / -1;
                justify-content: center;
                margin-top: 1rem;
                order: 3;
            }

            .cart-item-price {
                text-align: left;
                grid-column: 2;
                grid-row: 1;
                justify-self: end;
            }

            .cart-item-remove {
                position: absolute;
                top: 1rem;
                right: 0;
            }

            .cart-summary {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .cart-container {
                padding: 0 1rem;
            }

            .cart-header {
                margin-bottom: 1.5rem;
            }

            .cart-title {
                font-size: 1.75rem;
            }

            .cart-subtitle {
                font-size: 1rem;
            }

            .cart-items {
                padding: 1rem;
            }

            .cart-item {
                grid-template-columns: 70px 1fr;
                gap: 0.75rem;
                padding: 1rem 0;
            }

            .cart-item-image {
                width: 70px;
                height: 70px;
            }

            .cart-item-name {
                font-size: 1.1rem;
            }

            .cart-item-specs {
                font-size: 0.85rem;
            }

            .empty-cart {
                padding: 3rem 1.5rem;
            }

            .empty-cart-icon {
                font-size: 4rem;
            }

            .empty-cart-title {
                font-size: 1.75;
            }

            .empty-cart-text {
                font-size: 1rem;
            }

            .shop-now-btn {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }

        .action-badge {
            position: absolute !important;
            top: -8px !important;
            right: -8px !important;
            background: var(--light-green) !important;
            color: var(--white) !important;
            border-radius: 50% !important;
            min-width: 18px !important;
            height: 18px !important;
            padding: 0 4px !important;
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            display: none !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            border: 2px solid #1a2412 !important;
            z-index: 1001 !important;
        }

        .action-badge:not(:empty) {
            display: flex !important;
        }

        /* Animation for badge updates */
        @keyframes badgeBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .action-badge.updated {
            animation: badgeBounce 0.3s ease;
        }
        </style>

        @stack('scripts')
        
</body>
</html>