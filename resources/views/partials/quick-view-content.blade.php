<div class="row">
    <div class="col-md-6">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid rounded" 
                 style="max-height: 300px; width: 100%; object-fit: cover;">
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                 style="height: 300px;">
                <i class="fas fa-image fa-3x text-muted"></i>
            </div>
        @endif
    </div>
    
    <div class="col-md-6">
        <h4 class="mb-3">{{ $product->name }}</h4>
        
        <div class="mb-3">
            <span class="h3 text-success">Ksh {{ number_format($product->price, 2) }}</span>
            <small class="text-muted d-block">per unit</small>
        </div>
        
        <div class="mb-3">
            <p class="text-muted">{{ $product->description }}</p>
        </div>
        
        <div class="mb-3">
            <div class="row small text-muted">
                <div class="col-6">
                    <strong>Category:</strong><br>
                    <span class="badge bg-info">{{ ucfirst($product->category) }}</span>
                </div>
                <div class="col-6">
                    <strong>Available:</strong><br>
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
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            @if($product->is_available && $product->quantity > 0)
                @if($product->accepts_bids)
                    <button class="btn btn-warning btn-sm me-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#bidModal{{ $product->id }}"
                            onclick="closeQuickView()">
                        <i class="fas fa-gavel"></i> Place Bid
                    </button>
                @endif
                
                <button class="btn btn-success btn-sm me-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#purchaseModal{{ $product->id }}"
                        onclick="closeQuickView()">
                    <i class="fas fa-shopping-cart"></i> Buy Now
                </button>
                
                <button class="btn btn-outline-success btn-sm" 
                        onclick="addToCart({{ $product->id }})">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </button>
            @else
                <button class="btn btn-secondary btn-sm" disabled>
                    <i class="fas fa-times"></i> Out of Stock
                </button>
            @endif
        </div>
    </div>
</div>

@if($similarProducts->count() > 0)
<hr>
<div class="row mt-4">
    <div class="col-12">
        <h6>Similar Products</h6>
        <div class="row">
            @foreach($similarProducts as $similarProduct)
            <div class="col-6 col-md-3 mb-3">
                <div class="card similar-product-card h-100" style="cursor: pointer;" 
                     onclick="openQuickView({{ $similarProduct->id }})">
                    @if($similarProduct->image)
                        <img src="{{ asset('storage/' . $similarProduct->image) }}" 
                             class="card-img-top" 
                             alt="{{ $similarProduct->name }}"
                             style="height: 80px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 80px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body p-2">
                        <h6 class="card-title small mb-1">{{ Str::limit($similarProduct->name, 30) }}</h6>
                        <p class="card-text text-success small mb-1">Ksh {{ number_format($similarProduct->price, 2) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif