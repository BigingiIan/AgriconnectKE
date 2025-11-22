@extends('layouts.app')

@section('title', 'Farmer Dashboard - AgriconnectKE')

@section('styles')
<style>
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
    .dashboard-stat-card:hover {
        transform: translateY(-5px);
    }
    .dashboard-stat-card .icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.2;
        transform: rotate(-15deg);
    }
    .bg-gradient-success { background: linear-gradient(135deg, #198754 0%, #20c997 100%); }
    .bg-gradient-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #0dcaf0 0%, #3dd5f3 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%); color: #000; }
    
    .content-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
        transition: all 0.3s ease;
    }
    .content-card:hover {
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .content-card .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 1rem 1.5rem;
        transition: background-color 0.2s;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .btn-rounded {
        border-radius: 50px;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-4 mb-3 border-bottom">
    <div>
        <h1 class="h2 fw-bold">Dashboard</h1>
        <p class="text-muted">Overview of your farm's performance</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('farmer.products.create') }}" class="btn btn-success btn-rounded shadow-sm">
            <i class="fas fa-plus me-2"></i> Add Product
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4 g-4">
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-primary">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Total Products</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $stats['total_products'] }}</h2>
                </div>
                <i class="fas fa-box-open fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-box-open icon-bg"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-warning text-dark">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Active Bids</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $stats['active_bids'] }}</h2>
                </div>
                <i class="fas fa-gavel fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-gavel icon-bg"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-success">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Total Sales</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $stats['total_sales'] }}</h2>
                </div>
                <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-shopping-cart icon-bg"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-stat-card bg-gradient-info text-white">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Revenue</h5>
                    <h2 class="display-6 fw-bold mb-0">Ksh {{ number_format($stats['revenue'], 0) }}</h2>
                </div>
                <i class="fas fa-wallet fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-wallet icon-bg"></i>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4 g-4">
    <div class="col-md-8">
        <div class="card content-card">
            <div class="card-header">
                <i class="fas fa-chart-line me-2 text-primary"></i>Sales Overview (Last 30 Days)
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card content-card">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2 text-primary"></i>Top Products
            </div>
            <div class="card-body">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Products -->
    <div class="col-md-6">
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-primary"><i class="fas fa-leaf me-2"></i>Recent Products</span>
                <a href="{{ route('farmer.products') }}" class="btn btn-sm btn-outline-primary btn-rounded">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentProducts as $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $product->name }}</h6>
                                    <small class="text-muted">Stock: {{ $product->quantity }} units</small>
                                </div>
                                <span class="badge bg-light text-dark border">Ksh {{ number_format($product->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-25"></i>
                        <p class="text-muted mb-3">No products added yet.</p>
                        <a href="{{ route('farmer.products.create') }}" class="btn btn-sm btn-success btn-rounded">Add First Product</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bids -->
    <div class="col-md-6">
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-primary"><i class="fas fa-gavel me-2"></i>Recent Bids</span>
                <a href="{{ route('farmer.bids') }}" class="btn btn-sm btn-outline-primary btn-rounded">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentBids->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentBids as $bid)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold">{{ $bid->product->name }}</h6>
                                    <small class="text-muted">{{ $bid->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">By {{ $bid->buyer->name }}</small>
                                    <span class="fw-bold text-primary">Bid: Ksh {{ number_format($bid->amount, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-gavel fa-3x text-muted mb-3 opacity-25"></i>
                        <p class="text-muted mb-0">No active bids.</p>
                    </div>
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
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [2, 4]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
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
                    '#198754',
                    '#20c997',
                    '#ffc107',
                    '#0dcaf0',
                    '#0d6efd'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection