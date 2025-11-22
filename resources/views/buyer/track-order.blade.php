<!-- resources/views/buyer/track-order.blade.php -->
@extends('layouts.app')

@section('title', 'Track Order #' . $order->id)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Track Order #{{ $order->id }}</h1>
    <a href="{{ route('buyer.orders') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <!-- Order Status Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Status</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="steps">
                            <div class="step {{ $order->status == 'pending' || $order->status == 'paid' || $order->status == 'shipped' || $order->status == 'delivered' ? 'active' : '' }}">
                                <div class="step-icon">1</div>
                                <div class="step-label">Order Placed</div>
                            </div>
                            <div class="step {{ $order->status == 'paid' || $order->status == 'shipped' || $order->status == 'delivered' ? 'active' : '' }}">
                                <div class="step-icon">2</div>
                                <div class="step-label">Payment Confirmed</div>
                            </div>
                            <div class="step {{ $order->status == 'shipped' || $order->status == 'delivered' ? 'active' : '' }}">
                                <div class="step-icon">3</div>
                                <div class="step-label">Shipped</div>
                            </div>
                            <div class="step {{ $order->status == 'delivered' ? 'active' : '' }}">
                                <div class="step-icon">4</div>
                                <div class="step-label">Delivered</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Order Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Product Information</h6>
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
                        <p><strong>Product:</strong> {{ $order->product->name }}</p>
                        <p><strong>Farmer:</strong> {{ $order->farmer->name }}</p>
                        <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                        <p><strong>Unit Price:</strong> Ksh {{ number_format($order->product->price, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Delivery Information</h6>
                        <p><strong>Total Amount:</strong> Ksh {{ number_format($order->amount, 2) }}</p>
                        <p><strong>Delivery Cost:</strong> Ksh {{ number_format($order->delivery_cost, 2) }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ 
                                $order->status == 'pending' ? 'warning' : 
                                ($order->status == 'paid' ? 'info' : 
                                ($order->status == 'shipped' ? 'primary' : 
                                ($order->status == 'delivered' ? 'success' : 'secondary'))) 
                            }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                        @if($order->mpesa_receipt)
                            <p><strong>MPesa Receipt:</strong> {{ $order->mpesa_receipt }}</p>
                        @endif
                        @if($order->paid_at)
                            <p><strong>Paid On:</strong> {{ $order->paid_at->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Tracking -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-truck"></i> Delivery Tracking
                </h5>
            </div>
            <div class="card-body">
                @if($order->driver)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-user"></i> Driver Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $order->driver->name }}</p>
                                <p class="mb-1"><strong>Phone:</strong> 
                                    <a href="tel:{{ $order->driver->phone }}">{{ $order->driver->phone }}</a>
                                </p>
                                @if($driverLocation)
                                    <p class="mb-0"><strong>Last Update:</strong> {{ $driverLocation->location_updated_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($order->status == 'shipped')
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-shipping-fast"></i> Delivery In Progress</h6>
                                    <p class="mb-0">Your order is on the way! Track the driver's location below.</p>
                                </div>
                            @elseif($order->status == 'delivered')
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-check-circle"></i> Delivery Completed</h6>
                                    <p class="mb-0">Your order has been successfully delivered.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Map Container -->
                    <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                    
                    <!-- Distance and ETA Information -->
                    @if($order->status == 'shipped')
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="alert alert-light border">
                                <h6><i class="fas fa-road"></i> Distance</h6>
                                <p class="mb-0" id="distance">Calculating...</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-light border">
                                <h6><i class="fas fa-clock"></i> Estimated Arrival</h6>
                                <p class="mb-0" id="eta">Calculating...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Refresh Button -->
                    <div class="mt-3 text-center">
                        <button id="refreshLocation" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sync-alt"></i> Refresh Location
                        </button>
                    </div>
                    @endif
                @else
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-truck-loading fa-2x mb-3"></i>
                        <h5>Awaiting Driver Assignment</h5>
                        <p class="mb-0">No driver has been assigned to your order yet.</p>
                        <p>We're working to find the nearest available driver for your delivery.</p>
                        <p class="small text-muted">Please check back in a few minutes.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar with Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('buyer.orders') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Orders
                    </a>
                    @if($order->driver)
                        <a href="tel:{{ $order->driver->phone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone"></i> Call Driver
                        </a>
                    @endif
                    @if($order->mpesa_receipt && ($order->status == 'paid' || $order->status == 'shipped' || $order->status == 'delivered'))
                        <a href="{{ route('buyer.receipt.download', $order) }}" class="btn btn-outline-info">
                            <i class="fas fa-receipt"></i> Download Receipt
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Product Total:</span>
                    <span>Ksh {{ number_format($order->product->price * $order->quantity, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery Cost:</span>
                    <span>Ksh {{ number_format($order->delivery_cost, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold text-success">
                    <span>Total Paid:</span>
                    <span>Ksh {{ number_format($order->amount, 2) }}</span>
                </div>
                @if($order->mpesa_receipt)
                <div class="mt-2">
                    <small class="text-muted">Receipt: {{ $order->mpesa_receipt }}</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <p><strong>Farmer:</strong> {{ $order->farmer->name }}</p>
                <p><strong>Phone:</strong> 
                    <a href="tel:{{ $order->farmer->phone }}">{{ $order->farmer->phone }}</a>
                </p>
                <p><strong>Email:</strong> 
                    <a href="mailto:{{ $order->farmer->email }}">{{ $order->farmer->email }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
    }
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 60%;
        width: 80%;
        height: 2px;
        background-color: #dee2e6;
        z-index: 1;
    }
    .step.active:not(:last-child)::after {
        background-color: #198754;
    }
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }
    .step.active .step-icon {
        background-color: #198754;
        color: white;
    }
    .step-label {
        font-size: 0.875rem;
        text-align: center;
    }
</style>
@endpush

@push('scripts')
@if($order->driver && $driverLocation)
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
    let map;
    let driverMarker;
    let deliveryMarker;
    let routeLine;

    function initMap() {
        // Initialize map centered between driver and delivery location
        const driverLat = {{ $driverLocation->latitude }};
        const driverLng = {{ $driverLocation->longitude }};
        const deliveryLat = {{ $order->delivery_lat }};
        const deliveryLng = {{ $order->delivery_lng }};
        
        const centerLat = (driverLat + deliveryLat) / 2;
        const centerLng = (driverLng + deliveryLng) / 2;
        
        map = L.map('map').setView([centerLat, centerLng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);
        
        // Add driver location marker
        const driverIcon = L.divIcon({
            html: '<div style="background-color: #dc3545; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
            className: 'driver-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        
        driverMarker = L.marker([driverLat, driverLng], { icon: driverIcon })
            .addTo(map)
            .bindPopup(`
                <div>
                    <strong>Driver Location</strong><br>
                    {{ $order->driver->name }}<br>
                    <small>Last updated: {{ $driverLocation->location_updated_at->diffForHumans() }}</small>
                </div>
            `);
        
        // Add delivery location marker
        const deliveryIcon = L.divIcon({
            html: '<div style="background-color: #198754; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
            className: 'delivery-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        
        deliveryMarker = L.marker([deliveryLat, deliveryLng], { icon: deliveryIcon })
            .addTo(map)
            .bindPopup(`
                <div>
                    <strong>Delivery Location</strong><br>
                    {{ $order->delivery_address }}<br>
                    <small>Your address</small>
                </div>
            `)
            .openPopup();
        
        // Add route line
        routeLine = L.polyline([
            [driverLat, driverLng],
            [deliveryLat, deliveryLng]
        ], {
            color: '#0d6efd',
            weight: 4,
            opacity: 0.7,
            dashArray: '10, 10'
        }).addTo(map);
        
        // Calculate initial distance and ETA
        updateDistanceAndETA();
        
        // Fit map to show both markers
        const group = new L.featureGroup([driverMarker, deliveryMarker]);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    function updateDistanceAndETA() {
        const distance = driverMarker.getLatLng().distanceTo(deliveryMarker.getLatLng()) / 1000; // Convert to km
        document.getElementById('distance').textContent = `${distance.toFixed(1)} km remaining`;

        // Estimate ETA (assuming average speed of 40 km/h)
        const etaMinutes = (distance / 40) * 60;
        document.getElementById('eta').textContent = `${Math.round(etaMinutes)} minutes`;
    }

    function refreshDriverLocation() {
        fetch('/driver/location/current?driver_id={{ $order->driver_id }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.latitude && data.longitude) {
                    const newLatLng = [data.latitude, data.longitude];
                    driverMarker.setLatLng(newLatLng);
                    
                    // Update route line
                    routeLine.setLatLngs([
                        newLatLng,
                        [{{ $order->delivery_lat }}, {{ $order->delivery_lng }}]
                    ]);
                    
                    // Update distance and ETA
                    updateDistanceAndETA();

                    // Show notification
                    showNotification('Location updated successfully!', 'success');
                } else {
                    console.warn('Driver location not available:', data.error);
                    showNotification('Driver location not available', 'warning');
                }
            })
            .catch(error => {
                console.error('Error fetching driver location:', error);
                showNotification('Failed to update driver location', 'error');
            });
    }

    function showNotification(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        
        // Refresh location button
        const refreshBtn = document.getElementById('refreshLocation');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', refreshDriverLocation);
        }
        
        // Auto-refresh every 30 seconds if order is shipped
        @if($order->status == 'shipped')
        setInterval(refreshDriverLocation, 30000);
        @endif
    });
</script>
@endif
@endpush