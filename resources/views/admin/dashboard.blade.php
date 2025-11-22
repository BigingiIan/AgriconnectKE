<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            .card { border: 1px solid #ddd !important; }
            .col-md-6 { width: 50% !important; float: left !important; }
            .col-md-3 { width: 25% !important; float: left !important; }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Admin Dashboard</h1>
            <button onclick="window.print()" class="btn btn-secondary no-print">
                <i class="fas fa-print"></i> Print Report
            </button>
        </div>

        <!-- Date Filter -->
        <div class="card mb-4 no-print">
            <div class="card-body">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter Data</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="card-text">{{ $stats['total_users'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Farmers</h5>
                        <h2 class="card-text">{{ $stats['total_farmers'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Buyers</h5>
                        <h2 class="card-text">{{ $stats['total_buyers'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <h2 class="card-text">{{ $stats['total_orders'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">Sales Overview</div>
                    <div class="card-body">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">User Growth</div>
                    <div class="card-body">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Top Products -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">Top Selling Products</div>
                    <div class="card-body">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Delivery Central -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Delivery Central</span>
                        <span class="badge bg-success">{{ $deliveryStats['active_drivers'] }} Active Drivers</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h5>Ongoing Deliveries: {{ $deliveryStats['ongoing_deliveries'] }}</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Driver</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deliveryStats['active_deliveries_list'] as $delivery)
                                        <tr>
                                            <td>#{{ $delivery->order_number }}</td>
                                            <td>{{ $delivery->driver->name }}</td>
                                            <td><span class="badge bg-info">{{ $delivery->status }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No active deliveries</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-2">
                            <a href="{{ route('admin.track-drivers') }}" class="btn btn-sm btn-outline-primary">Track Drivers Map</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: { responsive: true }
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
                    backgroundColor: 'rgb(54, 162, 235)'
                }]
            },
            options: { responsive: true }
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
                    ]
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>
</html>