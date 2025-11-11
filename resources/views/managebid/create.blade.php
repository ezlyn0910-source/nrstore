@extends('admin.adminbase')
@section('title', 'Create New Bid')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_bid/create.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="bid-create-container">
    <!-- Header Section -->
    <div class="create-header">
        <div class="header-left">
            <h1 class="page-title">Create New Bid</h1>
            <p class="page-subtitle">Set up a new auction bid for your products</p>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.managebid.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Bids
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="create-form-container">
        <form action="{{ route('admin.managebid.store') }}" method="POST" class="bid-create-form" id="bidCreateForm">
            @csrf

            <!-- Product Selection Section -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-cube"></i>
                        Product Selection
                    </h3>
                    <p class="section-description">Choose the product for this bid auction</p>
                </div>
                
                <div class="form-group">
                    <label for="product_id" class="form-label">Select Product *</label>
                    <select name="product_id" id="product_id" class="form-select" required>
                        <option value="">Choose a product...</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                                data-price="{{ $product->price }}"
                                data-stock="{{ $product->stock_quantity }}"
                                data-image="{{ $product->main_image_url }}"
                                data-description="{{ $product->description }}">
                            {{ $product->name }} - RM {{ number_format($product->price, 2) }} (Stock: {{ $product->stock_quantity }})
                        </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Product Preview -->
                <div class="product-preview" id="productPreview" style="display: none;">
                    <div class="preview-card">
                        <div class="preview-image">
                            <img id="previewImage" src="" alt="Product Image">
                        </div>
                        <div class="preview-details">
                            <h4 id="previewName" class="preview-name"></h4>
                            <p id="previewDescription" class="preview-description"></p>
                            <div class="preview-meta">
                                <span class="meta-item">
                                    <i class="fas fa-tag"></i>
                                    Regular Price: <strong id="previewPrice"></strong>
                                </span>
                                <span class="meta-item">
                                    <i class="fas fa-box"></i>
                                    Stock: <strong id="previewStock"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Pricing Configuration
                    </h3>
                    <p class="section-description">Set the bid starting price and reserve price</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="starting_price" class="form-label">Starting Price (RM) *</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="starting_price" id="starting_price" 
                                   class="form-control" step="0.01" min="0" 
                                   placeholder="0.00" required>
                        </div>
                        @error('starting_price')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Recommended: 50-70% of regular price</div>
                    </div>

                    <div class="form-group">
                        <label for="reserve_price" class="form-label">Reserve Price (RM)</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="reserve_price" id="reserve_price" 
                                   class="form-control" step="0.01" min="0" 
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
                                   class="form-control" step="0.01" min="0.01" 
                                   value="1.00" required>
                        </div>
                        @error('bid_increment')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Minimum amount each bid must increase by</div>
                    </div>
                </div>
            </div>

            <!-- Timing Section -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-clock"></i>
                        Timing Configuration
                    </h3>
                    <p class="section-description">Set the bid start and end times</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_time" class="form-label">Start Time *</label>
                        <input type="datetime-local" name="start_time" id="start_time" 
                               class="form-control" required>
                        @error('start_time')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Bid will automatically start at this time</div>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="form-label">End Time *</label>
                        <input type="datetime-local" name="end_time" id="end_time" 
                               class="form-control" required>
                        @error('end_time')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Bid will automatically end at this time</div>
                    </div>

                    <div class="form-group">
                        <label for="bid_duration" class="form-label">Bid Duration</label>
                        <div class="duration-display" id="durationDisplay">
                            <span class="duration-value">--</span>
                            <span class="duration-label">days, -- hours</span>
                        </div>
                        <div class="form-hint">Calculated automatically from start and end times</div>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-cogs"></i>
                        Advanced Settings
                    </h3>
                    <p class="section-description">Configure additional bid options</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group checkbox-group">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" name="auto_extend" id="auto_extend" value="1">
                            <label for="auto_extend" class="checkbox-label">
                                <span class="checkbox-custom"></span>
                                Enable Auto-Extend
                            </label>
                        </div>
                        <div class="form-hint">Automatically extend bid time if last-minute bids are placed</div>
                    </div>

                    <div class="form-group" id="extensionMinutesGroup" style="display: none;">
                        <label for="extension_minutes" class="form-label">Extension Minutes *</label>
                        <div class="input-group">
                            <input type="number" name="extension_minutes" id="extension_minutes" 
                                   class="form-control" min="1" value="5">
                            <span class="input-group-text">minutes</span>
                        </div>
                        @error('extension_minutes')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">How long to extend when a last-minute bid is placed</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                    <textarea name="terms_conditions" id="terms_conditions" 
                              class="form-control" rows="4" 
                              placeholder="Optional bid terms and conditions..."></textarea>
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
                    Reset Form
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Create Bid
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const productPreview = document.getElementById('productPreview');
    const startingPriceInput = document.getElementById('starting_price');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const durationDisplay = document.getElementById('durationDisplay');
    const autoExtendCheckbox = document.getElementById('auto_extend');
    const extensionMinutesGroup = document.getElementById('extensionMinutesGroup');

    // Set minimum datetime to current time
    const now = new Date();
    const timezoneOffset = now.getTimezoneOffset() * 60000;
    const localISOTime = new Date(now - timezoneOffset).toISOString().slice(0, 16);
    startTimeInput.min = localISOTime;

    // Product selection handler
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            // Show product preview
            document.getElementById('previewImage').src = selectedOption.getAttribute('data-image');
            document.getElementById('previewName').textContent = selectedOption.text.split(' - ')[0];
            document.getElementById('previewDescription').textContent = selectedOption.getAttribute('data-description');
            document.getElementById('previewPrice').textContent = 'RM ' + parseFloat(selectedOption.getAttribute('data-price')).toFixed(2);
            document.getElementById('previewStock').textContent = selectedOption.getAttribute('data-stock');
            
            productPreview.style.display = 'block';
            
            // Set suggested starting price (50% of regular price)
            const regularPrice = parseFloat(selectedOption.getAttribute('data-price'));
            const suggestedPrice = (regularPrice * 0.5).toFixed(2);
            startingPriceInput.value = suggestedPrice;
            startingPriceInput.placeholder = suggestedPrice;
            
        } else {
            productPreview.style.display = 'none';
            startingPriceInput.value = '';
            startingPriceInput.placeholder = '0.00';
        }
    });

    // Duration calculation
    function calculateDuration() {
        const startTime = new Date(startTimeInput.value);
        const endTime = new Date(endTimeInput.value);
        
        if (startTimeInput.value && endTimeInput.value && startTime < endTime) {
            const duration = endTime - startTime;
            const days = Math.floor(duration / (1000 * 60 * 60 * 24));
            const hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            
            durationDisplay.querySelector('.duration-value').textContent = 
                `${days}d, ${hours}h`;
            durationDisplay.querySelector('.duration-label').textContent = 
                `(${days} days, ${hours} hours)`;
        } else {
            durationDisplay.querySelector('.duration-value').textContent = '--';
            durationDisplay.querySelector('.duration-label').textContent = 'days, -- hours';
        }
    }

    startTimeInput.addEventListener('change', function() {
        if (this.value) {
            endTimeInput.min = this.value;
            calculateDuration();
        }
    });

    endTimeInput.addEventListener('change', calculateDuration);

    // Auto-extend toggle
    autoExtendCheckbox.addEventListener('change', function() {
        extensionMinutesGroup.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            document.getElementById('extension_minutes').value = '5';
        }
    });

    // Form validation
    document.getElementById('bidCreateForm').addEventListener('submit', function(e) {
        const startTime = new Date(startTimeInput.value);
        const endTime = new Date(endTimeInput.value);
        
        if (startTime >= endTime) {
            e.preventDefault();
            alert('End time must be after start time.');
            return false;
        }
        
        if (startTime < new Date()) {
            e.preventDefault();
            alert('Start time must be in the future.');
            return false;
        }

        const reservePrice = parseFloat(document.getElementById('reserve_price').value) || 0;
        const startingPrice = parseFloat(startingPriceInput.value);
        
        if (reservePrice > 0 && reservePrice <= startingPrice) {
            e.preventDefault();
            alert('Reserve price must be greater than starting price.');
            return false;
        }

        // If all validations pass, form will submit normally and redirect to index
    });
});
</script>
@endsection