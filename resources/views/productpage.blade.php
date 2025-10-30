@extends('layouts.app')

@section('content')
<div class="product-page">
    <!-- Hero Section -->
    <section class="hero-section" style="position: relative; height: 400px; background-color: #1f2937; overflow: hidden; margin-bottom: 2rem;">
        <img src="{{ asset('storage/images/productbanner.png') }}" alt="Products Banner" 
            style="width: 100%; height: 100%; object-fit: cover; opacity: 1;">
        <div style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center;">
            <h1 style="font-size: 10rem; font-weight: bold; color: white; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7); margin: 0;">
                Product
            </h1>
        </div>
    </section>

    <!-- White Box Container -->
    <section style="padding: 2rem 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <div style="background: white; border-radius: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); padding: 2rem;">
                
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
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Category</h3>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                @foreach($categories as $category)
                                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                    <input type="checkbox" name="category" value="{{ $category->slug }}">
                                    <span>{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Price</h3>
                            <select style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
                                <option>Default Sorting</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Brand</h3>
                            <select style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
                                <option>All Brands</option>
                                <option>HP</option>
                                <option>Dell</option>
                                <option>Microsoft</option>
                                <option>Lenovo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Main -->
                    <div style="width: 75%;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                            @foreach($products as $product)
                            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                                <div style="width: 100%; height: 150px; background-color: #f3f4f6; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                        alt="{{ $product->name }}" 
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="padding: 0.75rem;">
                                    <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 0.875rem; line-height: 1.25;">{{ $product->name }}</h3>
                                    <p style="font-weight: bold; color: #1f2937; margin-bottom: 0.75rem; font-size: 1rem;">${{ number_format($product->price, 2) }}</p>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.4rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Cart
                                        </button>
                                        <button style="flex: 1; background: #1f2937; color: white; padding: 0.4rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; border: none;">Buy Now</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div style="border-top: 1px solid #e5e7eb; margin: 2rem 0;"></div>

                        <!-- Pagination -->
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background: white;">Previous</button>
                            <div style="display: flex; gap: 0.5rem;">
                                @for($i = 1; $i <= 6; $i++)
                                <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background: {{ $i == 1 ? '#1f2937' : 'white' }}; color: {{ $i == 1 ? 'white' : '#1f2937' }};">{{ $i }}</button>
                                @endfor
                            </div>
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; background: white;">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h2 style="font-size: 1.5rem; font-weight: bold; color: #1f2937;">Explore our recommendations</h2>
                        <div style="display: flex; gap: 0.5rem;">
                            <button style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 50%; background: white;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 50%; background: white;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; overflow-x: auto; gap: 1rem; padding-bottom: 1rem;">
                        @foreach($recommendedProducts as $product)
                        <div style="flex: 0 0 auto; width: 200px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                            <div style="width: 100%; height: 120px; background-color: #f3f4f6; overflow: hidden;">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}" 
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="padding: 0.75rem;">
                                <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 0.75rem;">{{ $product->name }}</h3>
                                <p style="font-weight: bold; color: #1f2937; margin-bottom: 0.5rem; font-size: 0.875rem;">${{ number_format($product->price, 2) }}</p>
                                <div style="display: flex; gap: 0.25rem;">
                                    <button style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.625rem; display: flex; align-items: center; justify-content: center; gap: 0.125rem;">
                                        <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Cart
                                    </button>
                                    <button style="flex: 1; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.625rem; border: none;">Buy Now</button>
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