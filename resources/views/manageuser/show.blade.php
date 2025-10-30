@extends('admin.adminbase')

@section('title', 'User Details - ' . $manageuser->name)
@section('page_title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- User Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">User Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $manageuser->id }}</p>
                    <p><strong>Name:</strong> {{ $manageuser->name }}</p>
                    <p><strong>Email:</strong> {{ $manageuser->email }}</p>
                    <p><strong>Phone:</strong> {{ $manageuser->phone ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> 
                        @if($manageuser->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">Suspended</span>
                        @endif
                    </p>
                    <p><strong>Registered:</strong> {{ $manageuser->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Email Verified:</strong> 
                        {{ $manageuser->email_verified_at ? $manageuser->email_verified_at->format('M d, Y') : 'Not Verified' }}
                    </p>
                    <p><strong>Last Login:</strong> 
                        @if($manageuser->last_login_at)
                            {{ $manageuser->last_login_at->diffForHumans() }}
                            @if($manageuser->last_login_ip)
                                <br><small class="text-muted">IP: {{ $manageuser->last_login_ip }}</small>
                            @endif
                        @else
                            Never
                        @endif
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100">
                        <a href="{{ route('manageuser.edit', $manageuser) }}" class="btn btn-primary">Edit User</a>
                        @if($manageuser->status === 'active')
                        <form action="{{ route('manageuser.suspend', $manageuser) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('Are you sure you want to suspend this user?')">
                                Suspend
                            </button>
                        </form>
                        @else
                        <form action="{{ route('manageuser.activate', $manageuser) }}" method="POST" class="d-inline">
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
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Bids</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Auctions Won</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Spent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$0.00</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unpaid Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-4">
                <a href="{{ route('manageuser.index') }}" class="btn btn-secondary">Back to Users</a>
            </div>
        </div>
    </div>
</div>
@endsection