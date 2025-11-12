@extends('admin.adminbase')
@section('title', 'Edit Bid - ' . $bid->product->name)

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_bid/edit.css', 'resources/js/app.js'])
@endsection

@section('content')
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