@extends('layouts.app')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/productpage.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="product-page">

<div class="product-page">
    <!-- Hero Section with Overlap -->
    <section class="hero-section" style="position: relative; height: 450px; background-color: #1f2937; overflow: hidden; margin-bottom: 0;">
        <img src="{{ asset('storage/images/productbanner.png') }}" alt="Products Banner" 
            style="width: 100%; height: 100%; object-fit: cover; opacity: 1;">
        <div style="position: absolute; bottom: -14px; left: 0; right: 0; text-align: center;">
            <h1 style="font-size: 18rem; font-weight: bold; color: white; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7); margin: 0;">
                Product
            </h1>
        </div>
    </section>

    <!-- White Box Container with Overlap -->
    <section style="padding: 0; margin-top: -100px; position: relative; z-index: 10;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <div style="background: white; border-radius: 1.5rem; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); padding: 3rem 2rem 2rem; border: 1px solid #e5e7eb;">
                
                <!-- Header Row -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h2 style="font-size: 1.75rem; font-weight: 600; color: #1f2937;">Give All You Need</h2>
                    <div style="width: 400px; position: relative;">
                        <form method="GET" action="{{ url('/products') }}" style="display: flex; position: relative;">
                            <div style="position: relative; flex: 1;">
                                <!-- Search Icon -->
                                <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); z-index: 10;">
                                    <svg style="width: 1rem; height: 1rem; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <!-- Search Input -->
                                <input type="text" name="search" placeholder="Search products..." 
                                    value="{{ request('search') }}"
                                    style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; outline: none; transition: all 0.2s ease;">
                            </div>
                            <!-- Search Button - Overlapped -->
                            <button type="submit" style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 0.6rem 1.25rem; background: #1f2937; color: white; border: none; border-radius: 2rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease; z-index: 5;">
                                Search
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div style="display: flex; gap: 2rem;">
                    <!-- Filters Sidebar -->
                    <div style="width: 25%;">
                        <form method="GET" action="{{ url('/products') }}">
                            <!-- Brand Filter -->
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Brand</h3>
                                <select name="brand" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
                                    <option value="">All Brands</option>
                                    @foreach($brandsList as $brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Filter -->
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Price</h3>
                                <select name="sort" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
                                    <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default Sorting</option>
                                    <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>

                            <!-- Type Filter -->
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Type</h3>
                                
                                <!-- Laptop Type -->
                                <div style="margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <input type="checkbox" id="laptop-type" name="laptop_type_main" value="laptop">
                                        <label for="laptop-type" style="font-weight: 500; color: #374151; font-size: 0.875rem;">Laptop Type</label>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.4rem; margin-left: 1.5rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="all-type" disabled>
                                            <span style="color: #6b7280;">All Type</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="2-in-1" disabled>
                                            <span style="color: #6b7280;">2-in-1</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="notebook" disabled>
                                            <span style="color: #6b7280;">Notebook</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="ultrabook" disabled>
                                            <span style="color: #6b7280;">Ultrabook</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="gaming-laptop" disabled>
                                            <span style="color: #6b7280;">Gaming Laptop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="mobile-workstation" disabled>
                                            <span style="color: #6b7280;">Mobile Workstation</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="business-laptop" disabled>
                                            <span style="color: #6b7280;">Business Laptop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="student-laptop" disabled>
                                            <span style="color: #6b7280;">Student Laptop</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Desktop Type -->
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <input type="checkbox" id="desktop-type" name="desktop_type_main" value="desktop">
                                        <label for="desktop-type" style="font-weight: 500; color: #374151; font-size: 0.875rem;">Desktop Type</label>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.4rem; margin-left: 1.5rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="all-type" disabled>
                                            <span style="color: #6b7280;">All Type</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="aio" disabled>
                                            <span style="color: #6b7280;">All-in-One (AIO) Desktop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="gaming-desktop" disabled>
                                            <span style="color: #6b7280;">Gaming Desktop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="workstation-desktop" disabled>
                                            <span style="color: #6b7280;">Workstation Desktop</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" style="width: 100%; padding: 0.75rem; background: #1f2937; color: white; border: none; border-radius: 2rem; margin-top: 1rem;">
                                Apply Filters
                            </button>
                        </form>
                    </div>

                    <!-- Products Main -->
                    <div style="width: 75%;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                            @foreach($products as $product)                       
                            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; transition: all 0.3s ease; padding: 0; margin: 0;" class="product-card">
                                <div style="width: 100%; height: 150px; background-color: #f3f4f6; overflow: hidden; margin: 0; padding: 0; border-radius: 0.5rem 0.5rem 0 0;">
                                    <img src="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"    
                                        alt="{{ $product->name }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; margin: 0; padding: 0; display: block; border-radius: 0.5rem 0.5rem 0 0;">
                                </div>
                                <div style="padding: 0.75rem;">
                                    <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 0.875rem; line-height: 1.25;">{{ $product->name }}</h3>
                                    
                                        <!-- Product Specs with Price -->
                                        <div style="margin-bottom: 0.75rem;">
                                            @if($product->processor)
                                            <p style="color: #6b7280; font-size: 0.75rem; margin-bottom: 0.125rem;">{{ $product->processor }}</p>
                                            @endif
                                            @if($product->ram && $product->storage)
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <p style="color: #6b7280; font-size: 0.75rem; margin: 0;">{{ $product->ram }} • {{ $product->storage }}</p>
                                                <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">RM{{ number_format($product->price, 2) }}</p>
                                            </div>
                                            @elseif($product->ram)
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <p style="color: #6b7280; font-size: 0.75rem; margin: 0;">{{ $product->ram }}</p>
                                                <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">RM{{ number_format($product->price, 2) }}</p>
                                            </div>
                                            @elseif($product->storage)
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <p style="color: #6b7280; font-size: 0.75rem; margin: 0;">{{ $product->storage }}</p>
                                                <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">RM{{ number_format($product->price, 2) }}</p>
                                            </div>
                                            @else
                                            <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">RM{{ number_format($product->price, 2) }}</p>
                                            @endif
                                        </div>
                                    
                                        <!-- Buttons Row -->
                                        <div style="display: flex; gap: 0.5rem;">
                                            <button style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.4rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem; transition: all 0.2s ease;">
                                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                Cart
                                            </button>
                                            <button style="flex: 1; background: #1f2937; color: white; padding: 0.4rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; border: none; transition: all 0.2s ease;">Buy Now</button>
                                        </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                        <div style="border-top: 1px solid #e5e7eb; margin: 2rem 0;"></div>

                        <!-- Pagination -->
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: white; transition: all 0.2s ease;">Previous</button>
                            <div style="display: flex; gap: 0.5rem;">
                                @for($i = 1; $i <= 6; $i++)
                                <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: {{ $i == 1 ? '#1f2937' : 'white' }}; color: {{ $i == 1 ? 'white' : '#1f2937' }}; transition: all 0.2s ease;">{{ $i }}</button>
                                @endfor
                            </div>
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: white; transition: all 0.2s ease;">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h2 style="font-size: 1.5rem; font-weight: bold; color: #1f2937;">Explore our recommendations</h2>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 50%; background: white; transition: all 0.2s ease;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 50%; background: white; transition: all 0.2s ease;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; overflow-x: auto; gap: 1rem; padding-bottom: 1rem; scrollbar-width: none;">
                        @foreach($recommendedProducts as $product)
                        <div style="flex: 0 0 auto; width: 300px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; transition: all 0.3s ease;">
                            <div style="width: 100%; height: 200px; background-color: #f3f4f6; overflow: hidden; margin: 0; padding: 0;">
                                <img src="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"    
                                    alt="{{ $product->name }}" 
                                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; margin: 0; padding: 0; display: block;">
                            </div>
                            <div style="padding: 0.75rem;">
                                <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 1rem;">{{ $product->name }}</h3>
                                <p style="font-weight: bold; color: #1f2937; margin-bottom: 0.5rem; font-size: 1rem;">RM{{ number_format($product->price, 2) }}</p>
                                <div style="display: flex; gap: 0.25rem;">
                                    <button style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.25rem 0.5rem; border-radius: 2rem; font-size: 0.8; display: flex; align-items: center; justify-content: center; gap: 0.125rem; transition: all 0.2s ease;">
                                        <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Cart
                                    </button>
                                    <button style="flex: 1; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 2rem; font-size: 0.8rem; border: none; transition: all 0.2s ease;">Buy Now</button>
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
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Type filter functionality
    const laptopTypeCheckbox = document.getElementById('laptop-type');
    const desktopTypeCheckbox = document.getElementById('desktop-type');
    const laptopSubOptions = document.querySelectorAll('input[name="laptop_type[]"]');
    const desktopSubOptions = document.querySelectorAll('input[name="desktop_type[]"]');

    // Enable/disable sub-options based on main type selection
    function toggleSubOptions(mainCheckbox, subOptions) {
        if (mainCheckbox.checked) {
            subOptions.forEach(option => {
                option.disabled = false;
                option.parentElement.style.color = '#374151';
            });
        } else {
            subOptions.forEach(option => {
                option.disabled = true;
                option.checked = false;
                option.parentElement.style.color = '#6b7280';
            });
        }
    }

    // Laptop type toggle
    if (laptopTypeCheckbox) {
        laptopTypeCheckbox.addEventListener('change', function() {
            toggleSubOptions(laptopTypeCheckbox, laptopSubOptions);
        });
    }

    // Desktop type toggle
    if (desktopTypeCheckbox) {
        desktopTypeCheckbox.addEventListener('change', function() {
            toggleSubOptions(desktopTypeCheckbox, desktopSubOptions);
        });
    }

    // All-type selection for laptop
    const laptopAllType = document.querySelector('input[name="laptop_type[]"][value="all-type"]');
    if (laptopAllType) {
        laptopAllType.addEventListener('change', function() {
            if (this.checked) {
                laptopSubOptions.forEach(option => {
                    if (option !== laptopAllType) {
                        option.checked = true;
                    }
                });
            } else {
                laptopSubOptions.forEach(option => {
                    if (option !== laptopAllType) {
                        option.checked = false;
                    }
                });
            }
        });
    }

    // All-type selection for desktop
    const desktopAllType = document.querySelector('input[name="desktop_type[]"][value="all-type"]');
    if (desktopAllType) {
        desktopAllType.addEventListener('change', function() {
            if (this.checked) {
                desktopSubOptions.forEach(option => {
                    if (option !== desktopAllType) {
                        option.checked = true;
                    }
                });
            } else {
                desktopSubOptions.forEach(option => {
                    if (option !== desktopAllType) {
                        option.checked = false;
                    }
                });
            }
        });
    }

    // FIXED: Recommendations slider functionality
    function initRecommendationSlider() {
        const recommendationsSection = document.querySelector('[style*="margin-top: 3rem"]');
        if (!recommendationsSection) return;

        const slider = recommendationsSection.querySelector('[style*="overflow-x: auto"]');
        const prevBtn = recommendationsSection.querySelector('button:first-child');
        const nextBtn = recommendationsSection.querySelector('button:last-child');

        if (slider && prevBtn && nextBtn) {
            // Remove any existing event listeners
            const newPrevBtn = prevBtn.cloneNode(true);
            const newNextBtn = nextBtn.cloneNode(true);
            prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
            nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);

            // Add new event listeners
            newPrevBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -320, behavior: 'smooth' });
            });
            
            newNextBtn.addEventListener('click', () => {
                slider.scrollBy({ left: 320, behavior: 'smooth' });
            });

            // Add hover effects to slider buttons
            [newPrevBtn, newNextBtn].forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
                    this.style.borderColor = '#1f2937';
                    this.style.background = '#1f2937';
                    this.style.color = 'white';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                    this.style.borderColor = '#d1d5db';
                    this.style.background = 'white';
                    this.style.color = 'currentColor';
                });
            });
        }
    }

    // Initialize recommendation slider
    initRecommendationSlider();

    // Product card hover effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
            const img = card.querySelector('img');
            if (img) img.style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'none';
            const img = card.querySelector('img');
            if (img) img.style.transform = 'scale(1)';
        });

        // Add click event to product cards for popup
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                return;
            }
            
            const productName = this.querySelector('h3').textContent;
            const priceElement = this.querySelector('p[style*="font-weight: bold"]');
            const price = priceElement ? priceElement.textContent.replace('RM', '') : '0.00';
            const image = this.querySelector('img').src;
            
            // Get all specs properly
            let processor = '';
            let ram = '';
            let storage = '';
            
            // Find the specs container
            const specsContainer = this.querySelector('div[style*="margin-bottom: 0.75rem"]');
            if (specsContainer) {
                // Get processor (first line)
                const processorElement = specsContainer.querySelector('p[style*="color: #6b7280"][style*="margin-bottom: 0.125rem"]');
                if (processorElement) {
                    processor = processorElement.textContent.trim();
                }
                
                // Get RAM and storage (second line)
                const ramStorageElement = specsContainer.querySelector('p[style*="color: #6b7280"]:not([style*="margin-bottom: 0.125rem"])');
                if (ramStorageElement) {
                    const ramStorageText = ramStorageElement.textContent.trim();
                    if (ramStorageText.includes('•')) {
                        const parts = ramStorageText.split('•');
                        ram = parts[0].trim();
                        storage = parts[1].trim();
                    } else {
                        ram = ramStorageText;
                    }
                }
            }
            
            const product = {
                name: productName,
                price: price,
                image: image,
                processor: processor,
                ram: ram,
                storage: storage,
                brand: productName.split(' ')[0] || 'Brand',
                description: 'High-performance device designed for professionals and enthusiasts alike.'
            };
            
            showProductPopup(product);
        });
    });

    // Button hover effects
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        // Skip recommendation slider buttons as they have their own handlers
        if (!button.closest('[style*="margin-top: 3rem"]')) {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        }
    });

    // Add click events to recommendation product cards
    const recommendedProducts = document.querySelectorAll('[style*="overflow-x: auto"] .product-card');
    recommendedProducts.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                return;
            }
            
            const productName = this.querySelector('h3').textContent;
            const priceElement = this.querySelector('p[style*="font-weight: bold"]');
            const price = priceElement ? priceElement.textContent.replace('RM', '') : '0.00';
            const image = this.querySelector('img').src;
            
            const product = {
                name: productName,
                price: price,
                image: image,
                processor: '',
                ram: '',
                storage: '',
                brand: productName.split(' ')[0] || 'Brand',
                description: 'Recommended product with excellent performance and features.'
            };
            
            showProductPopup(product);
        });
    });
});

