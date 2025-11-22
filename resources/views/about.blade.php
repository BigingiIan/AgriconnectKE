@extends('layouts.app')

@section('title', 'About AgriconnectKE')

@section('styles')
<style>
    .mission-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/about-bg.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 100px 0;
    }
    
    .value-card {
        transition: transform 0.3s;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .value-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #198754;
    }
    
    .team-member-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .team-member-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .team-member-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<!-- Mission Section -->
<section class="mission-section">
    <div class="container text-center">
        <h1 class="display-4 mb-4 fw-bold">Our Mission</h1>
        <p class="lead mb-4">Revolutionizing agriculture through technology by connecting farmers directly with buyers, ensuring fair prices, and promoting sustainable farming practices across Kenya.</p>
    </div>
</section>

<!-- Values Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Our Core Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card value-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4 class="card-title fw-bold">Trust & Transparency</h4>
                        <p class="card-text text-muted">Building strong relationships through honest communication and clear transactions between all parties.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card value-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="value-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h4 class="card-title fw-bold">Sustainability</h4>
                        <p class="card-text text-muted">Promoting environmentally conscious farming practices and supporting local agriculture.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card value-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="card-title fw-bold">Community Focus</h4>
                        <p class="card-text text-muted">Empowering local communities by creating opportunities and fostering growth.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-4 fw-bold">Our Story</h2>
                <p class="lead">AgriconnectKE was founded with a simple but powerful vision: to revolutionize how agricultural products are bought and sold in Kenya.</p>
                <p>We recognized the challenges faced by farmers in reaching buyers directly and getting fair prices for their produce. Similarly, buyers struggled to find reliable sources of fresh, quality produce.</p>
                <p>Our platform bridges this gap by leveraging technology to create direct connections between farmers and buyers, while ensuring efficient delivery through our network of trusted drivers.</p>
            </div>
            <div class="col-md-6">
                <img src="/images/about-story.jpg" alt="Our Story" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Our Impact</h2>
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="h1 text-success mb-3 fw-bold">{{ number_format($stats['farmers']) }}+</div>
                <h5>Farmers Empowered</h5>
            </div>
            <div class="col-md-3">
                <div class="h1 text-success mb-3 fw-bold">{{ number_format($stats['products']) }}+</div>
                <h5>Products Listed</h5>
            </div>
            <div class="col-md-3">
                <div class="h1 text-success mb-3 fw-bold">{{ number_format($stats['buyers']) }}+</div>
                <h5>Active Buyers</h5>
            </div>
            <div class="col-md-3">
                <div class="h1 text-success mb-3 fw-bold">{{ number_format($stats['deliveries']) }}+</div>
                <h5>Successful Deliveries</h5>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Our Team</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card team-member-card h-100">
                    <div class="card-body text-center p-4">
                        <img src="/images/team/ceo.jpg" alt="CEO" class="team-member-img mb-4 shadow-sm">
                        <h4 class="fw-bold">John Kamau</h4>
                        <p class="text-muted">CEO & Founder</p>
                        <p class="card-text">Former farmer with 15 years of experience in agricultural technology.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card team-member-card h-100">
                    <div class="card-body text-center p-4">
                        <img src="/images/team/cto.jpg" alt="CTO" class="team-member-img mb-4 shadow-sm">
                        <h4 class="fw-bold">Sarah Wanjiru</h4>
                        <p class="text-muted">Chief Technology Officer</p>
                        <p class="card-text">Tech innovator with a passion for solving agricultural challenges.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card team-member-card h-100">
                    <div class="card-body text-center p-4">
                        <img src="/images/team/ops.jpg" alt="Operations Director" class="team-member-img mb-4 shadow-sm">
                        <h4 class="fw-bold">David Ochieng</h4>
                        <p class="text-muted">Operations Director</p>
                        <p class="card-text">Expert in supply chain management and logistics optimization.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="mb-4 fw-bold">Join Our Growing Community</h2>
        <p class="lead mb-4">Be part of the agricultural revolution in Kenya</p>
        @guest
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg btn-rounded shadow-sm text-success fw-bold">Get Started Today</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-rounded">Contact Us</a>
        </div>
        @endguest
    </div>
</section>
@endsection