@extends('layouts.app')

@section('title', 'Marketplace - AgriconnectKE')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Marketplace</h1>
                    <p class="text-muted mb-0">Discover fresh produce from local farmers</p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?category=vegetables">Vegetables</a></li>
                        <li><a class="dropdown-item" href="?category=fruits">Fruits</a></li>
                        <li><a class="dropdown-item" href="?category=grains">Grains</a></li>
                        <li><a class="dropdown-item" href="?category=dairy">Dairy</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('buyer.market') }}">Show All</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Search Products</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" placeholder="Search for products..." id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="vegetables">Vegetables</option>
                                <option value="fruits">Fruits</option>
                                <option value="grains">Grains & Cereals</option>
                                <option value="dairy">Dairy Products</option>
                                <option value="poultry">Poultry</option>
                                <option value="livestock">Livestock</option>
                                <option value="herbs">Herbs & Spices</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="productsContainer">
        @forelse($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-item" 
             data-product-id="{{ $product->id }}" 
             data-name="{{ strtolower($product->name) }}" 
             data-category="{{ $product->category }}"
             data-price="{{ $product->price }}">
            
            <div class="card product-card h-100 border-0 shadow-sm hover-shadow">
                <!-- Product Image -->
                <div class="product-image-container position-relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top product-image" 
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top product-image-placeholder d-flex align-items-center justify-content-center bg-light"
                             style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                <p class="small text-muted mb-0">No Image</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Status Badges -->
                    <div class="position-absolute top-0 start-0 m-2">
                        @if(!$product->is_available)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($product->quantity < 10)
                            <span class="badge bg-warning">Low Stock</span>
                        @endif
                    </div>
                    
                    @if($product->accepts_bids)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-gavel me-1"></i>Bids
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="card-body d-flex flex-column">
                    <!-- Product Info -->
                    <div class="mb-2">
                        <h6 class="card-title fw-semibold mb-1 text-dark">{{ $product->name }}</h6>
                        <p class="card-text text-muted small mb-2 line-clamp-2">
                            {{ Str::limit($product->description, 70) }}
                        </p>
                    </div>

                    <!-- Farmer Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-user me-1"></i>
                            <span class="fw-medium">{{ $product->farmer->name }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted small mt-1">
                            <i class="fas fa-tag me-1"></i>
                            <span class="text-capitalize">{{ $product->category }}</span>
                        </div>
                    </div>

                    <!-- Pricing and Stock -->
                    <div class="mt-auto">
                        

                        <!-- Action Buttons -->
                        <!-- Action Buttons -->
<div class="mt-auto">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="h5 text-success fw-bold mb-0">Ksh {{ number_format($product->price, 2) }}</span>
            <small class="text-muted d-block">per unit</small>
        </div>
        <div class="text-end">
            <small class="{{ $product->quantity > 10 ? 'text-success' : 'text-warning' }} fw-medium">
                <i class="fas fa-box me-1"></i>{{ $product->quantity }} available
            </small>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-grid gap-2">
        @if($product->is_available && $product->quantity > 0)
            <div class="btn-group" role="group">
                @if($product->accepts_bids)
                <button class="btn btn-outline-warning btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#bidModal{{ $product->id }}">
                    <i class="fas fa-gavel me-1"></i>Bid
                </button>
                @endif
                
                <!-- Add to Cart Button -->
                <button class="btn btn-outline-primary btn-sm" 
                        onclick="addToCart({{ $product->id }})"
                        id="addToCartBtn{{ $product->id }}">
                    <i class="fas fa-cart-plus me-1"></i>Add to Cart
                </button>
                
                <!-- Buy Now Button -->
                <button class="btn btn-success btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#purchaseModal{{ $product->id }}">
                    <i class="fas fa-bolt me-1"></i>Buy Now
                </button>
            </div>
        @else
            <button class="btn btn-secondary btn-sm" disabled>
                <i class="fas fa-times me-1"></i>Out of Stock
            </button>
        @endif
    </div>
</div>
                    </div>
                </div>
            </div>

            <!-- Bid Modal -->
            <div class="modal fade" id="bidModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('buyer.place-bid', $product) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-light">
                                <h5 class="modal-title fw-semibold">Place Bid - {{ $product->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @if($product->image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 120px;">
                                    </div>
                                @endif
                                
                                <div class="alert alert-info border-0">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle me-2 mt-1"></i>
                                        <div class="small">
                                            The farmer will review your bid and may accept or reject it.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Current Price</label>
                                    <div class="form-control bg-light">Ksh {{ number_format($product->price, 2) }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amount{{ $product->id }}" class="form-label fw-medium">Your Bid Amount (Ksh)</label>
                                    <input type="number" class="form-control" id="amount{{ $product->id }}" 
                                           name="amount" step="0.01" min="0.01" 
                                           value="{{ $product->price }}" required>
                                    <div class="form-text">Enter your proposed price for this product</div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-paper-plane me-1"></i>Submit Bid
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Purchase Modal -->
            <div class="modal fade" id="purchaseModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('buyer.purchase', $product) }}" method="POST" id="purchaseForm{{ $product->id }}">
                            @csrf
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-semibold">Purchase - {{ $product->name }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @if($product->image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 120px;">
                                    </div>
                                @endif
                                
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="border rounded p-2 text-center bg-light">
                                            <small class="text-muted d-block">Price</small>
                                            <strong class="text-success">Ksh {{ number_format($product->price, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2 text-center bg-light">
                                            <small class="text-muted d-block">Available</small>
                                            <strong>{{ $product->quantity }} units</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <label for="quantity{{ $product->id }}" class="form-label fw-medium">Quantity</label>
                                    <input type="number" class="form-control" id="quantity{{ $product->id }}" 
                                           name="quantity" min="1" max="{{ $product->quantity }}" 
                                           value="1" required>
                                    <div class="form-text">Maximum available: {{ $product->quantity }} units</div>
                                </div>
                                
                                <div class="alert alert-warning border-0 mt-3">
                                    <div class="d-flex">
                                        <i class="fas fa-truck me-2 mt-1"></i>
                                        <div class="small">
                                            Delivery cost will be calculated based on your location
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Unit Price:</span>
                                        <span>Ksh {{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Quantity:</span>
                                        <span id="quantityDisplay{{ $product->id }}">1</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between fw-semibold">
                                        <span>Subtotal:</span>
                                        <span class="text-success">Ksh <span id="totalPrice{{ $product->id }}">{{ number_format($product->price, 2) }}</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success" id="purchaseBtn{{ $product->id }}">
                                    <i class="fas fa-credit-card me-1"></i>Proceed to Checkout
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h3 class="text-muted">No Products Available</h3>
                    <p class="text-muted mb-4">There are no products available in the marketplace at the moment.</p>
                    <a href="{{ route('buyer.market') }}" class="btn btn-success">
                        <i class="fas fa-refresh me-1"></i>Refresh Page
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .hover-shadow:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .product-image-container {
        overflow: hidden;
    }
    
    .product-image {
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .modal-content {
        border-radius: 12px;
    }
    
    .btn {
        border-radius: 8px;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
    }
    
    .badge {
        border-radius: 6px;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    console.log('Marketplace loaded successfully');
    
    // Price calculation for purchase modals
    @foreach($products as $product)
    const quantityInput{{ $product->id }} = document.getElementById('quantity{{ $product->id }}');
    const quantityDisplay{{ $product->id }} = document.getElementById('quantityDisplay{{ $product->id }}');
    const totalPrice{{ $product->id }} = document.getElementById('totalPrice{{ $product->id }}');
    const purchaseForm{{ $product->id }} = document.getElementById('purchaseForm{{ $product->id }}');
    const purchaseBtn{{ $product->id }} = document.getElementById('purchaseBtn{{ $product->id }}');
    
    if (quantityInput{{ $product->id }} && totalPrice{{ $product->id }}) {
        quantityInput{{ $product->id }}.addEventListener('input', function() {
            const quantity = parseInt(this.value) || 0;
            const price = {{ $product->price }};
            const subtotal = quantity * price;
            
            if (quantityDisplay{{ $product->id }}) {
                quantityDisplay{{ $product->id }}.textContent = quantity;
            }
            
            totalPrice{{ $product->id }}.textContent = subtotal.toLocaleString('en-KE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    }

    if (purchaseForm{{ $product->id }}) {
        purchaseForm{{ $product->id }}.addEventListener('submit', function(e) {
            if (purchaseBtn{{ $product->id }}) {
                purchaseBtn{{ $product->id }}.disabled = true;
                purchaseBtn{{ $product->id }}.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
            }
        });
    }
    @endforeach

    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const productItems = document.querySelectorAll('.product-item');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value;

        productItems.forEach(item => {
            const name = item.dataset.name;
            const itemCategory = item.dataset.category;
            const matchesSearch = name.includes(searchTerm);
            const matchesCategory = !category || itemCategory === category;

            item.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
        });
    }

    if (searchInput && categoryFilter) {
        searchInput.addEventListener('input', filterProducts);
        categoryFilter.addEventListener('change', filterProducts);
    }

    // Reset modal states when closed
    @foreach($products as $product)
    const purchaseModal{{ $product->id }} = document.getElementById('purchaseModal{{ $product->id }}');
    if (purchaseModal{{ $product->id }}) {
        purchaseModal{{ $product->id }}.addEventListener('hidden.bs.modal', function () {
            const purchaseBtn = document.getElementById('purchaseBtn{{ $product->id }}');
            if (purchaseBtn) {
                purchaseBtn.disabled = false;
                purchaseBtn.innerHTML = '<i class="fas fa-credit-card me-1"></i>Proceed to Checkout';
            }
        });
    }
    @endforeach
});

// Enhanced addToCart function with better error handling
function addToCart(productId, quantity = 1) {
    console.log('Adding product to cart:', productId);
    
    const addButton = document.getElementById(`addToCartBtn${productId}`);
    
    if (!addButton) {
        console.error('Add to cart button not found for product:', productId);
        showToast('error', 'Unable to add product to cart');
        return;
    }
    
    const originalText = addButton.innerHTML;
    const originalClasses = addButton.className;
    
    // Show loading state
    addButton.disabled = true;
    addButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
    addButton.className = originalClasses.replace('btn-outline-primary', 'btn-secondary');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    fetch(`/buyer/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            quantity: quantity,
            _token: csrfToken
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            if (response.status === 401) {
                throw new Error('Please log in to add items to cart');
            } else if (response.status === 403) {
                throw new Error('Access denied. Buyer account required.');
            } else if (response.status === 404) {
                throw new Error('Product not found');
            } else if (response.status === 422) {
                return response.json().then(data => {
                    const errorMessage = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                    throw new Error(errorMessage || 'Validation error');
                });
            } else {
                throw new Error(`Server error: ${response.status}`);
            }
        }
        return response.json();
    })
    .then(data => {
        console.log('Add to cart response:', data);
        
        if (data.success) {
            showToast('success', data.message || 'Product added to cart successfully!');
            updateCartCount(data.cart_count);
            
            // Update button to show success state
            addButton.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
            addButton.className = originalClasses.replace('btn-outline-primary', 'btn-success');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                addButton.disabled = false;
                addButton.innerHTML = originalText;
                addButton.className = originalClasses;
            }, 2000);
            
        } else {
            throw new Error(data.error || data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        console.error('Add to cart error:', error);
        
        let errorMessage = error.message || 'Failed to add product to cart';
        
        // Handle specific error cases
        if (errorMessage.includes('log in')) {
            showToast('warning', 'Please log in to add items to cart');
            // Redirect to login after 2 seconds
            setTimeout(() => {
                window.location.href = '{{ route("login") }}';
            }, 2000);
        } else if (errorMessage.includes('Buyer account')) {
            showToast('error', 'You need a buyer account to add items to cart');
        } else {
            showToast('error', errorMessage);
        }
        
        // Reset button state
        addButton.disabled = false;
        addButton.innerHTML = originalText;
        addButton.className = originalClasses;
    });
}

// Enhanced updateCartCount function
function updateCartCount(count) {
    console.log('Updating cart count:', count);
    
    // Update navbar cart count
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        
        if (count > 0) {
            cartCountElement.classList.remove('d-none');
            // Add animation
            cartCountElement.classList.add('pulse-animation');
            setTimeout(() => {
                cartCountElement.classList.remove('pulse-animation');
            }, 500);
        } else {
            cartCountElement.classList.add('d-none');
        }
    } else {
        console.warn('Cart count element not found');
    }
    
    // Update mobile cart count if exists
    const mobileCartCount = document.getElementById('mobileCartCount');
    if (mobileCartCount) {
        mobileCartCount.textContent = count;
        if (count > 0) {
            mobileCartCount.classList.remove('d-none');
        } else {
            mobileCartCount.classList.add('d-none');
        }
    }
}

// Enhanced toast notification
function showToast(type, message) {
    console.log('Showing toast:', type, message);
    
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => {
        if (toast.parentNode) {
            toast.remove();
        }
    });
    
    const toast = document.createElement('div');
    toast.className = `custom-toast alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    toast.style.maxWidth = '400px';
    toast.style.borderRadius = '8px';
    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    toast.style.border = 'none';
    
    const icon = type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-triangle' : 
                type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    const textColor = type === 'warning' ? 'text-dark' : 'text-white';
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas ${icon} me-2 fs-5 ${textColor}"></i>
            <div class="flex-grow-1 ${textColor}">${message}</div>
            <button type="button" class="btn-close ${type === 'warning' ? 'btn-close-dark' : 'btn-close-white'}" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
    
    // Add click to dismiss
    toast.addEventListener('click', function() {
        if (toast.parentNode) {
            toast.remove();
        }
    });
}

// Debug function to test cart functionality
function testCartSetup() {
    console.log('=== Cart Setup Test ===');
    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')?.substring(0, 20) + '...');
    console.log('Add to Cart buttons found:', document.querySelectorAll('[id^="addToCartBtn"]').length);
    console.log('Cart count element:', document.getElementById('cartCount'));
    console.log('=== End Test ===');
}

// Run test on load
document.addEventListener('DOMContentLoaded', testCartSetup);
</script>
@endpush