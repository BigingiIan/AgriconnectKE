@extends('layouts.app')

@section('title', 'Checkout - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Checkout</h1>
    <a href="{{ route('buyer.cart') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Cart
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Order Summary -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $cartItem)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($cartItem['product']->image)
                                            <img src="{{ asset('storage/' . $cartItem['product']->image) }}" 
                                                 alt="{{ $cartItem['product']->name }}" 
                                                 class="img-thumbnail me-3" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $cartItem['product']->name }}</h6>
                                            <small class="text-muted">by {{ $cartItem['product']->farmer->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    Ksh {{ number_format($cartItem['product']->price, 2) }}
                                </td>
                                <td class="align-middle">
                                    {{ $cartItem['quantity'] }}
                                </td>
                                <td class="align-middle">
                                    <strong class="text-success">
                                        Ksh {{ number_format($cartItem['item_total'], 2) }}
                                    </strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pricing Summary -->
                <div class="row mt-4">
                    <div class="col-md-6 offset-md-6">
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Ksh {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Delivery Cost:</span>
                                <span>Ksh {{ number_format($deliveryCost, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 fs-5 fw-bold text-success">
                                <span>Total:</span>
                                <span>Ksh {{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Delivery Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Delivery Address:</strong><br>
                            {{ Auth::user()->address }}
                        </p>
                        <p><strong>Contact Phone:</strong><br>
                            {{ Auth::user()->phone }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Delivery Note</h6>
                            <p class="mb-0 small">
                                Products will be delivered from different farmers. 
                                Each product may arrive separately based on the farmer's location and available drivers.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Method</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-mobile-alt"></i> M-Pesa Payment</h6>
                    <p class="mb-2">Complete your purchase using M-Pesa</p>
                </div>
                
                <form method="POST" action="{{ route('buyer.payment', $order) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="phone" class="form-label">M-Pesa Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="{{ Auth::user()->phone }}" required placeholder="e.g., 254712345678">
                        <div class="form-text">Enter your M-Pesa registered phone number</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-credit-card"></i> Pay Ksh {{ number_format($total, 2) }}
                        </button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-lock"></i> Your payment is secure and encrypted
                    </small>
                </div>
            </div>
        </div>

        <!-- Order Help -->
        <div class="card mt-4">
            <div class="card-body">
                <h6>Need Help?</h6>
                <p class="small text-muted mb-2">
                    <i class="fas fa-phone"></i> Call: 0700 000000
                </p>
                <p class="small text-muted mb-0">
                    <i class="fas fa-envelope"></i> Email: support@agriconnectke.com
                </p>
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
                    <li>Multiple products may arrive separately</li>
                    <li>You will receive tracking information for each delivery</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection