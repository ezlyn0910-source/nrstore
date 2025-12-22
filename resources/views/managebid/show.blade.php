@extends('admin.adminbase')
@section('title', 'Bid Details - ' . $bid->product->name)

@section('content')
<style>
/* Bid Show Page Styles */
.bid-show-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    background-color: var(--light-bone);
    min-height: 100vh;
}

/* Header Section */
.show-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding: 25px;
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 36, 18, 0.1);
    border: 1px solid var(--border-light);
}

.breadcrumb {
    margin-bottom: 15px;
}

.breadcrumb-link {
    color: var(--primary-green);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.breadcrumb-link:hover {
    color: var(--primary-dark);
    transform: translateX(-5px);
}

.header-left .page-title {
    color: var(--primary-dark);
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-green));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-left .page-subtitle {
    color: var(--light-text);
    font-size: 1.1rem;
    margin: 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* Main Content Grid */
.show-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
}

.content-left {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.content-right {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

/* Card Styles */
.overview-card,
.pricing-card,
.activity-card,
.status-card,
.winner-card,
.details-card,
.participants-card,
.terms-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 6px 25px rgba(26, 36, 18, 0.08);
    border: 1px solid var(--border-light);
    overflow: hidden;
    padding: 1.2rem;
}

.card-header {
    padding: 25px 25px 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.card-title {
    color: var(--primary-dark);
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-title i {
    color: var(--accent-gold);
}

.card-body {
    padding: 0 25px 25px;
}

/* Bid Status */
.bid-status {
    display: flex;
    align-items: center;
    gap: 12px;
}

.bid-id {
    background: var(--light-bone);
    color: var(--light-text);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid var(--border-light);
}

/* Product Display */
.product-display {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    margin-bottom: 25px;
}

.product-image {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.live-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #dc3545, #e83e8c);
    color: var(--white);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 4px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.product-details {
    flex: 1;
}

.product-name {
    color: var(--primary-dark);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    line-height: 1.3;
}

.product-description {
    color: var(--light-text);
    margin: 0 0 15px 0;
    line-height: 1.5;
}

.product-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--primary-dark);
    font-size: 0.9rem;
}

.meta-item i {
    color: var(--accent-gold);
    width: 16px;
}

/* Bid Statistics Grid */
.bid-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 20px 15px;
    background: var(--light-bone);
    border-radius: 12px;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    color: var(--accent-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 1rem;
}

.stat-value {
    color: var(--primary-green);
    font-size: 1.2rem;
    font-weight: 800;
    margin-bottom: 5px;
    line-height: 1;
}

.stat-label {
    color: var(--light-text);
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Pricing Information */
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.price-item {
    text-align: center;
    padding: 20px;
    background: var(--light-bone);
    border-radius: 12px;
    border: 1px solid var(--border-light);
}

.price-item label {
    display: block;
    color: var(--light-text);
    font-size: 0.7rem;
    font-weight: 600;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.price-value {
    color: var(--primary-dark);
    font-size: 1.2rem;
    font-weight: 700;
}

.price-value.current {
    color: var(--primary-green);
    font-size: 1.2rem;
}

.price-value.reserve {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.reserve-status {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
}

.reserve-status.met {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.reserve-status.not-met {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.price-value.winning {
    color: var(--accent-gold);
    font-size: 1.4rem;
}

/* Bidding Activity */
.bids-timeline {
    max-height: 400px;
    overflow-y: auto;
}

.bid-activity-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.bid-activity-item:hover {
    background: var(--light-bone);
    border-color: var(--border-light);
}

.bid-activity-item.new-bid {
    animation: highlight 2s ease;
}

@keyframes highlight {
    0% { background: rgba(40, 167, 69, 0.2); }
    100% { background: transparent; }
}

.activity-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-gold), var(--primary-green));
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.activity-header strong {
    color: var(--primary-dark);
    font-size: 0.95rem;
}

.activity-time {
    color: var(--light-text);
    font-size: 0.8rem;
}

.activity-body {
    display: flex;
    align-items: center;
    gap: 10px;
}

.bid-amount {
    color: var(--primary-green);
    font-size: 1.1rem;
    font-weight: 700;
}

.auto-bid-badge {
    background: rgba(23, 162, 184, 0.1);
    color: #17a2b8;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}
 
.no-activity { 
    text-align: center;
    padding: 40px 20px;
    color: var(--light-text);
}

.no-activity i {
    font-size: 3rem;
    margin-bottom: 15px;
    display: block;
    opacity: 0.5;
}

/* Timer & Status */
.countdown-timer {
    text-align: center;
}

.timer-display {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 20px;
}

.time-unit {
    padding: 15px 10px;
    background: var(--light-bone);
    border-radius: 10px;
    border: 1px solid var(--border-light);
}

.time-value {
    color: var(--primary-green);
    font-size: 1.8rem;
    font-weight: 800;
    display: block;
    line-height: 1;
    margin-bottom: 5px;
    font-family: 'Courier New', monospace;
}

.time-label {
    color: var(--light-text);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--border-light);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent-gold), var(--primary-green));
    transition: width 1s ease;
    border-radius: 3px;
}

.timer-meta {
    color: var(--light-text);
    font-size: 0.85rem;
}

/* State Styles */
.ended-state,
.upcoming-state,
.paused-state {
    text-align: center;
    padding: 30px 20px;
}

.ended-icon,
.upcoming-icon,
.paused-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--light-bone);
    color: var(--light-text);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
}

.ended-state h4,
.upcoming-state h4,
.paused-state h4 {
    color: var(--primary-dark);
    margin-bottom: 8px;
}

.ended-state p,
.upcoming-state p,
.paused-state p {
    color: var(--light-text);
    margin-bottom: 0;
}

.start-countdown {
    font-family: 'Courier New', monospace;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--primary-green);
    margin: 15px 0;
}

