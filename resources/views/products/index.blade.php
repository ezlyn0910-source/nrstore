@extends('admin.adminbase')
@section('title', 'Manage Products')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-boxes me-2"></i>Product Management</h1>
            <p class="text-muted">Manage your product inventory and variations</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Product
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Low Stock Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('total_stock', '<', 10)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Out of Stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('total_stock', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
            <div class="d-flex gap-2">
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search products..." style="width: 200px;">
                <select id="categoryFilter" class="form-control form-control-sm" style="width: 150px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="60px">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th width="80px">ID</th>
                            <th>Product</th>
                            <th width="120px">Price</th>
                            <th width="100px">Stock</th>
                            <th width="120px">Category</th>
                            <th width="120px">Status</th>
                            <th width="150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="product-row" data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->product_name) }}">
                            <td>
                                <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                            </td>
                            <td>
                                <span class="badge bg-secondary">#{{ $product->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                                             alt="{{ $product->product_name }}" 
                                             class="rounded me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1">{{ $product->product_name }}</h6>
                                        <small class="text-muted">
                                            @if($product->has_variations)
                                                <span class="badge bg-info me-1">
                                                    <i class="fas fa-layer-group me-1"></i>{{ $product->variations_count }} variations
                                                </span>
                                            @endif
                                            @if($product->description)
                                                {{ Str::limit($product->description, 50) }}
                                            @else
                                                <span class="text-muted">No description</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($product->has_variations && $product->variations_count > 0)
                                    @php
                                        $minPrice = $product->variations->min('price') ?? $product->base_price;
                                        $maxPrice = $product->variations->max('price') ?? $product->base_price;
                                    @endphp
                                    @if($minPrice == $maxPrice)
                                        <strong class="text-success">RM {{ number_format($minPrice, 2) }}</strong>
                                    @else
                                        <div>
                                            <strong class="text-success">RM {{ number_format($minPrice, 2) }}</strong>
                                            <small class="text-muted">- RM {{ number_format($maxPrice, 2) }}</small>
                                        </div>
                                    @endif
                                @else
                                    <strong class="text-success">RM {{ number_format($product->base_price, 2) }}</strong>
                                @endif
                            </td>
                            <td>
                                @php
                                    $totalStock = $product->has_variations ? $product->variations->sum('stock') : $product->total_stock;
                                @endphp
                                @if($totalStock == 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($totalStock < 10)
                                    <span class="badge bg-warning text-dark">Low Stock ({{ $totalStock }})</span>
                                @else
                                    <span class="badge bg-success">{{ $totalStock }} in stock</span>
                                @endif
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-primary">{{ $product->category->category_name }}</span>
                                @else
                                    <span class="badge bg-secondary">No Category</span>
                                @endif
                            </td>
                            <td>
                                @if($product->has_variations)
                                    <span class="badge bg-info">
                                        <i class="fas fa-layer-group me-1"></i>With Variations
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Simple Product</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="btn btn-info" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="btn btn-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($product->has_variations)
                                        <a href="{{ route('products.variations.create', $product) }}" 
                                           class="btn btn-secondary" 
                                           data-bs-toggle="tooltip" 
                                           title="Add Variation">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger" 
                                                data-bs-toggle="tooltip" 
                                                title="Delete Product"
                                                onclick="return confirm('Are you sure you want to delete {{ $product->product_name }}? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <h5>No products found</h5>
                                    <p>Get started by adding your first product.</p>
                                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add New Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bulk Actions -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="bulk-actions d-none" id="bulkActions">
                    <select class="form-select form-select-sm me-2" style="width: auto;" id="bulkActionSelect">
                        <option value="">Bulk Actions</option>
                        <option value="delete">Delete Selected</option>
                        <option value="activate">Mark as Active</option>
                        <option value="deactivate">Mark as Inactive</option>
                    </select>
                    <button class="btn btn-sm btn-primary" id="applyBulkAction">Apply</button>
                    <button class="btn btn-sm btn-secondary" id="cancelBulkAction">Cancel</button>
                </div>
                <div class="ms-auto">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.product-row:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 6px !important;
    margin: 0 2px;
}

.alert {
    border: none;
    border-radius: 8px;
    border-left: 4px solid;
}

.border-left-primary { border-left-color: #4e73df !important; }
.border-left-success { border-left-color: #1cc88a !important; }
.border-left-info { border-left-color: #36b9cc !important; }
.border-left-warning { border-left-color: #f6c23e !important; }

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const productRows = document.querySelectorAll('.product-row');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const categoryValue = categoryFilter.value;

        productRows.forEach(row => {
            const productName = row.getAttribute('data-name');
            const productCategory = row.getAttribute('data-category');
            
            const nameMatch = productName.includes(searchTerm);
            const categoryMatch = !categoryValue || productCategory === categoryValue;
            
            if (nameMatch && categoryMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);

    // Bulk actions functionality
    const selectAll = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const applyBulkAction = document.getElementById('applyBulkAction');
    const cancelBulkAction = document.getElementById('cancelBulkAction');

    selectAll.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        toggleBulkActions();
    });

    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });

    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActions.classList.remove('d-none');
        } else {
            bulkActions.classList.add('d-none');
        }
    }

    cancelBulkAction.addEventListener('click', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAll.checked = false;
        bulkActions.classList.add('d-none');
    });

    applyBulkAction.addEventListener('click', function() {
        const action = document.getElementById('bulkActionSelect').value;
        const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (!action) {
            alert('Please select a bulk action');
            return;
        }

        if (selectedProducts.length === 0) {
            alert('Please select at least one product');
            return;
        }

        if (action === 'delete') {
            if (confirm(`Are you sure you want to delete ${selectedProducts.length} product(s)? This action cannot be undone.`)) {
                // Implement bulk delete
                console.log('Bulk delete:', selectedProducts);
            }
        } else {
            // Implement other bulk actions
            console.log(`Bulk ${action}:`, selectedProducts);
        }
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush