@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Delivery Details #{{ $order->id }}</h1>
    <a href="{{ route('driver.deliveries') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Deliveries
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
                            <span class="badge bg-{{ 
                                $order->status == 'paid' ? 'warning' : 
                                ($order->status == 'shipped' ? 'primary' : 
                                ($order->status == 'delivered' ? 'success' : 'secondary')) 
                            }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Delivery Cost:</strong><br> Ksh {{ number_format($order->delivery_cost, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong><br> Ksh {{ number_format($order->amount, 2) }}</p>
                        <p><strong>Quantity:</strong><br> {{ $order->quantity }}</p>
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
                        <img src="{{ asset('storage/' . $order->product->image) }}" 
                             alt="{{ $order->product->name }}" 
                             class="img-thumbnail me-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @endif
                    <div>
                        <h5>{{ $order->product->name }}</h5>
                        <p class="text-muted mb-2">{{ $order->product->description }}</p>
                        <p class="mb-0"><strong>Category:</strong> {{ $order->product->category }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Delivery Actions</h5>
            </div>
            <div class="card-body">
                @if($order->status === 'paid')
                    <form action="{{ route('driver.deliveries.update-status', $order) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="shipped">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-shipping-fast"></i> Start Delivery
                        </button>
                        <p class="text-muted mt-2">This will notify the buyer that their order is on the way.</p>
                    </form>
                @elseif($order->status === 'shipped')
                    <form action="{{ route('driver.deliveries.update-status', $order) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check"></i> Mark as Delivered
                        </button>
                        <p class="text-muted mt-2">This will complete the delivery and notify both buyer and farmer.</p>
                    </form>
                @elseif($order->status === 'delivered')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> This delivery was completed on 
                        {{ $order->updated_at->format('M d, Y h:i A') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Buyer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->buyer->name }}</p>
                <p><strong>Phone:</strong> {{ $order->buyer->phone }}</p>
                <p><strong>Email:</strong> {{ $order->buyer->email }}</p>
                <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                
                <div class="mt-3">
                    <a href="tel:{{ $order->buyer->phone }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone"></i> Call Buyer
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Farmer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->farmer->name }}</p>
                <p><strong>Phone:</strong> {{ $order->farmer->phone }}</p>
                <p><strong>Email:</strong> {{ $order->farmer->email }}</p>
                <p><strong>Address:</strong> {{ $order->farmer->address }}</p>
            </div>
        </div>
    </div>
</div>
@endsection