// Popup functionality
function showProductPopup(product) {
    // Remove any existing popup
    const existingPopup = document.querySelector('.popup-overlay');
    if (existingPopup) {
        existingPopup.remove();
    }

    const popup = document.createElement('div');
    popup.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        backdrop-filter: blur(5px);
    `;

    popup.innerHTML = `
        <div style="
            background: white;
            border-radius: 1.5rem;
            width: 900px;
            max-width: 95vw;
            height: 550px;
            max-height: 90vh;
            display: flex;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        ">
            <!-- Close Button -->
            <button onclick="this.closest('.popup-overlay').remove()" style="
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: #1f2937;
                color: white;
                border: none;
                width: 2.5rem;
                height: 2.5rem;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                font-weight: bold;
                cursor: pointer;
                z-index: 10;
                transition: all 0.2s ease;
            ">×</button>

            <!-- Left Side - Product Image -->
            <div style="
                flex: 1;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
                padding: 2rem;
            ">
                <!-- Brand Logo Background -->
                <div style="
                    position: absolute;
                    font-size: 6rem;
                    font-weight: 900;
                    color: rgba(0, 0, 0, 0.03);
                    transform: rotate(-45deg);
                    white-space: nowrap;
                    user-select: none;
                ">${product.brand}</div>
                
                <!-- Product Image -->
                <img src="${product.image}" alt="${product.name}" style="
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    filter: drop-shadow(20px 20px 30px rgba(0, 0, 0, 0.2));
                    position: relative;
                    z-index: 2;
                ">
            </div>

            <!-- Right Side - Product Details -->
            <div style="
                flex: 1;
                padding: 2.5rem 2rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            ">
                <!-- Product Info -->
                <div>
                    <!-- Product Name -->
                    <h2 style="
                        font-size: 1.75rem;
                        font-weight: bold;
                        color: #1f2937;
                        margin: 0 0 1.5rem 0;
                        line-height: 1.3;
                    ">${product.name}</h2>

                    <!-- All Specs Display -->
                    <div style="margin-bottom: 2rem;">
                        ${product.processor ? `
                        <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                            <div style="width: 4px; height: 4px; background: #1f2937; border-radius: 50%; margin-right: 0.75rem;"></div>
                            <span style="color: #1f2937; font-size: 0.95rem;">${product.processor}</span>
                        </div>
                        ` : ''}
                        
                        ${product.ram ? `
                        <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                            <div style="width: 4px; height: 4px; background: #1f2937; border-radius: 50%; margin-right: 0.75rem;"></div>
                            <span style="color: #1f2937; font-size: 0.95rem;">${product.ram}</span>
                        </div>
                        ` : ''}
                        
                        ${product.storage ? `
                        <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                            <div style="width: 4px; height: 4px; background: #1f2937; border-radius: 50%; margin-right: 0.75rem;"></div>
                            <span style="color: #1f2937; font-size: 0.95rem;">${product.storage}</span>
                        </div>
                        ` : ''}
                    </div>

                    <!-- Price -->
                    <p style="
                        font-size: 2.25rem;
                        color: #1f2937;
                        margin: 0 0 1.5rem 0;
                        font-weight: bold;
                    ">RM${parseFloat(product.price).toFixed(2)}</p>

                    <!-- Description -->
                    <p style="
                        color: #6b7280;
                        line-height: 1.6;
                        margin: 0;
                        font-size: 0.95rem;
                    ">${product.description}</p>
                </div>

                <!-- Buttons - Fixed Position -->
                <div style="display: flex; gap: 1rem; margin-top: auto;">
                    <button style="
                        flex: 1;
                        border: 1px solid #1f2937;
                        background: white;
                        color: #1f2937;
                        padding: 0.875rem 1.5rem;
                        border-radius: 2rem;
                        font-size: 1rem;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.5rem;
                        transition: all 0.2s ease;
                        cursor: pointer;
                        font-weight: 500;
                    ">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Add to Cart
                    </button>
                    <button style="
                        flex: 1;
                        background: #1f2937;
                        color: white;
                        padding: 0.875rem 1.5rem;
                        border-radius: 2rem;
                        font-size: 1rem;
                        border: none;
                        transition: all 0.2s ease;
                        cursor: pointer;
                        font-weight: 500;
                    ">Buy Now</button>
                </div>
            </div>
        </div>
    `;

    popup.className = 'popup-overlay';
    document.body.appendChild(popup);

    // Add hover effects to popup buttons
    const popupButtons = popup.querySelectorAll('button');
    popupButtons.forEach(button => {
        if (button.textContent !== '×') {
            button.addEventListener('mouseenter', function() {
                if (this.style.background === 'white' || this.style.background.includes('white')) {
                    this.style.background = '#f8fafc';
                    this.style.borderColor = '#374151';
                } else {
                    this.style.background = '#374151';
                }
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.15)';
            });
            
            button.addEventListener('mouseleave', function() {
                if (this.style.background === 'rgb(248, 250, 252)' || this.style.background.includes('f8fafc')) {
                    this.style.background = 'white';
                    this.style.borderColor = '#1f2937';
                } else {
                    this.style.background = '#1f2937';
                }
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        }
    });
}
</script>

<style>
/* CSS Variables */
:root {
    --light-bone: #f8f9fa;
    --dark-text: #1f2937;
    --light-text: #6b7280;
    --primary-dark: #1f2937;
    --primary-green: #10b981;
    --accent-gold: #d97706;
    --border-light: #e5e7eb;
    --white: #ffffff;
}

/* Footer Styles - Matching Minimalist Theme */
.footer-dark {
    background: #1a1a1a;
    color: #e5e7eb;
    padding: 3rem 0 1rem;
    margin-top: 4rem;
    border-top: 1px solid #374151;
}

.footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-section {
    display: flex;
    flex-direction: column;
}

.footer-heading {
    color: #ffffff;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    letter-spacing: -0.5px;
}

.footer-text {
    color: #9ca3af;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.footer-social {
    display: flex;
    gap: 0.75rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #374151;
    border: 1.5px solid #4b5563;
    border-radius: 8px;
    color: #e5e7eb;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #10b981;
    border-color: #10b981;
    color: #ffffff;
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
    color: #9ca3af;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.footer-link:hover {
    color: #10b981;
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
    color: #9ca3af;
    font-size: 0.9rem;
}

.contact-item i {
    color: #10b981;
    width: 16px;
}

.footer-divider {
    height: 1px;
    background: #374151;
    margin: 2rem 0 1.5rem;
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.footer-copyright {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.footer-copyright p {
    color: #9ca3af;
    font-size: 0.875rem;
    margin: 0;
}

.footer-payment {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #9ca3af;
    font-size: 0.875rem;
}

.payment-methods {
    display: flex;
    gap: 0.5rem;
}

.payment-methods i {
    font-size: 1.5rem;
    color: #9ca3af;
    transition: color 0.2s ease;
}

.payment-methods i:hover {
    color: #10b981;
}

/* Filter Section Enhancements */
input[type="checkbox"] {
    accent-color: #000000; /* Black tick */
}

input[type="checkbox"]:checked {
    background-color: white; /* White background when checked */
}

/* Search Input Styles */
.search-container {
    width: 400px;
    position: relative;
}

.search-form {
    display: flex;
    position: relative;
}

.search-input-container {
    position: relative;
    flex: 1;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
}

.search-icon svg {
    width: 1rem;
    height: 1rem;
    color: #6b7280;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    outline: none;
    transition: all 0.2s ease;
}

.search-input:focus {
    border-color: #1f2937;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

.search-button {
    position: absolute;
    right: 4px;
    top: 50%;
    transform: translateY(-50%);
    padding: 0.6rem 1.25rem;
    background: #1f2937;
    color: white;
    border: none;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 5;
}

.search-button:hover {
    background: #374151;
    transform: translateY(-50%) scale(1.02);
}

/* Product Card Hover Effects */
.product-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.product-card:hover img {
    transform: scale(1.05);
}

/* Button Hover Effects */
button:not(.search-button):not(.social-link) {
    transition: all 0.2s ease;
}

button:not(.search-button):not(.social-link):hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Recommendation Slider Button Styles */
.recommendation-nav-btn {
    transition: all 0.2s ease;
}

.recommendation-nav-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-color: #1f2937;
    background: #1f2937;
    color: white;
}

/* Popup Overlay Styles */
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(5px);
}

/* Filter Section Styles */
.filter-section {
    margin-bottom: 1.5rem;
}

.filter-heading {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    text-transform: uppercase;
}

.filter-select {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.2s ease;
}

.filter-select:focus {
    border-color: #1f2937;
}

/* Type Filter Options */
.type-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.type-option label {
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
}

.sub-options {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    margin-left: 1.5rem;
}

.sub-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
}

.sub-option:not(.enabled) {
    color: #6b7280;
}

.sub-option.enabled {
    color: #374151;
}

/* Apply Filters Button */
.apply-filters {
    width: 100%;
    padding: 0.75rem;
    background: #1f2937;
    color: white;
    border: none;
    border-radius: 0.5rem;
    margin-top: 1rem;
    transition: all 0.2s ease;
}

.apply-filters:hover {
    background: #374151;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .footer-content {
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    .search-container {
        width: 350px;
    }
}

@media (max-width: 768px) {
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

    /* Adjust overlap for mobile */
    .hero-section {
        height: 300px;
    }

    .hero-section h1 {
        font-size: 6rem !important;
    }

    section[style*="margin-top: -100px"] {
        margin-top: -60px !important;
    }
    
    .search-container {
        width: 300px;
    }
    
    /* Stack filters and products on mobile */
    .product-layout {
        flex-direction: column;
    }
    
    .filters-sidebar {
        width: 100% !important;
        margin-bottom: 2rem;
    }
    
    .products-main {
        width: 100% !important;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 576px) {
    .footer-social {
        justify-content: center;
    }
    
    .footer-heading {
        text-align: center;
    }
    
    .footer-text {
        text-align: center;
    }

    .hero-section h1 {
        font-size: 4rem !important;
    }

    section[style*="margin-top: -100px"] {
        margin-top: -40px !important;
    }
    
    .search-container {
        width: 100%;
        max-width: 280px;
    }
    
    .products-grid {
        grid-template-columns: 1fr !important;
    }
    
    .header-row {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .header-row h2 {
        margin-bottom: 0;
    }
}

/* Hero Section Adjustments */
.hero-section {
    position: relative;
    height: 450px;
    background-color: #1f2937;
    overflow: hidden;
    margin-bottom: 0;
}

.hero-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 1;
}

.hero-title {
    position: absolute;
    bottom: -14px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 18rem;
    font-weight: bold;
    color: white;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
    margin: 0;
}

/* Main Content Container */
.main-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.content-box {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    padding: 3rem 2rem 2rem;
    border: 1px solid #e5e7eb;
    margin-top: -100px;
    position: relative;
    z-index: 10;
}

/* Smooth transitions for all interactive elements */
* {
    transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
}

/* Focus styles for accessibility */
button:focus,
input:focus,
select:focus {
    outline: 2px solid #1f2937;
    outline-offset: 2px;
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection