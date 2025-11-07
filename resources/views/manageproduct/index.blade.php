@extends('admin.adminbase')
@section('title', 'Manage Products')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/index.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="manage-products-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="page-title">Manage Products</h1>
            <p class="page-subtitle">Manage your product inventory and listings</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.manageproduct.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add New Product
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalProducts }}</h3>
                <p class="stat-label">Total Products</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $activeProducts }}</h3>
                <p class="stat-label">Active Products</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon featured">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $featuredProducts }}</h3>
                <p class="stat-label">Featured Products</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon low-stock">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $lowStockProducts }}</h3>
                <p class="stat-label">Low Stock</p>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-row">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search products..." class="search-input">
            </div>
            <div class="filter-controls">
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="categoryFilter" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-secondary" id="resetFilters">
                    <i class="fas fa-refresh"></i>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions-section">
        <form action="{{ route('admin.manageproduct.bulkAction') }}" method="POST" id="bulkActionForm">
            @csrf
            <div class="bulk-actions-row">
                <div class="bulk-checkbox">
                    <input type="checkbox" id="selectAll">
                    <label for="selectAll">Select All</label>
                </div>
                <select name="action" class="bulk-action-select" id="bulkActionSelect">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="applyBulkAction" disabled>
                    Apply
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="products-table-container">
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th class="checkbox-column">
                            <input type="checkbox" id="tableSelectAll">
                        </th>
                        <th class="product-column">Product</th>
                        <th class="category-column">Category</th>
                        <th class="price-column">Price</th>
                        <th class="stock-column">Stock</th>
                        <th class="status-column">Status</th>
                        <th class="featured-column">Featured</th>
                        <th class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="product-row" data-status="{{ $product->is_active ? 'active' : 'inactive' }}" data-category="{{ $product->category_id }}">
                            <td class="checkbox-column">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox">
                            </td>
                            <td class="product-column">
                                <div class="product-info">
                                    <div class="product-image">
                                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/default-product.png') }}'">
                                    </div>
                                    <div class="product-details">
                                        <h4 class="product-name">{{ $product->name }}</h4>
                                        <p class="product-sku">SKU: {{ $product->variations->first()->sku ?? 'N/A' }}</p>
                                        <div class="product-meta">
                                            @if($product->has_variations)
                                                <span class="variation-badge">{{ $product->variations->count() }} variations</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="category-column">
                                <span class="category-badge">{{ $product->category->name }}</span>
                            </td>
                            <td class="price-column">
                                @if($product->has_variations && $product->variations->isNotEmpty())
                                    <div class="price-range">
                                        RM {{ number_format($product->min_price, 2) }} - RM {{ number_format($product->max_price, 2) }}
                                    </div>
                                @else
                                    <div class="single-price">
                                        RM {{ number_format($product->price, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="stock-column">
                                <div class="stock-info">
                                    <span class="stock-amount {{ $product->stock_status }}">{{ $product->total_stock }}</span>
                                    <span class="stock-label {{ $product->stock_status }}">{{ $product->stock_status_label }}</span>
                                </div>
                            </td>
                            <td class="status-column">
                                <form action="{{ route('admin.manageproduct.update-status', $product) }}" method="POST" class="status-form">
                                    @csrf
                                    @method('PUT')
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="status-toggle">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </form>
                            </td>
                            <td class="featured-column">
                                <form action="{{ route('admin.manageproduct.toggle-featured', $product) }}" method="POST" class="featured-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="featured-toggle {{ $product->is_featured ? 'featured' : '' }}" title="{{ $product->is_featured ? 'Remove from featured' : 'Add to featured' }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="actions-column">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.manageproduct.edit', $product) }}" class="btn-action edit" title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn-action view" title="View Product">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.manageproduct.destroy', $product) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete Product" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-products">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <h3>No Products Found</h3>
                                    <p>Get started by adding your first product.</p>
                                    <a href="{{ route('admin.manageproduct.create') }}" class="btn btn-primary">
                                        Add New Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pagination-container">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Quick Edit Modal -->
<div class="modal fade" id="quickEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Quick edit form will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const tableSelectAll = document.getElementById('tableSelectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const applyBulkAction = document.getElementById('applyBulkAction');
    const bulkActionForm = document.getElementById('bulkActionForm');

    function updateBulkActionButton() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        applyBulkAction.disabled = checkedBoxes.length === 0 || bulkActionSelect.value === '';
    }

    selectAll?.addEventListener('change', function() {
        const isChecked = this.checked;
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActionButton();
    });

    tableSelectAll?.addEventListener('change', function() {
        const isChecked = this.checked;
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActionButton();
    });

    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionButton);
    });

    bulkActionSelect?.addEventListener('change', updateBulkActionButton);

    // Status toggle
    const statusToggles = document.querySelectorAll('.status-toggle');
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput?.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.product-row');
        
        rows.forEach(row => {
            const productName = row.querySelector('.product-name').textContent.toLowerCase();
            const productSku = row.querySelector('.product-sku').textContent.toLowerCase();
            
            if (productName.includes(searchTerm) || productSku.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const resetFilters = document.getElementById('resetFilters');

    function applyFilters() {
        const statusValue = statusFilter.value;
        const categoryValue = categoryFilter.value;
        const rows = document.querySelectorAll('.product-row');
        
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowCategory = row.getAttribute('data-category');
            
            const statusMatch = !statusValue || rowStatus === statusValue;
            const categoryMatch = !categoryValue || rowCategory === categoryValue;
            
            if (statusMatch && categoryMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    statusFilter?.addEventListener('change', applyFilters);
    categoryFilter?.addEventListener('change', applyFilters);
    resetFilters?.addEventListener('click', function() {
        statusFilter.value = '';
        categoryFilter.value = '';
        searchInput.value = '';
        applyFilters();
        
        // Show all rows
        const rows = document.querySelectorAll('.product-row');
        rows.forEach(row => row.style.display = '');
    });

    // Bulk action form submission
    bulkActionForm?.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one product.');
            return;
        }
        
        if (!bulkActionSelect.value) {
            e.preventDefault();
            alert('Please select a bulk action.');
            return;
        }
        
        if (bulkActionSelect.value === 'delete') {
            if (!confirm('Are you sure you want to delete the selected products? This action cannot be undone.')) {
                e.preventDefault();
                return;
            }
        }
    });
});
</script>
@endsection