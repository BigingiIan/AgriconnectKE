<!-- resources/views/farmer/sales.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales History</h1>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card dashboard-stat-card bg-gradient-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Total Sales</h5>
                    <div class="icon-bg">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <h2 class="card-text fw-bold mb-0">Ksh {{ number_format($totalSales, 2) }}</h2>
                <small class="text-white-50">Lifetime earnings</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($sales->count() > 0)
            <div class="card content-card">
                <div class="card-header bg-white border-0 pt-4 ps-4">
                    <h5 class="mb-0 fw-bold">Transaction History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start ps-4">Order ID</th>
                                    <th class="border-0">Product</th>
                                    <th class="border-0">Buyer</th>
                                    <th class="border-0">Quantity</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 rounded-end pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                    <td>{{ $order->product->name }}</td>
                                    <td>{{ $order->buyer->name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td class="fw-bold text-success">Ksh {{ number_format($order->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'paid' ? 'success' : 'warning' }} rounded-pill">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card content-card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">No Sales Yet</h4>
                    <p class="text-muted">You haven't made any sales yet. Keep your products updated!</p>
                    <a href="{{ route('farmer.products') }}" class="btn btn-success btn-rounded shadow-sm">Manage Products</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection