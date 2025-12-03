@extends('layouts.app')

@section('styles')
    @vite('resources/css/favorites/index.css')
@endsection

@section('content')
<div class="favorites-page">
    <section class="hero-section" style="position: relative; height: 350px; background-color: #1f2937; overflow: hidden; margin-bottom: 0;">
        <img src="{{ asset('storage/images/productbanner.png') }}" alt="Favorites Banner" 
            style="width: 100%; height: 100%; object-fit: cover; opacity: 1;">
        <div style="position: absolute; bottom: 5px; left: 0; right: 0; text-align: center;">
            <h1 style="font-size: 14rem; font-weight: bold; color: white; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7); margin: 0;">
                Favorites
            </h1>
        </div>
    </section>

    <!-- White Box Container -->
    <section class="white-box-container" style="padding: 0; margin-top: -120px; position: relative; z-index: 10; margin-bottom: 2rem;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <div style="background: white; border-radius: 1.5rem; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); padding: 3rem 2rem 2rem; border: 1px solid #e5e7eb;">
                
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h2 style="font-size: 1.75rem; font-weight: 600; color: #1f2937;">
                        My Favorite Products
                        <span style="font-size: 1rem; color: #6b7280; margin-left: 0.5rem;">({{ $favorites->count() }} items)</span>
                    </h2>
                </div>

                <!-- Favorites List -->
                @if($favorites->count() > 0)
                <div class="favorites-grid">
                    @foreach($favorites as $product)
                    <div class="favorite-product-card" data-product-id="{{ $product->id }}">
                        <div class="product-image-container">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}" 
                                class="product-image">
                            
                            <!-- Remove Favorite Button -->
                            <button class="favorite-btn favorited" data-product-id="{{ $product->id }}">
                                <svg class="favorite-icon" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            
                            <!-- Product Specs -->
                            <div class="product-specs">
                                @if($product->processor)
                                <p class="product-processor">{{ $product->processor }}</p>
                                @endif
                                
                                <div class="specs-price-row">
                                    @if($product->ram && $product->storage)
                                    <p class="product-ram-storage">{{ $product->ram }} • {{ $product->storage }}</p>
                                    @elseif($product->ram)
                                    <p class="product-ram-storage">{{ $product->ram }}</p>
                                    @elseif($product->storage)
                                    <p class="product-ram-storage">{{ $product->storage }}</p>
                                    @endif
                                    <p class="product-price">RM{{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                            
                            <!-- Buttons -->
                            <div class="product-buttons">
                                <button class="add-to-cart-btn btn-cart"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->price }}"
                                        data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Add to Cart
                                </button>
                                <button class="btn-buy">Buy Now</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="empty-favorites">
                    <div style="text-align: center; padding: 3rem 1rem;">
                        <svg style="width: 4rem; height: 4rem; color: #d1d5db; margin-bottom: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <h3 style="font-size: 1.5rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            No favorites yet
                        </h3>
                        <p style="color: #6b7280; margin-bottom: 2rem;">
                            Start adding products to your favorites by clicking the heart icon.
                        </p>
                        <a href="{{ url('/products') }}" 
                           style="display: inline-block; background: #1f2937; color: white; padding: 0.75rem 1.5rem; border-radius: 2rem; text-decoration: none; transition: all 0.2s ease;">
                            Browse Products
                        </a>
                    </div>
                </div>
                @endif
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
document.addEventListener('DOMContentLoaded', function() {
    // Remove from favorites functionality
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const productCard = this.closest('.favorite-product-card');
            
            fetch('/favorites/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the product card with animation
                    productCard.style.opacity = '0';
                    productCard.style.transform = 'translateX(100px)';
                    
                    setTimeout(() => {
                        productCard.remove();
                        
                        // Update item count
                        const itemCount = document.querySelectorAll('.favorite-product-card').length;
                        const countElement = document.querySelector('h2 span');
                        if (countElement) {
                            countElement.textContent = `(${itemCount} items)`;
                        }
                        
                        // Show empty state if no items left
                        if (itemCount === 0) {
                            document.querySelector('.favorites-grid').innerHTML = `
                                <div class="empty-favorites" style="width: 100%;">
                                    <div style="text-align: center; padding: 3rem 1rem;">
                                        <svg style="width: 4rem; height: 4rem; color: #d1d5db; margin-bottom: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <h3 style="font-size: 1.5rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                                            No favorites yet
                                        </h3>
                                        <p style="color: #6b7280; margin-bottom: 2rem;">
                                            Start adding products to your favorites by clicking the heart icon.
                                        </p>
                                        <a href="{{ url('/products') }}" 
                                           style="display: inline-block; background: #1f2937; color: white; padding: 0.75rem 1.5rem; border-radius: 2rem; text-decoration: none; transition: all 0.2s ease;">
                                            Browse Products
                                        </a>
                                    </div>
                                </div>
                            `;
                        }
                    }, 300);
                    
                    showNotification('Product removed from favorites!');
                } else {
                    showNotification(data.message || 'Failed to remove from favorites', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
        });
    });

    // Add to cart functionality (same as product page)
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            const productImage = this.getAttribute('data-product-image');
            
            // Show loading state
            const originalText = this.textContent;
            this.textContent = 'Adding...';
            this.disabled = true;
            
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
                    image: productImage
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Product added to cart successfully!');
                    updateHeaderCartCount(data.cart_count);
                    
                    setTimeout(() => {
                        this.textContent = 'Added!';
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.disabled = false;
                        }, 1000);
                    }, 500);
                } else {
                    showNotification('Failed to add product to cart', 'error');
                    this.textContent = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
                this.textContent = originalText;
                this.disabled = false;
            });
        });
    });

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('cart-notification');
        const messageElement = document.getElementById('notification-message');
        
        messageElement.textContent = message;
        notification.style.background = type === 'error' ? '#ef4444' : '#10b981';
        notification.style.display = 'block';
        
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

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
});
</script>
@endpush