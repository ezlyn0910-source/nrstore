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
    @vite(['resources/sass/app.scss', 'resources/css/home.css', 'resources/css/homepage.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body>
    <div id="app">
        @if (!request()->is('login') && !request()->is('register'))
        <!-- New Two-Header Design -->
        <header class="main-header">
            <!-- First Header -->
            <div class="header-top">
                <div class="container">
                    <div class="header-top-content">
                        <!-- Left Section -->
                        <div class="header-left">
                            <div class="language-dropdown">
                                <button class="language-btn">
                                    English <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-content">
                                    <a href="#" class="dropdown-item">English</a>
                                    <a href="#" class="dropdown-item">Malay</a>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <span class="warranty-text">Warranty 3 Months for Every Product Purchase</span>
                        </div>

                        <!-- Right Section -->
                        <div class="header-right">
                            @guest
                                @if (Route::has('login') && Route::has('register'))
                                    <div class="auth-links">
                                        <a href="{{ route('register') }}" class="auth-link">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Sign Up</span>
                                        </a>
                                        <div class="auth-divider"></div>
                                        <a href="{{ route('login') }}" class="auth-link">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span>Login</span>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="user-menu">
                                    <a href="#" class="header-link">
                                        <i class="fas fa-user"></i>
                                        <span>My Account</span>
                                    </a>
                                    <a href="#" class="header-link">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Cart</span>
                                    </a>
                                    <a href="#" class="header-link">
                                        <i class="fas fa-heart"></i>
                                        <span>Favorites</span>
                                    </a>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Header -->
            <div class="header-bottom">
                <div class="container">
                    <div class="header-bottom-content">
                        <!-- Left Section -->
                        <div class="header-left">
                            <div class="logo">
                                <a href="{{ url('/') }}" class="logo-link">NRStore</a>
                            </div>
                            <nav class="main-nav">
                                <a href="{{ url('/') }}" class="nav-link">Home</a>
                                <a href="{{ url('/products') }}" class="nav-link">Products</a>
                                <a href="{{ url('/orders') }}" class="nav-link">Order</a>
                                <a href="{{ url('/bid') }}" class="nav-link bid-link">
                                    <i class="fas fa-gavel"></i>
                                    Bid
                                </a>
                            </nav>
                        </div>

                        <!-- Right Section -->
                        <div class="header-right">
                            <div class="search-container">
                                <form action="{{ url('/search') }}" method="GET" class="search-form">
                                    <input type="text" name="q" placeholder="Search products..." class="search-input">
                                    <button type="submit" class="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        @endif

        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>