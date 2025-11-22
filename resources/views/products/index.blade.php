@extends('layouts.app')

@section('title', 'Products - AgriconnectKE')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Marketplace</h1>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter"></i> Category
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('products.index') }}">All Categories</a></li>
                    @foreach($categories as $category)
                        <li><a class="dropdown-item" href="{{ route('products.byCategory', $category) }}">{{ ucfirst($category) }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('products.search') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search products..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                        <p class="card-text text-muted small mb-2">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                        
                        <div class="product-meta mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> {{ $product->farmer->name }}
                            </small>
                            <small class="text-muted ms-2">
                                <i class="fas fa-tag"></i> {{ ucfirst($product->category) }}
                            </small>
                        </div>

                        <div class="product-pricing">
                            <span class="h5 text-success mb-0">Ksh {{ number_format($product->price, 2) }}</span>
                            <small class="text-muted d-block">per unit</small>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="{{ $product->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $product->quantity }} available
                            </small>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-success btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Products Found</h4>
                        <p class="text-muted">There are no products matching your search criteria.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-success">View All Products</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection