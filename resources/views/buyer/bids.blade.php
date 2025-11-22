<!-- resources/views/buyer/bids.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Bids</h1>
</div>

<div class="row">
    <div class="col-12">
        @if($bids->count() > 0)
            <div class="card content-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start ps-4">Product</th>
                                    <th class="border-0">Farmer</th>
                                    <th class="border-0">My Bid Amount</th>
                                    <th class="border-0">Product Price</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 rounded-end pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bids as $bid)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $bid->product->name }}</td>
                                    <td>{{ $bid->product->farmer->name }}</td>
                                    <td class="fw-bold text-success">Ksh {{ number_format($bid->amount, 2) }}</td>
                                    <td class="text-muted">Ksh {{ number_format($bid->product->price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $bid->status == 'pending' ? 'warning' : ($bid->status == 'accepted' ? 'success' : 'danger') }} rounded-pill">
                                            {{ ucfirst($bid->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4">{{ $bid->created_at->format('M d, Y H:i') }}</td>
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
                    <i class="fas fa-gavel fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">No Bids Yet</h4>
                    <p class="text-muted mb-4">You haven't placed any bids yet.</p>
                    <a href="{{ route('buyer.market') }}" class="btn btn-success btn-rounded shadow-sm">
                        <i class="fas fa-shopping-basket me-1"></i> Browse Marketplace
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection