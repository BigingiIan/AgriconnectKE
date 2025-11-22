@extends('layouts.app')

@section('title', 'Notifications - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Notifications</h1>
    <div>
        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-success btn-sm btn-rounded">
                <i class="fas fa-check-double me-1"></i> Mark All as Read
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if(isset($notifications) && $notifications->count() > 0)
            <div class="card content-card">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action p-4 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                    <h6 class="mb-1 fw-bold {{ $notification->read_at ? 'text-dark' : 'text-success' }}">
                                        @if(!$notification->read_at)
                                            <i class="fas fa-circle text-success me-2 small"></i>
                                        @endif
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted">{{ $notification->data['message'] ?? '' }}</p>
                                @if(isset($notification->data['action_url']))
                                    <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-success btn-rounded mt-2">
                                        View Details
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-3">
                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="card content-card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">No Notifications</h4>
                    <p class="text-muted mb-0">You're all caught up! Check back later for updates.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
