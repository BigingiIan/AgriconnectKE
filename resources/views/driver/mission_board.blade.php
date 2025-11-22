@extends('layouts.app')

@section('title', 'Mission Board - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mission Board</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary btn-rounded">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card content-card bg-gradient-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-2"><i class="fas fa-rocket me-2"></i>Available Missions</h4>
                        <p class="mb-0 opacity-75">Pick up these orders to earn extra income. First come, first served!</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="fas fa-map-marked-alt fa-4x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if(isset($availableOrders) && $availableOrders->count() > 0)
            <div class="card content-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start ps-4">Order ID</th>
                                    <th class="border-0">Pickup Location</th>
                                    <th class="border-0">Delivery Location</th>
                                    <th class="border-0">Distance (Est.)</th>
                                    <th class="border-0">Earnings</th>
                                    <th class="border-0 rounded-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableOrders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-light text-primary me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                <i class="fas fa-store small"></i>
                                            </div>
                                            <div>
                                                <span class="d-block fw-bold">{{ $order->farmer->name }}</span>
                                                <small class="text-muted">{{ Str::limit($order->farmer->address ?? 'Nairobi', 20) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-light text-danger me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                <i class="fas fa-map-marker-alt small"></i>
                                            </div>
                                            <div>
                                                <span class="d-block fw-bold">{{ $order->buyer->name }}</span>
                                                <small class="text-muted">{{ Str::limit($order->delivery_address, 20) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">
                                            <i class="fas fa-route me-1 text-muted"></i> ~5 km
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Ksh {{ number_format($order->delivery_fee ?? 250, 2) }}
                                    </td>
                                    <td class="pe-4">
                                        <form action="{{ route('driver.mission.accept', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm btn-rounded shadow-sm w-100">
                                                <i class="fas fa-check me-1"></i> Accept
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3">
                    <div class="d-flex justify-content-center">
                        {{ $availableOrders->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="card content-card text-center py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="fa-stack fa-2x">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-clipboard-check fa-stack-1x text-muted"></i>
                        </span>
                    </div>
                    <h4 class="text-muted">No Missions Available</h4>
                    <p class="text-muted mb-0">There are currently no open delivery missions. Check back later!</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
