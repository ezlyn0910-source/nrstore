@extends('admin.adminbase')
@section('title', 'Manage Products')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/index.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-boxes me-2"></i>Product Management</h1>
            <p class="text-muted">Manage your product inventory and variations</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.manageproduct.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Product
            </a>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload me-2"></i>Import
            </button>
        </div>
    </div>

    <!-- Enhanced Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.manageproduct.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search products...">
                    </div>
                    <div class="col-md-2">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Featured</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price Low-High</option>
                            <option value="stock_quantity" {{ request('sort') == 'stock_quantity' ? 'selected' : '' }}>Stock Level</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="order" class="form-label">Order</label>
                        <select class="form-select" id="order" name="order">
                            <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Products with Variations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('has_variations', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('stock_quantity', '<', 10)->where('stock_quantity', '>', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Out of Stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('stock_quantity', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Featured Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('is_featured', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
            <div class="d-flex gap-2 align-items-center">
                <span class="text-muted small me-2">{{ $products->total() }} products found</span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="showInactive" {{ request('show_inactive') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="showInactive">Show Inactive</label>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Bulk Actions -->
            <div class="bulk-actions mb-3 d-none" id="bulkActions">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted" id="selectedCount">0 products selected</span>
                    <select class="form-select form-select-sm" style="width: auto;" id="bulkActionSelect">
                        <option value="">Choose Action...</option>
                        <option value="delete">Delete Selected</option>
                        <option value="featured">Mark as Featured</option>
                        <option value="unfeatured">Unmark as Featured</option>
                        <option value="recommended">Mark as Recommended</option>
                        <option value="unrecommended">Unmark as Recommended</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-primary" id="applyBulkAction">Apply</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSelection">Clear</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Recommended</th>
                            <th>Variations</th>
                            <th>Created</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="{{ $product->is_active ? '' : 'table-warning' }}">
                                <td>
                                    <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $product->main_image_url }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded me-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->brand }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">${{ number_format($product->price, 2) }}</div>
                                    @if($product->has_variations)
                                        <small class="text-muted">
                                            ${{ number_format($product->min_price, 2) }} - ${{ number_format($product->max_price, 2) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            @php
                                                $stockPercentage = $product->total_stock > 0 ? min(100, ($product->total_stock / 100) * 100) : 0;
                                                $progressClass = $product->total_stock == 0 ? 'bg-danger' : ($product->total_stock < 10 ? 'bg-warning' : 'bg-success');
                                            @endphp
                                            <div class="progress-bar {{ $progressClass }}" 
                                                 style="width: {{ $stockPercentage }}%"></div>
                                        </div>
                                        <span class="badge {{ $product->stock_status == 'out_of_stock' ? 'bg-danger' : ($product->stock_status == 'low_stock' ? 'bg-warning' : 'bg-success') }}">
                                            {{ $product->total_stock }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->is_featured)
                                        <i class="fas fa-star text-warning" title="Featured"></i>
                                    @else
                                        <i class="far fa-star text-muted" title="Not Featured"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_recommended)
                                        <i class="fas fa-thumbs-up text-primary" title="Recommended"></i>
                                    @else
                                        <i class="far fa-thumbs-up text-muted" title="Not Recommended"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($product->has_variations)
                                        <span class="badge bg-info">{{ $product->variations->count() }} variants</span>
                                    @else
                                        <span class="badge bg-light text-dark">No variants</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $product->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.products.show', $product->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-outline-secondary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-{{ $product->is_active ? 'warning' : 'success' }} toggle-status"
                                                data-id="{{ $product->id }}"
                                                title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <h5>No products found</h5>
                                        <p>Get started by adding your first product.</p>
                                        <a href="{{ route('admin.manageproduct.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add Product
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                    </div>
                    <nav>
                        {{ $products->appends(request()->query())->links() }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Form -->
<form action="{{ route('admin.products.bulk-action') }}" method="POST" id="bulkActionForm">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="product_ids" id="bulkProductIds">
</form>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Import products from CSV file. Download the template file first.</p>
                <div class="mb-3">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>Download Template
                    </a>
                </div>
                <div class="mb-3">
                    <label for="importFile" class="form-label">Select CSV File</label>
                    <input class="form-control" type="file" id="importFile">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Import Products</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk selection functionality
    const selectAll = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const applyBulkAction = document.getElementById('applyBulkAction');
    const clearSelection = document.getElementById('clearSelection');
    const bulkActionForm = document.getElementById('bulkActionForm');
    const bulkAction = document.getElementById('bulkAction');
    const bulkProductIds = document.getElementById('bulkProductIds');

    // Select all functionality
    selectAll.addEventListener('change', function() {
        const isChecked = this.checked;
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActions();
    });

    // Individual checkbox change
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Update bulk actions visibility
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActions.classList.remove('d-none');
            selectedCount.textContent = `${count} product${count > 1 ? 's' : ''} selected`;
            selectAll.checked = count === productCheckboxes.length;
        } else {
            bulkActions.classList.add('d-none');
            selectAll.checked = false;
        }
    }

    // Apply bulk action
    applyBulkAction.addEventListener('click', function() {
        const action = bulkActionSelect.value;
        if (!action) {
            alert('Please select an action');
            return;
        }

        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const productIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);

        if (action === 'delete' && !confirm(`Are you sure you want to delete ${productIds.length} product(s)?`)) {
            return;
        }

        bulkAction.value = action;
        bulkProductIds.value = JSON.stringify(productIds);
        bulkActionForm.submit();
    });

    // Clear selection
    clearSelection.addEventListener('click', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActions();
    });

    // Toggle status
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            if (confirm('Are you sure you want to change the product status?')) {
                fetch(`/admin/products/${productId}/status`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        });
    });

    // Auto-submit filter form on select change
    document.getElementById('category').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('status').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('sort').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('order').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Show inactive products
    document.getElementById('showInactive').addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.checked) {
            url.searchParams.set('show_inactive', '1');
        } else {
            url.searchParams.delete('show_inactive');
        }
        window.location.href = url.toString();
    });
});
</script>
@endsection