@extends('admin.adminbase')
@section('title', 'Manage Bids')

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

/* Manage Bids Index Styles - Synced with Product Management */
.bids-management-container {
    padding: 2rem;
    background: var(--light-bone);
    min-height: 100vh;
}

/* Header Section - Synced */
.bids-header {
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

.header-actions .btn-primary {
    background: var(--primary-green);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    color: var(--white);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.header-actions .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.2);
}

/* Stats Grid - Synced */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 100px));
    gap: 1rem;
    margin-bottom: 2rem;
    width: 1000px;
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

.stat-icon.upcoming {
    background: rgba(23, 162, 184, 0.1);
    color: var(--warning);
}

.stat-icon.completed {
    background: rgba(45, 74, 53, 0.1);
    color: var(--success);
}

.stat-icon.draft {
    background: rgba(255, 193, 7, 0.1);
    color: var(--accent-gold);
}

.stat-icon.total {
    background: rgba(108, 117, 125, 0.1);
    color: var(--primary-green);
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

/* Quick Stats Bar - Simplified */
.quick-stats-bar {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
    padding: 1rem 1.5rem;
    background: var(--white);
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
}

.quick-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--dark-text);
    font-weight: 500;
}

.quick-stat i {
    color: var(--accent-gold);
    font-size: 1rem;
}

.quick-stat strong {
    color: var(--primary-green);
    font-weight: 600;
}

/* Filters Section - Synced */
.filters-section {
    background: var(--white);
    padding: 1.5rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
}

.filters-row {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-box .fas.fa-search {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--light-text);
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(45, 74, 53, 0.1);
}

.filter-controls {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    background: var(--white);
    color: var(--dark-text);
    min-width: 150px;
}

.view-controls {
    display: flex;
    gap: 0.25rem;
    background: var(--light-bone);
    padding: 0.25rem;
    border-radius: 0.5rem;
}

