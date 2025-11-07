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
                                    <td>{{ $product->product_name }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>
                                        @if($product->category)
                                            <span class="badge bg-primary">{{ $product->category->category_name }}</span>
                                        @else
                                            <span class="text-muted">No Category</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Pricing & Stock</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Base Price:</th>
                                    <td>
                                        @if($product->base_price)
                                            RM {{ number_format($product->base_price, 2) }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Stock:</th>
                                    <td>
                                        @if($product->total_stock)
                                            <span class="badge bg-success">{{ $product->total_stock }} units</span>
                                        @else
                                            <span class="badge bg-warning">Out of Stock</span>
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