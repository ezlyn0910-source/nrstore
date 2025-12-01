@extends('admin.adminbase')
@section('title', 'Manage Bids')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_bid/index.css', 'resources/js/app.js'])
@endsection

@section('content')
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