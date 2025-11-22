@extends('layouts.app')

@section('title', 'Shopping Cart - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Shopping Cart</h1>
    <div>
        @if(count($cartItems) > 0)
            <form action="{{ route('buyer.cart.clear') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm" 
                        onclick="return confirm('Are you sure you want to clear your cart?')">
                    <i class="fas fa-trash"></i> Clear Cart
                </button>
            </form>
        @endif
        <a href="{{ route('buyer.market') }}" class="btn btn-outline-success btn-sm">
            <i class="fas fa-shopping-bag"></i> Continue Shopping
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        @if(count($cartItems) > 0)
            <div class="card content-card">
                <div class="card-header bg-white border-0 pt-4 ps-4">
                    <h5 class="mb-0 fw-bold">Items in Cart</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start ps-4">Product</th>
                                    <th class="border-0">Price</th>
                                    <th class="border-0">Quantity</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0 rounded-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $cartItem)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($cartItem['product']->image)
                                                <img src="{{ asset('storage/' . $cartItem['product']->image) }}" 
                                                     alt="{{ $cartItem['product']->name }}" 
                                                     class="img-thumbnail me-3 rounded shadow-sm" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 80px; height: 80px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $cartItem['product']->name }}</h6>
                                                <small class="text-muted">by {{ $cartItem['product']->farmer->name }}</small>
                                                <br>
                                                <span class="badge bg-light text-dark border rounded-pill mt-1">{{ ucfirst($cartItem['product']->category) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle fw-bold">
                                        Ksh {{ number_format($cartItem['product']->price, 2) }}
                                    </td>
                                    <td class="align-middle">
                                        <form action="{{ route('buyer.cart.update', $cartItem['product']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <div class="input-group input-group-sm" style="width: 140px;">
                                                <input type="number" 
                                                       name="quantity" 
                                                       value="{{ $cartItem['quantity'] }}" 
                                                       min="1" 
                                                       max="{{ $cartItem['product']->quantity }}"
                                                       class="form-control text-center">
                                                <button type="submit" class="btn btn-outline-success">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <small class="text-muted d-block mt-1">
                                            Max: {{ $cartItem['product']->quantity }} available
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <strong class="text-success">
                                            Ksh {{ number_format($cartItem['item_total'], 2) }}
                                        </strong>
                                    </td>
                                    <td class="align-middle pe-4">
                                        <form action="{{ route('buyer.cart.remove', $cartItem['product']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm btn-rounded" 
                                                    onclick="return confirm('Remove this item from cart?')">
                                                <i class="fas fa-trash me-1"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end py-3"><strong>Total:</strong></td>
                                    <td colspan="2" class="py-3">
                                        <strong class="text-success h4 mb-0">
                                            Ksh {{ number_format($total, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row p-4">
                        <div class="col-md-8">
                            <div class="alert alert-info border-0 rounded-3">
                                <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i> Important Notes</h6>
                                <ul class="mb-0 small">
                                    <li>Delivery costs will be calculated during checkout based on your location</li>
                                    <li>Products are reserved when added to cart but not guaranteed until purchase</li>
                                    <li>Farmers may update product availability at any time</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-grid gap-2">
                                <a href="{{ route('buyer.market') }}" class="btn btn-outline-success btn-rounded">
                                    <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                                </a>
                                <a href="{{ route('buyer.checkout.cart') }}" class="btn btn-success btn-lg btn-rounded shadow-sm">
                                    <i class="fas fa-credit-card me-1"></i> Proceed to Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card content-card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-4 opacity-50"></i>
                    <h3 class="text-muted">Your Cart is Empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added any products to your cart yet.</p>
                    <a href="{{ route('buyer.market') }}" class="btn btn-success btn-lg btn-rounded shadow-sm">
                        <i class="fas fa-shopping-bag me-1"></i> Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection