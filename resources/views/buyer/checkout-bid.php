@extends('layouts.app')

@section('title', 'Checkout - Accepted Bid - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Checkout - Accepted Bid</h1>
    <a href="{{ route('buyer.orders') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Order Summary -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle"></i> Bid Accepted!</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h6><i class="fas fa-info-circle"></i> Congratulations!</h6>
                    <p class="mb-0">Your bid has been accepted by the farmer. Please complete your purchase to secure the product.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Product Details</h6>
                        <div class="d-flex align-items-center mb-3">
                            @if($order->product->image)
                                <img src="{{ asset('storage/' . $order->product->image) }}" 
                                     alt="{{ $order->product->name }}" 
                                     class="img-thumbnail me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <strong>{{ $order->product->name }}</strong><br>
                                <small class="text-muted">by {{ $order->product->farmer->name }}</small>
                            </div>
                        </div>
                        
                        <p><strong>Your Accepted Bid:</strong><br>
                            <span class="h5 text-success">Ksh {{ number_format($order->bid->amount, 2) }}</span>
                        </p>
                        
                        <p><strong>Original Price:</strong><br>
                            <span class="text-muted text-decoration-line-through">Ksh {{ number_format($order->product->price, 2) }}</span>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                        <p><strong>Delivery Cost:</strong> Ksh {{ number_format($order->delivery_cost, 2) }}</p>
                        <p><strong>Total Amount:</strong> 
                            <span class="h5 text-success">Ksh {{ number_format($order->amount, 2) }}</span>
                        </p>
                        <p><strong>Delivery Address:</strong><br>{{ $order->delivery_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Farmer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Farmer Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> {{ $order->farmer->name }}</p>
                        <p><strong>Phone:</strong> {{ $order->farmer->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ $order->farmer->email }}</p>
                        <p><strong>Location:</strong> {{ $order->farmer->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Complete Your Purchase</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6><i class="fas fa-clock"></i> Important</h6>
                    <p class="small mb-2">Please complete payment within 24 hours to secure your accepted bid.</p>
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
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the terms and conditions
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-credit-card"></i> Pay Ksh {{ number_format($order->amount, 2) }}
                        </button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-lock"></i> Secure payment processing
                    </small>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card mt-4">
            <div class="card-body">
                <h6>Order Timeline</h6>
                <div class="timeline-small">
                    <div class="timeline-item completed">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small>Bid Placed</small><br>
                            <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="timeline-item completed">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small>Bid Accepted</small><br>
                            <small class="text-muted">Farmer approved your offer</small>
                        </div>
                    </div>
                    <div class="timeline-item current">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <small>Payment Pending</small><br>
                            <small class="text-muted">Complete payment to secure order</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <small>Delivery</small><br>
                            <small class="text-muted">Product will be shipped after payment</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-small {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 15px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
    background-color: #dee2e6;
}
.timeline-marker.bg-success {
    background-color: #198754 !important;
}
.timeline-marker.bg-warning {
    background-color: #ffc107 !important;
}
.timeline-item.completed .timeline-content {
    color: #198754;
}
.timeline-item.current .timeline-content {
    font-weight: bold;
}
</style>
@endsection