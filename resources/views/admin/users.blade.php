@extends('layouts.app')

@section('title', 'User Management - AgriconnectKE')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary btn-rounded">
                <i class="fas fa-user-plus me-1"></i> Add User
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary btn-rounded">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card content-card">
            <div class="card-header bg-white border-0 pt-4 ps-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 fw-bold">All Users</h5>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.users') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2 rounded-pill" placeholder="Search users..." value="{{ request('search') }}">
                            <select name="role" class="form-select form-select-sm me-2 rounded-pill" style="width: 150px;">
                                <option value="">All Roles</option>
                                <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                                <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                                <option value="driver" {{ request('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary btn-rounded">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">Name</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Role</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Joined Date</th>
                                <th class="border-0 rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-secondary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                                            <small class="text-muted">ID: #{{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ 
                                        $user->role == 'admin' ? 'danger' : 
                                        ($user->role == 'farmer' ? 'success' : 
                                        ($user->role == 'driver' ? 'info' : 'primary')) 
                                    }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success rounded-pill">Active</span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary btn-rounded dropdown-toggle" type="button" id="dropdownMenuButton{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2 text-primary"></i> Edit</a></li>
                                            <li>
                                                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Reset password for this user?')">
                                                        <i class="fas fa-key me-2 text-warning"></i> Reset Password
                                                    </button>
                                                </form>
                                            </li>
                                            @if($user->role === 'driver')
                                            <li><a class="dropdown-item" href="{{ route('admin.track-driver', $user) }}"><i class="fas fa-map-marker-alt me-2 text-info"></i> Track Location</a></li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fas fa-trash-alt me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3 opacity-50"></i>
                                    <p class="text-muted">No users found matching your criteria.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-3">
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection