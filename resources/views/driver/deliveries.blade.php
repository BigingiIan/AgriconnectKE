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
<div class="row">
    <div class="col-12">
        @if($deliveries->count() > 0)
            <div class="card content-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start ps-4">Order ID</th>
                                    <th class="border-0">Product</th>
                                    <th class="border-0">Buyer</th>
                                    <th class="border-0">Delivery Address</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Assigned Date</th>
                                    <th class="border-0 rounded-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deliveries as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                    <td>{{ $order->product->name }}</td>
                                    <td>{{ $order->buyer->name }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $order->delivery_address }}">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $order->delivery_address }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">Ksh {{ number_format($order->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->status == 'paid' ? 'warning' : 
                                            ($order->status == 'shipped' ? 'primary' : 
                                            ($order->status == 'delivered' ? 'success' : 'secondary')) 
                                        }} rounded-pill">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->updated_at->format('M d, Y') }}</td>
                                    <td class="pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('driver.deliveries.show', $order) }}" class="btn btn-info btn-rounded text-white shadow-sm me-1">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            
                                            @if($order->status === 'paid' || $order->status === 'shipped')
                                            <form action="{{ route('driver.deliveries.update-status', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @if($order->status === 'paid')
                                                    <input type="hidden" name="status" value="shipped">
                                                    <button type="submit" class="btn btn-success btn-rounded shadow-sm">
                                                        <i class="fas fa-truck-loading me-1"></i> Start
                                                    </button>
                                                @elseif($order->status === 'shipped')
                                                    <input type="hidden" name="status" value="delivered">
                                                    <button type="submit" class="btn btn-success btn-rounded shadow-sm">
                                                        <i class="fas fa-check-circle me-1"></i> Complete
                                                    </button>
                                                @endif
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3">
                    <div class="d-flex justify-content-center">
                        {{ $deliveries->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="card content-card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-truck fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">No Deliveries Assigned</h4>
                    <p class="text-muted mb-2">You haven't been assigned any deliveries yet.</p>
                    <p class="text-muted small">Make sure your availability is set to "Online" to receive delivery assignments.</p>
                    <a href="{{ route('driver.mission-board') }}" class="btn btn-success btn-rounded shadow-sm mt-3">
                        <i class="fas fa-clipboard-list me-1"></i> Check Mission Board
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection