@extends('layouts.app')

@section('title', 'Admin Dashboard - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Admin Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary btn-rounded d-print-none">
                <i class="fas fa-print me-1"></i> Print Report
            </button>
        </div>
    </div>
</div>

<!-- Date Filter -->
<div class="card content-card mb-4 d-print-none">
    <div class="card-body">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-rounded shadow-sm">
                    <i class="fas fa-filter me-1"></i> Filter Data
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-rounded ms-2">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card dashboard-stat-card bg-gradient-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Total Users</h6>
                        <h2 class="card-title fw-bold mb-0">{{ $stats['total_users'] ?? 0 }}</h2>
                    </div>
                    <div class="icon-bg">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card dashboard-stat-card bg-gradient-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Farmers</h6>
                        <h2 class="card-title fw-bold mb-0">{{ $stats['total_farmers'] ?? 0 }}</h2>
                    </div>
                    <div class="icon-bg">
                        <i class="fas fa-tractor"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card dashboard-stat-card bg-gradient-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Buyers</h6>
                        <h2 class="card-title fw-bold mb-0">{{ $stats['total_buyers'] ?? 0 }}</h2>
                    </div>
                    <div class="icon-bg">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stat-card bg-gradient-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Total Orders</h6>
                        <h2 class="card-title fw-bold mb-0">{{ $stats['total_orders'] ?? 0 }}</h2>
                    </div>
                    <div class="icon-bg">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card content-card h-100">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <h5 class="mb-0 fw-bold">Sales Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card content-card h-100">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <h5 class="mb-0 fw-bold">User Growth</h5>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Products -->
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card content-card h-100">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <h5 class="mb-0 fw-bold">Top Selling Products</h5>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Delivery Central -->
    <div class="col-md-6">
        <div class="card content-card h-100">
            <div class="card-header bg-white border-0 pt-4 ps-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Delivery Central</h5>
                <span class="badge bg-success rounded-pill">{{ $deliveryStats['active_drivers'] }} Active Drivers</span>
            </div>
            <div class="card-body">
                <div class="mb-3 p-3 bg-light rounded-3">
                    <h6 class="mb-0 fw-bold text-primary">Ongoing Deliveries: {{ $deliveryStats['ongoing_deliveries'] }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-3">Order</th>
                                <th class="border-0">Driver</th>
                                <th class="border-0 rounded-end pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deliveryStats['active_deliveries_list'] as $delivery)
                                <tr>
                                    <td class="ps-3 fw-bold">#{{ $delivery->order_number }}</td>
                                    <td>{{ $delivery->driver->name }}</td>
                                    <td class="pe-3"><span class="badge bg-info rounded-pill">{{ $delivery->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No active deliveries</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('admin.track-drivers') }}" class="btn btn-sm btn-outline-primary btn-rounded">
                        <i class="fas fa-map-marked-alt me-1"></i> Track Drivers Map
                    </a>
                </div>
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
                label: 'Total Sales (Ksh)',
                data: {!! json_encode($salesChart['data']) !!},
                borderColor: 'rgb(40, 167, 69)', // Success green
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.3,
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

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($userGrowthChart['labels']) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($userGrowthChart['data']) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderRadius: 5
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
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 0
            }]
        },
        options: { 
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection