@extends('layouts.app')

@section('title', 'Driver Dashboard - AgriconnectKE')

@section('styles')
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-4 mb-3 border-bottom">
    <div>
        <h1 class="h2 fw-bold">Dashboard</h1>
        <p class="text-muted">Manage your deliveries and earnings</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-success btn-rounded shadow-sm" onclick="updateDriverLocation()">
            <i class="fas fa-sync-alt me-2"></i> Update Location
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats Cards -->
<div class="row mb-4 g-4">
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-success">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Assigned</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $assignedDeliveries }}</h2>
                </div>
                <i class="fas fa-box fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-box icon-bg"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-warning text-dark">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">In Progress</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $inProgressDeliveries }}</h2>
                </div>
                <i class="fas fa-shipping-fast fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-shipping-fast icon-bg"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-primary">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Completed</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $completedDeliveries }}</h2>
                </div>
                <i class="fas fa-check-circle fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-check-circle icon-bg"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-info text-white">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Earnings</h5>
                    <h2 class="display-6 fw-bold mb-0">Ksh {{ number_format($totalEarnings, 0) }}</h2>
                </div>
                <i class="fas fa-wallet fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-wallet icon-bg"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Current Deliveries -->
    <div class="col-md-8">
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-primary"><i class="fas fa-truck me-2"></i>Current Deliveries</span>
                <span class="badge bg-primary rounded-pill">{{ $currentDeliveries->count() }} Active</span>
            </div>
            <div class="card-body p-0">
                @if($currentDeliveries->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($currentDeliveries as $order)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-bold text-primary">Order #{{ $order->id }} - {{ $order->product->name }}</h6>
                                <span class="badge rounded-pill bg-{{ $order->status == 'shipped' ? 'warning' : 'info' }}">{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="row mb-3 g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.7rem;">Pickup From</small>
                                        <strong class="d-block text-dark">{{ $order->farmer->name ?? 'Farmer' }}</strong>
                                        <small class="text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $order->farmer->phone ?? 'N/A' }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.7rem;">Deliver To</small>
                                        <strong class="d-block text-dark">{{ $order->buyer->name }}</strong>
                                        <small class="text-muted d-block text-truncate"><i class="fas fa-map-marker-alt me-1"></i> {{ $order->delivery_address }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted"><i class="far fa-clock me-1"></i> Assigned {{ $order->updated_at->diffForHumans() }}</small>
                                <div>
                                    <a href="{{ route('driver.deliveries.show', $order) }}" class="btn btn-sm btn-outline-primary btn-rounded me-2">
                                        View Details
                                    </a>
                                    <form action="{{ route('driver.deliveries.update-status', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($order->status === 'paid')
                                            <input type="hidden" name="status" value="shipped">
                                            <button type="submit" class="btn btn-success btn-sm btn-rounded">
                                                <i class="fas fa-shipping-fast me-1"></i> Start Delivery
                                            </button>
                                        @elseif($order->status === 'shipped')
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="btn btn-success btn-sm btn-rounded">
                                                <i class="fas fa-check me-1"></i> Complete
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
                        <i class="fas fa-truck fa-3x text-muted mb-3 opacity-25"></i>
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
        <div class="card content-card mb-4">
            <div class="card-header">
                <i class="fas fa-satellite-dish me-2 text-primary"></i>Status
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold">Availability</span>
                    <span class="badge bg-{{ Auth::user()->is_available ? 'success' : 'danger' }} rounded-pill px-3 py-2">
                        {{ Auth::user()->is_available ? 'Online' : 'Offline' }}
                    </span>
                </div>
                <form action="{{ route('driver.toggle-availability') }}" method="POST" class="d-grid mb-3">
                    @csrf
                    <button type="submit" class="btn btn-{{ Auth::user()->is_available ? 'outline-danger' : 'outline-success' }} btn-rounded">
                        {{ Auth::user()->is_available ? 'Go Offline' : 'Go Online' }}
                    </button>
                </form>
                
                @if($driverLocation)
                <div class="p-3 bg-light rounded-3 small">
                    <div class="d-flex justify-content-between text-muted mb-2">
                        <span>Last Update:</span>
                        <span>{{ $driverLocation->location_updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-truncate text-dark fw-bold">
                        <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                        {{ number_format($driverLocation->latitude, 4) }}, {{ number_format($driverLocation->longitude, 4) }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent History -->
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2 text-primary"></i>History</span>
                <a href="{{ route('driver.deliveries') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentDeliveries->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentDeliveries->take(5) as $order)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold">#{{ $order->id }}</h6>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, H:i') }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill mb-1">Delivered</span>
                                    <small class="d-block text-success fw-bold">+ Ksh {{ number_format($order->delivery_cost, 0) }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
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
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Updating...';
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