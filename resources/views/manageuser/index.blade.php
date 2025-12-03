@extends('admin.adminbase')

@section('title', 'User Management')

@section('content')

<style>
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-gold: #DAA112;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
    --success: #28a745;
    --warning: #ffc107;
    --danger: #dc3545;
    --info: #17a2b8;
}

.user-management-container {
    padding: 2rem;
    background: var(--light-bone);
    min-height: 100vh;
}

/* Dashboard Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.header-content .page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.5rem 0;
}

.header-content .page-subtitle {
    color: var(--light-text);
    margin: 0;
    font-size: 1rem;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 100px));
    gap: 1rem;
    margin-bottom: 2rem;
    width: 800px;
}

.stat-card {
    background: var(--white);
    padding: 0.5rem 0.5rem;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
    display: flex;
    align-items: center;
    gap: 0.1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin: 0;
    width: 180px;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(26, 36, 18, 0.12);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: var(--light-bone);
    color: var(--primary-green);
}

.stat-icon.active {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
}

.stat-icon.featured {
    background: rgba(218, 161, 18, 0.1);
    color: var(--accent-gold);
}

.stat-icon.low-stock {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
}

.stat-content .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.25rem 0;
    line-height: 1;
}

.stat-content .stat-label {
    color: var(--light-text);
    margin: 0;
    font-size: 0.7rem;
}

/* Filter Section */
.filter-card {
    background: var(--white);
    border-radius: 12px;
    padding: 0.8rem;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
    border: 1px solid var(--border-light);
    margin-bottom: 1rem;
}

.filter-form .form-control {
    border: 1px solid var(--border-light);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    color: var(--dark-text);
    transition: all 0.3s ease;
}

.filter-form .form-control:focus {
    border-color: var(--accent-gold);
    box-shadow: 0 0 0 3px rgba(218, 161, 18, 0.1);
}

.filter-form .btn-primary {
    background: var(--border-light);
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.filter-form .btn-primary:hover {
    background: var(--light-text);
    transform: translateY(-1px);
}

.filter-form .btn-secondary {
    background: var(--light-bone);
    border: 1px solid var(--border-light);
    color: var(--light-text);
    border-radius: 8px;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
}

/* Table Section */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-light);
}

.section-header h2 {
    color: var(--primary-dark);
    font-weight: 700;
    margin: 0;
}

.bulk-actions .btn-outline-primary {
    border: 1px solid var(--light-bone);
    color: var(--primary-green);
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.bulk-actions .btn-outline-primary:hover {
    background: var(--light-text);
    color: var(--white);
}
 
/* Tables */
.data-table {
    background: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
    border: 1px solid var(--border-light);
}

.data-table .table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.data-table .table thead th {
    background: var(--light-bone);
    color: var(--primary-green);
    border: none;
    padding: 1rem 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.data-table .table tbody td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
    color: var(--dark-text);
}

.data-table .table tbody tr:last-child td {
    border-bottom: none;
}

.data-table .table tbody tr:hover {
    background: rgba(45, 74, 53, 0.04);
}

/* Checkboxes */
.table-checkbox {
    width: 18px;
    height: 18px;
    border: 2px solid var(--border-light);
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.table-checkbox:checked {
    background: var(--accent-gold);
    border-color: var(--accent-gold);
}

/* Badges */
.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}

.badge.bg-success {
    background: var(--success-light) !important;
    color: var(--primary-green) !important;
    border: 1px solid rgba(45, 74, 53, 0.2);
}

.badge.bg-warning {
    background: var(--warning-light) !important;
    color: #856404 !important;
    border: 1px solid rgba(218, 161, 18, 0.2);
}

/* Action Buttons */
.btn-group-sm > .btn {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    margin: 0 2px;
}

.btn-info {
    background: var(--primary-green);
    border: none;
    color: var(--white);
}

.btn-info:hover {
    background: var(--primary-dark);
    color: var(--white);
}

.btn-primary {
    background: var(--accent-gold);
    border: none;
    color: var(--primary-dark);
}

.btn-primary:hover {
    background: #c2910f;
    color: var(--primary-dark);
}

.btn-warning {
    background: #fff8e1;
    border: 1px solid var(--accent-gold);
    color: var(--accent-gold);
}

.btn-warning:hover {
    background: var(--accent-gold);
    color: var(--primary-dark);
}

.btn-success {
    background: var(--success-light);
    border: 1px solid var(--primary-green);
    color: var(--primary-green);
}

.btn-success:hover {
    background: var(--primary-green);
    color: var(--white);
}

/* Pagination */
.pagination {
    margin: 2rem 0 0 0;
}

.pagination .page-link {
    border: 1px solid var(--border-light);
    color: var(--light-text);
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
}

.pagination .page-item.active .page-link {
    background: var(--accent-gold);
    border-color: var(--accent-gold);
    color: var(--primary-dark);
}

/* Recent Users Section */
.recent-section {
    margin-top: 3rem;
}

.recent-section .section-header {
    border-bottom: 2px solid var(--border-light);
    padding-bottom: 1rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    flex-wrap: nowrap;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    padding: 0;
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Ensure icons are properly sized */
.action-btn i {
    font-size: 0.875rem;
    line-height: 1;
}

/* Bulk action dropdown icons */
.dropdown-item i {
    width: 16px;
    text-align: center;
}

/* Tooltip enhancements */
.action-btn {
    position: relative;
}

/* Responsive action buttons */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
    }
    
    .action-btn i {
        font-size: 0.75rem;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .filter-form .row {
        gap: 1rem;
    }
    
    .filter-form .col-md-3 {
        display: flex;
        gap: 0.5rem;
    }
}
</style>

<div class="user-management-container">
    <!-- Header Section-->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="page-title">Manage Users</h1>
            <p class="page-subtitle">Manage your users listings</p>
        </div>
    </div>

    <!-- Stats Cards -->

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total_users'] }}</h3>
                <p class="stat-label">Total Users</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['active_users'] }}</h3>
                <p class="stat-label">Active Users</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon featured">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['suspended_users'] }}</h3>
                <p class="stat-label">Suspended Users</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon low-stock">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['new_users_today'] }}</h3>
                <p class="stat-label">New Users Today</p>
            </div>
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
                                <div class="action-buttons">
                                    <a href="{{ route('admin.manageuser.show', $user) }}" class="btn btn-info btn-sm action-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
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