@extends('admin.adminbase')
@section('title', 'Add New Product')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/create.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Add New Product</h5>
                        <small class="opacity-75">Fill in the product details below</small>
                    </div>
                    <a href="{{ route('admin.manageproduct.index') }}" class="btn btn-light btn-sm rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Back to Products
                    </a>
                </div>
                
                <!-- Progress Indicator -->
                <div class="card-header bg-light border-bottom">
                    <div class="progress-indicator">
                        <div class="progress-step active" data-step="1">
                            <span class="step-number">1</span>
                            <span class="step-label">Basic Info</span>
                        </div>
                        <div class="progress-step" data-step="2">
                            <span class="step-number">2</span>
                            <span class="step-label">Pricing & Stock</span>
                        </div>
                        <div class="progress-step" data-step="3">
                            <span class="step-number">3</span>
                            <span class="step-label">Specifications</span>
                        </div>
                        <div class="progress-step" data-step="4">
                            <span class="step-number">4</span>
                            <span class="step-label">Media</span>
                        </div>
                        <div class="progress-step" data-step="5">
                            <span class="step-number">5</span>
                            <span class="step-label">Variations</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form id="productForm" action="{{ route('admin.manageproduct.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        
                        <!-- Step 1: Basic Information -->
                        <div class="form-step active" data-step="1">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header mb-4">
                                        <h6 class="section-title mb-2"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                        <p class="text-muted mb-0">Provide the essential product details</p>
                                    </div>
                                    <div class="card border-0 bg-light-subtle">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                           id="name" name="name" value="{{ old('name') }}" 
                                                           placeholder="Enter product name" required>
                                                    @error('name')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label for="slug" class="form-label fw-semibold">Slug</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                                               id="slug" name="slug" value="{{ old('slug') }}" 
                                                               placeholder="product-slug">
                                                        <button type="button" class="btn btn-outline-secondary" id="generateSlug">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </div>
                                                    <small class="form-text text-muted">Leave empty to auto-generate from product name</small>
                                                    @error('slug')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
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
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label for="brand" class="form-label fw-semibold">Brand</label>
                                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                                           id="brand" name="brand" value="{{ old('brand') }}" 
                                                           placeholder="Enter brand name">
                                                    @error('brand')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-12">
                                                    <label for="description" class="form-label fw-semibold">Description</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                                              id="description" name="description" rows="4" 
                                                              placeholder="Enter product description">{{ old('description') }}</textarea>
                                                    <div class="form-text d-flex justify-content-between">
                                                        <span>Describe your product in detail</span>
                                                        <span id="descriptionCount">0/1000</span>
                                                    </div>
                                                    @error('description')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Pricing & Stock -->
                        <div class="form-step" data-step="2">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header mb-4">
                                        <h6 class="section-title mb-2"><i class="fas fa-tag me-2"></i>Pricing & Stock</h6>
                                        <p class="text-muted mb-0">Set pricing, inventory and product options</p>
                                    </div>
                                    <div class="card border-0 bg-light-subtle">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="price" class="form-label fw-semibold">Price (RM) <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white">RM</span>
                                                        <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                                               id="price" name="price" value="{{ old('price') }}" required>
                                                    </div>
                                                    @error('price')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label for="stock_quantity" class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                                                    <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                                                    @error('stock_quantity')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Product Options</label>
                                                    <div class="d-flex flex-column gap-2 mt-2">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-medium" for="is_featured">
                                                                <i class="fas fa-star me-1 text-warning"></i>Featured Product
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="is_recommended" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-medium" for="is_recommended">
                                                                <i class="fas fa-thumbs-up me-1 text-primary"></i>Recommended Product
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Specifications -->
                        <div class="form-step" data-step="3">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header mb-4">
                                        <h6 class="section-title mb-2"><i class="fas fa-cogs me-2"></i>Specifications</h6>
                                        <p class="text-muted mb-0">Add technical specifications</p>
                                    </div>
                                    <div class="card border-0 bg-light-subtle">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="processor" class="form-label fw-semibold">Processor</label>
                                                    <input type="text" class="form-control @error('processor') is-invalid @enderror" 
                                                           id="processor" name="processor" value="{{ old('processor') }}" 
                                                           placeholder="e.g., Intel Core i7">
                                                    @error('processor')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label for="ram" class="form-label fw-semibold">RAM</label>
                                                    <input type="text" class="form-control @error('ram') is-invalid @enderror" 
                                                           id="ram" name="ram" value="{{ old('ram') }}" 
                                                           placeholder="e.g., 16GB DDR4">
                                                    @error('ram')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label for="storage" class="form-label fw-semibold">Storage</label>
                                                    <input type="text" class="form-control @error('storage') is-invalid @enderror" 
                                                           id="storage" name="storage" value="{{ old('storage') }}" 
                                                           placeholder="e.g., 512GB SSD">
                                                    @error('storage')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Images -->
                        <div class="form-step" data-step="4">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header mb-4">
                                        <h6 class="section-title mb-2"><i class="fas fa-images me-2"></i>Product Images</h6>
                                        <p class="text-muted mb-0">Upload product images</p>
                                    </div>
                                    <div class="card border-0 bg-light-subtle">
                                        <div class="card-body">
                                            <!-- Main Image -->
                                            <div class="mb-4">
                                                <label for="main_image" class="form-label fw-semibold">Main Image <span class="text-danger">*</span></label>
                                                <div class="file-upload-area">
                                                    <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                                           id="main_image" name="main_image" accept="image/*" required>
                                                    <div class="file-upload-info">
                                                        <small class="text-muted">Recommended: 800x800px, JPG/PNG, max 2MB</small>
                                                    </div>
                                                </div>
                                                @error('main_image')
                                                    <div class="invalid-feedback d-flex align-items-center">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                                <div id="mainImagePreview" class="mt-3"></div>
                                            </div>
                                            
                                            <!-- Gallery Images -->
                                            <div class="mb-3">
                                                <label for="product_images" class="form-label fw-semibold">Gallery Images</label>
                                                <div class="file-upload-area">
                                                    <input type="file" class="form-control @error('product_images') is-invalid @enderror" 
                                                           id="product_images" name="product_images[]" multiple accept="image/*">
                                                    <div class="file-upload-info">
                                                        <small class="text-muted">You can select multiple images. Recommended: 800x800px, JPG/PNG, max 2MB each</small>
                                                    </div>
                                                </div>
                                                @error('product_images')
                                                    <div class="invalid-feedback d-flex align-items-center">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                                @error('product_images.*')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                                <div id="galleryPreview" class="row mt-3 g-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Variations -->
                        <div class="form-step" data-step="5">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header mb-4">
                                        <h6 class="section-title mb-2"><i class="fas fa-layer-group me-2"></i>Product Variations</h6>
                                        <p class="text-muted mb-0">Add product variations if needed</p>
                                    </div>
                                    <div class="card border-0 bg-light-subtle">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="has_variations" name="has_variations" value="1" {{ old('has_variations') ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="has_variations">
                                                        This product has variations
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div id="variationsContainer" class="{{ old('has_variations') ? '' : 'd-none' }}">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0 fw-semibold">Variations</h6>
                                                    <button type="button" class="btn btn-primary btn-sm rounded-pill" id="addVariation">
                                                        <i class="fas fa-plus me-1"></i> Add Variation
                                                    </button>
                                                </div>
                                                
                                                <div id="variationsList">
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
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" id="prevBtn" disabled>
                                        <i class="fas fa-arrow-left me-1"></i> Previous
                                    </button>
                                    
                                    <div>
                                        <button type="button" class="btn btn-primary" id="nextBtn">
                                            Next <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                        
                                        <button type="submit" class="btn btn-success d-none" id="submitBtn">
                                            <i class="fas fa-save me-1"></i> Create Product
                                        </button>
                                    </div>
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
<template id="variationTemplate">
    <div class="variation-item" id="variation_TEMPLATE_INDEX">
        <div class="variation-header">
            <h6 class="variation-title">Variation #<span class="variation-number">TEMPLATE_NUMBER</span></h6>
            <button type="button" class="btn-remove-variation" onclick="removeVariation('TEMPLATE_INDEX')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_sku" class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_sku" name="variations[TEMPLATE_INDEX][sku]" required>
            </div>
            
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_price" class="form-label fw-semibold">Price (RM)</label>
                <input type="number" step="0.01" min="0" class="form-control" 
                       id="variations_TEMPLATE_INDEX_price" name="variations[TEMPLATE_INDEX][price]">
            </div>
            
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_stock" class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                <input type="number" min="0" class="form-control" 
                       id="variations_TEMPLATE_INDEX_stock" name="variations[TEMPLATE_INDEX][stock]" value="0" required>
            </div>
            
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_model" class="form-label fw-semibold">Model</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_model" name="variations[TEMPLATE_INDEX][model]">
            </div>
            
            <div class="col-md-4">
                <label for="variations_TEMPLATE_INDEX_processor" class="form-label fw-semibold">Processor</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_processor" name="variations[TEMPLATE_INDEX][processor]">
            </div>
            
            <div class="col-md-4">
                <label for="variations_TEMPLATE_INDEX_ram" class="form-label fw-semibold">RAM</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_ram" name="variations[TEMPLATE_INDEX][ram]">
            </div>
            
            <div class="col-md-4">
                <label for="variations_TEMPLATE_INDEX_storage" class="form-label fw-semibold">Storage</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_storage" name="variations[TEMPLATE_INDEX][storage]">
            </div>
            
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_storage_type" class="form-label fw-semibold">Storage Type</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_storage_type" name="variations[TEMPLATE_INDEX][storage_type]">
            </div>
            
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_graphics_card" class="form-label fw-semibold">Graphics Card</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_graphics_card" name="variations[TEMPLATE_INDEX][graphics_card]">
            </div>
            
            <div class="col-md-4">
                <label for="variations_TEMPLATE_INDEX_screen_size" class="form-label fw-semibold">Screen Size</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_screen_size" name="variations[TEMPLATE_INDEX][screen_size]">
            </div>
            
            <div class="col-md-4">
                <label for="variations_TEMPLATE_INDEX_os" class="form-label fw-semibold">Operating System</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_os" name="variations[TEMPLATE_INDEX][os]">
            </div>
            
            <div class="col-md-4">
                <label for="variations_TEMPLATE_INDEX_warranty" class="form-label fw-semibold">Warranty</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_warranty" name="variations[TEMPLATE_INDEX][warranty]">
            </div>
            
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_voltage" class="form-label fw-semibold">Voltage</label>
                <input type="text" class="form-control" 
                       id="variations_TEMPLATE_INDEX_voltage" name="variations[TEMPLATE_INDEX][voltage]">
            </div>
            
            <div class="col-md-6">
                <label for="variations_TEMPLATE_INDEX_image_file" class="form-label fw-semibold">Variation Image</label>
                <input type="file" class="form-control" 
                       id="variations_TEMPLATE_INDEX_image_file" name="variations[TEMPLATE_INDEX][image_file]" 
                       accept="image/*">
                <div id="variationImagePreview_TEMPLATE_INDEX" class="mt-2"></div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/createproduct.js'])
@endsection