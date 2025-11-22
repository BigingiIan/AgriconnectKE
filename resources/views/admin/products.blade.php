@extends('layouts.app')

@section('title', 'Products Management - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Products Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary btn-rounded">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card content-card">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 fw-bold">All Products</h5>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.products') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2 rounded-pill" placeholder="Search products..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary btn-rounded">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">Product</th>
                                <th class="border-0">Farmer</th>
                                <th class="border-0">Category</th>
                                <th class="border-0">Price</th>
                                <th class="border-0">Stock</th>
                                <th class="border-0 rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-thumbnail me-3 rounded shadow-sm" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $product->name }}</h6>
                                            <small class="text-muted">ID: #{{ $product->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->farmer->name }}</td>
                                <td><span class="badge bg-light text-dark border rounded-pill">{{ ucfirst($product->category) }}</span></td>
                                <td class="fw-bold text-success">Ksh {{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if($product->quantity > 10)
                                        <span class="badge bg-success rounded-pill">{{ $product->quantity }} in stock</span>
                                    @elseif($product->quantity > 0)
                                        <span class="badge bg-warning text-dark rounded-pill">{{ $product->quantity }} low stock</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Out of stock</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-rounded" onclick="return confirm('Are you sure you want to remove this product?')">
                                            <i class="fas fa-trash-alt me-1"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
                                    <p class="text-muted">No products found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-3">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection