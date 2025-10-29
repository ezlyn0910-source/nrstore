@extends('admin.adminbase')
@section('title', 'Add New Product')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/create.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Product</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        
                        <ul class="nav nav-tabs" id="productTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Basic Information</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="variations-tab" data-bs-toggle="tab" data-bs-target="#variations" type="button" role="tab">Product Variations</button>
                            </li>
                        </ul>

                        <div class="tab-content p-3 border border-top-0" id="productTabsContent">
                            <!-- Basic Information Tab -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                                   id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                            @error('product_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                        <!-- Main Product Image -->
                                        <div class="mb-3">
                                            <label for="main_image" class="form-label">Main Product Image</label>
                                            <div class="image-upload-container">
                                                <div class="image-preview mb-2" id="mainImagePreview">
                                                    <img src="" alt="Main Image Preview" class="img-thumbnail d-none" id="previewMainImage" style="max-height: 200px;">
                                                </div>
                                                <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                                       id="main_image" name="main_image" accept="image/*">
                                                <small class="text-muted">Primary product image. Recommended: 500x500px, Max: 2MB</small>
                                                @error('main_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Multiple Product Images -->
                                        <div class="mb-3">
                                            <label class="form-label">Additional Product Images</label>
                                            <div class="multiple-image-upload">
                                                <div class="image-gallery-preview mb-2 d-flex flex-wrap gap-2" id="imageGalleryPreview">
                                                    <!-- Gallery images will appear here -->
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

                                        <div class="mb-3">
                                            <label for="base_price" class="form-label">Base Price (RM)</label>
                                            <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                                                   id="base_price" name="base_price" value="{{ old('base_price') }}">
                                            @error('base_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="total_stock" class="form-label">Total Stock</label>
                                            <input type="number" class="form-control @error('total_stock') is-invalid @enderror" 
                                                   id="total_stock" name="total_stock" value="{{ old('total_stock') }}">
                                            @error('total_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="has_variations" name="has_variations" value="1" {{ old('has_variations') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_variations">This product has variations</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Variations Tab -->
                            <div class="tab-pane fade" id="variations" role="tabpanel">
                                <div class="alert alert-info">
                                    <strong>Note:</strong> If this product has different models/specifications (e.g., different RAM, storage, colors), add them as variations below.
                                    If no variations are added, the product will use the base price and stock above.
                                </div>

                                <div id="variations-container">
                                    <!-- Variations will be added here dynamically -->
                                </div>

                                <button type="button" class="btn btn-success btn-sm mt-3" id="add-variation-btn">
                                    <i class="fas fa-plus"></i> Add Variation
                                </button>

                                <!-- Variation Template (Hidden) -->
                                <div id="variation-template" class="d-none">
                                    <div class="variation-item card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Variation <span class="variation-number">1</span></h6>
                                            <button type="button" class="btn btn-danger btn-sm remove-variation">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Basic Information</h6>
                                                    
                                                    <!-- Variation Image Upload -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Variation Image</label>
                                                        <div class="variation-image-upload">
                                                            <div class="image-preview mb-2 position-relative" style="width: 100px;">
                                                                <img src="" alt="Variation Image Preview" class="img-thumbnail variation-preview d-none" style="max-height: 100px; width: 100%;">
                                                                <button type="button" class="btn btn-sm btn-outline-danger variation-remove-image d-none" style="position: absolute; top: 2px; right: 2px; padding: 2px 5px;">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <input type="file" class="form-control variation-image-input d-none" accept="image/*">
                                                            <input type="hidden" class="variation-image-data" name="variations[0][image]">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm mt-1 variation-image-upload-btn">
                                                                <i class="fas fa-upload"></i> Upload Image
                                                            </button>
                                                            <small class="text-muted d-block">Optional: Specific image for this variation</small>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">SKU <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control variation-sku" name="variations[0][sku]" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Price (RM)</label>
                                                        <input type="number" step="0.01" class="form-control variation-price" name="variations[0][price]">
                                                        <small class="text-muted">Leave empty to use product base price</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Stock <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control variation-stock" name="variations[0][stock]" value="0" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Technical Specifications</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Model</label>
                                                        <input type="text" class="form-control variation-model" name="variations[0][model]">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Processor</label>
                                                        <input type="text" class="form-control variation-processor" name="variations[0][processor]">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">RAM (GB)</label>
                                                                <input type="number" class="form-control variation-ram" name="variations[0][ram]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Storage (GB)</label>
                                                                <input type="number" class="form-control variation-storage" name="variations[0][storage]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Storage Type</label>
                                                        <select class="form-control variation-storage-type" name="variations[0][storage_type]">
                                                            <option value="">Select Type</option>
                                                            <option value="SSD">SSD</option>
                                                            <option value="HDD">HDD</option>
                                                            <option value="NVMe">NVMe</option>
                                                            <option value="eMMC">eMMC</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Graphics Card</label>
                                                        <input type="text" class="form-control variation-graphics" name="variations[0][graphics_card]">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Screen Size</label>
                                                        <input type="text" class="form-control variation-screen" name="variations[0][screen_size]" placeholder="e.g., 15.6 inch">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Operating System</label>
                                                        <input type="text" class="form-control variation-os" name="variations[0][os]" placeholder="e.g., Windows 11 Home">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Warranty</label>
                                                        <input type="text" class="form-control variation-warranty" name="variations[0][warranty]" placeholder="e.g., 2 Years">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Voltage</label>
                                                        <input type="text" class="form-control variation-voltage" name="variations[0][voltage]" placeholder="e.g., 100-240V">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let variationCount = 0;
    const variationsContainer = document.getElementById('variations-container');
    const variationTemplate = document.getElementById('variation-template');
    const addVariationBtn = document.getElementById('add-variation-btn');
    const hasVariationsCheckbox = document.getElementById('has_variations');

    // ===== PRODUCT IMAGE HANDLING =====
    
    // Main image preview
    const mainImageInput = document.getElementById('main_image');
    const previewMainImage = document.getElementById('previewMainImage');
    
    if (mainImageInput) {
        mainImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewMainImage.src = e.target.result;
                    previewMainImage.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                previewMainImage.src = '';
                previewMainImage.classList.add('d-none');
            }
        });
    }

    // Multiple gallery images preview
    const galleryImagesInput = document.getElementById('product_images');
    const galleryPreview = document.getElementById('imageGalleryPreview');
    
    if (galleryImagesInput) {
        galleryImagesInput.addEventListener('change', function(e) {
            const files = e.target.files;
            galleryPreview.innerHTML = '';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative';
                    imgContainer.style.width = '80px';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';
                    img.style.width = '100%';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute';
                    removeBtn.style.top = '2px';
                    removeBtn.style.right = '2px';
                    removeBtn.style.padding = '2px 5px';
                    removeBtn.style.fontSize = '12px';
                    removeBtn.style.lineHeight = '1';
                    removeBtn.innerHTML = '×';
                    removeBtn.onclick = function() {
                        imgContainer.remove();
                        updateFileInput(files, i);
                    };
                    
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    galleryPreview.appendChild(imgContainer);
                };
                
                reader.readAsDataURL(file);
            }
        });
    }

    // Update file input after removal
    function updateFileInput(originalFiles, indexToRemove) {
        // Create a new FileList without the removed file
        const dataTransfer = new DataTransfer();
        
        for (let i = 0; i < originalFiles.length; i++) {
            if (i !== indexToRemove) {
                dataTransfer.items.add(originalFiles[i]);
            }
        }
        
        galleryImagesInput.files = dataTransfer.files;
    }

    // ===== VARIATION HANDLING =====
    
    // Add variation function
    function addVariation() {
        const newVariation = variationTemplate.cloneNode(true);
        newVariation.classList.remove('d-none');
        
        // Update all indices in the new variation
        const inputs = newVariation.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[0]', `[${variationCount}]`));
            }
        });

        // Update variation number
        newVariation.querySelector('.variation-number').textContent = variationCount + 1;

        // Enhanced image upload functionality for variation
        const imageInput = newVariation.querySelector('.variation-image-input');
        const imageData = newVariation.querySelector('.variation-image-data');
        const previewImg = newVariation.querySelector('.variation-preview');
        const uploadBtn = newVariation.querySelector('.variation-image-upload-btn');
        const removeBtn = newVariation.querySelector('.variation-remove-image');

        // Upload button click handler
        uploadBtn.addEventListener('click', function() {
            imageInput.click();
        });

        // Image input change handler
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('d-none');
                    removeBtn.classList.remove('d-none');
                    imageData.value = e.target.result; // Store base64 data
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image button handler
        removeBtn.addEventListener('click', function() {
            previewImg.src = '';
            previewImg.classList.add('d-none');
            removeBtn.classList.add('d-none');
            imageData.value = '';
            imageInput.value = '';
        });

        // Remove variation button handler
        newVariation.querySelector('.remove-variation').addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this variation?')) {
                newVariation.remove();
                variationCount--;
                if (variationCount === 0) {
                    hasVariationsCheckbox.checked = false;
                }
            }
        });

        variationsContainer.appendChild(newVariation);
        variationCount++;
    }

    // Add first variation automatically if checkbox is checked
    if (hasVariationsCheckbox && hasVariationsCheckbox.checked) {
        addVariation();
    }

    // Toggle variations based on checkbox
    if (hasVariationsCheckbox) {
        hasVariationsCheckbox.addEventListener('change', function() {
            if (this.checked && variationCount === 0) {
                addVariation();
            } else if (!this.checked) {
                if (variationCount > 0 && confirm('This will remove all variations. Continue?')) {
                    variationsContainer.innerHTML = '';
                    variationCount = 0;
                } else if (variationCount === 0) {
                    // Do nothing
                } else {
                    this.checked = true; // Keep checked if user cancels
                }
            }
        });
    }

    // Add variation button
    if (addVariationBtn) {
        addVariationBtn.addEventListener('click', addVariation);
    }

    // ===== FORM VALIDATION =====
    
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            let isValid = true;
            const productName = document.getElementById('product_name');
            
            // Basic validation
            if (!productName.value.trim()) {
                isValid = false;
                productName.classList.add('is-invalid');
            } else {
                productName.classList.remove('is-invalid');
            }
            
            // Validate variations if any
            if (variationCount > 0) {
                const variationSkus = variationsContainer.querySelectorAll('.variation-sku');
                variationSkus.forEach((skuInput, index) => {
                    if (!skuInput.value.trim()) {
                        isValid = false;
                        skuInput.classList.add('is-invalid');
                    } else {
                        skuInput.classList.remove('is-invalid');
                    }
                });
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields (marked with *)');
            }
        });
    }
});

// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const triggerTabList = [].slice.call(document.querySelectorAll('#productTabs button'));
    triggerTabList.forEach(function (triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});
</script>


@endsection