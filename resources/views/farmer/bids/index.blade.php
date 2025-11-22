@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Product Bids</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        @if($bids->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Buyer</th>
                            <th>Buyer Phone</th>
                            <th>Bid Amount</th>
                            <th>Product Price</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- resources/views/farmer/bids/index.blade.php -->
@foreach($bids as $bid)
<tr>
    <td>{{ $bid->product->name }}</td>
    <td>{{ $bid->buyer->name }}</td>
    <td>Ksh {{ number_format($bid->amount, 2) }}</td>
    <td>
        <span class="badge bg-{{ $bid->status == 'pending' ? 'warning' : ($bid->status == 'accepted' ? 'success' : 'danger') }}">
            {{ ucfirst($bid->status) }}
        </span>
        @if($bid->status == 'accepted' && $bid->order)
            <span class="badge bg-info">Pending Payment</span>
        @endif
    </td>
    <td>{{ $bid->created_at->format('M d, Y H:i') }}</td>
    <td>
        @if($bid->status == 'pending')
            <form action="{{ route('farmer.bids.accept', $bid) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm" 
                        onclick="return confirm('Accept this bid?')">
                    <i class="fas fa-check"></i> Accept
                </button>
            </form>
            <form action="{{ route('farmer.bids.reject', $bid) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Reject this bid?')">
                    <i class="fas fa-times"></i> Reject
                </button>
            </form>
        @elseif($bid->status == 'accepted')
            <span class="text-success">
                <i class="fas fa-check-circle"></i> Accepted
                @if($bid->order)
                    <br><small>Order #{{ $bid->order->id }}</small>
                @endif
            </span>
        @else
            <span class="text-danger"><i class="fas fa-times-circle"></i> Rejected</span>
        @endif
    </td>
</tr>
@endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $bids->links() }}
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Bids Received</h4>
                    <p class="text-muted">When buyers place bids on your products, they will appear here.</p>
                    <p class="text-muted">Make sure your products have "Accept Bids" enabled!</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection