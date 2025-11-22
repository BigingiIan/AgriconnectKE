<!-- resources/views/farmer/bids.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Product Bids</h1>
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
                                    <th class="border-0">Buyer</th>
                                    <th class="border-0">Buyer Phone</th>
                                    <th class="border-0">Bid Amount</th>
                                    <th class="border-0">Product Price</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0 rounded-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bids as $bid)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $bid->product->name }}</td>
                                    <td>{{ $bid->buyer->name }}</td>
                                    <td>
                                        <a href="tel:{{ $bid->buyer->phone }}" class="text-decoration-none text-dark">
                                            <i class="fas fa-phone-alt text-success me-1"></i> {{ $bid->buyer->phone }}
                                        </a>
                                    </td>
                                    <td class="fw-bold text-success">Ksh {{ number_format($bid->amount, 2) }}</td>
                                    <td class="text-muted">Ksh {{ number_format($bid->product->price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $bid->status == 'pending' ? 'warning' : ($bid->status == 'accepted' ? 'success' : 'danger') }} rounded-pill">
                                            {{ ucfirst($bid->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $bid->created_at->format('M d, Y H:i') }}</td>
                                    <td class="pe-4">
                                        @if($bid->status == 'pending')
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('farmer.bids.status', $bid) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="btn btn-success btn-rounded mx-1" onclick="return confirm('Accept this bid?')">
                                                    <i class="fas fa-check me-1"></i> Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('farmer.bids.status', $bid) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-outline-danger btn-rounded mx-1" onclick="return confirm('Reject this bid?')">
                                                    <i class="fas fa-times me-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <span class="text-muted fst-italic">
                                            <i class="fas fa-check-circle me-1"></i> Processed
                                        </span>
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
                    <i class="fas fa-gavel fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">No Bids Yet</h4>
                    <p class="text-muted">No bids have been placed on your products yet. Ensure your products are listed as "Accepts Bids".</p>
                    <a href="{{ route('farmer.products') }}" class="btn btn-success btn-rounded shadow-sm">Manage Products</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection