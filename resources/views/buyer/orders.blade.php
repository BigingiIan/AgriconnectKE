<!-- resources/views/buyer/orders.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Orders</h1>
</div>

<div class="row">
    <div class="col-12">
        @if($orders->count() > 0)
            <div class="card content-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start ps-4">Order ID</th>
                                    <th class="border-0">Product</th>
                                    <th class="border-0">Farmer</th>
                                    <th class="border-0">Quantity</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0 rounded-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($order->product->image)
                                                <img src="{{ asset('storage/' . $order->product->image) }}" 
                                                     alt="{{ $order->product->name }}" 
                                                     class="img-thumbnail me-2 rounded shadow-sm" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong class="text-dark">{{ $order->product->name }}</strong><br>
                                                <small class="text-muted">by {{ $order->farmer->name }}</small>
                                                @if($order->bid)
                                                    <br><small class="text-warning fw-bold"><i class="fas fa-gavel me-1"></i>Bid: Ksh {{ number_format($order->bid->amount, 2) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $order->farmer->name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td class="fw-bold text-success">Ksh {{ number_format($order->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->status == 'pending' ? 'warning' : 
                                            ($order->status == 'paid' ? 'info' : 
                                            ($order->status == 'shipped' ? 'primary' : 
                                            ($order->status == 'delivered' ? 'success' : 'secondary'))) 
                                        }} rounded-pill">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @if($order->bid_id)
                                            <span class="badge bg-warning text-dark rounded-pill ms-1">Bid Order</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td class="pe-4">
                                        @if($order->status == 'pending' && $order->bid_id)
                                            {{-- Show checkout button for pending bid orders --}}
                                            <a href="{{ route('buyer.checkout.bid', $order) }}" class="btn btn-success btn-sm btn-rounded shadow-sm">
                                                <i class="fas fa-credit-card me-1"></i> Purchase
                                            </a>
                                        @elseif($order->status == 'paid' || $order->status == 'shipped')
                                            <a href="{{ route('buyer.track-order', $order) }}" class="btn btn-info btn-sm btn-rounded text-white shadow-sm">
                                                <i class="fas fa-truck me-1"></i> Track
                                            </a>
                                        @elseif($order->status == 'pending' && !$order->bid_id)
                                            <a href="{{ route('buyer.checkout', $order) }}" class="btn btn-success btn-sm btn-rounded shadow-sm">
                                                <i class="fas fa-credit-card me-1"></i> Checkout
                                            </a>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge bg-success rounded-pill"><i class="fas fa-check me-1"></i>Delivered</span>
                                            @if($order->mpesa_receipt)
                                                <a href="{{ route('buyer.receipt.download', $order) }}" class="btn btn-outline-success btn-sm btn-rounded mt-1 d-block">
                                                    <i class="fas fa-receipt me-1"></i> Receipt
                                                </a>
                                            @endif
                                        @endif
                                    </td>
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
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">No Orders Yet</h4>
                    <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('buyer.market') }}" class="btn btn-success btn-rounded shadow-sm">
                        <i class="fas fa-shopping-cart me-1"></i> Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection