@extends('layouts.app')

@section('title', $product->name . ' - AgriconnectKE')

@section('styles')
<style>
    .product-image {
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }
    
    .similar-product-card {
        transition: transform 0.3s;
    }
    
    .similar-product-card:hover {
        transform: translateY(-5px);
    }
    
    .bid-history {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .action-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.byCategory', $product->category) }}">{{ ucfirst($product->category) }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images & Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-6">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid product-image w-100">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 300px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div class="col-md-6">
                            <h1 class="h3 mb-3">{{ $product->name }}</h1>
                            
                            <div class="mb-3">
                                <span class="h4 text-success mb-0">Ksh {{ number_format($product->price, 2) }}</span>
                                <small class="text-muted d-block">per unit</small>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted mb-2">{{ $product->description }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Category:</strong>
                                        <span class="badge bg-info">{{ ucfirst($product->category) }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Available:</strong>
                                        <span class="{{ $product->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $product->quantity }} units
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Farmer:</strong>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-user text-muted me-2"></i>
                                    <span>{{ $product->farmer->name }}</span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $product->farmer->address }}
                                </small>
                            </div>
                            
                            <!-- Status Badges -->
                            <div class="mb-4">
                                @if(!$product->is_available || $product->quantity <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($product->quantity < 10)
                                    <span class="badge bg-warning">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                                
                                @if($product->accepts_bids)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-gavel"></i> Bids Welcome
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Action Buttons for Buyers -->
                            @auth
                                @if(Auth::user()->role === 'buyer')
                                    <div class="action-buttons">
                                        @if($product->is_available && $product->quantity > 0)
                                            @if($product->accepts_bids)
                                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bidModal">
                                                    <i class="fas fa-gavel"></i> Place Bid
                                                </button>
                                            @endif
                                            
                                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#purchaseModal">
                                                <i class="fas fa-shopping-cart"></i> Buy Now
                                            </button>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                <i class="fas fa-times"></i> Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <p class="mb-2">Want to purchase this product?</p>
                                    <a href="{{ route('login') }}" class="btn btn-success btn-sm">Login as Buyer</a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">Register</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $product->description }}</p>
                    
                    <div class="mt-3">
                        <h6>Product Details:</h6>
                        <ul>
                            <li>Category: {{ ucfirst($product->category) }}</li>
                            <li>Price: Ksh {{ number_format($product->price, 2) }} per unit</li>
                            <li>Available Quantity: {{ $product->quantity }} units</li>
                            <li>Farmer: {{ $product->farmer->name }}</li>
                            <li>Location: {{ $product->farmer->address }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Farmer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Farmer Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                    </div>
                    
                    <h6 class="text-center">{{ $product->farmer->name }}</h6>
                    <p class="text-muted text-center small mb-3">Verified Farmer</p>
                    
                    <div class="farmer-details">
                        <p><strong>Email:</strong><br>{{ $product->farmer->email }}</p>
                        <p><strong>Phone:</strong><br>{{ $product->farmer->phone }}</p>
                        <p><strong>Location:</strong><br>{{ $product->farmer->address }}</p>
                    </div>
                    
                    @auth
                        @if(Auth::user()->role === 'buyer')
                            <div class="text-center mt-3">
                                <button class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-envelope"></i> Contact Farmer
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            
            <!-- Bid History (For Farmers Only) -->
            @auth
                @if(Auth::user()->role === 'farmer' && Auth::id() === $product->farmer_id && $bids->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Bid History</h5>
                        </div>
                        <div class="card-body">
                            <div class="bid-history">
                                @foreach($bids as $bid)
                                    <div class="border-bottom pb-2 mb-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $bid->buyer->name }}</strong>
                                                <div class="text-success">Ksh {{ number_format($bid->amount, 2) }}</div>
                                            </div>
                                            <span class="badge bg-{{ $bid->status === 'pending' ? 'warning' : ($bid->status === 'accepted' ? 'success' : 'danger') }}">
                                                {{ ucfirst($bid->status) }}
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $bid->created_at->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
    
    <!-- Similar Products -->
    @if($similarProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Similar Products</h3>
                <div class="row">
                    @foreach($similarProducts as $similarProduct)
                        <div class="col-md-3 mb-4">
                            <div class="card similar-product-card h-100">
                                @if($similarProduct->image)
                                    <img src="{{ asset('storage/' . $similarProduct->image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $similarProduct->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($similarProduct->name, 50) }}</h6>
                                    <p class="card-text text-success mb-2">Ksh {{ number_format($similarProduct->price, 2) }}</p>
                                    <small class="text-muted">by {{ $similarProduct->farmer->name }}</small>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="{{ route('products.show', $similarProduct) }}" class="btn btn-outline-success btn-sm w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Bid Modal -->
@auth
    @if(Auth::user()->role === 'buyer' && $product->accepts_bids)
        <div class="modal fade" id="bidModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('buyer.place-bid', $product) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Place Bid - {{ $product->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle"></i> 
                                    The farmer will review your bid and may accept or reject it.
                                </small>
                            </div>
                            <p><strong>Current Price:</strong> Ksh {{ number_format($product->price, 2) }}</p>
                            <div class="mb-3">
                                <label for="bidAmount" class="form-label">Your Bid Amount (Ksh)</label>
                                <input type="number" class="form-control" id="bidAmount" 
                                       name="amount" step="0.01" min="0.01" 
                                       value="{{ $product->price }}" required>
                                <div class="form-text">Enter your proposed price for this product</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-paper-plane"></i> Submit Bid
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- Purchase Modal -->
@auth
    @if(Auth::user()->role === 'buyer')
        <div class="modal fade" id="purchaseModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('buyer.purchase', $product) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Purchase - {{ $product->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Price:</strong> Ksh {{ number_format($product->price, 2) }} per unit</p>
                            <p><strong>Available:</strong> {{ $product->quantity }} units</p>
                            
                            <div class="mb-3">
                                <label for="purchaseQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="purchaseQuantity" 
                                       name="quantity" min="1" max="{{ $product->quantity }}" 
                                       value="1" required>
                            </div>
                            
                            <div class="alert alert-warning">
                                <small>
                                    <i class="fas fa-truck"></i> 
                                    Delivery cost will be calculated based on your location
                                </small>
                            </div>
                            
                            <p class="total-price fw-bold">
                                Subtotal: Ksh <span id="totalPrice">{{ number_format($product->price, 2) }}</span>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate total price for purchase modal
        const quantityInput = document.getElementById('purchaseQuantity');
        const totalPrice = document.getElementById('totalPrice');
        
        if (quantityInput && totalPrice) {
            quantityInput.addEventListener('input', function() {
                const quantity = parseInt(this.value) || 0;
                const price = {{ $product->price }};
                const subtotal = quantity * price;
                totalPrice.textContent = subtotal.toLocaleString('en-KE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            });
        }
    });
</script>
@endpush
@endsection