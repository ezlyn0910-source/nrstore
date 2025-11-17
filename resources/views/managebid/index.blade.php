@extends('admin.adminbase')
@section('title', 'Manage Bids')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_bid/index.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="bids-management-container">
    <!-- Header Section -->
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
                <button class="btn btn-secondary" id="bulkActionsBtn">
                    <i class="fas fa-layer-group"></i>
                    Bulk Actions
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total'] }}</h3>
                <p class="stat-label">Total Bids</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['active'] }}</h3>
                <p class="stat-label">Active Bids</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon upcoming">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['upcoming'] }}</h3>
                <p class="stat-label">Upcoming</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon completed">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['completed'] }}</h3>
                <p class="stat-label">Completed</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['draft'] }}</h3>
                <p class="stat-label">Draft</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="quick-stats-bar">
        <div class="quick-stat">
            <i class="fas fa-users"></i>
            <span>Total Participants: <strong id="totalParticipants">0</strong></span>
        </div>
        <div class="quick-stat">
            <i class="fas fa-money-bill-wave"></i>
            <span>Total Bid Value: <strong id="totalBidValue">RM 0.00</strong></span>
        </div>
        <div class="quick-stat">
            <i class="fas fa-trophy"></i>
            <span>Active Winners: <strong id="activeWinners">0</strong></span>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-left">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search bids by product name, winner, or status...">
                <button class="search-clear" id="searchClear" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="upcoming">Upcoming</option>
                <option value="draft">Draft</option>
                <option value="paused">Paused</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select id="timeFilter" class="filter-select">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="tomorrow">Tomorrow</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="past">Past Bids</option>
            </select>
        </div>
        <div class="filters-right">
            <div class="view-controls">
                <button class="view-btn active" data-view="table" title="Table View">
                    <i class="fas fa-table"></i>
                </button>
                <button class="view-btn" data-view="grid" title="Grid View">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>
            <button class="btn btn-secondary" id="exportBtn">
                <i class="fas fa-download"></i>
                Export CSV
            </button>
            <button class="btn btn-secondary" id="refreshBtn">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Bulk Actions Bar (Hidden by default) -->
    <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
        <div class="bulk-info">
            <span id="selectedCount">0 bids selected</span>
        </div>
        <div class="bulk-buttons">
            <button class="btn btn-success btn-sm" id="bulkStartBtn">
                <i class="fas fa-play"></i> Start
            </button>
            <button class="btn btn-warning btn-sm" id="bulkPauseBtn">
                <i class="fas fa-pause"></i> Pause
            </button>
            <button class="btn btn-primary btn-sm" id="bulkCompleteBtn">
                <i class="fas fa-flag-checkered"></i> Complete
            </button>
            <button class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                <i class="fas fa-trash"></i> Delete
            </button>
            <button class="btn btn-secondary btn-sm" id="bulkDeselectBtn">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>

    <!-- Bids Table View -->
    <div class="bids-view" id="tableView">
        <div class="bids-table-container">
            <div class="table-responsive">
                <table class="bids-table">
                    <thead>
                        <tr>
                            <th class="select-col">
                                <input type="checkbox" id="selectAllCheckbox">
                            </th>
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
                            data-status="{{ $bid->status }}" 
                            data-search="{{ strtolower($bid->product->name) }}"
                            data-id="{{ $bid->id }}"
                            data-start-time="{{ $bid->start_time->format('Y-m-d H:i:s') }}"
                            data-end-time="{{ $bid->end_time->format('Y-m-d H:i:s') }}">
                            <td class="select-cell">
                                <input type="checkbox" class="bid-checkbox" value="{{ $bid->id }}">
                            </td>
                            <td class="product-cell">
                                <div class="product-info">
                                    <div class="product-image">
                                        <img src="{{ $bid->product->main_image_url }}" alt="{{ $bid->product->name }}" 
                                             onerror="this.src='{{ asset('images/default-product.png') }}'">
                                        @if($bid->is_active)
                                        <div class="live-indicator" title="Live Auction">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="product-details">
                                        <h4 class="product-name">{{ $bid->product->name }}</h4>
                                        <p class="product-sku">SKU: {{ $bid->product->sku ?? 'N/A' }}</p>
                                        <div class="bid-dates">
                                            <small>
                                                <i class="fas fa-play"></i> 
                                                {{ $bid->start_time->format('M d, Y H:i') }}
                                            </small>
                                            <small>
                                                <i class="fas fa-flag-checkered"></i> 
                                                {{ $bid->end_time->format('M d, Y H:i') }}
                                            </small>
                                        </div>
                                        @if($bid->auto_extend)
                                        <div class="auto-extend-badge">
                                            <i class="fas fa-history"></i> Auto-extend
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="price-cell">
                                <div class="current-price">{{ $bid->formatted_current_price }}</div>
                                <div class="starting-price">
                                    Start: {{ 'RM ' . number_format($bid->starting_price, 2) }}
                                </div>
                                @if($bid->reserve_price)
                                <div class="reserve-price">
                                    Reserve: {{ 'RM ' . number_format($bid->reserve_price, 2) }}
                                    @if($bid->reserve_met)
                                    <span class="reserve-met">✓ Met</span>
                                    @else
                                    <span class="reserve-not-met">✗ Not Met</span>
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td class="bids-cell">
                                <div class="bids-count">
                                    <i class="fas fa-gavel"></i>
                                    {{ $bid->bid_count }} bids
                                </div>
                                <div class="bid-increment">
                                    Increment: RM {{ number_format($bid->bid_increment, 2) }}
                                </div>
                                @if($bid->bid_count > 0)
                                <div class="last-bid-time">
                                    Last bid: {{ $bid->bids->first() ? $bid->bids->first()->created_at->diffForHumans() : 'N/A' }}
                                </div>
                                @endif
                            </td>
                            <td class="time-cell">
                                @if($bid->is_active)
                                <div class="countdown-timer" data-end-time="{{ $bid->end_time->format('Y-m-d H:i:s') }}">
                                    <div class="timer-display">
                                        <span class="days">00</span>d
                                        <span class="hours">00</span>h
                                        <span class="minutes">00</span>m
                                        <span class="seconds">00</span>s
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" id="progress-{{ $bid->id }}"></div>
                                    </div>
                                    <div class="timer-label">Time Remaining</div>
                                </div>
                                @elseif($bid->has_ended)
                                <div class="ended-state">
                                    <span class="ended-badge">Ended</span>
                                    <div class="ended-time">
                                        {{ $bid->end_time->diffForHumans() }}
                                    </div>
                                </div>
                                @elseif(!$bid->has_started)
                                <div class="upcoming-state">
                                    <span class="starts-in">Starts in</span>
                                    <div class="start-countdown" data-start-time="{{ $bid->start_time->format('Y-m-d H:i:s') }}">
                                        <span class="start-days">00</span>d
                                        <span class="start-hours">00</span>h
                                    </div>
                                </div>
                                @else
                                <span class="paused-badge">Paused</span>
                                @endif
                            </td>
                            <td class="status-cell">
                                <span class="status-badge status-{{ $bid->status }}">
                                    <i class="status-icon"></i>
                                    {{ ucfirst($bid->status) }}
                                </span>
                                @if($bid->is_active)
                                <div class="status-meta">
                                    <small>{{ $bid->bid_count }} bids</small>
                                </div>
                                @endif
                            </td>
                            <td class="winner-cell">
                                @if($bid->winner)
                                <div class="winner-info">
                                    <div class="winner-avatar">
                                        {{ substr($bid->winner->name, 0, 1) }}
                                    </div>
                                    <div class="winner-details">
                                        <strong>{{ $bid->winner->name }}</strong>
                                        <small>{{ $bid->formatted_winning_bid_amount ?? 'N/A' }}</small>
                                        <div class="winner-contact">
                                            <small>{{ $bid->winner->email }}</small>
                                        </div>
                                    </div>
                                </div>
                                @elseif($bid->has_ended && $bid->bid_count > 0)
                                <div class="no-winner pending">
                                    <i class="fas fa-clock"></i>
                                    <span>Processing winner...</span>
                                </div>
                                @else
                                <div class="no-winner">
                                    <i class="fas fa-user-slash"></i>
                                    <span>No winner yet</span>
                                </div>
                                @endif
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.managebid.show', $bid) }}" class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.managebid.edit', $bid) }}" class="btn-action edit" title="Edit Bid">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Status-specific actions -->
                                    @if($bid->status === 'draft')
                                    <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action start" title="Start Bid">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($bid->status === 'active')
                                    <form action="{{ route('admin.managebid.pause', $bid) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action pause" title="Pause Bid">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($bid->status === 'paused')
                                    <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action start" title="Resume Bid">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if(in_array($bid->status, ['active', 'paused']))
                                    <form action="{{ route('admin.managebid.complete', $bid) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action complete" title="Complete Bid" 
                                                onclick="return confirm('Are you sure you want to complete this bid? This will determine the winner.')">
                                            <i class="fas fa-flag-checkered"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Quick actions dropdown -->
                                    <div class="dropdown">
                                        <button class="btn-action more" title="More Actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('admin.managebid.participants', $bid) }}" class="dropdown-item">
                                                <i class="fas fa-users"></i> View Participants
                                            </a>
                                            <a href="#" class="dropdown-item" onclick="duplicateBid({{ $bid->id }})">
                                                <i class="fas fa-copy"></i> Duplicate Bid
                                            </a>
                                            @if($bid->has_ended && !$bid->winner)
                                            <a href="#" class="dropdown-item" onclick="assignWinner({{ $bid->id }})">
                                                <i class="fas fa-trophy"></i> Assign Winner
                                            </a>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('admin.managebid.destroy', $bid) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this bid? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i> Delete Bid
                                                </button>
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
                                    <i class="fas fa-gavel"></i>
                                    <h3>No Bids Found</h3>
                                    <p>Get started by creating your first bid auction.</p>
                                    <a href="{{ route('admin.managebid.create') }}" class="btn btn-primary">
                                        Create First Bid
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="table-footer">
                <div class="table-summary">
                    Showing <strong>{{ $bids->firstItem() ?? 0 }}-{{ $bids->lastItem() ?? 0 }}</strong> 
                    of <strong>{{ $bids->total() }}</strong> bids
                </div>
                
                <!-- Pagination -->
                @if($bids->hasPages())
                <div class="pagination-container">
                    {{ $bids->onEachSide(1)->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bids Grid View (Hidden by default) -->
    <div class="bids-view" id="gridView" style="display: none;">
        <div class="bids-grid-container">
            <div class="bids-grid">
                @forelse($bids as $bid)
                <div class="bid-card" data-status="{{ $bid->status }}" data-id="{{ $bid->id }}">
                    <div class="card-header">
                        <div class="card-image">
                            <img src="{{ $bid->product->main_image_url }}" alt="{{ $bid->product->name }}"
                                 onerror="this.src='{{ asset('images/default-product.png') }}'">
                            @if($bid->is_active)
                            <div class="live-badge">LIVE</div>
                            @endif
                            <div class="card-actions">
                                <input type="checkbox" class="bid-checkbox" value="{{ $bid->id }}">
                            </div>
                        </div>
                        <div class="card-status">
                            <span class="status-badge status-{{ $bid->status }}">
                                {{ ucfirst($bid->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="product-name">{{ $bid->product->name }}</h4>
                        <div class="price-info">
                            <div class="current-price">{{ $bid->formatted_current_price }}</div>
                            <div class="starting-price">Start: RM {{ number_format($bid->starting_price, 2) }}</div>
                        </div>
                        <div class="bid-stats">
                            <div class="stat">
                                <i class="fas fa-gavel"></i>
                                <span>{{ $bid->bid_count }} bids</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span class="grid-timer" data-end-time="{{ $bid->end_time->format('Y-m-d H:i:s') }}">
                                    @if($bid->is_active)
                                    <span class="days">00</span>d <span class="hours">00</span>h
                                    @elseif($bid->has_ended)
                                    Ended
                                    @else
                                    Starts in <span class="start-days">00</span>d
                                    @endif
                                </span>
                            </div>
                        </div>
                        @if($bid->winner)
                        <div class="winner-info">
                            <div class="winner-avatar small">
                                {{ substr($bid->winner->name, 0, 1) }}
                            </div>
                            <span class="winner-name">{{ $bid->winner->name }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="card-actions">
                            <a href="{{ route('admin.managebid.show', $bid) }}" class="btn-action view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.managebid.edit', $bid) }}" class="btn-action edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($bid->status === 'draft')
                            <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-action start" title="Start">
                                    <i class="fas fa-play"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-data-grid">
                    <i class="fas fa-gavel"></i>
                    <h3>No Bids Found</h3>
                    <p>Get started by creating your first bid auction.</p>
                    <a href="{{ route('admin.managebid.create') }}" class="btn btn-primary">
                        Create First Bid
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Select bids to perform bulk actions:</p>
                <div class="bulk-actions-grid">
                    <button class="bulk-action-btn" data-action="start">
                        <i class="fas fa-play"></i>
                        Start Selected
                    </button>
                    <button class="bulk-action-btn" data-action="pause">
                        <i class="fas fa-pause"></i>
                        Pause Selected
                    </button>
                    <button class="bulk-action-btn" data-action="complete">
                        <i class="fas fa-flag-checkered"></i>
                        Complete Selected
                    </button>
                    <button class="bulk-action-btn" data-action="delete">
                        <i class="fas fa-trash"></i>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Modal -->
<div class="modal fade" id="quickStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bid Statistics</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="stats-overview">
                    <div class="stat-item">
                        <div class="stat-value" id="modalTotalBids">0</div>
                        <div class="stat-label">Total Bids</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="modalTotalValue">RM 0.00</div>
                        <div class="stat-label">Total Value</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="modalAvgBids">0</div>
                        <div class="stat-label">Avg Bids/Auction</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="modalSuccessRate">0%</div>
                        <div class="stat-label">Success Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize enhanced bid management system
    initBidManagement();
});

function initBidManagement() {
    // Core elements
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    const statusFilter = document.getElementById('statusFilter');
    const timeFilter = document.getElementById('timeFilter');
    const bidRows = document.querySelectorAll('.bid-row');
    const bidCards = document.querySelectorAll('.bid-card');
    const viewButtons = document.querySelectorAll('.view-btn');
    const tableView = document.getElementById('tableView');
    const gridView = document.getElementById('gridView');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bidCheckboxes = document.querySelectorAll('.bid-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const refreshBtn = document.getElementById('refreshBtn');
    const exportBtn = document.getElementById('exportBtn');
    const bulkActionsBtn = document.getElementById('bulkActionsBtn');

    // Initialize quick stats
    updateQuickStats();
    
    // Initialize countdown timers
    initCountdownTimers();
    
    // Initialize view controls
    initViewControls();
    
    // Initialize bulk actions
    initBulkActions();
    
    // Initialize filters
    initFilters();

    // Search functionality with debouncing
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterBids();
            searchClear.style.display = this.value ? 'block' : 'none';
        }, 300);
    });

    searchClear.addEventListener('click', function() {
        searchInput.value = '';
        searchClear.style.display = 'none';
        filterBids();
        searchInput.focus();
    });

    // Filter functionality
    function filterBids() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const timeValue = timeFilter.value;
        const now = new Date();

        bidRows.forEach(row => {
            const searchText = row.getAttribute('data-search');
            const rowStatus = row.getAttribute('data-status');
            const startTime = new Date(row.getAttribute('data-start-time'));
            const endTime = new Date(row.getAttribute('data-end-time'));
            let shouldShow = true;

            // Search filter
            if (searchTerm && !searchText.includes(searchTerm)) {
                shouldShow = false;
            }

            // Status filter
            if (statusValue) {
                if (statusValue === 'active' && !row.classList.contains('status-active')) {
                    shouldShow = false;
                } else if (statusValue === 'upcoming' && rowStatus !== 'active') {
                    shouldShow = false;
                } else if (statusValue !== rowStatus) {
                    shouldShow = false;
                }
            }

            // Time filter
            if (timeValue) {
                switch(timeValue) {
                    case 'today':
                        shouldShow = shouldShow && isToday(startTime);
                        break;
                    case 'tomorrow':
                        shouldShow = shouldShow && isTomorrow(startTime);
                        break;
                    case 'week':
                        shouldShow = shouldShow && isThisWeek(startTime);
                        break;
                    case 'month':
                        shouldShow = shouldShow && isThisMonth(startTime);
                        break;
                    case 'past':
                        shouldShow = shouldShow && endTime < now;
                        break;
                }
            }

            row.style.display = shouldShow ? '' : 'none';
        });

        // Also filter grid view
        filterGridView();
        updateQuickStats();
    }

    // Date helper functions
    function isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }

    function isTomorrow(date) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        return date.toDateString() === tomorrow.toDateString();
    }

    function isThisWeek(date) {
        const now = new Date();
        const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay()));
        const endOfWeek = new Date(now.setDate(now.getDate() + 6));
        return date >= startOfWeek && date <= endOfWeek;
    }

    function isThisMonth(date) {
        const now = new Date();
        return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
    }

    statusFilter.addEventListener('change', filterBids);
    timeFilter.addEventListener('change', filterBids);

    // Countdown timers with progress bars
    function initCountdownTimers() {
        function updateCountdowns() {
            document.querySelectorAll('.countdown-timer').forEach(timer => {
                const endTime = new Date(timer.getAttribute('data-end-time')).getTime();
                const now = new Date().getTime();
                const distance = endTime - now;
                const bidId = timer.closest('.bid-row').getAttribute('data-id');

                if (distance < 0) {
                    timer.innerHTML = '<span class="ended-badge">Ended</span>';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timer.querySelector('.days').textContent = days.toString().padStart(2, '0');
                timer.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
                timer.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
                timer.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');

                // Update progress bar
                const totalDuration = endTime - new Date(timer.closest('.bid-row').getAttribute('data-start-time')).getTime();
                const elapsed = totalDuration - distance;
                const progress = (elapsed / totalDuration) * 100;
                const progressFill = document.getElementById(`progress-${bidId}`);
                if (progressFill) {
                    progressFill.style.width = `${Math.min(progress, 100)}%`;
                }
            });

            // Update grid view timers
            document.querySelectorAll('.grid-timer').forEach(timer => {
                const endTime = new Date(timer.getAttribute('data-end-time')).getTime();
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    timer.innerHTML = 'Ended';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

                timer.querySelector('.days').textContent = days.toString().padStart(2, '0');
                timer.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
            });
        }

        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    }

    // View controls
    function initViewControls() {
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                
                // Update active button
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Show/hide views
                tableView.style.display = view === 'table' ? 'block' : 'none';
                gridView.style.display = view === 'grid' ? 'block' : 'none';
                
                // Store preference
                localStorage.setItem('bidViewPreference', view);
            });
        });

        // Load saved view preference
        const savedView = localStorage.getItem('bidViewPreference') || 'table';
        const savedViewBtn = document.querySelector(`[data-view="${savedView}"]`);
        if (savedViewBtn) {
            savedViewBtn.click();
        }
    }

    // Bulk actions
    function initBulkActions() {
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            bidCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateBulkActionsBar();
        });

        // Individual checkbox changes
        bidCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionsBar);
        });

        // Bulk actions bar
        function updateBulkActionsBar() {
            const selectedBids = Array.from(bidCheckboxes).filter(cb => cb.checked);
            const count = selectedBids.length;
            
            if (count > 0) {
                selectedCount.textContent = `${count} bid${count > 1 ? 's' : ''} selected`;
                bulkActionsBar.style.display = 'flex';
                
                // Update select all checkbox state
                selectAllCheckbox.checked = count === bidCheckboxes.length;
                selectAllCheckbox.indeterminate = count > 0 && count < bidCheckboxes.length;
            } else {
                bulkActionsBar.style.display = 'none';
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        // Bulk action buttons
        document.getElementById('bulkStartBtn').addEventListener('click', () => performBulkAction('start'));
        document.getElementById('bulkPauseBtn').addEventListener('click', () => performBulkAction('pause'));
        document.getElementById('bulkCompleteBtn').addEventListener('click', () => performBulkAction('complete'));
        document.getElementById('bulkDeleteBtn').addEventListener('click', () => performBulkAction('delete'));
        document.getElementById('bulkDeselectBtn').addEventListener('click', clearSelection);

        function clearSelection() {
            bidCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulkActionsBar();
        }

        function performBulkAction(action) {
            const selectedBids = Array.from(bidCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedBids.length === 0) {
                alert('Please select at least one bid.');
                return;
            }

            if (confirm(`Are you sure you want to ${action} ${selectedBids.length} bid(s)?`)) {
                // Implement bulk action API calls here
                console.log(`Performing ${action} on bids:`, selectedBids);
                
                // For now, just show a message
                alert(`Bulk ${action} action would be performed on ${selectedBids.length} bid(s).`);
                
                // Clear selection after action
                clearSelection();
            }
        }
    }

    // Quick stats
    function updateQuickStats() {
        // Calculate real-time stats from visible bids
        const visibleBids = Array.from(bidRows).filter(row => row.style.display !== 'none');
        const totalBidValue = visibleBids.reduce((sum, row) => {
            const priceText = row.querySelector('.current-price')?.textContent || 'RM 0.00';
            const price = parseFloat(priceText.replace('RM', '').replace(',', '').trim()) || 0;
            return sum + price;
        }, 0);

        const totalParticipants = visibleBids.reduce((sum, row) => {
            const bidCount = parseInt(row.querySelector('.bids-count')?.textContent || 0);
            return sum + bidCount;
        }, 0);

        const activeWinners = visibleBids.filter(row => {
            return row.querySelector('.winner-info') && row.getAttribute('data-status') === 'completed';
        }).length;

        document.getElementById('totalParticipants').textContent = totalParticipants;
        document.getElementById('totalBidValue').textContent = `RM ${totalBidValue.toLocaleString('en-MY', {minimumFractionDigits: 2})}`;
        document.getElementById('activeWinners').textContent = activeWinners;
    }

    // Grid view filtering
    function filterGridView() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const timeValue = timeFilter.value;
        const now = new Date();

        bidCards.forEach(card => {
            const searchText = card.querySelector('.product-name').textContent.toLowerCase();
            const rowStatus = card.getAttribute('data-status');
            const startTime = new Date(card.getAttribute('data-start-time'));
            let shouldShow = true;

            // Search filter
            if (searchTerm && !searchText.includes(searchTerm)) {
                shouldShow = false;
            }

            // Status filter
            if (statusValue && statusValue !== rowStatus) {
                shouldShow = false;
            }

            // Time filter
            if (timeValue) {
                switch(timeValue) {
                    case 'today':
                        shouldShow = shouldShow && isToday(startTime);
                        break;
                    case 'tomorrow':
                        shouldShow = shouldShow && isTomorrow(startTime);
                        break;
                    case 'week':
                        shouldShow = shouldShow && isThisWeek(startTime);
                        break;
                    case 'month':
                        shouldShow = shouldShow && isThisMonth(startTime);
                        break;
                    case 'past':
                        shouldShow = shouldShow && new Date(card.getAttribute('data-end-time')) < now;
                        break;
                }
            }

            card.style.display = shouldShow ? 'block' : 'none';
        });
    }

    // Export functionality
    exportBtn.addEventListener('click', function() {
        // Implement CSV export
        const visibleBids = Array.from(bidRows).filter(row => row.style.display !== 'none');
        const exportData = visibleBids.map(row => ({
            product: row.querySelector('.product-name').textContent,
            currentPrice: row.querySelector('.current-price').textContent,
            bids: row.querySelector('.bids-count').textContent,
            status: row.getAttribute('data-status'),
            winner: row.querySelector('.winner-info strong')?.textContent || 'No winner'
        }));

        console.log('Exporting data:', exportData);
        alert(`Preparing to export ${exportData.length} bids as CSV...`);
    });

    // Refresh functionality
    refreshBtn.addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    });

    // Bulk actions modal
    bulkActionsBtn.addEventListener('click', function() {
        $('#bulkActionsModal').modal('show');
    });
}

// Additional utility functions
function duplicateBid(bidId) {
    if (confirm('Duplicate this bid? A new draft will be created with the same settings.')) {
        // Implement duplicate functionality
        console.log('Duplicating bid:', bidId);
        alert('Bid duplication feature would be implemented here.');
    }
}

function assignWinner(bidId) {
    if (confirm('Manually assign winner for this bid?')) {
        // Implement winner assignment
        console.log('Assigning winner for bid:', bidId);
        alert('Winner assignment feature would be implemented here.');
    }
}

function initFilters() {
    // Restore filter states from localStorage
    const savedStatus = localStorage.getItem('bidStatusFilter');
    const savedTime = localStorage.getItem('bidTimeFilter');
    
    if (savedStatus) {
        document.getElementById('statusFilter').value = savedStatus;
    }
    if (savedTime) {
        document.getElementById('timeFilter').value = savedTime;
    }
    
    // Save filter states
    document.getElementById('statusFilter').addEventListener('change', function() {
        localStorage.setItem('bidStatusFilter', this.value);
    });
    
    document.getElementById('timeFilter').addEventListener('change', function() {
        localStorage.setItem('bidTimeFilter', this.value);
    });
}
</script>
@endsection