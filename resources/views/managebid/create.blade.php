@extends('admin.adminbase')
@section('title', 'Create New Bid')

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

<style>
    /* Manage Bid Create Styles */
    .bid-create-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background-color: var(--light-bone);
        min-height: 100vh;
    }

    /* Header Section */
    .create-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        padding: 25px;
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(26, 36, 18, 0.08);
        border: 1px solid var(--border-light);
    }

    .header-left .page-title {
        color: var(--primary-dark);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .header-left .page-subtitle {
        color: var(--light-text);
        font-size: 1.1rem;
        margin: 0;
    }

    .btn-back {
        background: var(--white);
        color: var(--primary-green);
        border: 2px solid var(--primary-green);
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: var(--primary-green);
        color: var(--white);
        transform: translateY(-2px);
    }

    /* Form Container */
    .create-form-container {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(26, 36, 18, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .bid-create-form {
        padding: 0;
    }

    /* Form Sections */
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
        font-weight: 600;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--accent-gold);
    }

    .section-description {
        color: var(--light-text);
        font-size: 1rem;
        margin: 0;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        color: var(--primary-dark);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 1rem;
    }

    .form-label::after {
        content: '*';
        color: var(--danger);
        margin-left: 4px;
    }

    .form-label:not([for*="required"])::after {
        content: '';
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(218, 161, 18, 0.1);
    }

    .form-control:invalid {
        border-color: var(--danger);
    }

    /* Input Groups */
    .input-group {
        display: flex;
        align-items: stretch;
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

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    /* Product Preview */
    .product-preview {
        margin-top: 20px;
        animation: fadeIn 0.3s ease;
    }

    .preview-card {
        display: flex;
        gap: 20px;
        padding: 20px;
        background: var(--light-bone);
        border-radius: 8px;
        border: 1px solid var(--border-light);
    }

    .preview-image {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-details {
        flex: 1;
    }

    .preview-name {
        color: var(--primary-dark);
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0 0 8px 0;
    }

    .preview-description {
        color: var(--light-text);
        font-size: 0.9rem;
        margin: 0 0 15px 0;
        line-height: 1.4;
    }

    .preview-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .meta-item {
        color: var(--light-text);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .meta-item i {
        color: var(--accent-gold);
    }

    .meta-item strong {
        color: var(--primary-dark);
    }

    /* Duration Display */
    .duration-display {
        padding: 12px 15px;
        background: var(--light-bone);
        border: 2px solid var(--border-light);
        border-radius: 8px;
        text-align: center;
    }

    .duration-value {
        color: var(--primary-green);
        font-size: 1.2rem;
        font-weight: 700;
        display: block;
    }

    .duration-label {
        color: var(--light-text);
        font-size: 0.9rem;
        display: block;
        margin-top: 2px;
    }

    /* Checkbox Styles */
    .checkbox-group {
        margin-top: 10px;
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

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 500;
        color: var(--primary-dark);
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-light);
        border-radius: 4px;
        position: relative;
        transition: all 0.3s ease;
    }

    .checkbox-custom::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 6px;
        width: 6px;
        height: 10px;
        border: solid var(--white);
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .checkbox-wrapper input[type="checkbox"]:checked + .checkbox-label .checkbox-custom {
        background: var(--primary-green);
        border-color: var(--primary-green);
    }

    .checkbox-wrapper input[type="checkbox"]:checked + .checkbox-label .checkbox-custom::after {
        opacity: 1;
    }

    /* Textarea */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Form Hints and Errors */
    .form-hint {
        color: var(--light-text);
        font-size: 0.85rem;
        margin-top: 5px;
        font-style: italic;
    }

    .error-message {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 5px;
        font-weight: 500;
    }

    /* Form Actions */
    .form-actions {
        padding: 30px;
        background: var(--light-bone);
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-primary {
        background: var(--primary-green);
        color: var(--white);
    }

    .btn-primary:hover {
        background: #24382d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(45, 74, 53, 0.3);
    }

    .btn-secondary {
        background: var(--light-text);
        color: var(--white);
    }

    .btn-secondary:hover {
        background: #5a6b63;
        transform: translateY(-2px);
    }

    .btn-outline {
        background: var(--white);
        color: var(--primary-green);
        border: 2px solid var(--primary-green);
    }

    .btn-outline:hover {
        background: var(--primary-green);
        color: var(--white);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(26, 36, 18, 0.2);
    }

    .modal-header {
        background: var(--primary-dark);
        color: var(--white);
        border-bottom: 1px solid var(--border-light);
        padding: 20px;
    }

    .modal-title {
        margin: 0;
        font-weight: 600;
    }

    .close {
        background: none;
        border: none;
        color: var(--white);
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.2s ease;
    }

    .close:hover {
        opacity: 1;
    }

    .preview-summary {
        padding: 20px 0;
    }

    .preview-summary h4 {
        color: var(--primary-dark);
        margin-bottom: 20px;
        font-weight: 600;
    }

    .preview-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .preview-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .preview-label {
        color: var(--light-text);
        font-weight: 500;
    }

    .preview-value {
        color: var(--primary-dark);
        font-weight: 600;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .bid-create-container {
            padding: 15px;
        }
        
        .create-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .form-section {
            padding: 20px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .preview-card {
            flex-direction: column;
            text-align: center;
        }
        
        .preview-image {
            align-self: center;
        }
        
        .preview-meta {
            justify-content: center;
        }
        
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .bid-create-container {
            padding: 10px;
        }
        
        .header-left .page-title {
            font-size: 1.5rem;
        }
        
        .section-title {
            font-size: 1.2rem;
        }
        
        .form-section {
            padding: 15px;
        }
        
        .preview-card {
            padding: 15px;
        }
    }

    /* Loading States */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Print Styles */
    @media print {
        .create-header,
        .form-actions {
            display: none;
        }
        
        .bid-create-container {
            background: white;
            padding: 0;
        }
        
        .create-form-container {
            box-shadow: none;
            border: 1px solid #ccc;
        }
    }

</style>
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