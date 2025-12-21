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

                {{-- Price Display --}}
                @if($product->variations->count() > 0)
                    @php
                        $minPrice = $product->variations->min('price') ?? $product->price;
                        $maxPrice = $product->variations->max('price') ?? $product->price;
                    @endphp

                    <p style="font-size: 1.8rem; font-weight: 700; color: #5FBF87; margin-bottom: 0rem;">
                        RM {{ number_format($minPrice, 2) }}
                        @if($minPrice != $maxPrice)
                            - RM {{ number_format($maxPrice, 2) }}
                        @endif
                    </p>
                @else
                    <p style="font-size: 1.8rem; font-weight: 700; color: #5FBF87; margin-bottom: 0rem;">
                        {{ $product->formatted_price }}
                    </p>
                @endif

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

            {{-- BOTTOM: Divider + Specs + Variations + Qty/Buttons pushed down --}}
            <div style="margin-top: auto;">
                <hr style="margin: 1.5rem 0 1.5rem 0; border: none; border-top: 1.5px solid #ddd;">

                {{-- Variations (Processor + Memory/Storage) --}}
                @if($product->has_variations && $product->variations->count() > 0)
                    @php
                        // Use only active variations (controller already loads active, but safe)
                        $activeVars = $product->variations;

                        $defaultVar = $activeVars->first();
                        $defaultProcessor = $defaultVar->processor ?? 'Default';

                        // Unique processors
                        $processors = $activeVars->pluck('processor')->filter()->unique()->values();

                        // If processor is empty for all, fall back to model (still shown under "Processor")
                        if ($processors->count() === 0) {
                            $processors = $activeVars->pluck('model')->filter()->unique()->values();
                            $defaultProcessor = $defaultVar->model ?? 'Default';
                        }
                    @endphp

                    <div style="margin-bottom: 1.25rem;">
                        <div style="display:flex; flex-direction:column; gap:0.9rem;">

                            {{-- Row 1: Processor --}}
                            <div style="display:grid; grid-template-columns: 140px 1fr; gap: 1rem; align-items:start;">
                                <div style="color:#6b7c72; font-size:0.95rem; padding-top:0.35rem;">
                                    Processor
                                </div>

                                <div id="processorOptions" style="display:flex; flex-wrap:wrap; gap:0.6rem;">
                                    @foreach($processors as $p)
                                        <button type="button"
                                            class="var-chip processor-chip"
                                            data-processor="{{ $p }}"
                                            style="
                                                border:1.5px solid #e5e7eb;
                                                background:#fff;
                                                color:#1a2412;
                                                padding:0.55rem 0.9rem;
                                                border-radius:8px;
                                                cursor:pointer;
                                                font-size:0.9rem;
                                                display:flex;
                                                align-items:center;
                                                gap:0.55rem;
                                                transition:all .15s;
                                            "
                                        >
                                            <span>{{ $p }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Row 2: Memory/Storage --}}
                            <div style="display:grid; grid-template-columns: 140px 1fr; gap: 1rem; align-items:start;">
                                <div style="color:#6b7c72; font-size:0.95rem; padding-top:0.35rem;">
                                    Memory/Storage
                                </div>

                                <div id="memoryOptions" style="display:flex; flex-wrap:wrap; gap:0.6rem;"></div>
                            </div>
                        </div>
                    </div>
                @endif

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
                    <div style="display: flex; gap: 0.6rem; align-items:center;">
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

                        <span id="variationError"
                            style="
                                font-size:0.85rem;
                                color:#b91c1c;
                                margin-left:0.5rem;
                                display:none;
                            ">
                        </span>

                    </div>

                </div>

                {{-- HIDDEN FORMS FOR CART & BUY NOW --}}
                @auth
                    {{-- Add to Cart form --}}
                    <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="cartQtyInput">
                        <input type="hidden" name="variation_id" id="cartVariationId">
                        <input type="hidden" name="source" value="product_show">
                    </form>

                    {{-- Buy Now form --}}
                    <form id="buyNowForm" method="POST" action="{{ route('buy-now') }}" style="display:none;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="buyNowQtyInput">
                        <input type="hidden" name="variation_id" id="buyNowVariationId">
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

    // ===== Variation Selection (2-way compatible variants) =====
    let selectedProcessor = null;
    let selectedMemLabel = null;      // e.g. "8GB/128GB"
    let selectedVariationId = null;

    const hasVariations = @json($product->variations->count() > 0);

    @php
        $allVariationsData = $product->variations->map(function($v){
            return [
                'id'        => $v->id,
                'processor' => $v->processor ?: ($v->model ?: 'Default'),
                'ram'       => $v->ram,
                'storage'   => $v->storage,
            ];
        })->values();
    @endphp

    const allVariations = @json($allVariationsData);

    const errorEl = document.getElementById('variationError');

    function clearError() {
        if (errorEl) errorEl.style.display = 'none';
    }
    function showError(msg) {
        if (!errorEl) return;
        errorEl.textContent = msg;
        errorEl.style.display = 'inline';
    }

    // Build a clean label for Memory/Storage (safe if null)
    function memLabelOf(v) {
        const ram = (v.ram ?? '').toString().trim();
        const st  = (v.storage ?? '').toString().trim();
        if (ram && st) return `${ram}/${st}`;   // ✅
        if (ram) return ram;
        if (st) return st;
        return 'Standard';
    }

    // Check if a combination exists
    function hasCombo(proc, memLabel) {
        return allVariations.some(v => v.processor === proc && memLabelOf(v) === memLabel);
    }

    // Find the exact variation id for selected combo
    function findVariationId(proc, memLabel) {
        const found = allVariations.find(v => v.processor === proc && memLabelOf(v) === memLabel);
        return found ? found.id : null;
    }

    // Helpers to set chip UI
    function setSelectedChip(btn, selector) {
        document.querySelectorAll(selector).forEach(b => {
            b.classList.remove('selected');
            b.style.borderColor = '#e5e7eb';
            b.style.background = '#fff';
        });
        btn.classList.add('selected');
        btn.style.borderColor = '#2d4a35';
        btn.style.background = '#f3f6f4';
    }

    function setDisabledChip(btn, disabled) {
        if (disabled) btn.classList.add('disabled');
        else btn.classList.remove('disabled');
    }

    // Render unique memory/storage chips (ALWAYS visible)
    const memoryWrap = document.getElementById('memoryOptions');
    if (memoryWrap) {
        memoryWrap.innerHTML = '';

        const uniqueMemLabels = [];
        allVariations.forEach(v => {
            const label = memLabelOf(v);
            if (!uniqueMemLabels.includes(label)) uniqueMemLabels.push(label);
        });

        uniqueMemLabels.forEach(label => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'var-chip memory-chip';
            btn.textContent = label;
            btn.dataset.memLabel = label;

            btn.style.border = '1.5px solid #e5e7eb';
            btn.style.background = '#fff';
            btn.style.color = '#1a2412';
            btn.style.padding = '0.55rem 0.9rem';
            btn.style.borderRadius = '8px';
            btn.style.cursor = 'pointer';
            btn.style.fontSize = '0.9rem';
            btn.style.transition = 'all .15s';

            btn.addEventListener('click', () => {
                if (btn.classList.contains('disabled')) return;

                setSelectedChip(btn, '.memory-chip');
                selectedMemLabel = btn.dataset.memLabel;

                clearError();
                refreshCompatibility();  // grey out processors based on memory
                updateSelectedVariation();
            });

            memoryWrap.appendChild(btn);
        });
    }

    // Processor chip click binding
    document.querySelectorAll('.processor-chip').forEach(btn => {
        btn.addEventListener('click', () => {
            setSelectedChip(btn, '.processor-chip');
            selectedProcessor = btn.dataset.processor;

            if (selectedMemLabel && !hasCombo(selectedProcessor, selectedMemLabel)) {
                document.querySelectorAll('.memory-chip').forEach(m => {
                    m.classList.remove('selected');
                    m.style.borderColor = '#e5e7eb';
                    m.style.background = '#fff';
                });
                selectedMemLabel = null;
                selectedVariationId = null;
            }

            clearError();
            refreshCompatibility();
            updateSelectedVariation();
        });
    });

    // Only set variationId when BOTH selected & combo exists
    function updateSelectedVariation() {
        if (selectedProcessor && selectedMemLabel) {
            selectedVariationId = findVariationId(selectedProcessor, selectedMemLabel);
        } else {
            selectedVariationId = null;
        }
    }

    // Core function: disable incompatible chips on both sides
    function refreshCompatibility() {
        document.querySelectorAll('.processor-chip').forEach(procBtn => {
            procBtn.classList.remove('disabled');
            procBtn.style.opacity = '';
            procBtn.style.pointerEvents = '';
            procBtn.style.background = '#fff';
            procBtn.style.borderColor = '#e5e7eb';
            procBtn.style.color = '#1a2412';

            // keep selected styling if selected
            if (procBtn.classList.contains('selected')) {
                procBtn.style.borderColor = '#2d4a35';
                procBtn.style.background = '#f3f6f4';
            }
        });

        // ✅ ONLY control memory/storage based on selected processor
        document.querySelectorAll('.memory-chip').forEach(memBtn => {
            const label = memBtn.dataset.memLabel;

            // if no processor selected, keep all memory enabled
            const disable = selectedProcessor ? !hasCombo(selectedProcessor, label) : false;

            setDisabledChip(memBtn, disable);

            // ✅ If current selected memory becomes invalid after processor change → auto deselect it
            if (disable && memBtn.classList.contains('selected')) {
                memBtn.classList.remove('selected');
                memBtn.style.borderColor = '#e5e7eb';
                memBtn.style.background = '#fff';
                selectedMemLabel = null;
            }
        });

        updateSelectedVariation();
    }

    // Initial state
    refreshCompatibility();

    // ===== Add to Cart / Buy Now actions =====
    const isLoggedIn      = @json(auth()->check());
    const loginUrl        = "{{ route('login') }}";

    const btnAddToCart    = document.getElementById('btnAddToCart');
    const btnBuyNow       = document.getElementById('btnBuyNow');
    const addToCartForm   = document.getElementById('addToCartForm');
    const buyNowForm      = document.getElementById('buyNowForm');
    const cartQtyInput    = document.getElementById('cartQtyInput');
    const cartVariationId = document.getElementById('cartVariationId');
    const buyNowQtyInput  = document.getElementById('buyNowQtyInput');
    const buyNowVariationId = document.getElementById('buyNowVariationId');
    const cartAlert       = document.getElementById('addToCartAlert');
    const csrfToken       = document.querySelector('meta[name="csrf-token"]')
                                ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                : '';
    const qtyInput = document.getElementById('qtyInput');

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
        const elements = document.querySelectorAll('[data-cart-count], .cart-count, #cart-count');
        elements.forEach(el => el.textContent = count);
    }

    function validateVariationsOrShowError() {
        if (!hasVariations) return true;

        if (!selectedProcessor && !selectedMemLabel) {
            showError('Please select the variations');
            return false;
        }
        if (!selectedProcessor) {
            showError('Please select processor');
            return false;
        }
        if (!selectedMemLabel) {
            showError('Please select memory/storage');
            return false;
        }
        if (!selectedVariationId) {
            showError('This variation is not available');
            return false;
        }
        clearError();
        return true;
    }

    if (btnAddToCart) {
        btnAddToCart.addEventListener('click', function () {
            if (!isLoggedIn) {
                window.location.href = loginUrl;
                return;
            }

            if (!validateVariationsOrShowError()) return;

            if (!addToCartForm || !cartQtyInput) return;

            cartQtyInput.value = getQuantity();
            if (hasVariations && cartVariationId) {
                cartVariationId.value = selectedVariationId;
            }

            const requestData = {
                product_id: {{ $product->id }},
                quantity: getQuantity(),
                variation_id: hasVariations ? selectedVariationId : null,
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
                try { data = await response.json(); } catch (e) {}

                if (response.ok && data && data.success) {
                    showCartAlert('The item has successfully added to cart', false);
                    updateCartBadge(data.cart_count || data.cart_total_items);
                } else if (response.ok && !data) {
                    showCartAlert('The item has successfully added to cart', false);
                } else {
                    showCartAlert((data && data.message) || 'Error adding item to cart.', true);
                }
            })
            .catch(() => showCartAlert('Error adding item to cart. Please try again.', true));
        });
    }

    if (btnBuyNow) {
        btnBuyNow.addEventListener('click', function () {
            if (!isLoggedIn) {
                window.location.href = loginUrl;
                return;
            }

            // ✅ FIXED: use same validation (processor + memLabel + variationId)
            if (!validateVariationsOrShowError()) return;

            if (!buyNowForm || !buyNowQtyInput) return;

            buyNowQtyInput.value = getQuantity();
            if (hasVariations && buyNowVariationId) {
                buyNowVariationId.value = selectedVariationId;
            }

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
            .then(async (r) => {
                // Check if response is a redirect
                if (r.redirected) {
                    window.location.href = r.url;
                    return;
                }
                
                // Try to parse as JSON
                try {
                    const data = await r.json();
                    
                    if (data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else if (data.success && r.ok) {
                        // Fallback: if success but no redirect_url, try to redirect anyway
                        window.location.href = "{{ route('checkout.index') }}";
                    } else {
                        showCartAlert(data.message || 'Error processing buy now.', true);
                    }
                } catch (e) {
                    // If not JSON, it might be HTML or a direct redirect
                    if (r.ok) {
                        // Successful response but not JSON - redirect to checkout
                        window.location.href = "{{ route('checkout.index') }}";
                    } else {
                        showCartAlert('Error processing buy now. Please try again.', true);
                    }
                }
            })
            .catch(() => showCartAlert('Error processing buy now. Please try again.', true));
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

    .var-chip:hover {
        border-color: #2d4a35 !important;
        background: #f3f6f4 !important;
    }

    .var-chip.selected {
        border-color: #2d4a35 !important;
        background: #f3f6f4 !important;
    }

    .var-chip.disabled {
        opacity: 0.45;
        cursor: not-allowed !important;
        pointer-events: none;
        background: #f3f4f6 !important;
        border-color: #e5e7eb !important;
        color: #9ca3af !important;
    }

    @media (max-width: 480px) {
        .product-thumb {
            max-width: 60px;
        }
        
    }
</style>
@endsection