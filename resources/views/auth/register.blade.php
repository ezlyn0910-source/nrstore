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
        @vite(['resources/scss/app.scss', 'resources/css/authlayout.css', 'resources/js/app.js']) 
    </head>

    <body>
        <div class="auth-container">
            <div class="auth-wrapper">
                <div class="auth-left">
                    <div class="auth-image-placeholder">
                        <!-- Add your image URL here later -->
                        <div class="image-placeholder-content">
                            <h2>Join NR Store</h2>
                            <p>Create your account and start shopping</p>
                        </div>
                    </div>
                </div>
                
                <div class="auth-right">
                    <div class="auth-form-container">
                        <div class="auth-header">
                            <h1>{{ __('Create Account') }}</h1>
                            <p>{{ __('Sign up to get started') }}</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="auth-form">
                            @csrf

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">{{ __('First Name') }}</label>
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                        name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus
                                        placeholder="Enter your first name">
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="last_name">{{ __('Last Name') }}</label>
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                        name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                                        placeholder="Enter your last name">
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="Enter your email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- ADD PHONE FIELD HERE -->
                            <div class="form-group">
                                <label for="phone">{{ __('Phone Number') }}</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                                    name="phone" value="{{ old('phone') }}" required autocomplete="phone"
                                    placeholder="Enter your phone number">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group password-group">
                                <label for="password">{{ __('Password') }}</label>
                                <div class="password-input-container">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                        name="password" required autocomplete="new-password"
                                        placeholder="Create a password">
                                    <span class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group password-group">
                                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                <div class="password-input-container">
                                    <input id="password-confirm" type="password" class="form-control" 
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Confirm your password">
                                    <span class="password-toggle" onclick="togglePassword('password-confirm')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="auth-btn">
                                {{ __('Create Account') }}
                            </button>

                            <div class="auth-divider">
                                <span>Already have an account? <a href="{{ route('login') }}" class="login-link">Log in</a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = passwordField.nextElementSibling.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        </script>

        <style>
            :root {
                --pinetree: #1a2412;
                --dustyblue: #5c6b5a;
                --shadowgreen: #1e3525;
                --sagegreen: #3c5a45;
                --accent-gold: #daa112;
                --dark-green: #091a0f;
                --morningblue: #6b7c72;
                --bone: #cad7b1;
                --softyellow: #c9a63d;
                --white: #ffffff;
                --black: #000000;
                --primary-blue: #0a2540;
            }

            /* Enhanced Body Styles */
            body {
                background: linear-gradient(
                    135deg,
                    var(--dark-green) 0%,
                    var(--pinetree) 100%
                );
                font-family: "Nunito", sans-serif;
                color: var(--bone);
                min-height: 100vh;
                margin: 0;
                padding: 0;
                overflow: hidden; /* Prevent body scrolling */
            }

            /* Auth Container */
            .auth-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                box-sizing: border-box;
            }

            .auth-wrapper {
                display: flex;
                max-width: 1100px; /* Reduced max-width */
                width: 100%;
                max-height: 90vh; /* Limit maximum height */
                background: var(--white);
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: 1px solid var(--sagegreen);
            }

            /* Left Side - Image */
            .auth-left {
                flex: 1;
                background: linear-gradient(
                    135deg,
                    var(--shadowgreen) 0%,
                    var(--pinetree) 100%
                );
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .auth-image-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(45deg, var(--sagegreen) 0%, transparent 100%);
                position: relative;
                padding: 30px; /* Reduced padding */
            }

            .auth-image-placeholder::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80")
                    center/cover;
                opacity: 0.1;
            }

            .image-placeholder-content {
                text-align: center;
                z-index: 2;
                position: relative;
                padding: 20px; /* Reduced padding */
            }

            .image-placeholder-content h2 {
                font-size: 2rem; /* Reduced font size */
                font-weight: 700;
                margin-bottom: 0.75rem;
                color: var(--bone);
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .image-placeholder-content p {
                font-size: 1rem; /* Reduced font size */
                color: var(--morningblue);
                font-weight: 500;
            }

            /* Right Side - Form */
            .auth-right {
                flex: 1;
                padding: 40px 50px;
                display: flex;
                align-items: flex-start; /* Changed from center to flex-start */
                justify-content: center;
                background: var(--white);
                overflow-y: auto;
                max-height: 90vh;
            }

            .auth-form-container {
                width: 100%;
                max-width: 400px;
                margin-top: 20px; /* Added top margin to push content down */
            }

            .auth-header {
                text-align: center;
                margin-bottom: 30px;
                padding-top: 10px; /* Added padding to ensure visibility */
            }

            .auth-header h1 {
                font-size: 2rem; /* Reduced font size */
                font-weight: 700;
                color: var(--pinetree);
                margin-bottom: 0.5rem;
            }

            .auth-header p {
                color: var(--morningblue);
                font-size: 1rem;
            }

            /* Form Styles */
            .auth-form {
                width: 100%;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: var(--pinetree);
                font-size: 0.9rem; /* Slightly smaller */
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .form-control {
                width: 100%;
                padding: 0.875rem 1.25rem;
                border: 2px solid var(--dustyblue);
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.9);
                color: var(--pinetree);
                font-size: 0.95rem;
                transition: all 0.3s ease;
                box-sizing: border-box;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--sagegreen);
                box-shadow: 0 0 0 3px rgba(0, 80, 55, 0.327);
                background: var(--white);
                color: var(--pinetree);
            }

            .form-control::placeholder {
                color: var(--morningblue);
            }

            /* Form Options */
            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem; /* Reduced margin */
            }

            .remember-me {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .remember-me input[type="checkbox"] {
                width: 16px; /* Slightly smaller */
                height: 16px;
                accent-color: var(--accent-gold);
            }

            .remember-me label {
                margin: 0;
                color: var(--pinetree);
                font-size: 0.85rem; /* Slightly smaller */
            }

            .forgot-password {
                color: var(--black);
                text-decoration: none;
                font-size: 0.85rem; /* Slightly smaller */
                font-weight: 500;
                transition: color 0.3s ease;
            }

            .forgot-password:hover {
                color: var(--softyellow);
                text-decoration: underline;
            }

            /* Password Input Styles */
            .password-group {
                position: relative;
            }

            .password-input-container {
                position: relative;
                display: flex;
                align-items: center;
            }

            .password-input-container .form-control {
                padding-right: 3rem; /* Make space for the eye icon */
            }

            .password-toggle {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: var(--morningblue);
                transition: color 0.3s ease;
                z-index: 3;
                background: transparent;
                border: none;
                padding: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .password-toggle:hover {
                color: var(--accent-gold);
            }

            .password-toggle i {
                font-size: 1rem;
                width: 16px;
                height: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Buttons */
            .auth-btn {
                width: 100%;
                padding: 0.875rem 2rem;
                background: var(--black); /* Changed to black */
                color: var(--white); /* Changed to white text for contrast */
                border: none;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 1rem;
                margin-bottom: 1.25rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Updated shadow color */
            }

            .auth-btn:hover {
                background: var(--sagegreen); /* Changed to sagegreen on hover */
                color: var(--white); /* Keep white text on hover */
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(60, 90, 69, 0.4);
            }

            /* Divider */
            .auth-divider {
                text-align: center;
                margin: 1.5rem 0; /* Reduced margin */
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .auth-divider::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 1px;
                background: var(--dustyblue);
                z-index: 1;
            }

            .auth-divider span {
                background: var(--white);
                padding: 0 0.7rem;
                color: var(--morningblue);
                font-size: 0.85rem;
                position: relative;
                z-index: 2;
            }

            .login-link {
                color: var(--accent-gold);
                font-weight: 700;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .login-link:hover {
                color: var(--softyellow);
                text-decoration: underline;
            }

            /* Alternative Button */
            .auth-alt-btn {
                display: block;
                width: 100%;
                padding: 0.875rem 2rem; /* Reduced padding */
                background: transparent;
                color: var(--pinetree);
                border: 2px solid var(--sagegreen);
                border-radius: 12px;
                font-size: 1rem; /* Reduced font size */
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .auth-alt-btn:hover {
                background: var(--sagegreen);
                border-color: var(--accent-gold);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(60, 90, 69, 0.4);
                color: var(--bone);
            }

            /* Error States */
            .invalid-feedback {
                display: block;
                margin-top: 0.5rem;
                color: #ff6b6b;
                font-size: 0.8rem; /* Slightly smaller */
            }

            .form-control.is-invalid {
                border-color: #ff6b6b;
                box-shadow: 0 0 0 2px rgba(255, 107, 107, 0.1);
            }

            /* Responsive Design */
            @media (max-width: 968px) {
                .auth-wrapper {
                    flex-direction: column;
                    max-width: 450px;
                    max-height: 95vh;
                }

                .auth-left {
                    min-height: 150px; /* Reduced min-height */
                    padding: 20px;
                }

                .auth-right {
                    padding: 30px 25px; /* Reduced padding */
                }

                .image-placeholder-content h2 {
                    font-size: 1.75rem;
                }

                .image-placeholder-content p {
                    font-size: 0.9rem;
                }

                .auth-header h1 {
                    font-size: 1.75rem;
                }
            }

            @media (max-width: 480px) {
                body {
                    overflow: auto; /* Allow scrolling on very small screens */
                }

                .auth-container {
                    padding: 10px;
                }

                .auth-wrapper {
                    max-height: none;
                    border-radius: 15px;
                }

                .auth-right {
                    padding: 25px 20px;
                }

                .auth-header h1 {
                    font-size: 1.6rem;
                }

                .form-row {
                    grid-template-columns: 1fr;
                    gap: 1rem;
                }

                .form-options {
                    flex-direction: column;
                    gap: 1rem;
                    align-items: flex-start;
                }

                .image-placeholder-content {
                    padding: 15px;
                }

                .image-placeholder-content h2 {
                    font-size: 1.5rem;
                }
            }

            /* Animation for smooth transitions */
            .auth-btn,
            .auth-alt-btn,
            .form-control {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Loading animation for subtle effects */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .auth-form-container {
                animation: fadeInUp 0.6s ease-out;
            }
        </style>
    </body>
</html>