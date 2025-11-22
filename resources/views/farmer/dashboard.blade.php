@extends('layouts.app')

@section('title', 'Farmer Dashboard - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('farmer.products.create') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2 class="card-text">{{ $stats['total_products'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Active Bids</h5>
                <h2 class="card-text">{{ $stats['active_bids'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Sales</h5>
                <h2 class="card-text">{{ $stats['total_sales'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Revenue</h5>
                <h2 class="card-text">Ksh {{ number_format($stats['revenue'], 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Sales Overview (Last 30 Days)</div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Top Products</div>
            <div class="card-body">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Products -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Products</h5>
                <a href="{{ route('farmer.products') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentProducts as $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                    <small class="text-muted">Stock: {{ $product->quantity }}</small>
                                </div>
                                <span class="badge bg-secondary">Ksh {{ number_format($product->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No products added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bids -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Bids</h5>
                <a href="{{ route('farmer.bids') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentBids->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentBids as $bid)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $bid->product->name }}</h6>
                                    <small>{{ $bid->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Bid Amount: Ksh {{ number_format($bid->amount, 2) }}</p>
                                <small class="text-muted">By {{ $bid->buyer->name }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No active bids.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesChart['labels']) !!},
            datasets: [{
                label: 'Sales (Ksh)',
                data: {!! json_encode($salesChart['data']) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(topProductsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topProductsChart['labels']) !!},
            datasets: [{
                data: {!! json_encode($topProductsChart['data']) !!},
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endpush
@endsection