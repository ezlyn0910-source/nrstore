@extends('admin.adminbase')

@section('title', 'User Management')
@section('page_title', 'User Management Dashboard')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_user/index.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="user-management-container">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <p>{{ $stats['total_users'] }}</p>
        </div>
        <div class="stat-card">
            <h3>Active Users</h3>
            <p>{{ $stats['active_users'] }}</p>
        </div>
        <div class="stat-card">
            <h3>Suspended Users</h3>
            <p>{{ $stats['suspended_users'] }}</p>
        </div>
        <div class="stat-card">
            <h3>New Users Today</h3>
            <p>{{ $stats['new_users_today'] }}</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filter-card">
        <form action="{{ route('admin.manageuser.index') }}" method="GET" class="filter-form row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name or email..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.manageuser.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Users List</h2>
            <!-- Bulk Actions -->
            <div class="bulk-actions">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <form action="{{ route('admin.manageuser.bulk-action') }}" method="POST" id="bulkForm">
                            @csrf
                            <input type="hidden" name="user_ids" id="bulkUserIds">
                            <li><button type="submit" name="action" value="activate" class="dropdown-item">
                                <i class="fas fa-play me-2"></i>Activate Selected
                            </button></li>
                            <li><button type="submit" name="action" value="suspend" class="dropdown-item">
                                <i class="fas fa-pause me-2"></i>Suspend Selected
                            </button></li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>

        <div class="data-table">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll" class="table-checkbox">
                            </th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox" class="user-checkbox table-checkbox" value="{{ $user->id }}">
                            </td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Suspended</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.manageuser.show', $user) }}" class="btn btn-info btn-sm action-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.manageuser.edit', $user) }}" class="btn btn-primary btn-sm action-btn" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->status === 'active')
                                    <form action="{{ route('admin.manageuser.suspend', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm action-btn" title="Suspend User" 
                                                onclick="return confirm('Are you sure you want to suspend this user?')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.manageuser.activate', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm action-btn" title="Activate User">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Recent Users -->
    <div class="recent-section">
        <div class="section-header">
            <h2>Recent Users</h2>
        </div>
        <div class="data-table">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Suspended</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.manageuser.show', $user) }}" class="btn btn-info btn-sm action-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Bulk selection
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk form submission
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedUsers.length === 0) {
            e.preventDefault();
            alert('Please select at least one user.');
            return;
        }
        
        document.getElementById('bulkUserIds').value = selectedUsers.join(',');
    });
</script>
@endpush