<nav class="nav flex-column">
    <a class="nav-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}" href="{{ route('buyer.dashboard') }}">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </a>
    <a class="nav-link {{ request()->routeIs('buyer.market') ? 'active' : '' }}" href="{{ route('buyer.market') }}">
        <i class="fas fa-store me-2"></i>Marketplace
    </a>
    <a class="nav-link {{ request()->routeIs('buyer.cart') ? 'active' : '' }}" href="{{ route('buyer.cart') }}">
        <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
        <span class="badge bg-primary float-end cart-sidebar-count">
            {{ array_sum(array_column(session('cart', []), 'quantity')) }}
        </span>
    </a>
    <a class="nav-link {{ request()->routeIs('buyer.orders') ? 'active' : '' }}" href="{{ route('buyer.orders') }}">
        <i class="fas fa-clipboard-list me-2"></i>My Orders
    </a>
    <a class="nav-link {{ request()->routeIs('buyer.bids') ? 'active' : '' }}" href="{{ route('buyer.bids') }}">
        <i class="fas fa-gavel me-2"></i>My Bids
    </a>
    <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
        <i class="fas fa-bell me-2"></i>Notifications
        @php
            $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
        @endphp
        @if($unreadCount > 0)
            <span class="badge bg-danger float-end">{{ $unreadCount }}</span>
        @endif
    </a>
</nav>

<script>
    // Update sidebar cart count when cart is updated
    document.addEventListener('cartUpdated', function(e) {
        const sidebarCount = document.querySelector('.cart-sidebar-count');
        if (sidebarCount && e.detail && e.detail.count !== undefined) {
            sidebarCount.textContent = e.detail.count;
        }
    });
</script>