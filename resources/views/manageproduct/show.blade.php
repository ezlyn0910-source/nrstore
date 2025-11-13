@extends('admin.adminbase')
@section('title', 'Product Details')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_product/show.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Product Details</h3>
                    <div>
                        <a href="{{ route('admin.manageproduct.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('admin.manageproduct.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Product ID:</th>
                                    <td>{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <th>Product Name:</th>
                                    <td>{{ $product->name }}</td> {{-- Changed from product_name to name --}}
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>
                                        @if($product->category)
                                            <span class="badge bg-primary">{{ $product->category->name }}</span> {{-- Changed from category_name to name --}}
                                        @else
                                            <span class="text-muted">No Category</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Brand:</th>
                                    <td>{{ $product->brand ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Pricing & Stock</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Price:</th>
                                    <td>RM {{ number_format($product->price, 2) }}</td> {{-- Changed from base_price to price --}}
                                </tr>
                                <tr>
                                    <th>Total Stock:</th>
                                    <td>
                                        @if($product->total_stock > 0)
                                            <span class="badge bg-success">{{ $product->total_stock }} units</span>
                                        @else
                                            <span class="badge bg-warning">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Featured:</th>
                                    <td>
                                        @if($product->is_featured)
                                            <span class="badge bg-warning">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $product->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $product->updated_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($product->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Description</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Variations Section -->
                    @if($product->has_variations && $product->variations->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Product Variations</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Model</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variations as $variation)
                                        <tr>
                                            <td>{{ $variation->sku }}</td>
                                            <td>RM {{ number_format($variation->price, 2) }}</td>
                                            <td>{{ $variation->stock }}</td>
                                            <td>{{ $variation->model ?? 'N/A' }}</td>
                                            <td>
                                                @if($variation->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Images Section -->
                    @if($product->images->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Product Images</h5>
                            <div class="row">
                                @foreach($product->images as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ $image->image_url }}" class="card-img-top" alt="Product Image">
                                        <div class="card-body text-center">
                                            @if($image->is_primary)
                                                <span class="badge bg-primary">Primary</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <form action="{{ route('admin.manageproduct.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete Product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection