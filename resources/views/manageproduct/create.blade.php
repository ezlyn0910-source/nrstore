@extends('admin.adminbase')
@section('title', 'Add New Product')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/create.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add New Product</h5>
                    <a href="{{ route('manageproduct.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <form id="productForm" action="{{ route('manageproduct.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="section-title">Basic Information</h6>
                                <div class="border rounded p-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                                   id="slug" name="slug" value="{{ old('slug') }}">
                                            <small class="form-text text-muted">Leave empty to auto-generate from product name</small>
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                                    id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="brand" class="form-label">Brand</label>
                                            <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                                   id="brand" name="brand" value="{{ old('brand') }}">
                                            @error('brand')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-12 mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Stock Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="section-title">Pricing & Stock</h6>
                                <div class="border rounded p-3">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="price" class="form-label">Price (RM) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price') }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                            <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                                            @error('stock_quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Product Options</label>
                                            <div class="d-flex gap-3 mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_featured">Featured</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_recommended" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_recommended">Recommended</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Specifications Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="section-title">Specifications</h6>
                                <div class="border rounded p-3">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="processor" class="form-label">Processor</label>
                                            <input type="text" class="form-control @error('processor') is-invalid @enderror" 
                                                   id="processor" name="processor" value="{{ old('processor') }}">
                                            @error('processor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="ram" class="form-label">RAM</label>
                                            <input type="text" class="form-control @error('ram') is-invalid @enderror" 
                                                   id="ram" name="ram" value="{{ old('ram') }}">
                                            @error('ram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="storage" class="form-label">Storage</label>
                                            <input type="text" class="form-control @error('storage') is-invalid @enderror" 
                                                   id="storage" name="storage" value="{{ old('storage') }}">
                                            @error('storage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="section-title">Product Images</h6>
                                <div class="border rounded p-3">
                                    <!-- Main Image -->
                                    <div class="mb-4">
                                        <label for="main_image" class="form-label">Main Image</label>
                                        <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                               id="main_image" name="main_image" accept="image/*">
                                        @error('main_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div id="mainImagePreview" class="mt-2"></div>
                                    </div>
                                    
                                    <!-- Gallery Images -->
                                    <div class="mb-3">
                                        <label for="product_images" class="form-label">Gallery Images</label>
                                        <input type="file" class="form-control @error('product_images') is-invalid @enderror" 
                                               id="product_images" name="product_images[]" multiple accept="image/*">
                                        @error('product_images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('product_images.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div id="galleryPreview" class="row mt-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Variations Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="section-title">Product Variations</h6>
                                <div class="border rounded p-3">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_variations" name="has_variations" value="1" {{ old('has_variations') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_variations">This product has variations</label>
                                        </div>
                                    </div>
                                    
                                    <div id="variationsContainer" class="{{ old('has_variations') ? '' : 'd-none' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Variations</h6>
                                            <button type="button" class="btn btn-sm btn-primary" id="addVariation">
                                                <i class="fas fa-plus me-1"></i> Add Variation
                                            </button>
                                        </div>
                                        
                                        <div id="variationsList">
                                            <!-- Variations will be added here dynamically -->
                                            @if(old('has_variations') && old('variations'))
                                                @foreach(old('variations') as $index => $variation)
                                                    @include('admin.manageproduct.partials.variation-form', ['index' => $index, 'variation' => $variation])
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('manageproduct.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Create Product
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

<!-- Variation Template (Hidden) -->
<div id="variationTemplate" style="display: none;">
    <div class="variation-item" id="variation_TEMPLATE_INDEX">
        <div class="variation-header">
            <h6 class="variation-title">Variation #<span class="variation-number">TEMPLATE_NUMBER</span></h6>
            <button type="button" class="btn-remove-variation" onclick="removeVariation('TEMPLATE_INDEX')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_sku" class="form-label">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_sku" name="variations[TEMPLATE_INDEX][sku]" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_price" class="form-label">Price (RM)</label>
                <input type="number" step="0.01" min="0" class="form-control" 
                       id="variations_TEMPLATE_INDEX_price" name="variations[TEMPLATE_INDEX][price]">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_stock" class="form-label">Stock <span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" 
                       id="variations_TEMPLATE_INDEX_stock" name="variations[TEMPLATE_INDEX][stock]" value="0" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_model" class="form-label">Model</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_model" name="variations[TEMPLATE_INDEX][model]">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="variations_TEMPLATE_INDEX_processor" class="form-label">Processor</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_processor" name="variations[TEMPLATE_INDEX][processor]">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="variations_TEMPLATE_INDEX_ram" class="form-label">RAM</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_ram" name="variations[TEMPLATE_INDEX][ram]">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="variations_TEMPLATE_INDEX_storage" class="form-label">Storage</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_storage" name="variations[TEMPLATE_INDEX][storage]">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_storage_type" class="form-label">Storage Type</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_storage_type" name="variations[TEMPLATE_INDEX][storage_type]">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_graphics_card" class="form-label">Graphics Card</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_graphics_card" name="variations[TEMPLATE_INDEX][graphics_card]">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="variations_TEMPLATE_INDEX_screen_size" class="form-label">Screen Size</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_screen_size" name="variations[TEMPLATE_INDEX][screen_size]">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="variations_TEMPLATE_INDEX_os" class="form-label">Operating System</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_os" name="variations[TEMPLATE_INDEX][os]">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="variations_TEMPLATE_INDEX_warranty" class="form-label">Warranty</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_warranty" name="variations[TEMPLATE_INDEX][warranty]">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_voltage" class="form-label">Voltage</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_voltage" name="variations[TEMPLATE_INDEX][voltage]">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="variations_TEMPLATE_INDEX_image_file" class="form-label">Variation Image</label>
                <input type="file" class="form-control" 
                       id="variations_TEMPLATE_INDEX_image_file" name="variations[TEMPLATE_INDEX][image_file]" 
                       accept="image/*">
                <div id="variationImagePreview_TEMPLATE_INDEX" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from product name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('blur', function() {
        if (!slugInput.value) {
            generateSlug();
        }
    });
    
    function generateSlug() {
        const name = nameInput.value;
        const slug = name.toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugInput.value = slug;
    }

    // Image preview functionality
    const mainImageInput = document.getElementById('main_image');
    const galleryInput = document.getElementById('product_images');
    const mainImagePreview = document.getElementById('mainImagePreview');
    const galleryPreview = document.getElementById('galleryPreview');

    // Main image preview
    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mainImagePreview.innerHTML = `
                    <div class="image-preview-container">
                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">
                        <button type="button" class="btn-remove-image" onclick="removeMainImage()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Gallery images preview
    galleryInput.addEventListener('change', function(e) {
        const files = e.target.files;
        galleryPreview.innerHTML = '';
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = (function(file, index) {
                return function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-3';
                    col.innerHTML = `
                        <div class="image-preview-container">
                            <img src="${e.target.result}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                            <button type="button" class="btn-remove-image" onclick="removeGalleryImage(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    galleryPreview.appendChild(col);
                };
            })(file, i);
            
            reader.readAsDataURL(file);
        }
    });

    // Variation management
    const hasVariationsCheckbox = document.getElementById('has_variations');
    const variationsContainer = document.getElementById('variationsContainer');
    const variationsList = document.getElementById('variationsList');
    const addVariationBtn = document.getElementById('addVariation');
    let variationCount = {{ old('has_variations') && old('variations') ? count(old('variations')) : 0 }};

    hasVariationsCheckbox.addEventListener('change', function() {
        variationsContainer.classList.toggle('d-none', !this.checked);
    });

    addVariationBtn.addEventListener('click', function() {
        addVariation();
    });

    function addVariation() {
        const template = document.getElementById('variationTemplate');
        let variationHtml = template.innerHTML;
        
        // Replace all template placeholders with actual values
        variationHtml = variationHtml.replace(/TEMPLATE_INDEX/g, variationCount);
        variationHtml = variationHtml.replace(/TEMPLATE_NUMBER/g, variationCount + 1);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = variationHtml;
        
        variationsList.appendChild(tempDiv.firstElementChild);
        
        // Initialize image preview for the new variation
        const variationIndex = variationCount;
        const variationImageInput = document.getElementById(`variations_${variationIndex}_image_file`);
        const variationImagePreview = document.getElementById(`variationImagePreview_${variationIndex}`);
        
        if (variationImageInput && variationImagePreview) {
            variationImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        variationImagePreview.innerHTML = `
                            <div class="image-preview-container">
                                <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">
                                <button type="button" class="btn-remove-image" onclick="removeVariationImage(${variationIndex})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        variationCount++;
    }

    // Remove variation
    window.removeVariation = function(index) {
        const variationElement = document.getElementById(`variation_${index}`);
        if (variationElement) {
            variationElement.remove();
        }
        // Re-number remaining variations
        updateVariationNumbers();
    };

    function updateVariationNumbers() {
        const variationItems = variationsList.querySelectorAll('.variation-item');
        variationItems.forEach((item, index) => {
            const numberSpan = item.querySelector('.variation-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    // Image removal functions
    window.removeMainImage = function() {
        mainImageInput.value = '';
        mainImagePreview.innerHTML = '';
    };

    window.removeGalleryImage = function(index) {
        // Create a new FileList without the removed file
        const dt = new DataTransfer();
        const files = galleryInput.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        galleryInput.files = dt.files;
        
        // Update preview
        galleryInput.dispatchEvent(new Event('change'));
    };

    window.removeVariationImage = function(index) {
        const input = document.getElementById(`variations_${index}_image_file`);
        const preview = document.getElementById(`variationImagePreview_${index}`);
        
        if (input) input.value = '';
        if (preview) preview.innerHTML = '';
    };

    // Form validation
    const form = document.getElementById('productForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Basic validation
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            }
        });

        // Variation validation if enabled
        if (hasVariationsCheckbox.checked) {
            const variationElements = variationsList.querySelectorAll('.variation-item');
            if (variationElements.length === 0) {
                isValid = false;
                alert('Please add at least one variation when variations are enabled.');
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Initialize variations if coming from validation error
    @if(old('has_variations') && old('variations'))
        // Variations are already rendered via PHP
        variationCount = {{ count(old('variations')) }};
    @endif
});
</script>
@endsection