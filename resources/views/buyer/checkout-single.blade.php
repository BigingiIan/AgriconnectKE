@extends('layouts.app')

@section('title', 'Checkout - AgriconnectKE')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Checkout</h1>
                    <p class="text-muted mb-0">Complete your purchase</p>
                </div>
                <a href="{{ route('buyer.market') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Market
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Order Summary -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($order->product->image)
                                        <img src="{{ asset('storage/' . $order->product->image) }}" 
                                             alt="{{ $order->product->name }}" 
                                             class="rounded" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <h6 class="mb-1">{{ $order->product->name }}</h6>
                                    <p class="text-muted small mb-1">by {{ $order->product->farmer->name }}</p>
                                    <p class="text-muted small mb-0">Category: {{ ucfirst($order->product->category) }}</p>
                                </div>
                                <div class="col-auto text-end">
                                    <div class="h5 text-success mb-1">Ksh {{ number_format($order->product->price, 2) }}</div>
                                    <small class="text-muted">per unit</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Quantity:</strong> {{ $order->quantity }}
                                </div>
                                <div class="col-6 text-end">
                                    <strong>Subtotal:</strong> Ksh {{ number_format($order->product->price * $order->quantity, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Delivery Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Delivery Address:</strong><br>
                                        {{ $order->delivery_address }}
                                    </p>
                                    <p><strong>Contact Phone:</strong><br>
                                        {{ Auth::user()->phone }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Delivery Note</h6>
                                        <p class="mb-0 small">
                                            The product will be delivered directly from the farmer. 
                                            Delivery time may vary based on your location.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Complete Payment</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info border-0">
                                <h6><i class="fas fa-mobile-alt me-2"></i>M-Pesa Payment</h6>
                                <p class="mb-0 small">Complete your purchase using M-Pesa</p>
                            </div>
                            
                            <!-- Pricing Breakdown -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Product Subtotal:</span>
                                    <span>Ksh {{ number_format($order->product->price * $order->quantity, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Delivery Cost:</span>
                                    <span>Ksh {{ number_format($order->delivery_cost, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2 fs-5 fw-bold text-success">
                                    <span>Total Amount:</span>
                                    <span>Ksh {{ number_format($order->amount, 2) }}</span>
                                </div>
                            </div>
                            
                            <form method="POST" action="{{ route('buyer.payment', $order) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-medium">M-Pesa Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="{{ Auth::user()->phone }}" required 
                                           placeholder="e.g., 254712345678">
                                    <div class="form-text">Enter your M-Pesa registered phone number</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg py-3">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Pay Ksh {{ number_format($order->amount, 2) }}
                                    </button>
                                </div>
                            </form>

                            <div class="mt-3 text-center">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i> Secure payment processing
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Farmer Information -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Farmer Information</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Name:</strong> {{ $order->farmer->name }}</p>
                            <p class="mb-2"><strong>Phone:</strong> {{ $order->farmer->phone }}</p>
                            <p class="mb-0"><strong>Location:</strong> {{ $order->farmer->address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Purchase Terms</h6>
                <ul>
                    <li>All prices are in Kenyan Shillings (Ksh)</li>
                    <li>Delivery costs are calculated based on distance</li>
                    <li>Products are subject to availability</li>
                    <li>Farmers may cancel orders if products become unavailable</li>
                    <li>Refunds are processed within 3-5 business days</li>
                </ul>
                
                <h6>Delivery Terms</h6>
                <ul>
                    <li>Delivery times may vary based on location</li>
                    <li>You will receive tracking information for your delivery</li>
                    <li>Please ensure someone is available to receive the delivery</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
    }
    
    .btn {
        border-radius: 8px;
    }
    
    .form-control {
        border-radius: 8px;
    }
    
    .sticky-top {
        position: sticky;
        z-index: 100;
    }
</style>
@endpush