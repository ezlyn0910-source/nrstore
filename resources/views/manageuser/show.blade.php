@extends('admin.adminbase')

@section('title', 'User Details - ' . $user->name)
@section('page_title', 'User Details')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_user/show.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="container-fluid user-details-container">
    <div class="row">
        <div class="col-md-4">
            <!-- User Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">User Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $user->id }}</p>
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        @if($user->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">Suspended</span>
                        @endif
                    </p>
                    <p><strong>Registered:</strong> {{ $user->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Email Verified:</strong> 
                        {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not Verified' }}
                    </p>
                    <p><strong>Last Login:</strong> 
                        @if($user->last_login_at)
                            {{ $user->last_login_at->diffForHumans() }}
                            @if($user->last_login_ip)
                                <br><small class="text-muted">IP: {{ $user->last_login_ip }}</small>
                            @endif
                        @else
                            Never
                        @endif
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100">
                        @if($user->status === 'active')
                        <form action="{{ route('admin.manageuser.suspend', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('Are you sure you want to suspend this user?')">
                                Suspend
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.manageuser.activate', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Activate</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Activity Stats -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body text-center">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Bids</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body text-center">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Auctions Won</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body text-center">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Spent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">RM0.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-4">
                <a href="{{ route('admin.manageuser.index') }}" class="btn btn-secondary">Back to Users</a>
            </div>
        </div>
    </div>
</div>
@endsection