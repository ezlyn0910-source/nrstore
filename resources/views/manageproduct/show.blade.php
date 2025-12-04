@extends('admin.adminbase')
@section('title', 'Product Details')

@section('content')

<style> 
/* Minimalist Color Theme */
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-gold: #DAA112;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
    --success: #28a745;
    --warning: #ffc107;
    --danger: #dc3545;
    --info: #17a2b8;
}


/* Base Styles */
.product-detail-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(26, 36, 18, 0.08);
    overflow: hidden;
    width: 63.5rem;
}

.roweor {
    margin: 0;

}

.card-header {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-green));
    color: var(--white);
    border-bottom: none;
    padding: 1.5rem 2rem;
}

.card-title {
    font-weight: 600;
    margin: 0;
    font-size: 1.5rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.75rem;
}

.btn {
    border: none;
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background: var(--accent-gold);
    color: var(--primary-dark);
}

.btn-edit:hover {
    background: #c2910f;
    transform: translateY(-1px);
    color: var(--primary-dark);
}

.btn-back {
    background: var(--light-bone);
    color: var(--dark-text);
    border: 1px solid var(--border-light);
}

.btn-back:hover {
    background: #e9ecef;
    transform: translateY(-1px);
    color: var(--dark-text);
}

.btn-delete {
    background: var(--danger);
    color: var(--white);
}

.btn-delete:hover {
    background: #c82333;
    transform: translateY(-1px);
    color: var(--white);
}

.card-body {
    padding: 25px;
}

/* Section Headers */
.section-header {
    display: flex;
    align-items: center;
    justify-content: between;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--border-light);
}

.section-header h5 {
    color: var(--primary-dark);
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-header h5 i {
    color: var(--accent-gold);
}

.variation-count,
.image-count {
    background: var(--primary-green);
    color: var(--white);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: auto;
}

/* Info Tables */
.info-table {
    background: var(--white);
    border-radius: 8px;
    overflow: hidden;
}

.info-row {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    transition: background-color 0.2s ease;
}

.info-row:hover {
    background-color: var(--light-bone);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    flex: 0 0 40%;
    font-weight: 600;
    color: var(--primary-dark);
    font-size: 0.9rem;
}

.info-value {
    flex: 1;
    color: var(--light-text);
    font-size: 0.9rem;
}

/* Badges */
.category-badge {
    background: var(--primary-green);
    color: var(--white);
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.no-category {
    color: var(--light-text);
    font-style: italic;
}

.stock-badge,
.status-badge,
.featured-badge,
.not-featured-badge,
.variations-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stock-badge.in-stock {
    background: #d4edda;
    color: #155724;
}

.stock-badge.out-of-stock {
    background: #f8d7da;
    color: #721c24;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #e2e3e5;
    color: #383d41;
}

.status-badge.in_stock {
    background: #d4edda;
    color: #155724;
}

.status-badge.low_stock {
    background: #fff3cd;
    color: #856404;
}

.status-badge.out_of_stock {
    background: #f8d7da;
    color: #721c24;
}

.featured-badge {
    background: #fff3cd;
    color: #856404;
}

.not-featured-badge {
    background: #e2e3e5;
    color: #383d41;
}

.variations-badge.has-variations {
    background: #cce7ff;
    color: #004085;
}

.variations-badge.no-variations {
    background: #e2e3e5;
    color: #383d41;
}

/* Price Styling */
.price {
    color: var(--primary-green);
    font-weight: 600;
    font-size: 1.1rem;
}

/* Description Section */
.description-card {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    overflow: hidden;
}

.description-content {
    padding: 1.5rem;
    line-height: 1.6;
    color: var(--light-text);
}

/* Specifications Grid */
.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.spec-item {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
}

.spec-label {
    font-weight: 600;
    color: var(--primary-dark);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.spec-value {
    color: var(--light-text);
    font-size: 1rem;
    font-weight: 500;
}

/* Variations Table */
.variations-table-container {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    overflow: hidden;
}

.variations-table {
    width: 100%;
    border-collapse: collapse;
}

.variations-table th {
    background: var(--light-bone);
    color: var(--primary-dark);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border-light);
}

.variations-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-light);
    color: var(--light-text);
}