.start-time {
    color: var(--light-text);
    font-size: 0.9rem;
}

/* Winner Information */
.winner-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.winner-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-gold), var(--primary-green));
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.winner-details {
    flex: 1;
}

.winner-name {
    color: var(--primary-dark);
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.winner-bid {
    color: var(--primary-green);
    font-weight: 600;
    margin-bottom: 8px;
}

.winner-contact {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.winner-contact small {
    color: var(--light-text);
    font-size: 0.8rem;
}

.pending-winner {
    text-align: center;
    padding: 20px;
}

.pending-winner i {
    font-size: 2.5rem;
    color: var(--info);
    margin-bottom: 15px;
    display: block;
}

.pending-winner p {
    color: var(--light-text);
    margin-bottom: 15px;
}

/* Details List */
.details-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-light);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item label {
    color: var(--light-text);
    font-weight: 600;
    font-size: 0.9rem;
}

.detail-item span {
    color: var(--primary-dark);
    font-weight: 500;
    text-align: right;
}

/* Participants List */
.participants-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.participant-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.participant-item:hover {
    background: var(--light-bone);
    border-color: var(--border-light);
}

.participant-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-gold), var(--primary-green));
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.participant-details {
    flex: 1;
}

.participant-details strong {
    color: var(--primary-dark);
    font-size: 0.95rem;
    display: block;
    margin-bottom: 2px;
}

.participant-details small {
    color: var(--light-text);
    font-size: 0.8rem;
}

.participant-bids {
    flex-shrink: 0;
}

.bid-count {
    background: var(--light-bone);
    color: var(--primary-dark);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid var(--border-light);
}

.no-participants {
    text-align: center;
    padding: 30px 20px;
    color: var(--light-text);
}

.no-participants i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    display: block;
    opacity: 0.5;
}

/* Terms & Conditions */
.terms-content {
    color: var(--primary-dark);
    line-height: 1.6;
    white-space: pre-wrap;
    max-height: 200px;
    overflow-y: auto;
    padding: 15px;
    background: var(--light-bone);
    border-radius: 8px;
    border: 1px solid var(--border-light);
}

/* Status Badges */
.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: capitalize;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-badge .status-icon {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.status-active { 
    background: #28a745; 
    color: var(--white);
}
.status-active .status-icon { background: var(--white); }

.status-draft { 
    background: #ffc107; 
    color: var(--primary-dark);
}
.status-draft .status-icon { background: var(--primary-dark); }

.status-paused { 
    background: #17a2b8; 
    color: var(--white);
}
.status-paused .status-icon { background: var(--white); }

.status-completed { 
    background:  #28a745; 
    color: var(--white);
}
.status-completed .status-icon { background: var(--white); }

.status-cancelled { 
    background:#dc3545; 
    color: var(--white);
}
.status-cancelled .status-icon { background: var(--white); }

/* Responsive Design */
@media (max-width: 1200px) {
    .show-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .content-right {
        order: -1;
    }
    
    .bid-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .bid-show-container {
        padding: 10px;
    }
    
    .show-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
        padding: 20px;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .bid-status {
        justify-content: center;
    }
    
    .product-display {
        flex-direction: column;
        text-align: center;
    }
    
    .product-image {
        align-self: center;
    }
    
    .winner-info {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .timer-display {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .pricing-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .header-left .page-title {
        font-size: 1.8rem;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .bid-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .timer-display {
        grid-template-columns: 1fr;
    }
    
    .activity-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .participant-item {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
}

/* Button Styles */
.btn-sm {
    padding: 8px 16px;
    font-size: 0.9rem;
}

.btn-block {
    width: 100%;
    justify-content: center;
}

/* Alert Styles */
.alert {
    border: none;
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 20px;
}

.alert-success {
    background: rgba(40, 167, 69, 0.1);
    border-left: 4px solid #28a745;
    color: #155724;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.1);
    border-left: 4px solid #dc3545;
    color: #721c24;
}

/* Dropdown Styles */
.dropdown-menu {
    border: none;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    padding: 8px 0;
}

.dropdown-item {
    padding: 10px 16px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: var(--light-bone);
    color: var(--primary-green);
}

.dropdown-item.text-danger:hover {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.dropdown-divider {
    margin: 8px 0;
    background-color: var(--border-light);
}
</style>

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
                    <div class="simple-timer">
                        <p>Time Remaining: 
                            <div class="endedin-time">{{ $bid->end_time->diffForHumans() }}</div>
                        </p>
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
                        <p>{{ $bid->start_time->format('M d, Y H:i') }}</p>
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

document.addEventListener('DOMContentLoaded', function () {
    const timerEl = document.getElementById('timeRemaining');
    if (!timerEl) return;

    const endTime = new Date(timerEl.dataset.endTime).getTime();

    function updateTimer() {
        const now = new Date().getTime();
        let distance = endTime - now;

        if (distance < 0) {
            timerEl.textContent = "00:00:00";
            clearInterval(timerInterval);
            return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerEl.textContent = 
            String(hours).padStart(2, '0') + ":" +
            String(minutes).padStart(2, '0') + ":" +
            String(seconds).padStart(2, '0');
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
});

</script>
@endsection