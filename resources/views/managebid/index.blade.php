@extends('admin.adminbase')
@section('title', 'Manage Bids')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_bid/index.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="bids-management-container">
    <!-- Header Section -->
    <div class="bids-header">
        <div class="header-left">
            <h1 class="page-title">Manage Bids</h1>
            <p class="page-subtitle">Monitor and manage all auction bids</p>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.managebid.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create New Bid
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-play-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $bids->where('status', 'active')->where('start_time', '<=', now())->where('end_time', '>', now())->count() }}</h3>
                <p class="stat-label">Active Bids</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon upcoming">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $bids->where('status', 'active')->where('start_time', '>', now())->count() }}</h3>
                <p class="stat-label">Upcoming</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon completed">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $bids->where('status', 'completed')->count() }}</h3>
                <p class="stat-label">Completed</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft">
                <i class="fas fa-edit"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $bids->where('status', 'draft')->count() }}</h3>
                <p class="stat-label">Drafts</p>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-left">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search bids by product name...">
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
        </div>
        <div class="filters-right">
            <button class="btn btn-secondary" id="exportBtn">
                <i class="fas fa-download"></i>
                Export
            </button>
            <button class="btn btn-secondary" id="refreshBtn">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Bids Table -->
    <div class="bids-table-container">
        <div class="table-responsive">
            <table class="bids-table">
                <thead>
                    <tr>
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
                    <tr class="bid-row" data-status="{{ $bid->status }}" data-search="{{ strtolower($bid->product->name) }}">
                        <td class="product-cell">
                            <div class="product-info">
                                <div class="product-image">
                                    <img src="{{ $bid->product->main_image_url }}" alt="{{ $bid->product->name }}">
                                </div>
                                <div class="product-details">
                                    <h4 class="product-name">{{ $bid->product->name }}</h4>
                                    <p class="product-sku">SKU: {{ $bid->product->sku ?? 'N/A' }}</p>
                                    <div class="bid-dates">
                                        <small>Start: {{ $bid->start_time->format('M d, Y H:i') }}</small>
                                        <small>End: {{ $bid->end_time->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="price-cell">
                            <div class="current-price">{{ $bid->formatted_current_price }}</div>
                            @if($bid->reserve_price)
                            <div class="reserve-price">
                                Reserve: {{ 'RM ' . number_format($bid->reserve_price, 2) }}
                                @if($bid->reserve_met)
                                <span class="reserve-met">✓ Met</span>
                                @else
                                <span class="reserve-not-met">Not Met</span>
                                @endif
                            </div>
                            @endif
                        </td>
                        <td class="bids-cell">
                            <div class="bids-count">{{ $bid->bid_count }}</div>
                            <div class="bid-increment">Increment: RM {{ number_format($bid->bid_increment, 2) }}</div>
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
                                    <div class="progress-fill" style="width: {{ $bid->time_remaining->days > 0 ? 100 - (($bid->time_remaining->days / 7) * 100) : 100 - (($bid->time_remaining->h * 60 + $bid->time_remaining->i) / (24 * 60) * 100) }}%"></div>
                                </div>
                            </div>
                            @elseif($bid->has_ended)
                            <span class="ended-badge">Ended</span>
                            @elseif(!$bid->has_started)
                            <span class="starts-in">Starts in {{ $bid->start_time->diffForHumans() }}</span>
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
                                <strong>{{ $bid->winner->name }}</strong>
                                <small>{{ $bid->formatted_winning_bid_amount ?? 'N/A' }}</small>
                            </div>
                            @else
                            <span class="no-winner">No winner yet</span>
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
                                    <button type="submit" class="btn-action complete" title="Complete Bid" onclick="return confirm('Are you sure you want to complete this bid?')">
                                        <i class="fas fa-flag-checkered"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.managebid.destroy', $bid) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Delete Bid" onclick="return confirm('Are you sure you want to delete this bid?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="no-data">
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

        <!-- Pagination -->
        @if($bids->hasPages())
        <div class="pagination-container">
            {{ $bids->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Quick Actions Modal -->
<div class="modal fade" id="quickActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Actions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="quick-actions-grid">
                    <button class="quick-action-btn" id="bulkStartBtn">
                        <i class="fas fa-play"></i>
                        Start Selected
                    </button>
                    <button class="quick-action-btn" id="bulkPauseBtn">
                        <i class="fas fa-pause"></i>
                        Pause Selected
                    </button>
                    <button class="quick-action-btn" id="bulkCompleteBtn">
                        <i class="fas fa-flag-checkered"></i>
                        Complete Selected
                    </button>
                    <button class="quick-action-btn" id="bulkDeleteBtn">
                        <i class="fas fa-trash"></i>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const bidRows = document.querySelectorAll('.bid-row');

    function filterBids() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        bidRows.forEach(row => {
            const searchText = row.getAttribute('data-search');
            const rowStatus = row.getAttribute('data-status');
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

            row.style.display = shouldShow ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterBids);
    statusFilter.addEventListener('change', filterBids);

    // Countdown timers
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(timer => {
            const endTime = new Date(timer.getAttribute('data-end-time')).getTime();
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

            timer.querySelector('.days').textContent = days.toString().padStart(2, '0');
            timer.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
            timer.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
            timer.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
        });
    }

    // Update countdowns every second
    setInterval(updateCountdowns, 1000);
    updateCountdowns();

    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        window.location.reload();
    });

    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        // Implement export logic here
        alert('Export functionality would be implemented here');
    });
});
</script>
@endsection