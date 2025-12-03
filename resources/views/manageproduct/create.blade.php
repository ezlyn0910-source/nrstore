@extends('admin.adminbase')
@section('title', 'Create Product')

@section('content')

<style>
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-gold: #DAA112;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
    --error-color: #dc3545;
    --success-color: #28a745;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background-color: var(--light-bone);
    color: var(--dark-text);
    line-height: 1.6;
}

.product-form-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.product-form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-light);
}

.product-form-header h2 {
    color: var(--primary-dark);
    font-size: 2rem;
    font-weight: 600;
}

.form-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel {
    background-color: var(--light-bone);
    color: var(--light-text);
    border: 1px solid var(--border-light);
}

.btn-cancel:hover {
    background-color: #e9ecef;
    color: var(--primary-dark);
}

.btn-save {
    background-color: var(--primary-green);
    color: var(--white);
}

.btn-save:hover {
    background-color: var(--primary-dark);
    transform: translateY(-1px);
}

.btn-remove {
    background-color: transparent;
    color: var(--error-color);
    padding: 0.5rem;
    border: 1px solid var(--error-color);
}

.btn-remove:hover {
    background-color: var(--error-color);
    color: var(--white);
}

.btn-add-variation {
    background-color: var(--accent-gold);
    color: var(--primary-dark);
    font-weight: 600;
}

.btn-add-variation:hover {
    background-color: #c2910f;
    transform: translateY(-1px);
}

.alert-messages {
    margin-bottom: 2rem;
}

.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.alert-error {
    background-color: #fee;
    color: var(--error-color);
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #eff8ff;
    color: var(--success-color);
    border: 1px solid #b8daff;
}

.product-form {
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.form-section {
    padding: 2rem;
    border-bottom: 1px solid var(--border-light);
}

.form-section:last-child {
    border-bottom: none;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.section-header i {
    color: var(--accent-gold);
    font-size: 1.25rem;
}

.section-header h3 {
    color: var(--primary-dark);
    font-size: 1.5rem;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--primary-dark);
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-light);
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background-color: var(--white);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--accent-gold);
    box-shadow: 0 0 0 3px rgba(218, 161, 18, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-help {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: var(--light-text);
}

.form-error {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: var(--error-color);
}

.form-checkboxes {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-container input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-light);
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-container input[type="checkbox"]:checked + .checkmark {
    background-color: var(--accent-gold);
    border-color: var(--accent-gold);
}

.checkbox-container input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: var(--white);
    font-size: 12px;
    font-weight: bold;
}

/* Image Upload Styles */
.image-upload-section h4 {
    margin-bottom: 1rem;
    color: var(--primary-dark);
    font-weight: 600;
}

.main-image-upload {
    margin-bottom: 2rem;
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.image-upload-box {
    position: relative;
    border: 2px dashed var(--border-light);
    border-radius: 8px;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

.image-upload-box:hover {
    border-color: var(--accent-gold);
}

.image-upload-box.main-image-box {
    height: 200px;
    max-width: 300px;
}

.image-preview {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}

.placeholder {
    text-align: center;
    color: var(--light-text);
}

.placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: var(--white);
}

.image-upload-box:hover .image-overlay {
    opacity: 1;
}

.image-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.add-image-content {
    text-align: center;
    color: var(--light-text);
}

.add-image-content i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

/* Variation Styles */
.variations-section {
    background-color: #fafbf9;
}

.variation-item {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.variation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-light);
}

.variation-header h4 {
    color: var(--primary-dark);
    font-weight: 600;
}

.variation-content .form-grid {
    margin-bottom: 1rem;
}

.variation-image-upload {
    margin-top: 1rem;
}

.variation-image-upload label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--primary-dark);
}

.variation-image-box {
    max-width: 200px;
    height: 120px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-form-container {
        padding: 1rem;
    }

    .product-form-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .form-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .form-section {
        padding: 1.5rem;
    }

    .image-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }

    .image-upload-box.main-image-box {
        max-width: 100%;
    }

    .form-checkboxes {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .product-form-header h2 {
        font-size: 1.5rem;
    }

    .section-header h3 {
        font-size: 1.25rem;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        font-size: 0.85rem;
    }

    .image-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Loading States */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Animation for new elements */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.variation-item,
.image-upload-box {
    animation: fadeIn 0.3s ease;
}

/* Focus styles for accessibility */
.btn:focus,
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: 2px solid var(--accent-gold);
    outline-offset: 2px;
}
</style>

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