.view-btn {
    background: transparent;
    border: none;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    color: var(--light-text);
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-btn.active {
    background: var(--white);
    color: var(--primary-green);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.view-btn:hover:not(.active) {
    color: var(--primary-dark);
}

/* Bulk Actions - Synced */
.bulk-actions-section {
    background: var(--white);
    padding: 1rem 1.5rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
}

.bulk-actions-row {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.bulk-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.bulk-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.bulk-checkbox label {
    color: var(--dark-text);
    font-weight: 500;
    margin: 0;
}

.bulk-action-select {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    background: var(--white);
    color: var(--dark-text);
    min-width: 200px;
}

#applyBulkAction:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Bids Table - Synced */
.bids-table-container {
    background: var(--white);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(26, 36, 18, 0.08);
}

.table-responsive {
    overflow-x: auto;
}

.bids-table {
    width: 100%;
    border-collapse: collapse;
}

.bids-table th {
    background: var(--light-bone);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--primary-dark);
    border-bottom: 1px solid var(--border-light);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bids-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
}

.bids-table tr:last-child td {
    border-bottom: none;
}

.bids-table tr:hover {
    background: var(--light-bone);
}

/* Select Column */
.select-column {
    width: 40px;
    text-align: center;
}

.select-column input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Product Column */
.product-column .product-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-image {
    width: 60px;
    height: 60px;
    border-radius: 0.5rem;
    overflow: hidden;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details .product-name {
    font-weight: 600;
    color: var(--primary-dark);
    margin: 0 0 0.25rem 0;
    font-size: 0.95rem;
    line-height: 1.3;
}

.product-details .product-sku {
    color: var(--light-text);
    font-size: 0.8rem;
    margin: 0 0 0.5rem 0;
}

.bid-dates small {
    color: var(--light-text);
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Price Column */
.price-range, .single-price {
    font-weight: 600;
    color: var(--primary-dark);
}

.current-price {
    font-size: 1.1rem;
    color: var(--primary-green);
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.starting-price {
    color: var(--light-text);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.reserve-price {
    color: var(--light-text);
    font-size: 0.8rem;
}

.reserve-met {
    color: var(--success);
    font-weight: 600;
}

.reserve-not-met {
    color: var(--warning);
    font-weight: 600;
}

/* Bids Column */
.bids-count {
    color: var(--primary-dark);
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.bid-increment {
    color: var(--light-text);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.last-bid-time {
    color: var(--info);
    font-size: 0.75rem;
    font-weight: 500;
}

/* Time Column */
.countdown-timer {
    text-align: center;
    padding: 0.75rem;
    background: rgba(45, 74, 53, 0.03);
    border-radius: 0.5rem;
    border: 1px solid var(--border-light);
}

.timer-display {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: var(--primary-dark);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: var(--border-light);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: var(--primary-green);
    transition: width 1s ease;
    border-radius: 2px;
}

.timer-label {
    color: var(--light-text);
    font-size: 0.75rem;
    font-weight: 500;
}

.ended-badge, .paused-badge, .starts-in {
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 0.5rem;
}

.ended-badge {
    background: var(--danger);
    color: var(--white);
}

.paused-badge {
    background: var(--warning);
    color: var(--primary-dark);
}

.starts-in {
    background: var(--info);
    color: var(--white);
}

/* Status Column */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: capitalize;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
}

.status-draft {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
}

.status-paused {
    background: rgba(23, 162, 184, 0.1);
    color: var(--info);
}

.status-completed {
    background: rgba(45, 74, 53, 0.1);
    color: var(--primary-green);
}

.status-cancelled {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
}

.status-meta small {
    color: var(--light-text);
    font-size: 0.75rem;
}

/* Winner Column */
.winner-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.winner-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--light-bone);
    color: var(--primary-green);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.winner-details strong {
    color: var(--primary-dark);
    display: block;
    margin-bottom: 0.125rem;
    font-size: 0.9rem;
}

.winner-details small {
    color: var(--primary-green);
    font-weight: 500;
    display: block;
    margin-bottom: 0.125rem;
}

.winner-contact small {
    color: var(--light-text);
    font-weight: normal;
}

.no-winner {
    text-align: center;
    color: var(--light-text);
    font-style: italic;
    padding: 1rem 0.5rem;
    background: rgba(0,0,0,0.02);
    border-radius: 0.5rem;
    border: 1px dashed var(--border-light);
}

/* Actions Column */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-action.view {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
}

.btn-action.view:hover {
    background: var(--success);
    color: var(--white);
}

.btn-action.edit {
    background: rgba(23, 162, 184, 0.1);
    color: var(--info);
}

.btn-action.edit:hover {
    background: var(--info);
    color: var(--white);
}

.btn-action.start {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
}

.btn-action.start:hover {
    background: var(--success);
    color: var(--white);
}

.btn-action.pause {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
}

.btn-action.pause:hover {
    background: var(--warning);
    color: var(--primary-dark);
}

.btn-action.complete {
    background: rgba(45, 74, 53, 0.1);
    color: var(--primary-green);
}

.btn-action.complete:hover {
    background: var(--primary-green);
    color: var(--white);
}

.btn-action.delete {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
}

.btn-action.delete:hover {
    background: var(--danger);
    color: var(--white);
}

/* Dropdown Menu */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 1000;
    display: none;
    min-width: 160px;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    font-size: 0.9rem;
    color: var(--dark-text);
    text-align: left;
    list-style: none;
    background-color: var(--white);
    background-clip: padding-box;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-item {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    clear: both;
    font-weight: 400;
    color: var(--dark-text);
    text-align: inherit;
    text-decoration: none;
    white-space: nowrap;
    background-color: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: var(--light-bone);
    color: var(--primary-green);
}

.dropdown-item.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.1);
    color: var(--danger);
}

.dropdown-divider {
    height: 1px;
    margin: 0.5rem 0;
    overflow: hidden;
    background-color: var(--border-light);
}

/* Table Footer */
.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-top: 1px solid var(--border-light);
    background: rgba(0,0,0,0.02);
}

.table-summary {
    color: var(--light-text);
    font-size: 0.9rem;
}

.table-summary strong {
    color: var(--primary-dark);
}

/* Empty State */
.no-bids {
    text-align: center;
    padding: 4rem 2rem !important;
}

.empty-state {
    color: var(--light-text);
}

.empty-state .fas {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--dark-text);
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin-bottom: 2rem;
}

/* Pagination */
.pagination-container {
    padding: 2rem;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.pagination .page-link {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    color: var(--dark-text);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
}

.pagination .page-item.active .page-link {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
}

.pagination .page-item.disabled .page-link {
    color: var(--light-text);
    background: var(--light-bone);
    border-color: var(--border-light);
}

/* Responsive Design */
@media (max-width: 768px) {
    .bids-management-container {
        padding: 1rem;
    }
    
    .bids-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-stats-bar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filters-row {
        flex-direction: column;
    }
    
    .search-box {
        min-width: 100%;
    }
    
    .filter-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .bulk-actions-row {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .product-info {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }
    
    .winner-info {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .bids-table {
        font-size: 0.8rem;
    }
    
    .bids-table th,
    .bids-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .table-footer {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .header-content .page-title {
        font-size: 1.5rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .stat-content .stat-number {
        font-size: 1.5rem;
    }
}
</style>

<div class="bids-management-container">
    <div class="bids-header">
        <div class="header-content">
            <h1 class="page-title">Manage Bids</h1>
            <p class="page-subtitle">Monitor and manage all auction bids</p>
        </div>
        <div class="header-right">
            <div class="header-actions">
                <a href="{{ route('admin.managebid.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Bid
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total"><i class="fas fa-box"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total'] }}</h3>
                <p class="stat-label">Total Bids</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['active'] }}</h3>
                <p class="stat-label">Active Bids</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon upcoming"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['upcoming'] }}</h3>
                <p class="stat-label">Upcoming</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon completed"><i class="fas fa-star"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['completed'] }}</h3>
                <p class="stat-label">Completed</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft"><i class="fas fa-file-alt"></i></div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['draft'] }}</h3>
                <p class="stat-label">Draft</p>
            </div>
        </div>
    </div>

    <div class="quick-stats-bar">
        <div class="quick-stat">
            <i class="fas fa-users"></i>
            <span>Page Participants: <strong id="totalParticipants">{{ $pageStats['participants'] }}</strong></span>
        </div>
        <div class="quick-stat">
            <i class="fas fa-money-bill-wave"></i>
            <span>Page Bid Value: <strong id="totalBidValue">RM {{ number_format($pageStats['total_value'], 2) }}</strong></span>
        </div>
        <div class="quick-stat">
            <i class="fas fa-trophy"></i>
            <span>Page Winners: <strong id="activeWinners">{{ $pageStats['winners'] }}</strong></span>
        </div>
    </div>

    <div class="filters-section">
        <form action="{{ route('admin.managebid.index') }}" method="GET" class="filters-left" id="filterForm">
            
            <select name="status" id="statusFilter" class="filter-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <select name="time_filter" id="timeFilter" class="filter-select" onchange="this.form.submit()">
                <option value="">All Time</option>
                <option value="today" {{ request('time_filter') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="tomorrow" {{ request('time_filter') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                <option value="week" {{ request('time_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ request('time_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                <option value="past" {{ request('time_filter') == 'past' ? 'selected' : '' }}>Past Bids</option>
            </select>

            <a href="{{ route('admin.managebid.index') }}" class="btn btn-secondary" id="refreshBtn">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </a>
            
        </form>
    </div>

    <form id="bulkActionForm" action="{{ route('admin.managebid.bulk_action') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkActionInput">
        <input type="hidden" name="ids" id="bulkIdsInput">
    </form>

    <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
        <div class="bulk-info">
            <span id="selectedCount">0 bids selected</span>
        </div>
        <div class="bulk-buttons">
            <button class="btn btn-success btn-sm" onclick="submitBulkAction('start')">
                <i class="fas fa-play"></i> Start
            </button>
            <button class="btn btn-warning btn-sm" onclick="submitBulkAction('pause')">
                <i class="fas fa-pause"></i> Pause
            </button>
            <button class="btn btn-primary btn-sm" onclick="submitBulkAction('complete')">
                <i class="fas fa-flag-checkered"></i> Complete
            </button>
            <button class="btn btn-danger btn-sm" onclick="submitBulkAction('delete')">
                <i class="fas fa-trash"></i> Delete
            </button>
            <button class="btn btn-secondary btn-sm" id="bulkDeselectBtn">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>

    <div class="bids-view" id="tableView">
        <div class="bids-table-container">
            <div class="table-responsive">
                <table class="bids-table">
                    <thead>
                        <tr>
                            <th class="select-col"><input type="checkbox" id="selectAllCheckbox"></th>
                            <th class="product-col">Product</th>
                            <th class="price-col">Current Price</th>
                            <th class="bids-col">Bids</th>
                            <th class="time-col">Time Remaining</th>
                            <th class="status-col">Status</th>
                            <th class="winner-col">Winner</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid) 
                        <tr class="bid-row" 
                            data-id="{{ $bid->id }}"
                            data-start-time="{{ $bid->start_time->format('c') }}"
                            data-end-time="{{ $bid->end_time->format('c') }}">
                            <td class="select-cell">
                                <input type="checkbox" class="bid-checkbox" value="{{ $bid->id }}">
                            </td>
                            <td class="product-cell">
                                <div class="product-info">
                                    <div class="product-image">
                                        <img src="{{ $bid->product->main_image_url }}" alt="{{ $bid->product->name }}" 
                                             onerror="this.src='{{ asset('images/default-product.png') }}'">
                                        @if($bid->is_active)
                                        <div class="live-indicator" title="Live Auction"><i class="fas fa-circle"></i></div>
                                        @endif
                                    </div>
                                    <div class="product-details">
                                        <h4 class="product-name">{{ $bid->product->name }}</h4>
                                        <p class="product-sku">SKU: {{ $bid->product->sku ?? 'N/A' }}</p>
                                        <div class="bid-dates">
                                            <small><i class="fas fa-play"></i> {{ $bid->start_time->format('M d, H:i') }}</small>
                                            <small><i class="fas fa-flag-checkered"></i> {{ $bid->end_time->format('M d, H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="price-cell">
                                <div class="current-price">{{ $bid->formatted_current_price }}</div>
                                <div class="starting-price">Start: {{ 'RM ' . number_format($bid->starting_price, 2) }}</div>
                                @if($bid->reserve_price)
                                <div class="reserve-price">
                                    Reserve: {{ 'RM ' . number_format($bid->reserve_price, 2) }}
                                    @if($bid->reserve_met) <span class="reserve-met">✓ Met</span>
                                    @else <span class="reserve-not-met">✗ Not Met</span> @endif
                                </div>
                                @endif
                            </td>
                            <td class="bids-cell">
                                <div class="bids-count"><i class="fas fa-gavel"></i> {{ $bid->bid_count }} bids</div>
                                <div class="bid-increment">Inc: RM {{ number_format($bid->bid_increment, 2) }}</div>
                            </td>
                            <td class="time-cell">
                                @if($bid->is_active)
                                <div class="countdown-timer" data-end-time="{{ $bid->end_time->format('c') }}">
                                    <div class="timer-display">
                                        <span class="days">00</span>d <span class="hours">00</span>h <span class="minutes">00</span>m <span class="seconds">00</span>s
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" id="progress-{{ $bid->id }}"></div>
                                    </div>
                                </div>
                                @elseif($bid->has_ended)
                                <div class="ended-state">
                                    <span class="ended-badge">Ended</span>
                                    <div class="ended-time">{{ $bid->end_time->diffForHumans() }}</div>
                                </div>
                                @elseif(!$bid->has_started)
                                <div class="upcoming-state">
                                    <span class="starts-in">Starts in</span>
                                    <div class="start-countdown" data-start-time="{{ $bid->start_time->format('c') }}">
                                        {{ $bid->start_time->diffForHumans() }}
                                    </div>
                                </div>
                                @else
                                <span class="paused-badge">Paused</span>
                                @endif
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-{{ $bid->status }}">
                                    {{ ucfirst($bid->status) }}
                                </span>
                            </td>
                            <td class="winner-cell">
                                @if($bid->winner)
                                <div class="winner-info">
                                    <div class="winner-avatar">{{ substr($bid->winner->name, 0, 1) }}</div>
                                    <div class="winner-details">
                                        <strong>{{ $bid->winner->name }}</strong>
                                        <small>{{ $bid->formatted_winning_bid_amount }}</small>
                                    </div>
                                </div>
                                @elseif($bid->has_ended && $bid->bid_count > 0)
                                <div class="no-winner pending"><span>Processing...</span></div>
                                @else
                                <div class="no-winner"><span>No winner</span></div>
                                @endif
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.managebid.show', $bid) }}" class="btn-action view"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.managebid.edit', $bid) }}" class="btn-action edit"><i class="fas fa-edit"></i></a>
                                    <div class="dropdown">
                                        <button class="btn-action more"><i class="fas fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu">
                                            @if($bid->status === 'draft')
                                            <form action="{{ route('admin.managebid.start', $bid) }}" method="POST">@csrf <button class="dropdown-item">Start Bid</button></form>
                                            @endif
                                            <form action="{{ route('admin.managebid.destroy', $bid) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item text-danger" onclick="return confirm('Delete this bid?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="no-data">
                                <div class="no-data-content">
                                    <h3>No Bids Found</h3>
                                    <a href="{{ route('admin.managebid.create') }}" class="btn btn-primary">Create First Bid</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="table-summary">
                    Showing <strong>{{ $bids->firstItem() ?? 0 }}-{{ $bids->lastItem() ?? 0 }}</strong> of <strong>{{ $bids->total() }}</strong> bids
                </div>
                <div class="pagination-container">
                    {{ $bids->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="bids-view" id="gridView" style="display: none;">
        </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initBidManagement();
});

function initBidManagement() {
    // 1. COUNTDOWN TIMERS
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(timer => {
            const endStr = timer.getAttribute('data-end-time');
            if (!endStr) return;

            const endTime = new Date(endStr).getTime();
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                timer.innerHTML = '<span class="ended-badge">Ended</span>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const dEl = timer.querySelector('.days'); if(dEl) dEl.textContent = String(days).padStart(2, '0');
            const hEl = timer.querySelector('.hours'); if(hEl) hEl.textContent = String(hours).padStart(2, '0');
            const mEl = timer.querySelector('.minutes'); if(mEl) mEl.textContent = String(minutes).padStart(2, '0');
            const sEl = timer.querySelector('.seconds'); if(sEl) sEl.textContent = String(seconds).padStart(2, '0');

            const row = timer.closest('.bid-row');
            if (row) {
                const startStr = row.getAttribute('data-start-time');
                if (startStr) {
                    const startTime = new Date(startStr).getTime();
                    const totalDuration = endTime - startTime;
                    const elapsed = totalDuration - distance;
                    const progress = (elapsed / totalDuration) * 100;
                    
                    const bar = timer.querySelector('.progress-fill');
                    if (bar) bar.style.width = Math.min(Math.max(progress, 0), 100) + '%';
                }
            }
        });
    }
    setInterval(updateCountdowns, 1000);
    updateCountdowns();

    // 2. BULK ACTIONS UI
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.bid-checkbox');
    const bulkBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const deselectBtn = document.getElementById('bulkDeselectBtn');

    function updateBulkUI() {
        const checked = document.querySelectorAll('.bid-checkbox:checked');
        if (checked.length > 0) {
            bulkBar.style.display = 'flex';
            selectedCount.textContent = checked.length + ' selected';
        } else {
            bulkBar.style.display = 'none';
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkUI();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkUI));

    if(deselectBtn) {
        deselectBtn.addEventListener('click', function() {
            checkboxes.forEach(cb => cb.checked = false);
            selectAll.checked = false;
            updateBulkUI();
        });
    }
}
</script>
@endsection