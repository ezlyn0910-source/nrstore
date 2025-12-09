@extends('layouts.app')

@section('content')
<div class="page-container" style="max-width: 1280px; margin: 0 auto; padding: 5rem 3rem;">
        {{-- Add to Cart alert --}}
        <div id="addToCartAlert"
            style="
                display:none;
                position: fixed;
                top: 150px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9999;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                border: 1px solid #34d399;
                background: #d1fae5;
                color: #065f46;
                font-size: 0.95rem;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            ">
            The item has successfully added to cart
        </div>

    <div class="product-detail">
        <div style="display: grid; grid-template-columns: 1.2fr 1.8fr; gap: 2rem; align-items: stretch;">
    
        {{-- LEFT: IMAGE + THUMBNAILS --}}
        <div>
            @php
                $images = [];

                // Prefer gallery images from relationship
                if ($product->images && $product->images->count() > 0) {
                    $primaryImage = $product->images->where('is_primary', true)->first();

                    // 1) Primary image first
                    if ($primaryImage && $primaryImage->image_path && file_exists(public_path($primaryImage->image_path))) {
                        $images[] = $primaryImage->image_path;
                    }

                    // 2) Then all other images (excluding the primary)
                    foreach ($product->images as $img) {
                        if (
                            $img->image_path &&
                            file_exists(public_path($img->image_path)) &&
                            !in_array($img->image_path, $images, true)
                        ) {
                            $images[] = $img->image_path;
                        }
                    }
                }
                // If no gallery images, fall back to single main image field
                elseif ($product->image && file_exists(public_path($product->image))) {
                    $images[] = $product->image;
                }

                // Fallback: if still empty, keep null so we show "No Image"
                $mainImage   = count($images) ? $images[0] : null;
                $imagesCount = count($images);
            @endphp

            {{-- MAIN IMAGE (SQUARE) --}}
            <div class="product-detail-image"
                style="width: 100%; aspect-ratio: 1 / 1; overflow: hidden; border-radius: 0.75rem; background: #f4f4f4; position: relative;">
                @if($mainImage)
                    <img id="mainProductImage"
                        src="{{ asset($mainImage) }}"
                        alt="{{ $product->name }}"
                        style="
                            position: absolute;
                            inset: 0;
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            display: block;
                            border-radius: 0.75rem;
                        ">
                @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #777;">
                        No Image Available
                    </div>
                @endif
            </div>

            {{-- THUMBNAILS --}}
            @if($imagesCount > 1)
                <div class="product-thumbs-row">
                    @foreach($images as $index => $imgPath)
                        <div class="product-thumb {{ $index === 0 ? 'active' : '' }}"
                            data-image-src="{{ asset($imgPath) }}">
                            <img src="{{ asset($imgPath) }}"
                                alt="Thumbnail {{ $index + 1 }}"
                                style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- RIGHT: INFO --}}
        <div class="product-detail-info" style="display: flex; flex-direction: column; height: 100%;">
            
            {{-- TOP: Title, price, desc --}}
            <div>
                {{-- Product Name and Brand --}}
                <h1 class="product-title" style="font-size: 1.9rem; font-weight: 700; margin-bottom: 1rem; color:#1a2412;">
                    {{ $product->name }}
                    <span style="font-size: 1rem; color: #6b7c72; margin-left: 8px;">
                        {{ strtoupper($product->brand) }}
                    </span>
                </h1>

                {{-- Price --}}
                <p style="font-size: 1.8rem; font-weight: 700; color: #5FBF87; margin-bottom: 0rem;">
                    {{ $product->formatted_price }}
                </p>

                {{-- Disclaimer --}}
                <p style="color:#666; font-size: 1rem; margin-top: 1rem;">
                    *Real product image; colors may vary slightly due to lighting.*
                </p>

                {{-- Description --}}
                @if($product->description)
                    <div style="margin-top: 1rem; font-size: 0.9rem; color: #6b7280; line-height: 1.7;">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @endif
            </div>

            {{-- BOTTOM: Divider + Specs + Qty/Buttons pushed down --}}
            <div style="margin-top: auto;">
                <hr style="margin: 1.5rem 0 1.5rem 0; border: none; border-top: 1.5px solid #ddd;">

                {{-- Product Specifications --}}
                <div style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #555;">
                    @if($product->processor)
                        <div><strong>Processor:</strong> {{ $product->processor }}</div>
                    @endif

                    @if($product->ram)
                        <div><strong>RAM:</strong> {{ $product->ram }}</div>
                    @endif

                    @if($product->storage)
                        <div><strong>Storage:</strong> {{ $product->storage }}</div>
                    @endif

                    @if($product->graphics_card)
                        <div><strong>Graphics:</strong> {{ $product->graphics_card }}</div>
                    @endif

                    @if($product->operating_system)
                        <div><strong>Operating System:</strong> {{ $product->operating_system }}</div>
                    @endif

                    @if($product->screen_size)
                        <div><strong>Screen Size:</strong> {{ $product->screen_size }}</div>
                    @endif

                    @if($product->sku)
                        <div><strong>SKU:</strong> {{ $product->sku }}</div>
                    @endif
                </div>

                {{-- Quantity + Buttons --}}
                <div style="
                    display: flex; 
                    align-items: center; 
                    gap: 1rem; 
                    margin-top: 1.5rem;
                ">

                    {{-- Quantity Controller --}}
                    <div style="display: flex; align-items: center; gap: 0.4rem;">

                        <button type="button"
                            id="qtyMinus"
                            style="
                                width: 32px; 
                                height: 32px; 
                                border-radius: 8px; 
                                border: 1px solid #ccc; 
                                background: #fff; 
                                cursor: pointer; 
                                font-size: 1.2rem;
                                font-weight: 600;
                            ">
                            –
                        </button>

                        <input id="qtyInput" type="text" value="1"
                            style="
                                width: 52px;
                                text-align: center;
                                border: 1px solid #ccc;
                                border-radius: 8px;
                                padding: 0.4rem 0;
                                font-size: 1rem;
                                -moz-appearance: textfield;
                            "
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        >

                        <button type="button"
                            id="qtyPlus"
                            style="
                                width: 32px; 
                                height: 32px; 
                                border-radius: 8px; 
                                border: 1px solid #ccc; 
                                background: #fff; 
                                cursor: pointer; 
                                font-size: 1.2rem;
                                font-weight: 600;
                            ">
                            +
                        </button>
                    </div>

                    {{-- Buttons --}}
                    <div style="display: flex; gap: 0.6rem;">
                        <button type="button" 
                            id="btnAddToCart"
                            style="
                                padding: 0.75rem 1.4rem; 
                                border-radius: 999px; 
                                border: none; 
                                background:#2d4a35; 
                                color:#fff; 
                                font-weight:600; 
                                cursor:pointer;
                                white-space: nowrap;
                            ">
                            Add to Cart
                        </button>

                        <button type="button" 
                            id="btnBuyNow"
                            style="
                                padding: 0.75rem 1.4rem; 
                                border-radius: 999px; 
                                border: 1px solid #2d4a35; 
                                background:#fff; 
                                color:#2d4a35; 
                                font-weight:600; 
                                cursor:pointer;
                                white-space: nowrap;
                            ">
                            Buy Now
                        </button>
                    </div>

                </div>

                {{-- HIDDEN FORMS FOR CART & BUY NOW --}}
                @auth
                    {{-- Add to Cart form --}}
                    <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="cartQtyInput">
                        <input type="hidden" name="source" value="product_show"> {{-- <── add this --}}
                    </form>

                    {{-- Buy Now form --}}
                    <form id="buyNowForm" method="POST" action="{{ route('buy-now') }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="buyNowQtyInput">
                    </form>
                @endauth
            </div>

        </div>
    </div>

        {{-- Related products --}}
        @if(isset($relatedProducts) && $relatedProducts->count())
            <hr style="margin: 2.5rem 0 2rem 0; border: none; border-top: 1.8px solid #ddd;">

            <div style="margin-top: 2.5rem;">
                <h2 style="font-size: 1.6rem; font-weight: 700; margin-bottom: 1.5rem; color:#1a2412;">
                    Related Products
                </h2>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.5rem;">
                    @foreach($relatedProducts as $rel)
                        <div class="product-card" 
                             style="background:#fff; border-radius:1rem; box-shadow:0 4px 12px rgba(0,0,0,0.06); overflow:hidden; cursor:pointer;"
                             onclick="window.location.href='{{ route('products.show', $rel->slug) }}'">
                            
                            {{-- Image --}}
                            <div style="width:100%; aspect-ratio:1/1; background:#f4f4f4; position:relative; overflow:hidden;">
                                @php
                                    $relImage = null;

                                    if ($rel->image && file_exists(public_path($rel->image))) {
                                        $relImage = $rel->image;
                                    } elseif ($rel->images && $rel->images->count() > 0) {
                                        $relPrimary = $rel->images->where('is_primary', true)->first();
                                        $relFirst = $rel->images->first();

                                        if ($relPrimary && $relPrimary->image_path && file_exists(public_path($relPrimary->image_path))) {
                                            $relImage = $relPrimary->image_path;
                                        } elseif ($relFirst && $relFirst->image_path && file_exists(public_path($relFirst->image_path))) {
                                            $relImage = $relFirst->image_path;
                                        }
                                    }
                                @endphp

                                @if($relImage)
                                    <img src="{{ asset($relImage) }}" 
                                         alt="{{ $rel->name }}" 
                                         style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                                @else
                                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#777;">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div style="padding:0.75rem 1rem 1rem;">
                                <div style="font-weight:600; font-size:0.95rem; margin-bottom:0.25rem; color:#1a2412;">
                                    {{ $rel->name }}
                                </div>

                                @if($rel->brand)
                                    <div style="font-size:0.8rem; color:#6b7c72; margin-bottom:0.25rem;">
                                        {{ $rel->brand }}
                                    </div>
                                @endif

                                <div style="font-weight:700; color:#daa112;">
                                    {{ $rel->formatted_price }}
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Thumbnail → main image swap
    const mainImage = document.getElementById('mainProductImage');
    const thumbs    = document.querySelectorAll('.product-thumb');

    if (mainImage && thumbs.length) {
        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                const newSrc = this.getAttribute('data-image-src') || this.dataset.imageSrc;
                if (!newSrc) return;

                mainImage.src = newSrc;

                thumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // Quantity controller
    const qtyInput = document.getElementById('qtyInput');
    const btnMinus = document.getElementById('qtyMinus');
    const btnPlus  = document.getElementById('qtyPlus');

    if (qtyInput && btnMinus && btnPlus) {
        btnMinus.addEventListener('click', function () {
            let current = parseInt(qtyInput.value || '1', 10);
            if (isNaN(current) || current <= 1) {
                qtyInput.value = 1;
            } else {
                qtyInput.value = current - 1;
            }
        });

        btnPlus.addEventListener('click', function () {
            let current = parseInt(qtyInput.value || '1', 10);
            if (isNaN(current) || current < 1) {
                qtyInput.value = 1;
            } else {
                qtyInput.value = current + 1;
            }
        });

        qtyInput.addEventListener('input', function () {
            let current = parseInt(qtyInput.value || '1', 10);
            if (isNaN(current) || current < 1) {
                qtyInput.value = 1;
            }
        });
    }

    // ===== Add to Cart / Buy Now actions =====
    const isLoggedIn      = @json(auth()->check());
    const loginUrl        = "{{ route('login') }}";

    const btnAddToCart    = document.getElementById('btnAddToCart');
    const btnBuyNow       = document.getElementById('btnBuyNow');
    const addToCartForm   = document.getElementById('addToCartForm');
    const buyNowForm      = document.getElementById('buyNowForm');
    const cartQtyInput    = document.getElementById('cartQtyInput');
    const buyNowQtyInput  = document.getElementById('buyNowQtyInput');
    const cartAlert       = document.getElementById('addToCartAlert');
    const csrfToken       = document.querySelector('meta[name="csrf-token"]')
                                ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                : '';

    function getQuantity() {
        let q = parseInt(qtyInput ? qtyInput.value : '1', 10);
        if (isNaN(q) || q < 1) q = 1;
        return q;
    }

    function showCartAlert(message, isError = false) {
        if (!cartAlert) return;

        cartAlert.textContent = message;
        cartAlert.style.display = 'block';

        if (isError) {
            cartAlert.style.backgroundColor = '#fee2e2';
            cartAlert.style.borderColor = '#f87171';
            cartAlert.style.color = '#b91c1c';
        } else {
            cartAlert.style.backgroundColor = '#d1fae5';
            cartAlert.style.borderColor = '#34d399';
            cartAlert.style.color = '#065f46';
        }

        setTimeout(() => {
            cartAlert.style.display = 'none';
        }, 3000);
    }

    function updateCartBadge(count) {
        if (typeof count === 'undefined' || count === null) return;

        // Try a few common selectors; adjust if your header uses a specific one
        const elements = document.querySelectorAll('[data-cart-count], .cart-count, #cart-count');
        elements.forEach(el => {
            el.textContent = count;
        });
    }

    if (btnAddToCart) {
        btnAddToCart.addEventListener('click', function () {
            if (!isLoggedIn) {
                window.location.href = loginUrl;
                return;
            }

            const quantity = getQuantity();

            const requestData = {
                product_id: {{ $product->id }},
                quantity: quantity,
                source: 'product_show'
            };

            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(async (response) => {
                let data = null;

                // Try to parse JSON; if it fails but response is OK, treat as success
                try {
                    data = await response.json();
                } catch (e) {
                    if (response.ok) {
                        showCartAlert('The item has successfully added to cart', false);
                        return;
                    }
                }

                if (data && data.success) {
                    showCartAlert('The item has successfully added to cart', false);
                    updateCartBadge(data.cart_count || data.cart_total_items);
                } else {
                    showCartAlert(
                        (data && data.message) || 'Error adding item to cart.',
                        true
                    );
                }
            })
            .catch(() => {
                showCartAlert('Error adding item to cart. Please try again.', true);
            });
        });
    }

    if (btnBuyNow) {
        btnBuyNow.addEventListener('click', function () {
            if (!isLoggedIn) {
                window.location.href = loginUrl;
                return;
            }

            if (!buyNowForm || !buyNowQtyInput) return;

            buyNowQtyInput.value = getQuantity();

            const formData = new FormData(buyNowForm);

            fetch(buyNowForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    // Buy now: go straight to checkout
                    window.location.href = data.redirect_url;
                } else {
                    showCartAlert(data.message || 'Error processing buy now.', true);
                }
            })
            .catch(() => {
                showCartAlert('Error processing buy now. Please try again.', true);
            });
    });
    }
});
</script>
@endpush

@section('styles')
<style>
    /* Thumbnails row fills the width & stays on one line, centered */
    .product-thumbs-row {
        margin-top: 0.75rem;
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: nowrap;
        width: 100%;
    }

    /* Thumbnail wrapper: up to 6 per row, auto-scale */
    .product-thumb {
        flex: 0 0 calc(100% / 6 - 0.5rem);
        max-width: 80px;
        aspect-ratio: 1 / 1;
        border-radius: 0.5rem;
        overflow: hidden;
        background: #f4f4f4;
        cursor: pointer;
        border: 2px solid transparent;
        box-sizing: border-box;
    }

    .product-thumb.active {
        border-color: #4CCB6B;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    @media (max-width: 480px) {
        .product-thumb {
            max-width: 60px;
        }
    }
</style>
@endsection
