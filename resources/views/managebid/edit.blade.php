@extends('admin.adminbase')
@section('title', 'Edit Bid - ' . $bid->product->name)

@section('content')
    <style> 
    /* Bid Edit Page Styles */
    .bid-edit-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        background-color: var(--light-bone);
        min-height: 100vh;
    }

    /* Header Section */
    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        padding: 25px;
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(26, 36, 18, 0.1);
        border: 1px solid var(--border-light);
        position: relative;
        overflow: hidden;
    }


    .header-left .page-title {
        color: var(--primary-dark);
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 8px;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-left .page-subtitle {
        color: var(--light-text);
        font-size: 0.9rem;
        margin: 0 0 15px 0;
        font-weight: 500;
    }

    .bid-status-info {
        display: flex;
        align-items: center;
        gap: 15px;
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

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-back {
        background: var(--white);
        color: var(--primary-dark);
        border: 2px solid var(--border-light);
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: var(--light-bone);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* Main Content Layout */
    .edit-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    .content-left {
        min-width: 0; /* Prevent grid overflow */
    }

    .content-right {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Form Sections */
    .edit-form-container {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 6px 25px rgba(26, 36, 18, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .form-section {
        padding: 30px;
        border-bottom: 1px solid var(--border-light);
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-header {
        margin-bottom: 25px;
    }

    .section-title {
        color: var(--primary-dark);
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--accent-gold);
        font-size: 1.2rem;
    }

    .section-description {
        color: var(--light-text);
        margin: 0;
        font-size: 0.95rem;
    }

    /* Product Display */
    .product-display {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        padding: 20px;
        background: var(--light-bone);
        border-radius: 12px;
        border: 1px solid var(--border-light);
    }

    .product-image {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        flex: 1;
    }

    .product-name {
        color: var(--primary-dark);
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 10px 0;
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

    /* Form Elements */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: var(--primary-dark);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(218, 161, 18, 0.1);
    }

    .form-control:read-only {
        background: var(--light-bone);
        color: var(--light-text);
        cursor: not-allowed;
    }

    .input-group {
        display: flex;
    }

    .input-group-text {
        background: var(--light-bone);
        border: 2px solid var(--border-light);
        border-right: none;
        padding: 12px 15px;
        color: var(--light-text);
        font-weight: 500;
        border-radius: 8px 0 0 8px;
    }

    .input-group .form-control {
        border-radius: 0 8px 8px 0;
        border-left: none;
    }

    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        background: var(--white);
        color: var(--dark-text);
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(218, 161, 18, 0.1);
    }

    /* Checkbox */
    .checkbox-group {
        margin-bottom: 15px;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .checkbox-wrapper input[type="checkbox"] {
        display: none;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-light);
        border-radius: 4px;
        position: relative;
        transition: all 0.3s ease;
    }

    .checkbox-wrapper input[type="checkbox"]:checked + .checkbox-label .checkbox-custom {
        background: var(--primary-green);
        border-color: var(--primary-green);
    }

    .checkbox-wrapper input[type="checkbox"]:checked + .checkbox-label .checkbox-custom::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--white);
        font-size: 12px;
        font-weight: bold;
    }

    .checkbox-label {
        color: var(--primary-dark);
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Duration Display */
    .duration-display {
        padding: 15px;
        background: var(--light-bone);
        border-radius: 8px;
        border: 1px solid var(--border-light);
        text-align: center;
    }

    .duration-value {
        color: var(--primary-green);
        font-size: 1.2rem;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
    }

    .duration-label {
        color: var(--light-text);
        font-size: 0.85rem;
    }

    /* Status Warnings */
    .status-warnings {
        margin-top: 15px;
    }

    .warning-message {
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.9rem;
    }

    .warning-message i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    .warning-message.danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .warning-message:not(.danger) {
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.2);
        color: #856404;
    }

    /* Form Hints */
    .form-hint {
        color: var(--light-text);
        font-size: 0.85rem;
        margin-top: 6px;
    }

    .form-hint.warning {
        color: var(--warning);
        font-weight: 500;
    }

    /* Error Messages */
    .error-message {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 6px;
        font-weight: 500;
    }

    .is-invalid {
        border-color: var(--danger) !important;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
    }

    /* Form Actions */
    .form-actions {
        padding: 30px;
        background: var(--light-bone);
        border-top: 1px solid var(--border-light);
        display: flex;
        gap: 15px;
        justify-content: flex-end;
    }

    .btn-outline {
        background: var(--white);
        color: var(--primary-dark);
        border: 2px solid var(--border-light);
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-outline:hover {
        background: var(--light-bone);
        border-color: var(--primary-dark);
    }

    /* Right Panel Styles */
    .actions-panel,
    .timeline-panel {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(26, 36, 18, 0.08);
        border: 1px solid var(--border-light);
        padding: 25px;
    }

    .panel-title {
        color: var(--primary-dark);
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 20px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--light-bone);
    }

    /* Quick Action Forms */
    .quick-action-form {
        margin-bottom: 20px;
    }

    .btn-block {
        width: 100%;
        justify-content: center;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: var(--white);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        border: none;
        color: var(--primary-dark);
    }

    /* Stats Card */
    .stats-card {
        background: var(--light-bone);
        border-radius: 10px;
        padding: 20px;
        border: 1px solid var(--border-light);
        margin: 20px 0;
    }

    .stats-title {
        color: var(--primary-dark);
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 15px 0;
        text-align: center;
    }

    .stats-grid-mini {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }

    .stat-mini {
        text-align: center;
    }

    .stat-value {
        color: var(--primary-green);
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--light-text);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .stats-meta {
        text-align: center;
    }

    .stats-meta small {
        color: var(--light-text);
        font-size: 0.8rem;
    }

    /* Danger Zone */
    .danger-zone {
        background: rgba(220, 53, 69, 0.05);
        border: 1px solid rgba(220, 53, 69, 0.2);
        border-radius: 10px;
        padding: 20px;
    }

    .danger-title {
        color: #dc3545;
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .danger-hint {
        margin-top: 10px;
        text-align: center;
    }

    .danger-hint small {
        color: #dc3545;
        font-size: 0.8rem;
    }

    .btn-danger {
        background: #dc3545;
        border: none;
        color: var(--white);
    }

    /* Timeline */
    .timeline {
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-light);
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--border-light);
        border: 3px solid var(--white);
        flex-shrink: 0;
        margin-top: 5px;
        position: relative;
        z-index: 2;
    }

    .timeline-item.completed .timeline-marker {
        background: var(--success);
    }

    .timeline-item.active .timeline-marker {
        background: var(--primary-green);
        animation: pulse 2s infinite;
    }

    .timeline-item.upcoming .timeline-marker {
        background: var(--light-text);
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-content strong {
        color: var(--primary-dark);
        font-size: 0.9rem;
        display: block;
        margin-bottom: 4px;
    }

    .timeline-content small {
        color: var(--light-text);
        font-size: 0.8rem;
        display: block;
    }

    /* Modal Styles */
    .preview-summary h4 {
        color: var(--primary-dark);
        margin-bottom: 20px;
        font-weight: 700;
    }

    .preview-details {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .preview-item {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 12px;
        background: var(--light-bone);
        border-radius: 8px;
    }

    .preview-label {
        color: var(--primary-dark);
        font-weight: 600;
        flex: 1;
    }

    .preview-value {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .preview-warning {
        padding: 15px;
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.3);
        border-radius: 8px;
        color: #856404;
    }

    .preview-warning i {
        font-size: 1.2rem;
        margin-bottom: 8px;
        display: block;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-badge .status-icon {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-active { 
        background:  #28a745; 
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
        background:#28a745; 
        color: var(--white);
    }
    .status-completed .status-icon { background: var(--white); }

    .status-cancelled { 
        background: #dc3545; 
        color: var(--white);
    }
    .status-cancelled .status-icon { background: var(--white); }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .edit-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .content-right {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .bid-edit-container {
            padding: 10px;
        }
        
        .edit-header {
            flex-direction: column;
            gap: 20px;
            text-align: center;
            padding: 20px;
        }
        
        .header-actions {
            justify-content: center;
        }
        
        .bid-status-info {
            justify-content: center;
        }
        
        .form-section {
            padding: 20px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .product-display {
            flex-direction: column;
            text-align: center;
        }
        
        .product-image {
            align-self: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .actions-panel,
        .timeline-panel {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .header-left .page-title {
            font-size: 1.8rem;
        }
        
        .section-title {
            font-size: 1.2rem;
        }
        
        .stats-grid-mini {
            grid-template-columns: 1fr;
        }
    }

    /* Animations */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Loading States */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }
    </style>

<div class="bid-edit-container">
    <!-- Header Section -->
    <div class="edit-header">
        <div class="header-left">
            <h1 class="page-title">Edit Bid</h1>
            <p class="page-subtitle">Update auction bid settings for {{ $bid->product->name }}</p>
            <div class="bid-status-info">
                <span class="status-badge status-{{ $bid->status }}">
                    <i class="status-icon"></i>
                    {{ ucfirst($bid->status) }}
                </span>
                <span class="bid-id">BID #{{ str_pad($bid->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
        <div class="header-right">
            <div class="header-actions">
                <a href="{{ route('admin.managebid.show', $bid) }}" class="btn btn-secondary">
                    <i class="fas fa-eye"></i>
                    View Details
                </a>
                <a href="{{ route('admin.managebid.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Bids
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

    <!-- Main Content -->
    <div class="edit-content">
        <div class="content-left">
            <!-- Edit Form -->
            <div class="edit-form-container">
                <form action="{{ route('admin.managebid.update', $bid) }}" method="POST" class="bid-edit-form" id="bidEditForm">
                    @csrf
                    @method('PUT')

                    <!-- Product Information (Read-only) -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-cube"></i>
                                Product Information
                            </h3>
                            <p class="section-description">Bid product cannot be changed</p>
                        </div>
                        
                        <div class="product-display">
                            <div class="product-image">
                                <img src="{{ $bid->product->main_image_url }}" alt="{{ $bid->product->name }}"
                                     onerror="this.src='{{ asset('images/default-product.png') }}'">
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
                    </div>

                    <!-- Pricing Configuration -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-money-bill-wave"></i>
                                Pricing Configuration
                            </h3>
                            <p class="section-description">Update bid pricing settings</p>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="starting_price" class="form-label">Starting Price (RM) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" name="starting_price" id="starting_price" 
                                           class="form-control @error('starting_price') is-invalid @enderror" 
                                           step="0.01" min="0.01" 
                                           value="{{ old('starting_price', $bid->starting_price) }}" 
                                           required
                                           {{ $bid->has_started ? 'readonly' : '' }}>
                                </div>
                                @error('starting_price')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                @if($bid->has_started)
                                <div class="form-hint warning">Starting price cannot be changed after bid has started</div>
                                @else
                                <div class="form-hint">Recommended: 50-70% of regular price</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="reserve_price" class="form-label">Reserve Price (RM)</label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" name="reserve_price" id="reserve_price" 
                                           class="form-control @error('reserve_price') is-invalid @enderror" 
                                           step="0.01" min="0" 
                                           value="{{ old('reserve_price', $bid->reserve_price) }}"
                                           placeholder="Optional">
                                </div>
                                @error('reserve_price')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">Minimum price to win the bid (hidden from bidders)</div>
                            </div>

                            <div class="form-group">
                                <label for="bid_increment" class="form-label">Bid Increment (RM) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" name="bid_increment" id="bid_increment" 
                                           class="form-control @error('bid_increment') is-invalid @enderror" 
                                           step="0.01" min="0.01" 
                                           value="{{ old('bid_increment', $bid->bid_increment) }}" required>
                                </div>
                                @error('bid_increment')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">Minimum amount each bid must increase by</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timing Configuration -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-clock"></i>
                                Timing Configuration
                            </h3>
                            <p class="section-description">Update bid schedule and duration</p>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="start_time" class="form-label">Start Time *</label>
                                <input type="datetime-local" name="start_time" id="start_time" 
                                       class="form-control @error('start_time') is-invalid @enderror" 
                                       value="{{ old('start_time', $bid->start_time->format('Y-m-d\TH:i')) }}" 
                                       required
                                       {{ $bid->has_started ? 'readonly' : '' }}>
                                @error('start_time')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                @if($bid->has_started)
                                <div class="form-hint warning">Start time cannot be changed after bid has started</div>
                                @else
                                <div class="form-hint">Bid will automatically start at this time</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="end_time" class="form-label">End Time *</label>
                                <input type="datetime-local" name="end_time" id="end_time" 
                                       class="form-control @error('end_time') is-invalid @enderror" 
                                       value="{{ old('end_time', $bid->end_time->format('Y-m-d\TH:i')) }}" 
                                       required>
                                @error('end_time')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">Bid will automatically end at this time</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Current Duration</label>
                                <div class="duration-display" id="durationDisplay">
                                    <span class="duration-value">
                                        {{ $bid->start_time->diff($bid->end_time)->format('%dd %hh %im') }}
                                    </span>
                                    <span class="duration-label">({{ $bid->start_time->diffInHours($bid->end_time) }} hours total)</span>
                                </div>
                                <div class="form-hint">Calculated from start and end times</div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Management -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-cogs"></i>
                                Status Management
                            </h3>
                            <p class="section-description">Control bid state and visibility</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label">Bid Status *</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $bid->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $bid->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ old('status', $bid->status) == 'paused' ? 'selected' : '' }}>Paused</option>
                                <option value="completed" {{ old('status', $bid->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $bid->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="form-hint">Change the current state of this bid</div>
                        </div>

                        <!-- Status Change Warnings -->
                        <div id="statusWarnings" class="status-warnings">
                            @if($bid->status === 'active' && $bid->is_active)
                            <div class="warning-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Active Bid:</strong> Changing status may affect live bidders
                            </div>
                            @endif
                            
                            @if($bid->status === 'completed')
                            <div class="warning-message">
                                <i class="fas fa-info-circle"></i>
                                <strong>Completed Bid:</strong> Reactivating may require manual winner assignment
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Advanced Settings -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-sliders-h"></i>
                                Advanced Settings
                            </h3>
                            <p class="section-description">Configure additional bid options</p>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group checkbox-group">
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" name="auto_extend" id="auto_extend" value="1" 
                                           {{ old('auto_extend', $bid->auto_extend) ? 'checked' : '' }}>
                                    <label for="auto_extend" class="checkbox-label">
                                        <span class="checkbox-custom"></span>
                                        Enable Auto-Extend
                                    </label>
                                </div>
                                <div class="form-hint">Automatically extend bid time if last-minute bids are placed</div>
                            </div>

                            <div class="form-group" id="extensionMinutesGroup" style="{{ old('auto_extend', $bid->auto_extend) ? 'display: block;' : 'display: none;' }}">
                                <label for="extension_minutes" class="form-label">Extension Minutes *</label>
                                <div class="input-group">
                                    <input type="number" name="extension_minutes" id="extension_minutes" 
                                           class="form-control @error('extension_minutes') is-invalid @enderror" 
                                           min="1" max="30" 
                                           value="{{ old('extension_minutes', $bid->extension_minutes ?? 5) }}">
                                    <span class="input-group-text">minutes</span>
                                </div>
                                @error('extension_minutes')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">How long to extend when a last-minute bid is placed (1-30 minutes)</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                            <textarea name="terms_conditions" id="terms_conditions" 
                                      class="form-control @error('terms_conditions') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Optional bid terms and conditions...">{{ old('terms_conditions', $bid->terms_conditions) }}</textarea>
                            @error('terms_conditions')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="form-hint">Additional rules and conditions for this bid</div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                        <button type="reset" class="btn btn-outline">
                            <i class="fas fa-redo"></i>
                            Reset Changes
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitButton">
                            <i class="fas fa-save"></i>
                            Update Bid
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="content-right">
            <!-- Quick Actions Panel -->
            <div class="actions-panel">
                <h4 class="panel-title">Quick Actions</h4>
                
                @if($bid->status === 'draft')
                <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="quick-action-form">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-play"></i>
                        Start Bid Now
                    </button>
                </form>
                @endif

                @if($bid->status === 'active')
                <form action="{{ route('admin.managebid.pause', $bid) }}" method="POST" class="quick-action-form">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-block">
                        <i class="fas fa-pause"></i>
                        Pause Bid
                    </button>
                </form>
                @endif

                @if($bid->status === 'paused')
                <form action="{{ route('admin.managebid.start', $bid) }}" method="POST" class="quick-action-form">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-play"></i>
                        Resume Bid
                    </button>
                </form>
                @endif

                @if(in_array($bid->status, ['active', 'paused']))
                <form action="{{ route('admin.managebid.complete', $bid) }}" method="POST" class="quick-action-form">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block" 
                            onclick="return confirm('Are you sure you want to complete this bid? This will determine the winner.')">
                        <i class="fas fa-flag-checkered"></i>
                        Complete Bid
                    </button>
                </form>
                @endif

                <!-- Statistics Card -->
                <div class="stats-card">
                    <h5 class="stats-title">Bid Statistics</h5>
                    <div class="stats-grid-mini">
                        <div class="stat-mini">
                            <div class="stat-value">{{ $bid->bid_count }}</div>
                            <div class="stat-label">Total Bids</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-value">{{ $bid->formatted_current_price }}</div>
                            <div class="stat-label">Current Price</div>
                        </div>
                    </div>
                    @if($bid->bids_count > 0)
                    <div class="stats-meta">
                        <small>Last bid: {{ $bid->bids->first() ? $bid->bids->first()->created_at->diffForHumans() : 'N/A' }}</small>
                    </div>
                    @endif
                </div>

                <!-- Danger Zone -->
                <div class="danger-zone">
                    <h5 class="danger-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Danger Zone
                    </h5>
                    <form action="{{ route('admin.managebid.destroy', $bid) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this bid? This action cannot be undone and will remove all bid history.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i>
                            Delete Bid
                        </button>
                    </form>
                    <div class="danger-hint">
                        <small>Permanently delete this bid and all associated data</small>
                    </div>
                </div>
            </div>

            <!-- Timeline Panel -->
            <div class="timeline-panel">
                <h4 class="panel-title">Bid Timeline</h4>
                <div class="timeline">
                    <div class="timeline-item {{ $bid->has_started ? 'completed' : 'upcoming' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Bid Created</strong>
                            <small>{{ $bid->created_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ $bid->has_started ? 'completed' : 'upcoming' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Scheduled Start</strong>
                            <small>{{ $bid->start_time->format('M d, Y H:i') }}</small>
                            @if(!$bid->has_started)
                            <small class="text-info">Starts in {{ $bid->start_time->diffForHumans() }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-item {{ $bid->has_ended ? 'completed' : ($bid->has_started ? 'active' : 'upcoming') }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Scheduled End</strong>
                            <small>{{ $bid->end_time->format('M d, Y H:i') }}</small>
                            @if($bid->is_active)
                            <small class="text-warning">Ends in {{ $bid->end_time->diffForHumans() }}</small>
                            @endif
                        </div>
                    </div>
                    @if($bid->has_ended || $bid->status === 'completed')
                    <div class="timeline-item {{ $bid->status === 'completed' ? 'completed' : 'upcoming' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <strong>Bid Completed</strong>
                            <small>
                                @if($bid->status === 'completed')
                                {{ $bid->updated_at->format('M d, Y H:i') }}
                                @else
                                Pending completion
                                @endif
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Changes</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="preview-summary">
                    <h4>Changes Summary</h4>
                    <div class="preview-details" id="previewDetails">
                        <!-- Dynamic content will be inserted here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" form="bidEditForm" class="btn btn-primary">
                    Confirm Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const durationDisplay = document.getElementById('durationDisplay');
    const autoExtendCheckbox = document.getElementById('auto_extend');
    const extensionMinutesGroup = document.getElementById('extensionMinutesGroup');
    const statusSelect = document.getElementById('status');
    const statusWarnings = document.getElementById('statusWarnings');
    const submitButton = document.getElementById('submitButton');
    const form = document.getElementById('bidEditForm');

    // Set minimum datetime for end time
    if (!startTimeInput.readOnly) {
        startTimeInput.addEventListener('change', function() {
            if (this.value) {
                endTimeInput.min = this.value;
                calculateDuration();
            }
        });
    }

    endTimeInput.addEventListener('change', calculateDuration);

    // Calculate duration
    function calculateDuration() {
        const startTime = new Date(startTimeInput.value);
        const endTime = new Date(endTimeInput.value);
        
        if (startTimeInput.value && endTimeInput.value && startTime < endTime) {
            const duration = endTime - startTime;
            const days = Math.floor(duration / (1000 * 60 * 60 * 24));
            const hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
            
            durationDisplay.querySelector('.duration-value').textContent = 
                `${days}d ${hours}h ${minutes}m`;
            durationDisplay.querySelector('.duration-label').textContent = 
                `(${Math.round(duration / (1000 * 60 * 60))} hours total)`;
        }
    }

    // Auto-extend toggle
    autoExtendCheckbox.addEventListener('change', function() {
        extensionMinutesGroup.style.display = this.checked ? 'block' : 'none';
        if (this.checked && (!document.getElementById('extension_minutes').value || document.getElementById('extension_minutes').value < 1)) {
            document.getElementById('extension_minutes').value = '5';
        }
    });

    // Status change warnings
    statusSelect.addEventListener('change', function() {
        updateStatusWarnings(this.value);
    });

    function updateStatusWarnings(newStatus) {
        let warningHtml = '';
        
        if (newStatus === 'active' && {{ $bid->has_ended ? 'true' : 'false' }}) {
            warningHtml = `
                <div class="warning-message danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> This bid has already ended. Reactivating may require manual adjustments.
                </div>
            `;
        } else if (newStatus === 'completed' && {{ $bid->bid_count }} === 0) {
            warningHtml = `
                <div class="warning-message danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> No bids placed yet. Completing will end the bid without a winner.
                </div>
            `;
        } else if (newStatus === 'cancelled') {
            warningHtml = `
                <div class="warning-message danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> Cancelling will end the bid immediately and notify participants.
                </div>
            `;
        }
        
        statusWarnings.innerHTML = warningHtml;
    }

    // Form validation
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Clear previous error highlights
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Validate end time
        const startTime = new Date(startTimeInput.value);
        const endTime = new Date(endTimeInput.value);
        
        if (startTime >= endTime) {
            endTimeInput.classList.add('is-invalid');
            showError('End time must be after start time.');
            hasErrors = true;
        }

        // Validate reserve price
        const reservePrice = parseFloat(document.getElementById('reserve_price').value) || 0;
        const startingPrice = parseFloat(document.getElementById('starting_price').value);
        
        if (reservePrice > 0 && reservePrice <= startingPrice) {
            document.getElementById('reserve_price').class.add('is-invalid');
            showError('Reserve price must be greater than starting price.');
            hasErrors = true;
        }

        // Validate extension minutes if auto-extend is enabled
        if (autoExtendCheckbox.checked) {
            const extensionMinutes = parseInt(document.getElementById('extension_minutes').value);
            if (!extensionMinutes || extensionMinutes < 1 || extensionMinutes > 30) {
                document.getElementById('extension_minutes').classList.add('is-invalid');
                showError('Extension minutes must be between 1 and 30.');
                hasErrors = true;
            }
        }

        if (hasErrors) {
            e.preventDefault();
            return false;
        }

        // Show preview modal for significant changes
        if (hasSignificantChanges()) {
            e.preventDefault();
            showPreviewModal();
        } else {
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        }
    });

    function hasSignificantChanges() {
        // Check if status is being changed to completed or cancelled
        const newStatus = statusSelect.value;
        const oldStatus = '{{ $bid->status }}';
        
        return newStatus !== oldStatus && (newStatus === 'completed' || newStatus === 'cancelled');
    }

    function showError(message) {
        // You can implement a toast notification or alert here
        alert(message);
    }

    function showPreviewModal() {
        const newStatus = statusSelect.value;
        const oldStatus = '{{ $bid->status }}';
        
        let previewHtml = `
            <div class="preview-item">
                <span class="preview-label">Status Change:</span>
                <span class="preview-value">
                    <span class="status-badge status-${oldStatus}">${oldStatus}</span>
                    <i class="fas fa-arrow-right mx-2"></i>
                    <span class="status-badge status-${newStatus}">${newStatus}</span>
                </span>
            </div>
        `;

        if (newStatus === 'completed') {
            previewHtml += `
                <div class="preview-warning">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    <strong>This will end the bid and determine the winner.</strong>
                    <p>All participants will be notified of the result.</p>
                </div>
            `;
        } else if (newStatus === 'cancelled') {
            previewHtml += `
                <div class="preview-warning">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    <strong>This will immediately cancel the bid.</strong>
                    <p>All participants will be notified and no winner will be declared.</p>
                </div>
            `;
        }

        document.getElementById('previewDetails').innerHTML = previewHtml;
        $('#previewModal').modal('show');
    }

    // Initialize
    calculateDuration();
    updateStatusWarnings(statusSelect.value);
});
</script>
@endsection