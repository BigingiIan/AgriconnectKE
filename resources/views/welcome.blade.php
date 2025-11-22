@extends('layouts.app')

@section('title', 'AgriconnectKE - Fresh Produce Direct from Farmers')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1495107334309-fcf20504a5ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
        height: 80vh;
        display: flex;
        align-items: center;
        color: white;
    }
    .feature-icon {
        font-size: 3rem;
        color: #198754;
        margin-bottom: 1rem;
    }
    .step-number {
        background-color: #198754;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 1rem;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Fresh Produce, Directly from Farmers</h1>
        <p class="lead mb-5">Connect directly with local farmers for the freshest fruits, vegetables, and grains. No middlemen, better prices.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-success btn-lg px-5 btn-rounded shadow-sm">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 btn-rounded">Login</a>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="card content-card h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-tractor feature-icon"></i>
                    <h3 class="card-title h4 fw-bold">For Farmers</h3>
                    <p class="card-text text-muted">List your produce, set your prices, and reach thousands of buyers directly. Manage your inventory and sales easily.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card content-card h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-shopping-basket feature-icon"></i>
                    <h3 class="card-title h4 fw-bold">For Buyers</h3>
                    <p class="card-text text-muted">Access fresh, organic produce at farm-gate prices. Bid on bulk orders or buy directly for your home or business.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card content-card h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-truck feature-icon"></i>
                    <h3 class="card-title h4 fw-bold">For Drivers</h3>
                    <p class="card-text text-muted">Earn money by delivering fresh produce. Manage your delivery schedule and track your earnings in real-time.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="step-number">1</div>
                <h5>Register</h5>
                <p>Sign up as a farmer, buyer, or driver.</p>
            </div>
            <div class="col-md-3">
                <div class="step-number">2</div>
                <h5>Connect</h5>
                <p>Farmers list produce, buyers place orders.</p>
            </div>
            <div class="col-md-3">
                <div class="step-number">3</div>
                <h5>Transact</h5>
                <p>Secure payments via M-Pesa integration.</p>
            </div>
            <div class="col-md-3">
                <div class="step-number">4</div>
                <h5>Deliver</h5>
                <p>Drivers pick up and deliver to your doorstep.</p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="container py-5 text-center">
    <h2 class="mb-4">Ready to join the revolution?</h2>
    <a href="{{ route('register') }}" class="btn btn-success btn-lg btn-rounded shadow-sm">Create Your Account Now</a>
</div>
@endsection
