@extends('layouts.app')

@section('title', 'Buyer Dashboard - AgriconnectKE')

@section('styles')
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-4 mb-3 border-bottom">
    <div>
        <h1 class="h2 fw-bold">Dashboard</h1>
        <p class="text-muted">Welcome back, {{ Auth::user()->name }}</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('buyer.market') }}" class="btn btn-success btn-rounded shadow-sm">
            <i class="fas fa-shopping-basket me-2"></i> Browse Market
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4 g-4">
    <div class="col-md-4">
        <div class="dashboard-stat-card bg-gradient-success">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Total Orders</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $orders->count() }}</h2>
                </div>
                <i class="fas fa-shopping-bag fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-shopping-bag icon-bg"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-stat-card bg-gradient-primary">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Active Bids</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $bids->where('status', 'pending')->count() }}</h2>
                </div>
                <i class="fas fa-gavel fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-gavel icon-bg"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-stat-card bg-gradient-info text-white">
            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                <div>
                    <h5 class="card-title mb-1 opacity-75">Market Products</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $products->count() }}</h2>
                </div>
                <i class="fas fa-carrot fa-3x opacity-50"></i>
            </div>
            <i class="fas fa-carrot icon-bg"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-md-6">
        <div class="card content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-primary"><i class="fas fa-receipt me-2"></i>Recent Orders</span>
                <a href="{{ route('buyer.orders') }}" class="btn btn-sm btn-outline-primary btn-rounded">View All</a>
            </div>
            <div class="card-body p-0">
                @if($orders->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($orders->take(5) as $order)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold">{{ $order->product->name }}</h6>
                                    <span class="badge rounded-pill bg-{{ $order->status == 'paid' ? 'success' : ($order->status == 'delivered' ? 'info' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $order->created_at->diffForHumans() }}</small>
                                    <span class="fw-bold text-success">Ksh {{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3 opacity-25"></i>
                        <p class="text-muted mb-3">No orders placed yet.</p>
                        <a href="{{ route('buyer.market') }}" class="btn btn-sm btn-success btn-rounded">Start Shopping</a>
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
                <a href="{{ route('buyer.bids') }}" class="btn btn-sm btn-outline-primary btn-rounded">View All</a>
            </div>
            <div class="card-body p-0">
                @if($bids->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($bids->take(5) as $bid)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold">{{ $bid->product->name }}</h6>
                                    <span class="badge rounded-pill bg-{{ $bid->status == 'pending' ? 'warning' : ($bid->status == 'accepted' ? 'success' : 'danger') }}">
                                        {{ ucfirst($bid->status) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $bid->created_at->diffForHumans() }}</small>
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
@endsection