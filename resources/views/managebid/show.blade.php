@extends('admin.adminbase')
@section('title', 'Bid Details - ' . $bid->product->name)

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_bid/show.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="bid-show-container">
    <!-- Header Section -->
    <div class="show-header">
        <div class="header-left">
            <div class="breadcrumb">
                <a href="{{ route('admin.managebid.index') }}" class="breadcrumb-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to Bids
                </a>
            </div>
            <h1 class="page-title">Bid Details</h1>
            <p class="page-subtitle">Complete overview of auction bid for {{ $bid->product->name }}</p>
        </div>
        <div class="header-right">
            <div class="header-actions">
                <a href="{{ route('admin.managebid.edit', $bid) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit Bid
                </a>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                        Actions
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.managebid.participants', $bid) }}" class="dropdown-item">
                            <i class="fas fa-users"></i> View Participants
                        </a>
                        @if($bid->status === 'draft')
                        <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-play"></i> Start Bid
                            </button>
                        </form>
                        @endif

                        @if($bid->status === 'active')
                        <form action="{{ route('admin.managebid.pause', $bid) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-pause"></i> Pause Bid
                            </button>
                        </form>
                        @endif

                        @if($bid->status === 'paused')
                        <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-play"></i> Resume Bid
                            </button>
                        </form>
                        @endif

                        @if(in_array($bid->status, ['active', 'paused']))
                        <form action="{{ route('admin.managebid.complete', $bid) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item" 
                                    onclick="return confirm('Are you sure you want to complete this bid?')">
                                <i class="fas fa-flag-checkered"></i> Complete Bid
                            </button>
                        </form>
                        @endif

                        @if(!$bid->winner && $bid->has_ended)
                        <a href="#" class="dropdown-item" onclick="assignWinner({{ $bid->id }})">
                            <i class="fas fa-trophy"></i> Assign Winner
                        </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <form action="{{ route('admin.managebid.destroy', $bid) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" 
                                    onclick="return confirm('Are you sure you want to delete this bid?')">
                                <i class="fas fa-trash"></i> Delete Bid
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="show-content">
        <!-- Left Column -->
        <div class="content-left">
            <!-- Product & Bid Overview -->
            <div class="overview-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Bid Overview
                    </h3>
                    <div class="bid-status">
                        <span class="status-badge status-{{ $bid->status }}">
                            <i class="status-icon"></i>
                            {{ ucfirst($bid->status) }}
                        </span>
                        <span class="bid-id">BID #{{ str_pad($bid->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="product-display">
                        <div class="product-image">
                            <img src="{{ $bid->product->main_image_url }}" alt="{{ $bid->product->name }}"
                                 onerror="this.src='{{ asset('images/default-product.png') }}'">
                            @if($bid->is_active)
                            <div class="live-badge">
                                <i class="fas fa-circle"></i>
                                LIVE
                            </div>
                            @endif
                        </div>
                        <div class="product-details">
                            <h4 class="product-name">{{ $bid->product->name }}</h4>
                            <p class="product-description">{{ $bid->product->description }}</p>
                            <div class="product-meta">
                                <div class="meta-item">
                                    <i class="fas fa-tag"></i>
                                    <span>Regular Price: <strong>RM {{ number_format($bid->product->price, 2) }}</strong></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-box"></i>
                                    <span>Stock: <strong>{{ $bid->product->stock_quantity }}</strong></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-barcode"></i>
                                    <span>SKU: <strong>{{ $bid->product->sku ?? 'N/A' }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bid Statistics -->
                    <div class="bid-stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-gavel"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $bid->bid_count }}</div>
                                <div class="stat-label">Total Bids</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $bid->formatted_current_price }}</div>
                                <div class="stat-label">Current Price</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $participants->count() }}</div>
                                <div class="stat-label">Participants</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">RM {{ number_format($bid->bid_increment, 2) }}</div>
                                <div class="stat-label">Bid Increment</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        Pricing Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="pricing-grid">
                        <div class="price-item">
                            <label>Starting Price</label>
                            <div class="price-value">{{ $bid->formatted_starting_price }}</div>
                        </div>
                        <div class="price-item">
                            <label>Current Price</label>
                            <div class="price-value current">{{ $bid->formatted_current_price }}</div>
                        </div>
                        @if($bid->reserve_price)
                        <div class="price-item">
                            <label>Reserve Price</label>
                            <div class="price-value reserve {{ $bid->reserve_met ? 'met' : 'not-met' }}">
                                RM {{ number_format($bid->reserve_price, 2) }}
                                <span class="reserve-status">
                                    {{ $bid->reserve_met ? '✓ Met' : '✗ Not Met' }}
                                </span>
                            </div>
                        </div>
                        @endif
                        @if($bid->winning_bid_amount)
                        <div class="price-item">
                            <label>Winning Bid</label>
                            <div class="price-value winning">RM {{ number_format($bid->winning_bid_amount, 2) }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bidding Activity -->
            <div class="activity-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        Bidding Activity
                    </h3>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline" id="refreshBids">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($bid->bids->count() > 0)
                    <div class="bids-timeline" id="bidsTimeline">
                        @foreach($bid->bids->sortByDesc('created_at')->take(10) as $bidItem)
                        <div class="bid-activity-item">
                            <div class="activity-avatar">
                                {{ substr($bidItem->user->name, 0, 1) }}
                            </div>
                            <div class="activity-content">
                                <div class="activity-header">
                                    <strong>{{ $bidItem->user->name }}</strong>
                                    <span class="activity-time">{{ $bidItem->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="activity-body">
                                    <span class="bid-amount">RM {{ number_format($bidItem->amount, 2) }}</span>
                                    @if($bidItem->is_auto_bid)
                                    <span class="auto-bid-badge" title="Auto-bid with max: RM {{ number_format($bidItem->max_auto_bid, 2) }}">
                                        <i class="fas fa-robot"></i> Auto-bid
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($bid->bids->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.managebid.participants', $bid) }}" class="btn btn-sm btn-outline">
                            View All {{ $bid->bids->count() }} Bids
                        </a>
                    </div>
                    @endif
                    @else
                    <div class="no-activity">
                        <i class="fas fa-gavel"></i>
                        <p>No bids placed yet</p>
                        <small>Bidding will start once the auction begins</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="content-right">
            <!-- Timer & Status Card -->
            <div class="status-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock"></i>
                        Auction Timer
                    </h3>
                </div>
                <div class="card-body">
                    @if($bid->is_active)
                    <div class="countdown-timer">
                        <div class="timer-display" data-end-time="{{ $bid->end_time->format('Y-m-d H:i:s') }}">
                            <div class="time-unit">
                                <span class="time-value days">00</span>
                                <span class="time-label">Days</span>
                            </div>
                            <div class="time-unit">
                                <span class="time-value hours">00</span>
                                <span class="time-label">Hours</span>
                            </div>
                            <div class="time-unit">
                                <span class="time-value minutes">00</span>
                                <span class="time-label">Minutes</span>
                            </div>
                            <div class="time-unit">
                                <span class="time-value seconds">00</span>
                                <span class="time-label">Seconds</span>
                            </div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" id="timerProgress"></div>
                        </div>
                        <div class="timer-meta">
                            <small>Ends at: {{ $bid->end_time->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                    @elseif($bid->has_ended)
                    <div class="ended-state">
                        <div class="ended-icon">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <h4>Auction Ended</h4>
                        <p>{{ $bid->end_time->diffForHumans() }}</p>
                        @if($bid->status !== 'completed')
                        <form action="{{ route('admin.managebid.complete', $bid) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                Complete Bid & Declare Winner
                            </button>
                        </form>
                        @endif
                    </div>
                    @elseif(!$bid->has_started)
                    <div class="upcoming-state">
                        <div class="upcoming-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Auction Starts In</h4>
                        <div class="start-countdown" data-start-time="{{ $bid->start_time->format('Y-m-d H:i:s') }}">
                            <span class="start-days">00</span>d
                            <span class="start-hours">00</span>h
                            <span class="start-minutes">00</span>m
                        </div>
                        <p class="start-time">{{ $bid->start_time->format('M d, Y H:i') }}</p>
                    </div>
                    @else
                    <div class="paused-state">
                        <div class="paused-icon">
                            <i class="fas fa-pause"></i>
                        </div>
                        <h4>Bid Paused</h4>
                        <p>This auction is currently paused</p>
                        <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                Resume Bid
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Winner Information -->
            @if($bid->winner)
            <div class="winner-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trophy"></i>
                        Winner
                    </h3>
                </div>
                <div class="card-body">
                    <div class="winner-info">
                        <div class="winner-avatar">
                            {{ substr($bid->winner->name, 0, 1) }}
                        </div>
                        <div class="winner-details">
                            <h5 class="winner-name">{{ $bid->winner->name }}</h5>
                            <div class="winner-bid">Winning Bid: <strong>{{ $bid->formatted_winning_bid_amount }}</strong></div>
                            <div class="winner-contact">
                                <small>{{ $bid->winner->email }}</small>
                                @if($bid->winner->phone)
                                <small>{{ $bid->winner->phone }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="winner-actions mt-3">
                        <button class="btn btn-outline btn-sm btn-block">
                            <i class="fas fa-envelope"></i>
                            Contact Winner
                        </button>
                    </div>
                </div>
            </div>
            @elseif($bid->has_ended && $bid->bid_count > 0)
            <div class="winner-card pending">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock"></i>
                        Winner Pending
                    </h3>
                </div>
                <div class="card-body">
                    <div class="pending-winner">
                        <i class="fas fa-hourglass-half"></i>
                        <p>Winner declaration pending</p>
                        <form action="{{ route('admin.managebid.complete', $bid) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm btn-block">
                                Declare Winner Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Bid Details -->
            <div class="details-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt"></i>
                        Bid Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="details-list">
                        <div class="detail-item">
                            <label>Start Time</label>
                            <span>{{ $bid->start_time->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>End Time</label>
                            <span>{{ $bid->end_time->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Duration</label>
                            <span>{{ $bid->start_time->diff($bid->end_time)->format('%dd %hh %im') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Auto-Extend</label>
                            <span>
                                @if($bid->auto_extend)
                                <i class="fas fa-check text-success"></i> Enabled ({{ $bid->extension_minutes }} minutes)
                                @else
                                <i class="fas fa-times text-muted"></i> Disabled
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Created</label>
                            <span>{{ $bid->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Last Updated</label>
                            <span>{{ $bid->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants Preview -->
            <div class="participants-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        Top Participants
                    </h3>
                    <a href="{{ route('admin.managebid.participants', $bid) }}" class="btn btn-sm btn-outline">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($participants->count() > 0)
                    <div class="participants-list">
                        @foreach($participants->take(5) as $participant)
                        <div class="participant-item">
                            <div class="participant-avatar">
                                {{ substr($participant->name, 0, 1) }}
                            </div>
                            <div class="participant-details">
                                <strong>{{ $participant->name }}</strong>
                                <small>{{ $participant->email }}</small>
                            </div>
                            <div class="participant-bids">
                                <span class="bid-count">{{ $participant->bids_count }} bids</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($participants->count() > 5)
                    <div class="text-center mt-3">
                        <small>and {{ $participants->count() - 5 }} more participants</small>
                    </div>
                    @endif
                    @else
                    <div class="no-participants">
                        <i class="fas fa-user-slash"></i>
                        <p>No participants yet</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Terms & Conditions -->
            @if($bid->terms_conditions)
            <div class="terms-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-contract"></i>
                        Terms & Conditions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="terms-content">
                        {{ $bid->terms_conditions }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Assign Winner Modal -->
<div class="modal fade" id="assignWinnerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Winner</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignWinnerForm">
                    @csrf
                    <div class="form-group">
                        <label for="winner_id">Select Winner</label>
                        <select name="winner_id" id="winner_id" class="form-control" required>
                            <option value="">Choose a participant...</option>
                            @foreach($participants as $participant)
                            <option value="{{ $participant->id }}">{{ $participant->name }} ({{ $participant->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="winning_bid_amount">Winning Bid Amount (RM)</label>
                        <input type="number" name="winning_bid_amount" id="winning_bid_amount" 
                               class="form-control" step="0.01" min="{{ $bid->starting_price }}" 
                               required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitWinnerAssignment()">Assign Winner</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize countdown timers
    initCountdownTimers();
    
    // Initialize real-time updates for active bids
    if ({{ $bid->is_active ? 'true' : 'false' }}) {
        initRealTimeUpdates();
    }

    // Refresh bids button
    document.getElementById('refreshBids').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    });
});

function initCountdownTimers() {
    // Main countdown timer for active bids
    const timerDisplay = document.querySelector('.countdown-timer .timer-display');
    if (timerDisplay) {
        const endTime = new Date(timerDisplay.getAttribute('data-end-time')).getTime();
        
        function updateTimer() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                // Timer ended, reload page to update status
                window.location.reload();
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Update display
            timerDisplay.querySelector('.days').textContent = days.toString().padStart(2, '0');
            timerDisplay.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
            timerDisplay.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
            timerDisplay.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
            
            // Update progress bar
            const totalDuration = endTime - new Date('{{ $bid->start_time->format('Y-m-d H:i:s') }}').getTime();
            const elapsed = totalDuration - distance;
            const progress = (elapsed / totalDuration) * 100;
            document.getElementById('timerProgress').style.width = `${Math.min(progress, 100)}%`;
        }
        
        // Update immediately and every second
        updateTimer();
        setInterval(updateTimer, 1000);
    }
    
    // Start countdown for upcoming bids
    const startCountdown = document.querySelector('.start-countdown');
    if (startCountdown) {
        const startTime = new Date(startCountdown.getAttribute('data-start-time')).getTime();
        
        function updateStartCountdown() {
            const now = new Date().getTime();
            const distance = startTime - now;
            
            if (distance < 0) {
                window.location.reload();
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            
            startCountdown.querySelector('.start-days').textContent = days.toString().padStart(2, '0');
            startCountdown.querySelector('.start-hours').textContent = hours.toString().padStart(2, '0');
            startCountdown.querySelector('.start-minutes').textContent = minutes.toString().padStart(2, '0');
        }
        
        updateStartCountdown();
        setInterval(updateStartCountdown, 60000); // Update every minute
    }
}

function initRealTimeUpdates() {
    // Simulate real-time bid updates (in a real app, you'd use WebSockets)
    setInterval(() => {
        // This would be replaced with actual WebSocket or AJAX calls
        console.log('Checking for new bids...');
    }, 10000); // Check every 10 seconds
}

function assignWinner(bidId) {
    $('#assignWinnerModal').modal('show');
}

function submitWinnerAssignment() {
    const form = document.getElementById('assignWinnerForm');
    const formData = new FormData(form);
    
    fetch(`/admin/managebid/${ {{ $bid->id }} }/assign-winner`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#assignWinnerModal').modal('hide');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while assigning winner.');
    });
}

// Live bid simulation (for demo purposes)
function simulateNewBid() {
    const bidsTimeline = document.getElementById('bidsTimeline');
    if (bidsTimeline && {{ $bid->is_active ? 'true' : 'false' }}) {
        // This would be replaced with actual WebSocket data
        const newBid = {
            user: { name: 'Demo User' },
            amount: (parseFloat({{ $bid->current_price }}) + parseFloat({{ $bid->bid_increment }})).toFixed(2),
            created_at: 'Just now',
            is_auto_bid: false
        };
        
        const bidElement = document.createElement('div');
        bidElement.className = 'bid-activity-item new-bid';
        bidElement.innerHTML = `
            <div class="activity-avatar">${newBid.user.name.charAt(0)}</div>
            <div class="activity-content">
                <div class="activity-header">
                    <strong>${newBid.user.name}</strong>
                    <span class="activity-time">${newBid.created_at}</span>
                </div>
                <div class="activity-body">
                    <span class="bid-amount">RM ${newBid.amount}</span>
                </div>
            </div>
        `;
        
        bidsTimeline.insertBefore(bidElement, bidsTimeline.firstChild);
        
        // Add animation
        setTimeout(() => {
            bidElement.classList.remove('new-bid');
        }, 1000);
    }
}

// Demo: Simulate a new bid every 30 seconds for active auctions
if ({{ $bid->is_active ? 'true' : 'false' }}) {
    setInterval(simulateNewBid, 30000);
}
</script>
@endsection