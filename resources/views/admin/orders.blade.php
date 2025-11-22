@extends('layouts.app')

@section('title', 'Orders Management - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Orders Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary btn-rounded">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card content-card">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 fw-bold">All Orders</h5>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.orders') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2 rounded-pill" placeholder="Search orders..." value="{{ request('search') }}">
                            <select name="status" class="form-select form-select-sm me-2 rounded-pill" style="width: 150px;">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary btn-rounded">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">Order ID</th>
                                <th class="border-0">Buyer</th>
                                <th class="border-0">Farmer</th>
                                <th class="border-0">Driver</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Date</th>
                                <th class="border-0 rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                <td>{{ $order->buyer->name }}</td>
                                <td>{{ $order->farmer->name }}</td>
                                <td>
                                    @if($order->driver)
                                        <span class="text-dark"><i class="fas fa-truck-moving me-1 text-info"></i>{{ $order->driver->name }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ 
                                        $order->status == 'pending' ? 'warning' : 
                                        ($order->status == 'paid' ? 'info' : 
                                        ($order->status == 'shipped' ? 'primary' : 
                                        ($order->status == 'delivered' ? 'success' : 'secondary'))) 
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-success">Ksh {{ number_format($order->amount, 2) }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info btn-rounded text-white shadow-sm me-1">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($order->status == 'paid' || $order->status == 'shipped')
                                            <a href="{{ route('admin.track-order', $order) }}" class="btn btn-primary btn-rounded shadow-sm">
                                                <i class="fas fa-map-marker-alt"></i> Track
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3 opacity-50"></i>
                                    <p class="text-muted">No orders found matching your criteria.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-3">
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection