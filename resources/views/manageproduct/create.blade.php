@extends('admin.adminbase')
@section('title', 'Create Product')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/create.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="product-form-container">
    <div class="product-form-header">
        <h2>Add New Product</h2>
        <div class="form-actions">
            <a href="{{ route('admin.manageproduct.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" form="productForm" class="btn btn-save">
                <i class="fas fa-save"></i> Save Product
            </button>
        </div>
    </div>
    
    @if ($errors->any())
    <div class="alert-messages">
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            Please fix the validation errors below.
        </div>
    </div>
    @endif

    <form id="productForm" method="POST" action="{{ route('admin.manageproduct.store') }}" enctype="multipart/form-data" class="product-form">
        @csrf
        
        <!-- Basic Product Information -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-info-circle"></i>
                <h3>Product Information</h3>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}">
                    @error('sku')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand') }}">
                    @error('brand')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Price (RM) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity *</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="{{ old('stock_quantity', 0) }}" required>
                    @error('stock_quantity')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="processor">Processor</label>
                    <input type="text" id="processor" name="processor" value="{{ old('processor') }}">
                    @error('processor')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ram">RAM</label>
                    <input type="text" id="ram" name="ram" value="{{ old('ram') }}">
                    @error('ram')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="storage">Storage</label>
                    <input type="text" id="storage" name="storage" value="{{ old('storage') }}">
                    @error('storage')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="storage_type">Storage Type</label>
                    <input type="text" id="storage_type" name="storage_type" value="{{ old('storage_type') }}">
                    @error('storage_type')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="graphics_card">Graphics Card</label>
                    <input type="text" id="graphics_card" name="graphics_card" value="{{ old('graphics_card') }}">
                    @error('graphics_card')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="screen_size">Screen Size</label>
                    <input type="text" id="screen_size" name="screen_size" value="{{ old('screen_size') }}">
                    @error('screen_size')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="os">Operating System</label>
                    <input type="text" id="os" name="os" value="{{ old('os') }}">
                    @error('os')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="warranty">Warranty</label>
                    <input type="text" id="warranty" name="warranty" value="{{ old('warranty') }}">
                    @error('warranty')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-checkboxes">
                <label class="checkbox-container">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    Featured Product
                </label>
                <label class="checkbox-container">
                    <input type="checkbox" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    Recommended Product
                </label>
                <label class="checkbox-container">
                    <input type="checkbox" name="has_variations" value="1" id="has_variations" {{ old('has_variations') ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    This product has variations
                </label>
            </div>
        </div>

        <!-- Product Images -->
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-images"></i>
                <h3>Product Images</h3>
            </div>
            
            <div class="image-upload-section">
                <div class="main-image-upload">
                    <h4>Main Image</h4>
                    <div class="image-upload-box main-image-box" id="main-image-container">
                        <div class="image-preview">
                            <div class="placeholder">
                                <i class="fas fa-camera"></i>
                                <span>Click to upload main image</span>
                            </div>
                        </div>
                        <input type="file" name="main_image" id="main_image" accept="image/*" class="image-input">
                    </div>
                    @error('main_image')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="gallery-images-upload">
                    <h4>Gallery Images (Max 5)</h4>
                    <div class="image-grid" id="gallery-container">
                        <div class="image-upload-box gallery-image-box" id="add-gallery-image">
                            <div class="add-image-content">
                                <i class="fas fa-plus"></i>
                                <span>Add Image</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Variations -->
        <div class="form-section variations-section" id="variations-section" style="{{ old('has_variations') ? '' : 'display: none;' }}">
            <div class="section-header">
                <i class="fas fa-palette"></i>
                <h3>Product Variations</h3>
            </div>
            
            <div class="variations-container" id="variations-container">
                <!-- Variations will be added here dynamically -->
            </div>

            <button type="button" class="btn btn-add-variation" id="add-variation">
                <i class="fas fa-plus"></i> Add Variation
            </button>
        </div>
    </form>
</div>

<!-- Variation Template (Hidden) -->
<template id="variation-template">
    <div class="variation-item">
        <div class="variation-header">
            <h4>Variation <span class="variation-number">1</span></h4>
            <button type="button" class="btn btn-remove remove-variation">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="variation-content">
            <div class="form-grid">
                <div class="form-group">
                    <label>SKU *</label>
                    <input type="text" name="variations[__index__][sku]" required>
                </div>
                <div class="form-group">
                    <label>Price (RM)</label>
                    <input type="number" name="variations[__index__][price]" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Stock *</label>
                    <input type="number" name="variations[__index__][stock]" min="0" required>
                </div>
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" name="variations[__index__][model]">
                </div>
                <div class="form-group">
                    <label>Processor</label>
                    <input type="text" name="variations[__index__][processor]">
                </div>
                <div class="form-group">
                    <label>RAM</label>
                    <input type="text" name="variations[__index__][ram]">
                </div>
                <div class="form-group">
                    <label>Storage</label>
                    <input type="text" name="variations[__index__][storage]">
                </div>
            </div>

            <div class="variation-image-upload">
                <label>Variation Image</label>
                <div class="image-upload-box variation-image-box">
                    <div class="image-preview">
                        <div class="placeholder">
                            <i class="fas fa-camera"></i>
                            <span>Variation image (optional)</span>
                        </div>
                    </div>
                    <input type="file" name="variations[__index__][image_file]" accept="image/*" class="image-input">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image upload functionality
    const mainImageContainer = document.getElementById('main-image-container');
    const mainImageInput = document.getElementById('main_image');
    const galleryContainer = document.getElementById('gallery-container');
    const addGalleryBtn = document.getElementById('add-gallery-image');
    let galleryImageCount = 0;
    const maxGalleryImages = 5;

    // Main image preview
    mainImageInput.addEventListener('change', function() {
        setupImagePreview(this, mainImageContainer);
    });

    // Add gallery image
    addGalleryBtn.addEventListener('click', function() {
        if (galleryImageCount >= maxGalleryImages) {
            alert('Maximum 5 gallery images allowed');
            return;
        }

        const newImageBox = document.createElement('div');
        newImageBox.className = 'image-upload-box gallery-image-box';
        newImageBox.innerHTML = `
            <div class="image-preview">
                <div class="placeholder">
                    <i class="fas fa-camera"></i>
                    <span>Gallery image</span>
                </div>
            </div>
            <input type="file" name="product_images[]" accept="image/*" class="image-input">
            <button type="button" class="btn btn-remove remove-gallery-image">
                <i class="fas fa-times"></i>
            </button>
        `;

        galleryContainer.insertBefore(newImageBox, addGalleryBtn);
        galleryImageCount++;

        // Setup event listener for the new image input
        const newInput = newImageBox.querySelector('.image-input');
        newInput.addEventListener('change', function() {
            setupImagePreview(this, newImageBox);
        });
    });

    // Remove gallery image
    galleryContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-gallery-image')) {
            const imageBox = e.target.closest('.gallery-image-box');
            imageBox.remove();
            galleryImageCount--;
        }
    });

    // Image preview function
    function setupImagePreview(input, container) {
        const preview = container.querySelector('.image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Uploaded image">
                    <div class="image-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                `;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Variations functionality
    const variationsSection = document.getElementById('variations-section');
    const variationsContainer = document.getElementById('variations-container');
    const addVariationBtn = document.getElementById('add-variation');
    const hasVariationsCheckbox = document.getElementById('has_variations');
    const variationTemplate = document.getElementById('variation-template');
    let variationCount = 0;

    // Toggle variations section
    hasVariationsCheckbox.addEventListener('change', function() {
        variationsSection.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            variationsContainer.innerHTML = '';
            variationCount = 0;
        }
    });

    // Add variation
    addVariationBtn.addEventListener('click', function() {
        const templateContent = variationTemplate.content.cloneNode(true);
        const variationItem = templateContent.querySelector('.variation-item');
        
        // Update all names with current index
        const inputs = variationItem.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace('__index__', variationCount);
            }
        });

        // Update variation number
        variationItem.querySelector('.variation-number').textContent = variationCount + 1;

        variationsContainer.appendChild(variationItem);
        variationCount++;

        // Setup image preview for variation image
        const variationImageInput = variationItem.querySelector('.variation-image-box .image-input');
        const variationImageBox = variationItem.querySelector('.variation-image-box');
        variationImageInput.addEventListener('change', function() {
            setupImagePreview(this, variationImageBox);
        });
    });

    // Remove variation
    variationsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-variation')) {
            const variationItem = e.target.closest('.variation-item');
            variationItem.remove();
            // Reindex remaining variations
            reindexVariations();
        }
    });

    function reindexVariations() {
        const variations = variationsContainer.querySelectorAll('.variation-item');
        variationCount = variations.length;
        
        variations.forEach((variation, index) => {
            variation.querySelector('.variation-number').textContent = index + 1;
            
            const inputs = variation.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    const currentName = input.name;
                    const newName = currentName.replace(/variations\[\d+\]/, `variations[${index}]`);
                    input.name = newName;
                }
            });
        });
    }

        // Initialize with existing variations from old input
        @if(old('has_variations') && old('variations'))
            @foreach(old('variations') as $index => $variation)
                setTimeout(() => {
                    addVariationBtn.click();
                    // Fill with old data
                    const variationItem = variationsContainer.lastElementChild;
                    const oldVariation = {!! json_encode($variation) !!};
                    Object.keys(oldVariation).forEach(key => {
                        const input = variationItem.querySelector(`[name="variations[${$index}][${key}]"]`);
                        if (input && oldVariation[key] !== null) {
                            input.value = oldVariation[key];
                        }
                    });
                }, 100);
            @endforeach
        @endif
});
</script>
@endsection