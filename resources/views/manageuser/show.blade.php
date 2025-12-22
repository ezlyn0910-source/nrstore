@extends('admin.adminbase')

@section('title', 'User Details - ' . $user->name)
@section('page_title', 'User Details')

@section('content')

<style>
/* User Details Page Styles - Color Theme */
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

.user-details-container {
    background-color: var(--light-bone);
    min-height: 100vh;
}

/* Card Styling */
.card {
    border: 1px solid var(--border-light);
    border-radius: 8px;
    background-color: var(--white);
    box-shadow: 0 2px 4px rgba(26, 36, 18, 0.1);
}

.card-header {
    background-color: var(--primary-green);
    color: var(--white);
    border-bottom: 1px solid var(--border-light);
    padding: 1rem 1.25rem;
}

.card-header h6 {
    margin: 0;
    font-weight: 600;
    color: var(--white);
}

.card-body {
    padding: 1.5rem;
    color: var(--dark-text);
}

.card-footer {
    background-color: var(--light-bone);
    border-top: 1px solid var(--border-light);
    padding: 1rem 1.25rem;
}

/* User Information Styling */
.card-body p {
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
    line-height: 1.4;
}

.card-body strong {
    color: var(--primary-dark);
    font-weight: 600;
    min-width: 120px;
    display: inline-block;
}

.card-body .text-muted {
    color: var(--light-text) !important;
    font-size: 0.85rem;
}

/* Badge Styling */
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 600;
    border-radius: 4px;
}

.badge.bg-success {
    background-color: var(--success) !important;
    color: var(--white);
}

.badge.bg-warning {
    background-color: var(--warning) !important;
    color: var(--dark-text);
}

/* Button Styling */
.btn-group {
    display: flex;
    gap: 0.5rem;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
    border: none;
    flex: 1;
}

.btn-warning {
    background-color: var(--warning);
    color: var(--dark-text);
    border: none;
}

.btn-warning:hover {
    background-color: #e0a800;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(218, 161, 18, 0.3);
}

.btn-success {
    background-color: var(--success);
    color: var(--white);
    border: none;
}

.btn-success:hover {
    background-color: #218838;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.btn-secondary {
    background-color: var(--light-text);
    color: var(--white);
    border: none;
}

.btn-secondary:hover {
    background-color: #5a6a60;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(107, 124, 114, 0.3);
}

/* Activity Stats Cards */
.card .card-body.text-center {
    padding: 1.5rem 1rem;
}

.text-xs {
    font-size: 0.8rem;
}

.font-weight-bold {
    font-weight: 600;
}

.text-uppercase {
    text-transform: uppercase;
}

.text-primary {
    color: var(--primary-green) !important;
}

.text-success {
    color: var(--success) !important;
}

.text-info {
    color: var(--info) !important;
}

.mb-1 {
    margin-bottom: 0.5rem;
}

.h5 {
    font-size: 1.5rem;
    font-weight: 600;
}

.mb-0 {
    margin-bottom: 0;
}

.font-weight-bold.text-gray-800 {
    color: var(--dark-text) !important;
}

/* Layout and Spacing */
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(26, 36, 18, 0.15) !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mt-4 {
    margin-top: 1.5rem !important;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0 15px;
    }
    
    .col-md-4, .col-md-8 {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .card-body {
        padding: 1rem;
    }
}

/* Hover Effects */
.card:hover {
    box-shadow: 0 4px 8px rgba(26, 36, 18, 0.15);
    transition: box-shadow 0.3s ease;
}

/* Status Indicators */
.status-active {
    color: var(--success);
}

.status-suspended {
    color: var(--warning);
}

/* Back Button Container */
.mt-4 .btn {
    max-width: 200px;
}

/* Form Elements */
form.d-inline {
    display: inline;
    width: 100%;
}

/* Ensure proper spacing in card footer */
.card-footer .btn-group {
    margin: -0.25rem;
}

.card-footer .btn-group .btn {
    margin: 0.25rem;
}

/* Additional styling for better visual hierarchy */
.user-info-section {
    background: linear-gradient(135deg, var(--light-bone) 0%, var(--white) 100%);
}

.stats-card {
    transition: all 0.3s ease;
    border-left: 4px solid var(--primary-green);
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.15);
}

.stats-card .h5 {
    color: var(--primary-dark);
}
</style>

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