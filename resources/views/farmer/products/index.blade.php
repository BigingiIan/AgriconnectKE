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
            <div class="card content-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start ps-4">Image</th>
                                    <th class="border-0">Name</th>
                                    <th class="border-0">Price</th>
                                    <th class="border-0">Quantity</th>
                                    <th class="border-0">Category</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Accepts Bids</th>
                                    <th class="border-0">Active Bids</th>
                                    <th class="border-0 rounded-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                                 class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle fw-bold">{{ $product->name }}</td>
                                    <td class="align-middle text-success fw-bold">Ksh {{ number_format($product->price, 2) }}</td>
                                    <td class="align-middle">{{ $product->quantity }}</td>
                                    <td class="align-middle">
                                        <span class="badge bg-info rounded-pill">{{ ucfirst($product->category) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-{{ $product->is_available && $product->quantity > 0 ? 'success' : 'danger' }} rounded-pill">
                                            {{ $product->is_available && $product->quantity > 0 ? 'Available' : 'Sold Out' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-{{ $product->accepts_bids ? 'warning' : 'secondary' }} rounded-pill">
                                            {{ $product->accepts_bids ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-primary rounded-pill">{{ $product->bids_count }}</span>
                                    </td>
                                    <td class="align-middle pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-outline-primary btn-rounded mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('farmer.products.destroy', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-rounded mx-1" 
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
                </div>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="card content-card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-box-open fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">No Products Listed</h4>
                    <p class="text-muted mb-4">You haven't listed any products yet. Start by adding your first product!</p>
                    <a href="{{ route('farmer.products.create') }}" class="btn btn-success btn-rounded shadow-sm px-4">
                        <i class="fas fa-plus me-2"></i> Add Your First Product
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection