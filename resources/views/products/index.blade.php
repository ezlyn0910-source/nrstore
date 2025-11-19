@extends('layouts.app')

@section('styles')
    @vite('resources/css/productpage.css')
@endsection

@section('content')
<div class="product-page">
    <section class="hero-section" style="position: relative; height: 350px; background-color: #1f2937; overflow: hidden; margin-bottom: 0;">
        <img src="{{ asset('storage/images/productbanner.png') }}" alt="Products Banner" 
            style="width: 100%; height: 100%; object-fit: cover; opacity: 1;">
        <div style="position: absolute; bottom: 5px; left: 0; right: 0; text-align: center;">
            <h1 style="font-size: 14rem; font-weight: bold; color: white; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7); margin: 0;">
                Product
            </h1>
        </div>
    </section>

    <!-- White Box Container -->
    <section class="white-box-container" style="padding: 0; margin-top: -120px; position: relative; z-index: 10; margin-bottom: 2rem;">
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
                            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; transition: all 0.3s ease; padding: 0; margin: 0;" class="product-card" data-product-id="{{ $product->id }}">
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
                                        <button class="add-to-cart-btn" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                                style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.4rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem; transition: all 0.2s ease; cursor: pointer;">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <span class="cart-btn-text">Add to Cart</span>
                                        </button>
                                        <button style="flex: 1; background: #1f2937; color: white; padding: 0.4rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; border: none; transition: all 0.2s ease; cursor: pointer;">Buy Now</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div style="border-top: 1px solid #e5e7eb; margin: 2rem 0;"></div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
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
                        <div style="flex: 0 0 auto; width: 300px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; transition: all 0.3s ease;" class="product-card" data-product-id="{{ $product->id }}">
                            <div style="width: 100%; height: 200px; background-color: #f3f4f6; overflow: hidden; margin: 0; padding: 0;">
                                <img src="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"    
                                    alt="{{ $product->name }}" 
                                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; margin: 0; padding: 0; display: block;">
                            </div>
                            <div style="padding: 0.75rem;">
                                <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 1rem;">{{ $product->name }}</h3>
                                <p style="font-weight: bold; color: #1f2937; margin-bottom: 0.5rem; font-size: 1rem;">RM{{ number_format($product->price, 2) }}</p>
                                <div style="display: flex; gap: 0.25rem;">
                                    <button class="add-to-cart-btn" 
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $product->price }}"
                                            data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                            style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.25rem 0.5rem; border-radius: 2rem; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.125rem; transition: all 0.2s ease; cursor: pointer;">
                                        <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="cart-btn-text">Add to Cart</span>
                                    </button>
                                    <button style="flex: 1; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 2rem; font-size: 0.8rem; border: none; transition: all 0.2s ease; cursor: pointer;">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Success Notification -->
<div id="cart-notification" style="display: none; position: fixed; top: 100px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); z-index: 10001; transition: all 0.3s ease;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="notification-message">Product added to cart!</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            const productImage = this.getAttribute('data-product-image');
            
            console.log('=== ADD TO CART DEBUG START ===');
            console.log('Product Data:', {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage
            });
            
            // Show loading state
            const originalText = this.querySelector('.cart-btn-text').textContent;
            this.querySelector('.cart-btn-text').textContent = 'Adding...';
            this.disabled = true;
            
            // Prepare the request data
            const requestData = {
                product_id: productId,
                product_name: productName,
                price: parseFloat(productPrice),
                quantity: 1,
                image: productImage
            };
            
            console.log('Request Data:', requestData);
            console.log('CSRF Token:', '{{ csrf_token() }}');
            
            // Send AJAX request to add to cart
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('Response Status:', response.status);
                console.log('Response OK:', response.ok);
                
                if (!response.ok) {
                    // Get the response text for more details
                    return response.text().then(text => {
                        console.log('Error Response Text:', text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success Response:', data);
                
                if (data.success) {
                    showNotification('Product added to cart successfully!');
                    
                    if (typeof updateHeaderCartCount === 'function') {
                        updateHeaderCartCount(data.cart_count);
                    }
                    
                    setTimeout(() => {
                        this.querySelector('.cart-btn-text').textContent = 'Added!';
                        setTimeout(() => {
                            this.querySelector('.cart-btn-text').textContent = originalText;
                            this.disabled = false;
                        }, 1000);
                    }, 500);
                } else {
                    console.error('Server Error:', data);
                    showNotification(data.message || 'Failed to add product to cart.', 'error');
                    this.querySelector('.cart-btn-text').textContent = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('=== FETCH ERROR ===');
                console.error('Error Name:', error.name);
                console.error('Error Message:', error.message);
                console.error('Error Stack:', error.stack);
                console.log('=== END DEBUG ===');
                
                showNotification('Network error. Please check console for details.', 'error');
                this.querySelector('.cart-btn-text').textContent = originalText;
                this.disabled = false;
            });
        });
    });

    // Cart count in header
    function updateCartCount(count) {
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

    // Show notification
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('cart-notification');
        const messageElement = document.getElementById('notification-message');
        
        // Set message and style based on type
        messageElement.textContent = message;
        if (type === 'error') {
            notification.style.background = '#ef4444';
        } else {
            notification.style.background = '#10b981';
        }
        
        // Show notification
        notification.style.display = 'block';
        
        // Hide after 3 seconds
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    // Type filter functionality (existing code)
    const laptopTypeCheckbox = document.getElementById('laptop-type');
    const desktopTypeCheckbox = document.getElementById('desktop-type');
    const laptopSubOptions = document.querySelectorAll('input[name="laptop_type[]"]');
    const desktopSubOptions = document.querySelectorAll('input[name="desktop_type[]"]');

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

    if (laptopTypeCheckbox) {
        laptopTypeCheckbox.addEventListener('change', function() {
            toggleSubOptions(laptopTypeCheckbox, laptopSubOptions);
        });
    }

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

    // Product card hover effects and popup functionality (existing code)
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
            
            const productId = this.getAttribute('data-product-id');
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
                id: productId,
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

    // Popup functionality (existing code)
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
                        <button class="popup-add-to-cart" 
                                data-product-id="${product.id}"
                                data-product-name="${product.name}"
                                data-product-price="${product.price}"
                                data-product-image="${product.image}"
                                style="
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

        // Add event listener to popup add to cart button
        const popupCartBtn = popup.querySelector('.popup-add-to-cart');
        if (popupCartBtn) {
            popupCartBtn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                const productPrice = this.getAttribute('data-product-price');
                const productImage = this.getAttribute('data-product-image');
                
                // Show loading state
                const originalText = this.textContent;
                this.textContent = 'Adding...';
                this.disabled = true;
                
                // Send AJAX request to add to cart
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        product_name: productName,
                        price: parseFloat(productPrice),
                        quantity: 1,
                        image: productImage,
                        specs: {
                            processor: product.processor,
                            ram: product.ram,
                            storage: product.storage
                        },
                        sku: '{{ $product->sku ?? "N/A" }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Product added to cart successfully!');
                        updateCartCount(data.cart_count);
                        
                        // Reset button state
                        setTimeout(() => {
                            this.textContent = 'Added!';
                            setTimeout(() => {
                                this.textContent = originalText;
                                this.disabled = false;
                            }, 1000);
                        }, 500);
                    } else {
                        showNotification('Failed to add product to cart. Please try again.', 'error');
                        this.textContent = originalText;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                    this.textContent = originalText;
                    this.disabled = false;
                });
            });
        }

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
});
</script>
@endpush