.variation-row:hover {
    background-color: var(--light-bone);
}

.variation-row.inactive-variation {
    background-color: #f8f9fa;
    opacity: 0.7;
}

.variation-row.inactive-variation td {
    color: #6c757d;
}

.sku-cell {
    font-family: 'Courier New', monospace;
}

.price-cell .variation-price {
    color: var(--primary-green);
    font-weight: 600;
}

.stock-indicator {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.stock-indicator.in-stock {
    background: #d4edda;
    color: #155724;
}

.stock-indicator.out-of-stock {
    background: #f8d7da;
    color: #721c24;
}

/* Images Grid */
.images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.image-card {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(26, 36, 18, 0.15);
}

.image-container {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-card:hover .product-image {
    transform: scale(1.05);
}

.primary-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: var(--accent-gold);
    color: var(--primary-dark);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
}

.image-meta {
    padding: 0.75rem;
    text-align: center;
    background: var(--light-bone);
}

.sort-order {
    color: var(--light-text);
    font-size: 0.75rem;
}

/* Action Section */
.action-section {
    padding: 1.5rem;
    background: var(--light-bone);
    border-radius: 8px;
    text-align: center;
}

.delete-form {
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 0.5rem;
    }
    
    .card-header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .info-label {
        flex: none;
    }
    
    .variations-table-container {
        overflow-x: auto;
    }
    
    .variations-table {
        min-width: 800px;
    }
    
    .images-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .specs-grid {
        grid-template-columns: 1fr;
    }
}

/* Loading States */
.product-image {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Additional CSS for new elements */

/* Price Range Styling */
.price-range {
    color: var(--primary-green);
    font-weight: 600;
    font-size: 1rem;
}

/* Variation Summary */
.variation-summary {
    display: flex;
    gap: 2rem;
    background: var(--light-bone);
    padding: 1rem 1.5rem;
    border-radius: 8px;
    border: 1px solid var(--border-light);
}

.summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.summary-label {
    font-size: 0.8rem;
    color: var(--light-text);
    margin-bottom: 0.25rem;
}

.summary-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary-dark);
}

/* Stock Info Card */
.stock-info-card {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    padding: 1.5rem;
}

.stock-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stock-info-item {
    text-align: center;
}

.stock-info-label {
    font-size: 0.9rem;
    color: var(--light-text);
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.stock-info-value {
    font-size: 1.5rem;
    font-weight: 600;
}

.stock-info-value.in-stock {
    color: var(--success);
}

.stock-info-value.out-of-stock {
    color: var(--danger);
}

/* Text Colors for Percentage Changes */
.text-danger { color: var(--danger); }
.text-success { color: var(--success); }
.text-warning { color: var(--warning); }
.text-muted { color: var(--light-text); }

/* Primary Image Indicator */
.primary-indicator {
    background: var(--accent-gold);
    color: var(--primary-dark);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

/* Custom badge for variation differences */
.variation-row .text-warning {
    font-size: 0.7rem;
    font-weight: 500;
}

/* Responsive adjustments for variation summary */
@media (max-width: 768px) {
    .variation-summary {
        flex-direction: column;
        gap: 1rem;
    }
    
    .summary-item {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    
    .stock-info-grid {
        grid-template-columns: 1fr;
    }
}

/* Utility Classes */
.text-success { color: var(--success); }
.text-warning { color: var(--warning); }
.text-danger { color: var(--danger); }
.text-info { color: var(--info); }

.bg-success { background-color: var(--success); }
.bg-warning { background-color: var(--warning); }
.bg-danger { background-color: var(--danger); }
.bg-info { background-color: var(--info); }
</style>

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