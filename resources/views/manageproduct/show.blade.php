@extends('admin.adminbase')
@section('title', 'Product Details')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/show.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="roweor col-md-10 x-auto">
            <div class="card product-detail-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Product Details</h3>
                    <div class="action-buttons">
                        <a href="{{ route('admin.manageproduct.edit', $product) }}" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.manageproduct.index') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Basic Information Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="section-header">
                                <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                            </div>
                            <div class="info-table">
                                <div class="info-row">
                                    <div class="info-label">Product ID:</div>
                                    <div class="info-value">{{ $product->id }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Product Name:</div>
                                    <div class="info-value">{{ $product->name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Category:</div>
                                    <div class="info-value">
                                        @if($product->category)
                                            <span class="category-badge">{{ $product->category->name }}</span>
                                        @else
                                            <span class="no-category">No Category</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Brand:</div>
                                    <div class="info-value">{{ $product->brand ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Type:</div>
                                    <div class="info-value">{{ $product->type ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Sub Type:</div>
                                    <div class="info-value">{{ $product->sub_type ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="section-header">
                                <h5><i class="fas fa-chart-line"></i> Pricing & Stock</h5>
                            </div>
                            <div class="info-table">
                                <div class="info-row">
                                    <div class="info-label">Base Price:</div>
                                    <div class="info-value price">RM {{ number_format($product->price, 2) }}</div>
                                </div>
                                @if($product->has_variations)
                                <div class="info-row">
                                    <div class="info-label">Price Range:</div>
                                    <div class="info-value">
                                        <span class="price-range">RM {{ number_format($product->min_price, 2) }} - RM {{ number_format($product->max_price, 2) }}</span>
                                    </div>
                                </div>
                                @endif
                                <div class="info-row">
                                    <div class="info-label">Total Stock:</div>
                                    <div class="info-value">
                                        @if($product->total_stock > 0)
                                            <span class="stock-badge in-stock">{{ $product->total_stock }} units</span>
                                        @else
                                            <span class="stock-badge out-of-stock">Out of Stock</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Stock Status:</div>
                                    <div class="info-value">
                                        <span class="status-badge {{ $product->stock_status }}">{{ $product->stock_status_label }}</span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Status:</div>
                                    <div class="info-value">
                                        @if($product->is_active)
                                            <span class="status-badge active">Active</span>
                                        @else
                                            <span class="status-badge inactive">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Featured:</div>
                                    <div class="info-value">
                                        @if($product->is_featured)
                                            <span class="featured-badge">Yes</span>
                                        @else
                                            <span class="not-featured-badge">No</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Recommended:</div>
                                    <div class="info-value">
                                        @if($product->is_recommended)
                                            <span class="featured-badge">Yes</span>
                                        @else
                                            <span class="not-featured-badge">No</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Has Variations:</div>
                                    <div class="info-value">
                                        @if($product->has_variations)
                                            <span class="variations-badge has-variations">Yes ({{ $product->variations->count() }})</span>
                                        @else
                                            <span class="variations-badge no-variations">No</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Created:</div>
                                    <div class="info-value">{{ $product->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Last Updated:</div>
                                    <div class="info-value">{{ $product->updated_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    @if($product->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="section-header">
                                <h5><i class="fas fa-file-alt"></i> Description</h5>
                            </div>
                            <div class="description-card">
                                <div class="description-content">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Product Specifications Section -->
                    @if($product->ram || $product->storage || $product->storage_type || $product->processor || $product->graphics_card || $product->screen_size || $product->os || $product->warranty)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="section-header">
                                <h5><i class="fas fa-cogs"></i> Product Specifications</h5>
                            </div>
                            <div class="specs-grid">
                                @if($product->processor)
                                <div class="spec-item">
                                    <div class="spec-label">Processor</div>
                                    <div class="spec-value">{{ $product->processor }}</div>
                                </div>
                                @endif
                                @if($product->ram)
                                <div class="spec-item">
                                    <div class="spec-label">RAM</div>
                                    <div class="spec-value">{{ $product->ram }}</div>
                                </div>
                                @endif
                                @if($product->storage)
                                <div class="spec-item">
                                    <div class="spec-label">Storage</div>
                                    <div class="spec-value">{{ $product->storage }}</div>
                                </div>
                                @endif
                                @if($product->storage_type)
                                <div class="spec-item">
                                    <div class="spec-label">Storage Type</div>
                                    <div class="spec-value">{{ $product->storage_type }}</div>
                                </div>
                                @endif
                                @if($product->graphics_card)
                                <div class="spec-item">
                                    <div class="spec-label">Graphics Card</div>
                                    <div class="spec-value">{{ $product->graphics_card }}</div>
                                </div>
                                @endif
                                @if($product->screen_size)
                                <div class="spec-item">
                                    <div class="spec-label">Screen Size</div>
                                    <div class="spec-value">{{ $product->screen_size }}</div>
                                </div>
                                @endif
                                @if($product->os)
                                <div class="spec-item">
                                    <div class="spec-label">Operating System</div>
                                    <div class="spec-value">{{ $product->os }}</div>
                                </div>
                                @endif
                                @if($product->warranty)
                                <div class="spec-item">
                                    <div class="spec-label">Warranty</div>
                                    <div class="spec-value">{{ $product->warranty }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Variations Section -->
                    @if($product->has_variations && $product->variations->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="section-header">
                                <h5><i class="fas fa-layer-group"></i> Product Variations</h5>
                                <span class="variation-count">{{ $product->variations->count() }} variations</span>
                            </div>
                            <div class="variations-table-container">
                                <table class="variations-table">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Model</th>
                                            <th>Processor</th>
                                            <th>RAM</th>
                                            <th>Storage</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variations as $variation)
                                        <tr class="variation-row {{ !$variation->is_active ? 'inactive-variation' : '' }}">
                                            <td class="sku-cell">
                                                <strong>{{ $variation->sku }}</strong>
                                                @if($variation->image)
                                                    <br><small class="text-muted">Has Image</small>
                                                @endif
                                            </td>
                                            <td class="price-cell">
                                                <span class="variation-price">RM {{ number_format($variation->price, 2) }}</span>
                                                @if($variation->price != $product->price)
                                                    <br>
                                                    <small class="{{ $variation->price > $product->price ? 'text-danger' : 'text-success' }}">
                                                        {{ $variation->price > $product->price ? '+' : '' }}{{ number_format((($variation->price - $product->price) / $product->price) * 100, 1) }}%
                                                    </small>
                                                @endif
                                            </td>
                                            <td class="stock-cell">
                                                @if($variation->stock > 0)
                                                    <span class="stock-indicator in-stock">{{ $variation->stock }}</span>
                                                @else
                                                    <span class="stock-indicator out-of-stock">0</span>
                                                @endif
                                            </td>
                                            <td class="model-cell">
                                                {{ $variation->model ?? 'N/A' }}
                                            </td>
                                            <td class="processor-cell">
                                                {{ $variation->processor ?? ($product->processor ? 'Inherited' : 'N/A') }}
                                                @if($variation->processor && $product->processor && $variation->processor != $product->processor)
                                                    <br><small class="text-warning">Custom</small>
                                                @endif
                                            </td>
                                            <td class="ram-cell">
                                                {{ $variation->ram ?? ($product->ram ? 'Inherited' : 'N/A') }}
                                                @if($variation->ram && $product->ram && $variation->ram != $product->ram)
                                                    <br><small class="text-warning">Custom</small>
                                                @endif
                                            </td>
                                            <td class="storage-cell">
                                                {{ $variation->storage ?? ($product->storage ? 'Inherited' : 'N/A') }}
                                                @if($variation->storage && $product->storage && $variation->storage != $product->storage)
                                                    <br><small class="text-warning">Custom</small>
                                                @endif
                                            </td>
                                            <td class="status-cell">
                                                @if($variation->is_active)
                                                    <span class="status-badge active">Active</span>
                                                @else
                                                    <span class="status-badge inactive">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @elseif(!$product->has_variations)
                    <!-- Single Product Stock Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="section-header">
                                <h5><i class="fas fa-cube"></i> Stock Information</h5>
                            </div>
                            <div class="stock-info-card">
                                <div class="stock-info-grid">
                                    <div class="stock-info-item">
                                        <div class="stock-info-label">Current Stock</div>
                                        <div class="stock-info-value {{ $product->stock_quantity > 0 ? 'in-stock' : 'out-of-stock' }}">
                                            {{ $product->stock_quantity }} units
                                        </div>
                                    </div>
                                    <div class="stock-info-item">
                                        <div class="stock-info-label">Stock Status</div>
                                        <div class="stock-info-value">
                                            <span class="status-badge {{ $product->stock_status }}">{{ $product->stock_status_label }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Images Section -->
                    @if($product->images->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="section-header">
                                <h5><i class="fas fa-images"></i> Product Images</h5>
                                <span class="image-count">{{ $product->images->count() }} images</span>
                            </div>
                            <div class="images-grid">
                                @foreach($product->images as $image)
                                <div class="image-card">
                                    <div class="image-container">
                                        <img src="{{ $image->image_url }}" class="product-image" alt="Product Image" loading="lazy">
                                        @if($image->is_primary)
                                            <div class="primary-badge">Primary</div>
                                        @endif
                                    </div>
                                    <div class="image-meta">
                                        <span class="sort-order">Order: {{ $image->sort_order }}</span>
                                        @if($image->is_primary)
                                            <span class="primary-indicator">Main Image</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection