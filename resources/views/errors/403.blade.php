<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .error-container {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
        }
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
        }
        .error-code {
            font-size: 5rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="error-container">
                    <div class="error-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="error-code">403</div>
                    <h2 class="mb-3">Access Denied</h2>
                    <p class="text-muted mb-4">
                        Sorry, you don't have permission to access this page.
                    </p>
                    
                    <hr>
                    
                    <div class="mt-3">
                        @auth
                            @if(Auth::user()->role === 'farmer')
                                <a href="{{ route('farmer.dashboard') }}" class="btn btn-primary">Go to Farmer Dashboard</a>
                            @elseif(Auth::user()->role === 'buyer')
                                <a href="{{ route('buyer.dashboard') }}" class="btn btn-primary">Go to Buyer Dashboard</a>
                            @elseif(Auth::user()->role === 'driver')
                                <a href="{{ route('driver.dashboard') }}" class="btn btn-primary">Go to Driver Dashboard</a>
                            @elseif(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Go to Admin Dashboard</a>
                            @endif
                        @endauth
                        <a href="{{ route('home') }}" class="btn btn-secondary">Return to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>