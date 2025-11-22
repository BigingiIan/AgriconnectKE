@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Products</h1>
    <a href="{{ route('farmer.products.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Add New Product
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Accepts Bids</th>
                            <th>Active Bids</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>Ksh {{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($product->category) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $product->is_available && $product->quantity > 0 ? 'success' : 'danger' }}">
                                    {{ $product->is_available && $product->quantity > 0 ? 'Available' : 'Sold Out' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $product->accepts_bids ? 'warning' : 'secondary' }}">
                                    {{ $product->accepts_bids ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $product->bids_count }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('farmer.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Products Listed</h4>
                    <p class="text-muted">You haven't listed any products yet. Start by adding your first product!</p>
                    <a href="{{ route('farmer.products.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Your First Product
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection