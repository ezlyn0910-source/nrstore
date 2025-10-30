@extends('admin.adminbase')
@section('title', 'Add New Product')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/create.css', 'resources/js/app.js'])
    <style>
        .image-preview-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 6px;
        }
        .variation-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #fff;
        }
        .variation-header {
            background: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        .gallery-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin: 5px;
        }
        .remove-image-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            color: white;
            font-size: 12px;
            line-height: 1;
            cursor: pointer;
        }
        .tab-pane {
            padding: 20px 0;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Add New Product
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        
                        <!-- Success/Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Navigation Tabs -->
                        <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                                    <i class="fas fa-images me-2"></i>Product Images
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="variations-tab" data-bs-toggle="tab" data-bs-target="#variations" type="button" role="tab">
                                    <i class="fas fa-layer-group me-2"></i>Product Variations
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Basic Information Tab -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label required-field">Product Name</label>
                                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                                   id="product_name" name="product_name" 
                                                   value="{{ old('product_name') }}" 
                                                   placeholder="Enter product name" required>
                                            @error('product_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="4"
                                                      placeholder="Enter product description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                                    id="category_id" name="category_id">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="base_price" class="form-label">Base Price (RM)</label>
                                            <input type="number" step="0.01" min="0" class="form-control @error('base_price') is-invalid @enderror" 
                                                   id="base_price" name="base_price" 
                                                   value="{{ old('base_price') }}" 
                                                   placeholder="0.00">
                                            @error('base_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="total_stock" class="form-label">Total Stock</label>
                                            <input type="number" min="0" class="form-control @error('total_stock') is-invalid @enderror" 
                                                   id="total_stock" name="total_stock" 
                                                   value="{{ old('total_stock', 0) }}">
                                            @error('total_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="has_variations" name="has_variations" value="1" {{ old('has_variations') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_variations">
                                                This product has variations (different models/specifications)
                                            </label>
                                        </div>

                                        <div class="alert alert-info">
                                            <small>
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Note:</strong> If you enable variations, the base price and stock will be ignored, and you'll need to add individual variations with their own pricing and stock levels.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Images Tab -->
                            <div class="tab-pane fade" id="images" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="main_image" class="form-label">Main Product Image</label>
                                            <div class="image-preview-container mb-3" id="mainImagePreviewContainer">
                                                <div id="mainImagePreview" class="text-center">
                                                    <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                                    <p class="text-muted mb-0">No image selected</p>
                                                </div>
                                            </div>
                                            <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                                   id="main_image" name="main_image" accept="image/*">
                                            <small class="text-muted">Recommended: 500x500px, Max: 2MB (JPEG, PNG, JPG, GIF)</small>
                                            @error('main_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_images" class="form-label">Additional Product Images</label>
                                            <div class="image-preview-container mb-3" id="galleryPreviewContainer">
                                                <div id="galleryPreview" class="d-flex flex-wrap justify-content-center align-items-center">
                                                    <i class="fas fa-images fa-2x text-muted mb-2"></i>
                                                    <p class="text-muted mb-0 w-100">No images selected</p>
                                                </div>
                                            </div>
                                            <input type="file" class="form-control @error('product_images') is-invalid @enderror" 
                                                   id="product_images" name="product_images[]" multiple accept="image/*">
                                            <small class="text-muted">You can select multiple images. Hold Ctrl/Cmd to select multiple files.</small>
                                            @error('product_images')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @error('product_images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Variations Tab -->
                            <div class="tab-pane fade" id="variations" role="tabpanel">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Important:</strong> Variations are only used if "This product has variations" is checked in the Basic Information tab.
                                </div>

                                <div id="variations-container">
                                    <!-- Variations will be added here dynamically -->
                                    <div class="text-center text-muted py-4" id="no-variations-message">
                                        <i class="fas fa-layer-group fa-2x mb-2"></i>
                                        <p>No variations added yet. Click the button below to add your first variation.</p>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-success" id="add-variation-btn">
                                        <i class="fas fa-plus me-2"></i>Add Variation
                                    </button>
                                </div>

                                <!-- Variation Template (Hidden) -->
                                <div id="variation-template" class="d-none">
                                    <div class="variation-card">
                                        <div class="variation-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-primary">
                                                <i class="fas fa-cube me-2"></i>Variation <span class="variation-number">1</span>
                                            </h6>
                                            <button type="button" class="btn btn-danger btn-sm remove-variation">
                                                <i class="fas fa-times me-1"></i>Remove
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="border-bottom pb-2">Basic Information</h6>
                                                    
                                                    <!-- Variation Image -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Variation Image</label>
                                                        <div class="variation-image-upload">
                                                            <div class="image-preview-container mb-2">
                                                                <div class="variation-image-preview text-center">
                                                                    <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                                                    <p class="text-muted mb-0">No image selected</p>
                                                                </div>
                                                            </div>
                                                            <input type="file" class="form-control variation-image-input d-none" accept="image/*">
                                                            <input type="hidden" class="variation-image-data" name="variations[INDEX][image]">
                                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 variation-image-upload-btn">
                                                                <i class="fas fa-upload me-2"></i>Upload Image
                                                            </button>
                                                            <small class="text-muted">Optional: Specific image for this variation</small>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label required-field">SKU</label>
                                                        <input type="text" class="form-control variation-sku" name="variations[INDEX][sku]" 
                                                               placeholder="Enter SKU" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Price (RM)</label>
                                                        <input type="number" step="0.01" min="0" class="form-control variation-price" 
                                                               name="variations[INDEX][price]" placeholder="0.00">
                                                        <small class="text-muted">Leave empty to use product base price</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label required-field">Stock</label>
                                                        <input type="number" min="0" class="form-control variation-stock" 
                                                               name="variations[INDEX][stock]" value="0" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="border-bottom pb-2">Technical Specifications</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Model</label>
                                                        <input type="text" class="form-control variation-model" 
                                                               name="variations[INDEX][model]" placeholder="Enter model">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Processor</label>
                                                        <input type="text" class="form-control variation-processor" 
                                                               name="variations[INDEX][processor]" placeholder="Enter processor">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">RAM (GB)</label>
                                                                <input type="number" min="0" class="form-control variation-ram" 
                                                                       name="variations[INDEX][ram]" placeholder="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Storage (GB)</label>
                                                                <input type="number" min="0" class="form-control variation-storage" 
                                                                       name="variations[INDEX][storage]" placeholder="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Storage Type</label>
                                                        <select class="form-control variation-storage-type" name="variations[INDEX][storage_type]">
                                                            <option value="">Select Type</option>
                                                            <option value="SSD">SSD</option>
                                                            <option value="HDD">HDD</option>
                                                            <option value="NVMe">NVMe</option>
                                                            <option value="eMMC">eMMC</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Graphics Card</label>
                                                        <input type="text" class="form-control variation-graphics" 
                                                               name="variations[INDEX][graphics_card]" placeholder="Enter graphics card">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Screen Size</label>
                                                        <input type="text" class="form-control variation-screen" 
                                                               name="variations[INDEX][screen_size]" placeholder="e.g., 15.6 inch">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Operating System</label>
                                                        <input type="text" class="form-control variation-os" 
                                                               name="variations[INDEX][os]" placeholder="e.g., Windows 11 Home">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Warranty</label>
                                                        <input type="text" class="form-control variation-warranty" 
                                                               name="variations[INDEX][warranty]" placeholder="e.g., 2 Years">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Voltage</label>
                                                        <input type="text" class="form-control variation-voltage" 
                                                               name="variations[INDEX][voltage]" placeholder="e.g., 100-240V">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitButton">
                                        <i class="fas fa-save me-2"></i>Create Product
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product creation form loaded successfully');

    // Initialize variables
    let variationCount = 0;
    const variationsContainer = document.getElementById('variations-container');
    const noVariationsMessage = document.getElementById('no-variations-message');
    const variationTemplate = document.getElementById('variation-template');
    const addVariationBtn = document.getElementById('add-variation-btn');
    const hasVariationsCheckbox = document.getElementById('has_variations');
    const productForm = document.getElementById('productForm');
    const submitButton = document.getElementById('submitButton');

    // ===== FORM SUBMISSION - SIMPLIFIED =====
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            console.log('Form submission started');
            
            // Basic validation
            const productName = document.getElementById('product_name');
            if (!productName.value.trim()) {
                e.preventDefault();
                productName.focus();
                alert('Please enter a product name');
                return false;
            }

            // Validate variations if any exist
            if (variationCount > 0) {
                const variationSkus = document.querySelectorAll('.variation-sku');
                const variationStocks = document.querySelectorAll('.variation-stock');
                let valid = true;

                variationSkus.forEach((skuInput, index) => {
                    if (!skuInput.value.trim()) {
                        valid = false;
                        skuInput.classList.add('is-invalid');
                    } else {
                        skuInput.classList.remove('is-invalid');
                    }
                });

                variationStocks.forEach((stockInput, index) => {
                    if (!stockInput.value || stockInput.value < 0) {
                        valid = false;
                        stockInput.classList.add('is-invalid');
                    } else {
                        stockInput.classList.remove('is-invalid');
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert('Please fill in all required variation fields (SKU and Stock)');
                    return false;
                }
            }

            // If we get here, form is valid
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
            console.log('Form validation passed, submitting...');
        });
    }

    // ===== IMAGE HANDLING =====
    
    // Main image preview
    const mainImageInput = document.getElementById('main_image');
    const mainImagePreview = document.getElementById('mainImagePreview');
    
    if (mainImageInput && mainImagePreview) {
        mainImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    mainImagePreview.innerHTML = `<img src="${e.target.result}" class="image-preview" alt="Main Image Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                mainImagePreview.innerHTML = '<i class="fas fa-image fa-3x text-muted mb-2"></i><p class="text-muted mb-0">No image selected</p>';
            }
        });
    }

    // Gallery images preview
    const galleryImagesInput = document.getElementById('product_images');
    const galleryPreview = document.getElementById('galleryPreview');
    
    if (galleryImagesInput && galleryPreview) {
        galleryImagesInput.addEventListener('change', function(e) {
            const files = e.target.files;
            
            if (files.length > 0) {
                galleryPreview.innerHTML = '';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'position-relative d-inline-block m-2';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'gallery-thumbnail';
                        img.alt = `Gallery image ${i + 1}`;
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'remove-image-btn';
                        removeBtn.innerHTML = '×';
                        removeBtn.onclick = function() {
                            imgContainer.remove();
                            // Update file input
                            const newFiles = Array.from(galleryImagesInput.files).filter((_, index) => index !== i);
                            const dataTransfer = new DataTransfer();
                            newFiles.forEach(file => dataTransfer.items.add(file));
                            galleryImagesInput.files = dataTransfer.files;
                        };
                        
                        imgContainer.appendChild(img);
                        imgContainer.appendChild(removeBtn);
                        galleryPreview.appendChild(imgContainer);
                    };
                    
                    reader.readAsDataURL(file);
                }
            } else {
                galleryPreview.innerHTML = '<i class="fas fa-images fa-2x text-muted mb-2"></i><p class="text-muted mb-0 w-100">No images selected</p>';
            }
        });
    }

    // ===== VARIATION HANDLING =====
    
    function addVariation() {
        if (!variationTemplate || !variationsContainer) {
            console.error('Required elements not found');
            return;
        }

        const newVariation = variationTemplate.cloneNode(true);
        newVariation.classList.remove('d-none');
        
        // Update all indices in the new variation
        const inputs = newVariation.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[INDEX]', `[${variationCount}]`));
            }
        });

        // Update variation number
        const variationNumber = newVariation.querySelector('.variation-number');
        if (variationNumber) {
            variationNumber.textContent = variationCount + 1;
        }

        // Variation image handling
        const imageInput = newVariation.querySelector('.variation-image-input');
        const imageData = newVariation.querySelector('.variation-image-data');
        const imagePreview = newVariation.querySelector('.variation-image-preview');
        const uploadBtn = newVariation.querySelector('.variation-image-upload-btn');

        if (uploadBtn && imageInput) {
            uploadBtn.addEventListener('click', function() {
                imageInput.click();
            });
        }

        if (imageInput && imagePreview && imageData) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.innerHTML = `<img src="${e.target.result}" class="image-preview" alt="Variation Image">`;
                        imageData.value = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Remove variation
        const removeBtn = newVariation.querySelector('.remove-variation');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this variation?')) {
                    newVariation.remove();
                    variationCount--;
                    updateVariationsDisplay();
                }
            });
        }

        variationsContainer.appendChild(newVariation);
        variationCount++;
        updateVariationsDisplay();
        
        console.log(`Variation added. Total: ${variationCount}`);
    }

    function updateVariationsDisplay() {
        if (noVariationsMessage) {
            noVariationsMessage.style.display = variationCount > 0 ? 'none' : 'block';
        }
    }

    // Add variation button
    if (addVariationBtn) {
        addVariationBtn.addEventListener('click', addVariation);
    }

    // Auto-add first variation if checkbox is checked
    if (hasVariationsCheckbox && hasVariationsCheckbox.checked) {
        addVariation();
    }

    // Toggle variations based on checkbox
    if (hasVariationsCheckbox) {
        hasVariationsCheckbox.addEventListener('change', function() {
            if (this.checked && variationCount === 0) {
                addVariation();
            }
        });
    }

    // ===== TAB FUNCTIONALITY =====
    const triggerTabList = [].slice.call(document.querySelectorAll('#productTabs button'));
    triggerTabList.forEach(function (triggerEl) {
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            const tabTrigger = new bootstrap.Tab(triggerEl);
            tabTrigger.show();
        });
    });

    console.log('All event listeners attached successfully');
});
</script>
@endsection