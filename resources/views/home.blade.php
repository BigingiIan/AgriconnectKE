<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'AgriconnectKE - Fresh Produce Direct from Farmers')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1495107334309-fcf20504a5ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 150px 0;
        margin-top: -1.5rem; /* Offset container padding */
    }
    
    .feature-icon {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        color: #198754;
        background: #e8f5e9;
        width: 80px;
        height: 80px;
        line-height: 80px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
    }
    
    .product-img-wrapper {
        height: 200px;
        overflow: hidden;
    }
    
    .content-card img {
        transition: transform 0.5s ease;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .content-card:hover img {
        transform: scale(1.1);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(25, 135, 84, 0.3);
    }
    
    .step-number {
        background-color: #198754;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
        box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-4">Fresh Produce, Directly from Farmers</h1>
        <p class="lead mb-5 fs-4">Connect directly with local farmers for the freshest fruits, vegetables, and grains.<br>No middlemen, better prices.</p>
        @guest
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-success btn-lg px-5 py-3 btn-rounded shadow-lg">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 py-3 btn-rounded">Sign In</a>
        </div>
        @else
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('products.index') }}" class="btn btn-success btn-lg px-5 py-3 btn-rounded shadow-lg">Browse Market</a>
        </div>
        @endguest
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose AgriconnectKE?</h2>
            <p class="text-muted lead">We're revolutionizing the agricultural supply chain</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card content-card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4 class="card-title mb-3 fw-bold">Direct Farm-to-Table</h4>
                        <p class="card-text text-muted">Connect directly with local farmers for the freshest produce at the best prices. No hidden fees.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card content-card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-truck-fast"></i>
                        </div>
                        <h4 class="card-title mb-3 fw-bold">Fast Delivery</h4>
                        <p class="card-text text-muted">Track your orders in real-time with our advanced GPS tracking system. Freshness guaranteed.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card content-card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h4 class="card-title mb-3 fw-bold">Fair Pricing</h4>
                        <p class="card-text text-muted">Transparent pricing for everyone. Farmers get paid better, buyers save more.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold mb-2">Featured Products</h2>
                <p class="text-muted mb-0">Fresh picks just for you</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-success btn-rounded">View All Products</a>
        </div>
        
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-3">
                <div class="card content-card h-100">
                    <div class="product-img-wrapper">
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0 fw-bold">{{ $product->name }}</h5>
                            <span class="badge bg-success rounded-pill">Fresh</span>
                        </div>
                        <p class="card-text text-success fw-bold fs-5 mb-1">KES {{ number_format($product->price, 2) }}</p>
                        <small class="text-muted d-block mb-3">
                            <i class="fas fa-user-circle me-1"></i> {{ $product->farmer->name }}
                        </small>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success w-100 btn-rounded">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-users fa-2x mb-3 opacity-75"></i>
                    <h3 class="display-4 fw-bold">{{ $stats['farmers'] }}</h3>
                    <p class="mb-0 fs-5">Farmers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-carrot fa-2x mb-3 opacity-75"></i>
                    <h3 class="display-4 fw-bold">{{ $stats['products'] }}</h3>
                    <p class="mb-0 fs-5">Products</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-shopping-basket fa-2x mb-3 opacity-75"></i>
                    <h3 class="display-4 fw-bold">{{ $stats['buyers'] }}</h3>
                    <p class="mb-0 fs-5">Happy Buyers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-truck fa-2x mb-3 opacity-75"></i>
                    <h3 class="display-4 fw-bold">{{ $stats['deliveries'] }}</h3>
                    <p class="mb-0 fs-5">Deliveries Done</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted lead">Simple steps to get started</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-3">
                    <div class="step-number">1</div>
                    <h4>Register</h4>
                    <p class="text-muted">Sign up as a farmer, buyer, or driver. It only takes a few minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <div class="step-number">2</div>
                    <h4>Connect</h4>
                    <p class="text-muted">Farmers list produce, buyers place orders, and drivers accept deliveries.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <div class="step-number">3</div>
                    <h4>Transact</h4>
                    <p class="text-muted">Secure payments via M-Pesa and real-time delivery tracking.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">What Our Users Say</h2>
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="mb-4 text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="mb-4 fst-italic">"{{ $testimonial->content }}"</p>
                    <div class="d-flex align-items-center">
                        <img src="{{ $testimonial->user->avatar }}" alt="{{ $testimonial->user->name }}" 
                             class="rounded-circle me-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $testimonial->user->name }}</h6>
                            <small class="text-muted">{{ ucfirst($testimonial->user->role) }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-success text-white text-center">
    <div class="container">
        <h2 class="fw-bold mb-4">Ready to join the revolution?</h2>
        <p class="lead mb-4">Join thousands of users transforming agriculture in Kenya.</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow text-success fw-bold">Create Your Account Now</a>
        @endguest
    </div>
</section>
@endsection