@extends('admin.adminbase')
@section('title', 'Edit Order #' . $order->id)

@section('content')
<style>
    .order-edit-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .order-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .header-left h1 {
        margin: 0;
        color: #2d3748;
        font-size: 28px;
        font-weight: 700;
    }

    .order-date {
        color: #718096;
        margin-top: 5px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-back {
        background: #6b7280;
        color: white;
    }

    .btn-back:hover {
        background: #4b5563;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-secondary {
        background: #9ca3af;
        color: white;
    }

    .btn-secondary:hover {
        background: #6b7280;
    }

    .order-edit-form {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .edit-section {
        padding: 30px;
        border-bottom: 1px solid #e2e8f0;
    }

    .edit-section:last-child {
        border-bottom: none;
    }

    .section-title {
        margin: 0 0 20px 0;
        color: #2d3748;
        font-size: 20px;
        font-weight: 600;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 500;
    }

    .form-select, .form-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .error-message {
        display: block;
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }

    .form-help {
        display: block;
        color: #6b7280;
        font-size: 12px;
        margin-top: 5px;
    }

    .tracking-group {
        transition: all 0.3s ease;
    }

    .items-list {
        space-y-4;
    }

    .order-item {
        display: grid;
        grid-template-columns: 80px 1fr auto auto auto;
        gap: 20px;
        align-items: center;
        padding: 20px;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .order-item.readonly {
        background: #f8fafc;
        opacity: 0.8;
    }

    .item-image img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
    }

    .item-details h4 {
        margin: 0 0 5px 0;
        color: #2d3748;
        font-size: 16px;
        font-weight: 600;
    }

    .item-variation, .item-sku {
        margin: 2px 0;
        color: #6b7280;
        font-size: 14px;
    }

    .item-quantity, .item-price, .item-total {
        color: #374151;
        font-size: 14px;
    }

    .item-total {
        font-weight: 600;
        color: #2d3748;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 30px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    .status-info {
        background: #f8fafc;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid #3b82f6;
    }

    .status-info p {
        margin: 0;
        color: #374151;
    }
</style>

<div class="order-edit-container">
    <!-- Header -->
    <div class="order-header">
        <div class="header-left">
            <h1 class="order-title">Edit Order #{{ $order->id }}</h1>
            <p class="order-date">Placed on {{ $order->created_at->format('F d, Y \\a\\t h:i A') }}</p>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.manageorder.show', $order) }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Order Details
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> Please fix the errors below.
        </div>
    @endif

    <!-- Status Information -->
    <div class="status-info">
        <p><strong>Current Status:</strong> <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
        @if($order->tracking_number)
            <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
        @endif
        @if($order->shipped_at)
            <p><strong>Shipped Date:</strong> {{ $order->shipped_at->format('M d, Y h:i A') }}</p>
        @endif
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.manageorder.update', $order) }}" method="POST" class="order-edit-form">
        @csrf
        @method('PUT')

        <!-- Order Status & Tracking -->
        <div class="edit-section">
            <h3 class="section-title">Order Status & Tracking</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="status" class="form-label">Order Status *</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="">Select Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" 
                                {{ old('status', $order->status) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group tracking-group" id="trackingGroup">
                    <label for="tracking_number" class="form-label">Tracking Number</label>
                    <input type="text" 
                           name="tracking_number" 
                           id="tracking_number" 
                           class="form-input"
                           value="{{ old('tracking_number', $order->tracking_number) }}"
                           placeholder="Enter tracking number">
                    @error('tracking_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small class="form-help">Required when status is set to "Shipped"</small>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="edit-section">
            <h3 class="section-title">Order Summary</h3>
            <div class="order-summary-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Customer Information -->
                <div class="info-card" style="background: #f8fafc; padding: 20px; border-radius: 8px;">
                    <h4 style="margin: 0 0 15px 0; color: #2d3748;">Customer Information</h4>
                    <div class="info-content">
                        <div class="info-item" style="margin-bottom: 8px;">
                            <strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}
                        </div>
                        <div class="info-item" style="margin-bottom: 8px;">
                            <strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}
                        </div>
                        <div class="info-item">
                            <strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- Order Totals -->
                <div class="info-card" style="background: #f8fafc; padding: 20px; border-radius: 8px;">
                    <h4 style="margin: 0 0 15px 0; color: #2d3748;">Order Totals</h4>
                    <div class="info-content">
                        <div class="info-item" style="margin-bottom: 8px;">
                            <strong>Subtotal:</strong> RM {{ number_format($order->total_amount, 2) }}
                        </div>
                        <div class="info-item" style="margin-bottom: 8px;">
                            <strong>Shipping:</strong> RM 0.00
                        </div>
                        <div class="info-item" style="margin-bottom: 8px;">
                            <strong>Tax:</strong> RM 0.00
                        </div>
                        <div class="info-item" style="font-weight: bold; color: #2d3748;">
                            <strong>Total:</strong> RM {{ number_format($order->total_amount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="edit-section">
            <h3 class="section-title">Order Items</h3>
            <div class="items-list">
                @foreach($order->orderItems as $item)
                <div class="order-item readonly">
                    <div class="item-image">
                        <img src="{{ optional($item->product)->main_image_url ?? asset('images/default-product.png') }}" 
                            alt="{{ optional($item->product)->name ?? ($item->product_name ?? 'Product') }}"
                            onerror="this.src='{{ asset('images/default-product.png') }}'">
                    </div>
                    <div class="item-details">
                        <h4 class="item-name">{{ optional($item->product)->name ?? ($item->product_name ?? 'Product') }}</h4>
                        @if(optional($item->variation)->specifications_html)
                            <p class="item-variation">{!! $item->variation->specifications_html !!}</p>
                        @endif
                        <p class="item-sku">SKU: {{ optional($item->variation)->sku ?? optional($item->product)->sku ?? 'N/A' }}</p>
                    </div>
                    <div class="item-quantity">
                        <strong>Qty:</strong> {{ $item->quantity }}
                    </div>
                    <div class="item-price">
                        RM {{ number_format($item->price, 2) }}
                    </div>
                    <div class="item-total">
                        <strong>RM {{ number_format($item->quantity * $item->price, 2) }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.manageorder.show', $order) }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Update Order
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const trackingGroup = document.getElementById('trackingGroup');
    const trackingInput = document.getElementById('tracking_number');

    function toggleTrackingField() {
        if (statusSelect.value === 'shipped') {
            trackingGroup.style.display = 'block';
            trackingInput.setAttribute('required', 'required');
            // Add visual indication that field is required
            trackingGroup.querySelector('.form-label').innerHTML = 'Tracking Number <span style="color: #dc2626;">*</span>';
        } else {
            trackingGroup.style.display = 'block';
            trackingInput.removeAttribute('required');
            // Remove visual indication
            trackingGroup.querySelector('.form-label').innerHTML = 'Tracking Number';
        }
    }

    // Initialize on page load
    toggleTrackingField();

    // Add event listener for status change
    statusSelect.addEventListener('change', toggleTrackingField);

    // Form validation
    const form = document.querySelector('.order-edit-form');
    form.addEventListener('submit', function(e) {
        if (statusSelect.value === 'shipped' && !trackingInput.value.trim()) {
            e.preventDefault();
            alert('Please enter tracking number for shipped orders.');
            trackingInput.focus();
        }
    });
});
</script>
@endsection
