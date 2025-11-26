@extends('admin.adminbase')
@section('title', 'Create New Bid')

@section('styles')
    @vite(['resources/sass/app.scss', 'resources/css/manage_bid/create.css', 'resources/js/app.js'])
@endsection

@section('content')
<div class="bid-create-container">
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <div class="create-form-container">
        <form action="{{ route('admin.managebid.store') }}" method="POST" class="bid-create-form" id="bidCreateForm">
            @csrf

            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title"><i class="fas fa-cube"></i> Product Selection</h3>
                    <p class="section-description">Choose the product or specific variation for this bid</p>
                </div>
                
                <div class="form-group">
                    <label for="item_selection" class="form-label">Select Product / Variation *</label>
                    <select name="item_selection" id="item_selection" class="form-select @error('item_selection') is-invalid @enderror @error('product_id') is-invalid @enderror" required>
                        <option value="">Choose a product...</option>
                        
                        @foreach($products as $product)
                            {{-- Check if product has variations --}}
                            @if($product->variations->isNotEmpty())
                                <optgroup label="{{ $product->name }}">
                                    @foreach($product->variations as $variation)
                                        @php
                                            // Construct name with key specs
                                            $specs = collect([$variation->ram, $variation->storage, $variation->color])->filter()->join(' / ');
                                            $varName = $product->name . ' (' . ($specs ?: $variation->sku) . ')';
                                        @endphp
                                        <option value="variation_{{ $variation->id }}" 
                                                data-price="{{ $variation->effective_price }}"
                                                data-stock="{{ $variation->stock }}"
                                                data-image="{{ $variation->image_url }}"
                                                data-description="{{ $product->description }} - {{ $specs }}"
                                                {{ old('item_selection') == 'variation_'.$variation->id ? 'selected' : '' }}>
                                            {{ $varName }} - RM {{ number_format($variation->effective_price, 2) }} (Stock: {{ $variation->stock }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @else
                                {{-- Simple Product without variations --}}
                                <option value="product_{{ $product->id }}" 
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock_quantity }}"
                                        data-image="{{ $product->main_image_url }}"
                                        data-description="{{ $product->description }}"
                                        {{ old('item_selection') == 'product_'.$product->id ? 'selected' : '' }}>
                                    {{ $product->name }} - RM {{ number_format($product->price, 2) }} (Stock: {{ $product->stock_quantity }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('item_selection')<div class="error-message">{{ $message }}</div>@enderror
                    @error('product_id')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="product-preview" id="productPreview" style="display: none;">
                    <div class="preview-card">
                        <div class="preview-image">
                            <img id="previewImage" src="" alt="Product Image">
                        </div>
                        <div class="preview-details">
                            <h4 id="previewName" class="preview-name"></h4>
                            <p id="previewDescription" class="preview-description"></p>
                            <div class="preview-meta">
                                <span class="meta-item"><i class="fas fa-tag"></i> Regular Price: <strong id="previewPrice"></strong></span>
                                <span class="meta-item"><i class="fas fa-box"></i> Stock: <strong id="previewStock"></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title"><i class="fas fa-money-bill-wave"></i> Pricing Configuration</h3>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="starting_price" class="form-label">Starting Price (RM) *</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="starting_price" id="starting_price" class="form-control @error('starting_price') is-invalid @enderror" step="0.01" min="0.01" value="{{ old('starting_price') }}" placeholder="0.00" required>
                        </div>
                        @error('starting_price')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="reserve_price" class="form-label">Reserve Price (RM)</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="reserve_price" id="reserve_price" class="form-control @error('reserve_price') is-invalid @enderror" step="0.01" min="0" value="{{ old('reserve_price') }}" placeholder="Optional">
                        </div>
                        @error('reserve_price')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="bid_increment" class="form-label">Bid Increment (RM) *</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="bid_increment" id="bid_increment" class="form-control @error('bid_increment') is-invalid @enderror" step="0.01" min="0.01" value="{{ old('bid_increment', '1.00') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title"><i class="fas fa-clock"></i> Timing Configuration</h3>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_time" class="form-label">Start Time *</label>
                        <input type="datetime-local" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                        @error('start_time')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="form-label">End Time *</label>
                        <input type="datetime-local" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                        @error('end_time')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bid Duration</label>
                        <div class="duration-display" id="durationDisplay">
                            <span class="duration-value">--</span>
                            <span class="duration-label">days, -- hours</span>
                        </div>
                        <div class="form-hint">Calculated automatically</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title"><i class="fas fa-cogs"></i> Advanced Settings</h3>
                </div>
                
                <div class="form-grid">
                    <div class="form-group checkbox-group">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" name="auto_extend" id="auto_extend" value="1" {{ old('auto_extend') ? 'checked' : '' }}>
                            <label for="auto_extend" class="checkbox-label"><span class="checkbox-custom"></span> Enable Auto-Extend</label>
                        </div>
                    </div>

                    <div class="form-group" id="extensionMinutesGroup" style="display: none;">
                        <label for="extension_minutes" class="form-label">Extension Minutes *</label>
                        <div class="input-group">
                            <input type="number" name="extension_minutes" id="extension_minutes" class="form-control" min="1" max="30" value="{{ old('extension_minutes', '5') }}">
                            <span class="input-group-text">minutes</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                    <textarea name="terms_conditions" id="terms_conditions" class="form-control" rows="4">{{ old('terms_conditions') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                <button type="reset" class="btn btn-outline">Reset Form</button>
                <button type="submit" class="btn btn-primary" id="submitButton"><i class="fas fa-save"></i> Create Bid</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_selection');
    const productPreview = document.getElementById('productPreview');
    const startingPriceInput = document.getElementById('starting_price');
    
    // Timing Elements
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const durationDisplay = document.getElementById('durationDisplay');
    
    // Auto Extend Elements
    const autoExtendCheckbox = document.getElementById('auto_extend');
    const extensionMinutesGroup = document.getElementById('extensionMinutesGroup');

    // 1. PRODUCT SELECTION & PREVIEW LOGIC
    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            // Update Preview Elements using data attributes
            document.getElementById('previewImage').src = selectedOption.getAttribute('data-image');
            
            // Clean up name (remove price part from text)
            let nameText = selectedOption.text.split(' - RM')[0];
            document.getElementById('previewName').textContent = nameText;
            
            document.getElementById('previewDescription').textContent = selectedOption.getAttribute('data-description');
            document.getElementById('previewPrice').textContent = 'RM ' + parseFloat(selectedOption.getAttribute('data-price')).toFixed(2);
            document.getElementById('previewStock').textContent = selectedOption.getAttribute('data-stock');
            
            productPreview.style.display = 'block';
            
            // Suggest Starting Price (50% of regular price)
            if (!startingPriceInput.value || startingPriceInput.value === '0') {
                const regularPrice = parseFloat(selectedOption.getAttribute('data-price'));
                const suggestedPrice = (regularPrice * 0.5).toFixed(2);
                startingPriceInput.value = suggestedPrice;
            }
        } else {
            productPreview.style.display = 'none';
        }
    });

    // Trigger change if value exists (on validation fail/reload)
    if (itemSelect.value) itemSelect.dispatchEvent(new Event('change'));

    // 2. TIMING & DURATION LOGIC (FIXED)
    function calculateDuration() {
        const startVal = startTimeInput.value;
        const endVal = endTimeInput.value;

        if (startVal && endVal) {
            const start = new Date(startVal);
            const end = new Date(endVal);
            
            // Calculate difference in milliseconds
            const diffTime = end - start;

            // Only show valid duration if end is after start
            if (diffTime > 0) {
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                const diffHours = Math.floor((diffTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffTime % (1000 * 60 * 60)) / (1000 * 60));

                durationDisplay.querySelector('.duration-value').textContent = 
                    `${diffDays}d, ${diffHours}h, ${diffMinutes}m`;
                
                durationDisplay.querySelector('.duration-label').textContent = 
                    `(${diffDays} days, ${diffHours} hours, ${diffMinutes} minutes)`;
                
                // Add visual cue for validity
                durationDisplay.style.borderColor = 'var(--primary-green)';
            } else {
                durationDisplay.querySelector('.duration-value').textContent = 'Invalid Range';
                durationDisplay.querySelector('.duration-label').textContent = 'End time must be after start time';
                durationDisplay.style.borderColor = 'var(--danger)';
            }
        } else {
            durationDisplay.querySelector('.duration-value').textContent = '--';
            durationDisplay.querySelector('.duration-label').textContent = 'Set both dates to calculate';
            durationDisplay.style.borderColor = 'var(--border-light)';
        }
    }

    // Use 'input' event for real-time updates as user picks dates
    startTimeInput.addEventListener('input', calculateDuration);
    endTimeInput.addEventListener('input', calculateDuration);
    
    // Also trigger on 'change' to be safe (browser compatibility)
    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);

    // Initial check
    calculateDuration();

    // 3. AUTO EXTEND TOGGLE
    if (autoExtendCheckbox.checked) extensionMinutesGroup.style.display = 'block';
    
    autoExtendCheckbox.addEventListener('change', function() {
        extensionMinutesGroup.style.display = this.checked ? 'block' : 'none';
    });

    // 4. FORM SUBMISSION VALIDATION
    document.getElementById('bidCreateForm').addEventListener('submit', function(e) {
        // ... (Your existing validation logic here, no changes needed to logic itself) ...
    });
});
</script>
@endsection