<!-- resources/views/buyer/orders.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Orders</h1>
</div>

<div class="row">
    <div class="col-12">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Farmer</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                       <!-- resources/views/buyer/orders.blade.php -->
@foreach($orders as $order)
<tr>
    <td>#{{ $order->id }}</td>
    <td>
        <div class="d-flex align-items-center">
            @if($order->product->image)
                <img src="{{ asset('storage/' . $order->product->image) }}" 
                     alt="{{ $order->product->name }}" 
                     class="img-thumbnail me-2" 
                     style="width: 50px; height: 50px; object-fit: cover;">
            @endif
            <div>
                <strong>{{ $order->product->name }}</strong><br>
                <small class="text-muted">by {{ $order->farmer->name }}</small>
                @if($order->bid)
                    <br><small class="text-warning">Bid: Ksh {{ number_format($order->bid->amount, 2) }}</small>
                @endif
            </div>
        </div>
    </td>
    <td>{{ $order->farmer->name }}</td>
    <td>{{ $order->quantity }}</td>
    <td>Ksh {{ number_format($order->amount, 2) }}</td>
    <td>
        <span class="badge bg-{{ 
            $order->status == 'pending' ? 'warning' : 
            ($order->status == 'paid' ? 'info' : 
            ($order->status == 'shipped' ? 'primary' : 
            ($order->status == 'delivered' ? 'success' : 'secondary'))) 
        }}">
            {{ ucfirst($order->status) }}
        </span>
        @if($order->bid_id)
            <span class="badge bg-warning text-dark">Bid Order</span>
        @endif
    </td>
    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
    <td>
        @if($order->status == 'pending' && $order->bid_id)
            {{-- Show checkout button for pending bid orders --}}
            <a href="{{ route('buyer.checkout.bid', $order) }}" class="btn btn-success btn-sm">
                <i class="fas fa-credit-card"></i> Complete Purchase
            </a>
        @elseif($order->status == 'paid' || $order->status == 'shipped')
            <a href="{{ route('buyer.track-order', $order) }}" class="btn btn-info btn-sm">
                <i class="fas fa-truck"></i> Track
            </a>
        @elseif($order->status == 'pending' && !$order->bid_id)
            <a href="{{ route('buyer.checkout', $order) }}" class="btn btn-success btn-sm">
                <i class="fas fa-credit-card"></i> Checkout
            </a>
        @elseif($order->status == 'delivered')
            <span class="badge bg-success">Delivered</span>
            @if($order->mpesa_receipt)
                <a href="{{ route('buyer.receipt.download', $order) }}" class="btn btn-outline-success btn-sm mt-1">
                    <i class="fas fa-receipt"></i> Receipt
                </a>
            @endif
        @endif
    </td>
</tr>
@endforeach</tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <p>You haven't placed any orders yet. <a href="{{ route('buyer.market') }}">Browse the marketplace</a> to make your first purchase.</p>
            </div>
        @endif
    </div>
</div>
@endsection