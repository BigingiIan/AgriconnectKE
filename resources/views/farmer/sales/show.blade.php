@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Order Details #{{ $order->id }}</h1>
    <a href="{{ route('farmer.sales') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Sales
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order Date:</strong><br> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        <p><strong>Status:</strong><br>
                            @if($order->status === 'pending')
                                <span class="badge bg-warning">Pending Payment</span>
                            @elseif($order->status === 'paid')
                                <span class="badge bg-info">Paid</span>
                            @elseif($order->status === 'shipped')
                                <span class="badge bg-primary">Shipped</span>
                            @elseif($order->status === 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong><br> Ksh {{ number_format($order->amount, 2) }}</p>
                        <p><strong>Quantity:</strong><br> {{ $order->quantity }}</p>
                        <p><strong>Unit Price:</strong><br> Ksh {{ number_format($order->product->price, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Product Details</h5>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    @if($order->product->image)
                        <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}" 
                             class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                    @endif
                    <div>
                        <h5>{{ $order->product->name }}</h5>
                        <p class="text-muted mb-2">{{ $order->product->description }}</p>
                        <p class="mb-0"><strong>Category:</strong> {{ $order->product->category }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Buyer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->buyer->name }}</p>
                <p><strong>Phone:</strong> {{ $order->buyer->phone }}</p>
                <p><strong>Email:</strong> {{ $order->buyer->email }}</p>
                <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
            </div>
        </div>

        @if($order->driver)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Driver Name:</strong> {{ $order->driver->name }}</p>
                    <p><strong>Driver Phone:</strong> {{ $order->driver->phone }}</p>
                    <p><strong>Delivery Status:</strong> 
                        @if($order->status === 'shipped')
                            <span class="badge bg-primary">In Transit</span>
                        @elseif($order->status === 'delivered')
                            <span class="badge bg-success">Delivered</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Order Placed</h6>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>

                    @if($order->status === 'paid' || $order->status === 'shipped' || $order->status === 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Payment Received</h6>
                            <small class="text-muted">Payment confirmed</small>
                        </div>
                    </div>
                    @endif

                    @if($order->status === 'shipped' || $order->status === 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Order Shipped</h6>
                            <small class="text-muted">Out for delivery</small>
                        </div>
                    </div>
                    @endif

                    @if($order->status === 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Order Delivered</h6>
                            <small class="text-muted">Delivery completed</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px;
}

.timeline-content {
    padding-bottom: 20px;
    border-left: 2px solid #e9ecef;
    margin-left: -31px;
    padding-left: 30px;
}

.timeline-item:last-child .timeline-content {
    border-left: none;
    padding-bottom: 0;
}
</style>
@endsection