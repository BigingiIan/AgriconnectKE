<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AgriconnectKE - Connecting Farmers and Buyers')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand { font-weight: bold; }
        .sidebar { 
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .nav-link {
            color: #333;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #198754;
            color: white;
        }
        main {
            padding: 0;
        }
        .container-fluid {
            padding: 0;
        }
        @auth
        .container-fluid {
            padding: 0 15px;
        }
        main {
            padding: 20px 0;
        }
        @endauth
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">🌾 AgriconnectKE</a>
            
            @auth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text me-3">Welcome, {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </div>
            @endauth
        </div>
        @auth
    @if(Auth::user()->role === 'buyer')
        @php
            $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
        @endphp
<!-- In your navigation menu -->
<li class="nav-item">
    <a class="nav-link position-relative" href="{{ route('buyer.cart') }}">
        <i class="fas fa-shopping-cart"></i>
        Cart
        @auth
    <span id="mobileCartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ session('cart') && count(session('cart')) > 0 ? '' : 'd-none' }}">
        {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
    </span>
        @endauth
    </a>
</li>
    @endif
@endauth
    </nav>

    <div class="container-fluid">
        <div class="row">
            @auth
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky">
                    @if(Auth::user()->role === 'farmer')
                        @include('partials.farmer-sidebar')
                    @elseif(Auth::user()->role === 'buyer')
                        @include('partials.buyer-sidebar')
                    @elseif(Auth::user()->role === 'driver')
                        @include('partials.driver-sidebar')
                    @elseif(Auth::user()->role === 'admin')
                        @include('partials.admin-sidebar')
                    @endif
                </div>
            </div>
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @else
            <!-- Full width for unauthenticated users -->
            <main class="col-12">
            @endauth
            
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-auto py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>🌾 AgriconnectKE</h5>
                    <p>Connecting farmers directly with buyers for fresh, quality produce.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="{{ route('login') }}" class="text-white text-decoration-none">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-white text-decoration-none">Register</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p>
                        <i class="fas fa-envelope me-2"></i> info@agriconnectke.com<br>
                        <i class="fas fa-phone me-2"></i> +254 700 000 000
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <small>&copy; {{ date('Y') }} AgriconnectKE. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>