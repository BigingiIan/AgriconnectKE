@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Deliveries</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        @if($deliveries->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Buyer</th>
                            <th>Delivery Address</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Assigned Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->buyer->name }}</td>
                            <td>{{ Str::limit($order->delivery_address, 30) }}</td>
                            <td>Ksh {{ number_format($order->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status == 'paid' ? 'warning' : 
                                    ($order->status == 'shipped' ? 'primary' : 
                                    ($order->status == 'delivered' ? 'success' : 'secondary')) 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->updated_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('driver.deliveries.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                
                                @if($order->status === 'paid' || $order->status === 'shipped')
                                <form action="{{ route('driver.deliveries.update-status', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($order->status === 'paid')
                                        <input type="hidden" name="status" value="shipped">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Start Delivery
                                        </button>
                                    @elseif($order->status === 'shipped')
                                        <input type="hidden" name="status" value="delivered">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Mark Delivered
                                        </button>
                                    @endif
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $deliveries->links() }}
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Deliveries Assigned</h4>
                    <p class="text-muted">You haven't been assigned any deliveries yet.</p>
                    <p class="text-muted">Make sure your availability is set to "Online" to receive delivery assignments.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection