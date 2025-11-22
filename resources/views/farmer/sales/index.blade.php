@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales History</h1>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Sales Revenue</h5>
                <h2 class="card-text">Ksh {{ number_format($totalSales, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($sales->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Buyer</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->buyer->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Ksh {{ number_format($order->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status == 'paid' ? 'success' : 
                                    ($order->status == 'delivered' ? 'info' : 
                                    ($order->status == 'shipped' ? 'primary' : 'warning')) 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('farmer.sales.show', $order) }}" class="btn btn-sm btn-info">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <p>No sales recorded yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection