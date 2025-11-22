@extends('layouts.app')

@section('title', 'Driver Dashboard - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-sm btn-outline-success" onclick="updateDriverLocation()">
            <i class="fas fa-sync-alt"></i> Update Location
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Assigned</h5>
                        <h2 class="card-text">{{ $assignedDeliveries }}</h2>
                    </div>
                    <i class="fas fa-box fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">In Progress</h5>
                        <h2 class="card-text">{{ $inProgressDeliveries }}</h2>
                    </div>
                    <i class="fas fa-shipping-fast fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Completed</h5>
                        <h2 class="card-text">{{ $completedDeliveries }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Earnings</h5>
                        <h2 class="card-text">Ksh {{ number_format($totalEarnings, 2) }}</h2>
                    </div>
                    <i class="fas fa-wallet fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Current Deliveries -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Current Deliveries</h5>
                <span class="badge bg-primary">{{ $currentDeliveries->count() }} Active</span>
            </div>
            <div class="card-body">
                @if($currentDeliveries->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($currentDeliveries as $order)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Order #{{ $order->id }} - {{ $order->product->name }}</h6>
                                <span class="badge bg-{{ $order->status == 'shipped' ? 'warning' : 'info' }}">{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Pickup From:</small>
                                    <strong>{{ $order->farmer->name ?? 'Farmer' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Deliver To:</small>
                                    <strong>{{ $order->buyer->name }}</strong><br>
                                    <small>{{ $order->delivery_address }}</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">Assigned {{ $order->updated_at->diffForHumans() }}</small>
                                <div>
                                    <a href="{{ route('driver.deliveries.show', $order) }}" class="btn btn-sm btn-outline-primary me-2">
                                        View Details
                                    </a>
                                    <form action="{{ route('driver.deliveries.update-status', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($order->status === 'paid')
                                            <input type="hidden" name="status" value="shipped">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-shipping-fast"></i> Start
                                            </button>
                                        @elseif($order->status === 'shipped')
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Complete
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-truck fa-3x text-muted mb-3 opacity-50"></i>
                        <h5 class="text-muted">No Active Deliveries</h5>
                        <p class="text-muted mb-0">You don't have any assigned deliveries at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Location Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Availability</span>
                    <span class="badge bg-{{ Auth::user()->is_available ? 'success' : 'danger' }} rounded-pill">
                        {{ Auth::user()->is_available ? 'Online' : 'Offline' }}
                    </span>
                </div>
                <form action="{{ route('driver.toggle-availability') }}" method="POST" class="d-grid">
                    @csrf
                    <button type="submit" class="btn btn-{{ Auth::user()->is_available ? 'outline-danger' : 'outline-success' }}">
                        {{ Auth::user()->is_available ? 'Go Offline' : 'Go Online' }}
                    </button>
                </form>
                
                @if($driverLocation)
                <hr>
                <div class="small">
                    <div class="d-flex justify-content-between text-muted mb-1">
                        <span>Last Update:</span>
                        <span>{{ $driverLocation->location_updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-truncate text-muted">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        {{ number_format($driverLocation->latitude, 4) }}, {{ number_format($driverLocation->longitude, 4) }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent History -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">History</h5>
                <a href="{{ route('driver.deliveries') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentDeliveries->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentDeliveries->take(5) as $order)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">#{{ $order->id }}</h6>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, H:i') }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success">Delivered</span>
                                    <small class="d-block text-success">+ Ksh {{ number_format($order->delivery_cost, 0) }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center text-muted">
                        <small>No delivery history yet.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateDriverLocation() {
    if (navigator.geolocation) {
        const btn = document.querySelector('button[onclick="updateDriverLocation()"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                fetch('{{ route("driver.location.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update location: ' + data.message);
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update location. Please try again.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            },
            function(error) {
                alert('Unable to get your location. Please ensure location services are enabled.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}
</script>
@endpush