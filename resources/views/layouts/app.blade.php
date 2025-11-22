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
        html, body {
            height: 100%;
            margin: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        
        /* Wrapper for the main content area including sidebar */
        #app-content {
            flex: 1 0 auto;
            display: flex;
            width: 100%;
        }
        
        /* Sidebar styling */
        .sidebar {
            width: 250px; /* Fixed width for sidebar */
            background-color: #fff;
            border-right: 1px solid #eee;
            flex-shrink: 0;
            display: none; /* Hidden by default on mobile */
        }
        
        @media (min-width: 768px) {
            .sidebar {
                display: block;
            }
        }
        
        /* Main content area */
        main {
            flex-grow: 1;
            padding: 20px;
            width: 100%; /* Ensure it takes full width available */
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        
        footer {
            flex-shrink: 0;
            width: 100%;
            background-color: #212529;
            color: white;
            padding: 1.5rem 0;
            margin-top: auto; /* Push to bottom */
            position: relative;
            z-index: 10;
        }

        /* Navbar styling */
        .navbar-brand { font-weight: bold; }
        
        /* Gradient backgrounds */
        .bg-gradient-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%) !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important; }
        .bg-gradient-info { background: linear-gradient(135deg, #0dcaf0 0%, #3dd5f3 100%) !important; }
        .bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%) !important; }
        .bg-gradient-danger { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%) !important; }
        
        /* Rounded buttons */
        .btn-rounded { border-radius: 50px !important; }
        
        /* Dashboard Stat Card */
        .dashboard-stat-card {
            border-radius: 15px;
            padding: 25px;
            color: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .dashboard-stat-card:hover { transform: translateY(-5px); }
        .dashboard-stat-card .icon-bg {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: 0.2;
            transform: rotate(-15deg);
        }
        
        /* Content Card */
        .content-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
            transition: all 0.3s ease;
            background-color: white;
        }
        .content-card:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .content-card .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
            font-weight: bold;
            font-size: 1.1rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        
        .nav-link {
            color: #333;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #e8f5e9;
            color: #198754;
            font-weight: bold;
        }
        .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
        
        .container-fluid { padding: 0; }
        @auth .container-fluid { padding: 0; } @endauth
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-success shadow-sm mb-0">
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
                            <button type="submit" class="btn btn-outline-light btn-sm btn-rounded">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
            <div class="navbar-nav ms-auto">
                <a class="nav-link btn btn-outline-light btn-sm me-2 btn-rounded" href="{{ route('login') }}">Login</a>
                <a class="nav-link btn btn-outline-light btn-sm btn-rounded" href="{{ route('register') }}">Register</a>
            </div>
            @endauth
        </div>
        @auth
        @if(Auth::user()->role === 'buyer')
        <li class="nav-item list-unstyled ms-3">
            <a class="nav-link position-relative text-white" href="{{ route('buyer.cart') }}">
                <i class="fas fa-shopping-cart"></i>
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

    <div id="app-content">
        @auth
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column p-3">
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
        @endauth

        <!-- Main content -->
        <main>
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-auto py-4 border-top">
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