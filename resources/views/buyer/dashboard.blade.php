@extends('layouts.app')

@section('title', 'Buyer Dashboard - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('buyer.market') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-shopping-basket"></i> Browse Market
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Orders</h5>
                        <h2 class="card-text">{{ $orders->count() }}</h2>
                    </div>
                    <i class="fas fa-shopping-bag fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Active Bids</h5>
                        <h2 class="card-text">{{ $bids->where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="fas fa-gavel fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Market Products</h5>
                        <h2 class="card-text">{{ $products->count() }}</h2>
                    </div>
                    <i class="fas fa-carrot fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="{{ route('buyer.orders') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($orders->take(5) as $order)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $order->product->name }}</h6>
                                    <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Amount: Ksh {{ number_format($order->total_amount, 2) }}</p>
                                <span class="badge bg-{{ $order->status == 'paid' ? 'success' : ($order->status == 'delivered' ? 'info' : 'warning') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No orders yet.</p>
                    <a href="{{ route('buyer.market') }}" class="btn btn-sm btn-outline-success mt-2">Start Shopping</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bids -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Bids</h5>
                <a href="{{ route('buyer.bids') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($bids->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($bids->take(5) as $bid)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $bid->product->name }}</h6>
                                    <small class="text-muted">{{ $bid->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Bid: Ksh {{ number_format($bid->amount, 2) }}</p>
                                <span class="badge bg-{{ $bid->status == 'pending' ? 'warning' : ($bid->status == 'accepted' ? 'success' : 'danger') }}">
                                    {{ ucfirst($bid->status) }}
                                </span>
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
@endsection