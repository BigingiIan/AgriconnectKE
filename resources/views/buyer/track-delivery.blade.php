@extends('layouts.app')

@section('title', 'Track Delivery')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    #deliveryMap { height: 500px; }
    .delivery-info {
        max-height: 400px;
        overflow-y: auto;
    }
    .status-card {
        transition: all 0.3s;
        cursor: pointer;
    }
    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .status-card.active {
        border: 2px solid #198754;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Map View -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div id="deliveryMap"></div>
                </div>
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Delivery Status</h5>
                </div>
                <div class="card-body">
                    <div class="delivery-info">
                        <div class="text-center mb-4">
                            <h4>Order #{{ $order->id }}</h4>
                            <p class="mb-1">Product: {{ $order->product->name }}</p>
                            <p class="mb-1">Quantity: {{ $order->quantity }}</p>
                            <p class="mb-3">Total Amount: KES {{ number_format($order->amount, 2) }}</p>
                        </div>

                        <!-- Delivery Progress -->
                        <div class="progress mb-4" style="height: 3px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>

                        <!-- Status Cards -->
                        <div class="status-card card mb-3 {{ $order->status === 'pending' ? 'active' : '' }}">
                            <div class="card-body">
                                <h6 class="d-flex align-items-center">
                                    <span class="badge bg-secondary rounded-circle me-2">1</span>
                                    Order Placed
                                </h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>

                        <div class="status-card card mb-3 {{ $order->status === 'paid' ? 'active' : '' }}">
                            <div class="card-body">
                                <h6 class="d-flex align-items-center">
                                    <span class="badge bg-secondary rounded-circle me-2">2</span>
                                    Payment Confirmed
                                </h6>
                                @if($order->status === 'paid')
                                <small class="text-muted">{{ $order->paid_at->format('M d, Y H:i') }}</small>
                                <p class="mb-0"><small>Receipt: {{ $order->mpesa_receipt }}</small></p>
                                @endif
                            </div>
                        </div>

                        <div class="status-card card mb-3 {{ $order->status === 'shipped' ? 'active' : '' }}">
                            <div class="card-body">
                                <h6 class="d-flex align-items-center">
                                    <span class="badge bg-secondary rounded-circle me-2">3</span>
                                    Out for Delivery
                                </h6>
                                @if($order->status === 'shipped')
                                <small class="text-muted">{{ $order->shipped_at->format('M d, Y H:i') }}</small>
                                @if($order->driver)
                                <p class="mb-0">
                                    <small>Driver: {{ $order->driver->name }}</small><br>
                                    <small>Phone: {{ $order->driver->phone }}</small>
                                </p>
                                @endif
                                @endif
                            </div>
                        </div>

                        <div class="status-card card {{ $order->status === 'delivered' ? 'active' : '' }}">
                            <div class="card-body">
                                <h6 class="d-flex align-items-center">
                                    <span class="badge bg-secondary rounded-circle me-2">4</span>
                                    Delivered
                                </h6>
                                @if($order->status === 'delivered')
                                <small class="text-muted">{{ $order->delivered_at->format('M d, Y H:i') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Details -->
                    @if($order->driver)
                    <div class="mt-4">
                        <h6>Delivery Details:</h6>
                        <p class="mb-1"><strong>Distance:</strong> <span id="distance">Calculating...</span></p>
                        <p class="mb-1"><strong>ETA:</strong> <span id="eta">Calculating...</span></p>
                        <p class="mb-0"><strong>Cost:</strong> KES {{ number_format($order->delivery_cost, 2) }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Initialize map
    const map = L.map('deliveryMap').setView([-1.2921, 36.8219], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Add markers for pickup and delivery locations
    const pickupPoint = L.latLng([{{ $order->farmer->latitude }}, {{ $order->farmer->longitude }}]);
    const deliveryPoint = L.latLng([{{ $order->delivery_lat }}, {{ $order->delivery_lng }}]);

    L.marker(pickupPoint).addTo(map)
        .bindPopup('Pickup: {{ $order->farmer->address }}');
    L.marker(deliveryPoint).addTo(map)
        .bindPopup('Delivery: {{ $order->delivery_address }}');

    @if($order->driver && $order->status === 'shipped')
    // Driver marker and real-time updates
    let driverMarker;
    let deliveryRoute;

    function updateDriverLocation() {
        fetch(`/api/driver-location/{{ $order->driver_id }}`)
            .then(response => response.json())
            .then(data => {
                const driverLocation = L.latLng([data.latitude, data.longitude]);

                if (driverMarker) {
                    driverMarker.setLatLng(driverLocation);
                } else {
                    driverMarker = L.marker(driverLocation, {
                        icon: L.divIcon({
                            className: 'driver-marker',
                            html: '🚚'
                        })
                    }).addTo(map);
                }

                // Update route
                if (!deliveryRoute) {
                    deliveryRoute = L.polyline([driverLocation, deliveryPoint], {
                        color: 'blue',
                        weight: 3
                    }).addTo(map);
                } else {
                    deliveryRoute.setLatLngs([driverLocation, deliveryPoint]);
                }

                // Update distance and ETA
                const distance = driverLocation.distanceTo(deliveryPoint) / 1000; // Convert to km
                document.getElementById('distance').textContent = 
                    `${distance.toFixed(1)} km remaining`;

                // Estimate ETA (assuming average speed of 40 km/h)
                const etaMinutes = (distance / 40) * 60;
                document.getElementById('eta').textContent = 
                    `${Math.round(etaMinutes)} minutes`;

                // Fit bounds
                const bounds = L.latLngBounds([
                    driverLocation,
                    pickupPoint,
                    deliveryPoint
                ]);
                map.fitBounds(bounds);
            });
    }

    // Update driver location every 30 seconds
    updateDriverLocation();
    setInterval(updateDriverLocation, 30000);
    @else
    // If no driver assigned yet, just show the route between pickup and delivery
    L.polyline([pickupPoint, deliveryPoint], {
        color: 'gray',
        weight: 3,
        dashArray: '5, 10'
    }).addTo(map);

    // Fit bounds to show both points
    const bounds = L.latLngBounds([pickupPoint, deliveryPoint]);
    map.fitBounds(bounds);
    @endif
</script>
@endsection