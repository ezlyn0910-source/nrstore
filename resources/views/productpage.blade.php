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
                    <div style="width: 300px;">
                        <input type="text" placeholder="Search products..." style="width: 100%; padding: 0.5rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
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
                            <button type="submit" style="width: 100%; padding: 0.75rem; background: #1f2937; color: white; border: none; border-radius: 0.5rem; margin-top: 1rem;">
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
                                    <p style="font-weight: bold; color: #1f2937; margin-bottom: 0.75rem; font-size: 1rem;">RM{{ number_format($product->price, 2) }}</p>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.4rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem; transition: all 0.2s ease;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Cart
                                        </button>
                                        <button style="flex: 1; background: #1f2937; color: white; padding: 0.4rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; border: none; transition: all 0.2s ease;">Buy Now</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div style="border-top: 1px solid #e5e7eb; margin: 2rem 0;"></div>

                        <!-- Pagination -->
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background: white; transition: all 0.2s ease;">Previous</button>
                            <div style="display: flex; gap: 0.5rem;">
                                @for($i = 1; $i <= 6; $i++)
                                <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background: {{ $i == 1 ? '#1f2937' : 'white' }}; color: {{ $i == 1 ? 'white' : '#1f2937' }}; transition: all 0.2s ease;">{{ $i }}</button>
                                @endfor
                            </div>
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background: white; transition: all 0.2s ease;">Next</button>
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
                                    <button style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.8; display: flex; align-items: center; justify-content: center; gap: 0.125rem; transition: all 0.2s ease;">
                                        <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Cart
                                    </button>
                                    <button style="flex: 1; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.8rem; border: none; transition: all 0.2s ease;">Buy Now</button>
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
    <footer class="footer-minimal">
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
    laptopTypeCheckbox.addEventListener('change', function() {
        toggleSubOptions(laptopTypeCheckbox, laptopSubOptions);
    });

    // Desktop type toggle
    desktopTypeCheckbox.addEventListener('change', function() {
        toggleSubOptions(desktopTypeCheckbox, desktopSubOptions);
    });

    // All-type selection for laptop
    const laptopAllType = document.querySelector('input[name="laptop_type[]"][value="all-type"]');
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

    // All-type selection for desktop
    const desktopAllType = document.querySelector('input[name="desktop_type[]"][value="all-type"]');
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

    // Recommendations slider
    const slider = document.querySelector('[style*="overflow-x: auto"]');
    const prevBtn = document.querySelectorAll('[style*="overflow-x: auto"]').previousElementSibling?.querySelector('button:first-child');
    const nextBtn = document.querySelectorAll('[style*="overflow-x: auto"]').previousElementSibling?.querySelector('button:last-child');
    
    if (prevBtn && nextBtn && slider) {
        prevBtn.addEventListener('click', () => {
            slider.scrollBy({ left: -200, behavior: 'smooth' });
        });
        
        nextBtn.addEventListener('click', () => {
            slider.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }

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
    });

    // Button hover effects
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>

<style>
/* Footer Styles - Matching Minimalist Theme */
.footer-minimal {
    background: var(--light-bone);
    color: var(--dark-text);
    padding: 3rem 0 1rem;
    margin-top: 4rem;
    border-top: 1px solid var(--border-light);
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
    color: var(--primary-dark);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    letter-spacing: -0.5px;
}

.footer-text {
    color: var(--light-text);
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
    background: var(--white);
    border: 1.5px solid var(--border-light);
    border-radius: 8px;
    color: var(--dark-text);
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--primary-green);
    border-color: var(--primary-green);
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
    color: var(--light-text);
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.footer-link:hover {
    color: var(--accent-gold);
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
    color: var(--light-text);
    font-size: 0.9rem;
}

.contact-item i {
    color: var(--accent-gold);
    width: 16px;
}

.footer-divider {
    height: 1px;
    background: var(--border-light);
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
    color: var(--light-text);
    font-size: 0.875rem;
    margin: 0;
}

.footer-payment {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: var(--light-text);
    font-size: 0.875rem;
}

.payment-methods {
    display: flex;
    gap: 0.5rem;
}

.payment-methods i {
    font-size: 1.5rem;
    color: var(--light-text);
    transition: color 0.2s ease;
}

.payment-methods i:hover {
    color: var(--accent-gold);
}

/* Filter Section Enhancements */
input[type="checkbox"] {
    accent-color: #000000; /* Black tick */
}

input[type="checkbox"]:checked {
    background-color: white; /* White background when checked */
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .footer-content {
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .footer-minimal {
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
}
</style>
@endsection