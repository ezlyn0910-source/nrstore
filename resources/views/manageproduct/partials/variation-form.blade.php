<div class="variation-item" id="variation_{{ $index }}">
    <div class="variation-header">
        <h6 class="variation-title">Variation #<span class="variation-number">{{ is_numeric($index) ? $index + 1 : 1 }}</span></h6>
        <button type="button" class="btn-remove-variation" onclick="removeVariation('{{ $index }}')">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_sku" class="form-label">SKU <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error("variations.{$index}.sku") is-invalid @enderror" 
                   id="variations_{{ $index }}_sku" name="variations[{{ $index }}][sku]" 
                   value="{{ old("variations.{$index}.sku") }}" required>
            @error("variations.{$index}.sku")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_price" class="form-label">Price (RM)</label>
            <input type="number" step="0.01" min="0" class="form-control @error("variations.{$index}.price") is-invalid @enderror" 
                   id="variations_{{ $index }}_price" name="variations[{{ $index }}][price]" 
                   value="{{ old("variations.{$index}.price") }}">
            @error("variations.{$index}.price")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_stock" class="form-label">Stock <span class="text-danger">*</span></label>
            <input type="number" min="0" class="form-control @error("variations.{$index}.stock") is-invalid @enderror" 
                   id="variations_{{ $index }}_stock" name="variations[{{ $index }}][stock]" 
                   value="{{ old("variations.{$index}.stock", 0) }}" required>
            @error("variations.{$index}.stock")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_model" class="form-label">Model</label>
            <input type="text" class="form-control @error("variations.{$index}.model") is-invalid @enderror" 
                   id="variations_{{ $index }}_model" name="variations[{{ $index }}][model]" 
                   value="{{ old("variations.{$index}.model") }}">
            @error("variations.{$index}.model")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4 mb-3">
            <label for="variations_{{ $index }}_processor" class="form-label">Processor</label>
            <input type="text" class="form-control @error("variations.{$index}.processor") is-invalid @enderror" 
                   id="variations_{{ $index }}_processor" name="variations[{{ $index }}][processor]" 
                   value="{{ old("variations.{$index}.processor") }}">
            @error("variations.{$index}.processor")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4 mb-3">
            <label for="variations_{{ $index }}_ram" class="form-label">RAM</label>
            <input type="text" class="form-control @error("variations.{$index}.ram") is-invalid @enderror" 
                   id="variations_{{ $index }}_ram" name="variations[{{ $index }}][ram]" 
                   value="{{ old("variations.{$index}.ram") }}">
            @error("variations.{$index}.ram")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4 mb-3">
            <label for="variations_{{ $index }}_storage" class="form-label">Storage</label>
            <input type="text" class="form-control @error("variations.{$index}.storage") is-invalid @enderror" 
                   id="variations_{{ $index }}_storage" name="variations[{{ $index }}][storage]" 
                   value="{{ old("variations.{$index}.storage") }}">
            @error("variations.{$index}.storage")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_storage_type" class="form-label">Storage Type</label>
            <input type="text" class="form-control @error("variations.{$index}.storage_type") is-invalid @enderror" 
                   id="variations_{{ $index }}_storage_type" name="variations[{{ $index }}][storage_type]" 
                   value="{{ old("variations.{$index}.storage_type") }}">
            @error("variations.{$index}.storage_type")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_graphics_card" class="form-label">Graphics Card</label>
            <input type="text" class="form-control @error("variations.{$index}.graphics_card") is-invalid @enderror" 
                   id="variations_{{ $index }}_graphics_card" name="variations[{{ $index }}][graphics_card]" 
                   value="{{ old("variations.{$index}.graphics_card") }}">
            @error("variations.{$index}.graphics_card")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4 mb-3">
            <label for="variations_{{ $index }}_screen_size" class="form-label">Screen Size</label>
            <input type="text" class="form-control @error("variations.{$index}.screen_size") is-invalid @enderror" 
                   id="variations_{{ $index }}_screen_size" name="variations[{{ $index }}][screen_size]" 
                   value="{{ old("variations.{$index}.screen_size") }}">
            @error("variations.{$index}.screen_size")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4 mb-3">
            <label for="variations_{{ $index }}_os" class="form-label">Operating System</label>
            <input type="text" class="form-control @error("variations.{$index}.os") is-invalid @enderror" 
                   id="variations_{{ $index }}_os" name="variations[{{ $index }}][os]" 
                   value="{{ old("variations.{$index}.os") }}">
            @error("variations.{$index}.os")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4 mb-3">
            <label for="variations_{{ $index }}_warranty" class="form-label">Warranty</label>
            <input type="text" class="form-control @error("variations.{$index}.warranty") is-invalid @enderror" 
                   id="variations_{{ $index }}_warranty" name="variations[{{ $index }}][warranty]" 
                   value="{{ old("variations.{$index}.warranty") }}">
            @error("variations.{$index}.warranty")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_voltage" class="form-label">Voltage</label>
            <input type="text" class="form-control @error("variations.{$index}.voltage") is-invalid @enderror" 
                   id="variations_{{ $index }}_voltage" name="variations[{{ $index }}][voltage]" 
                   value="{{ old("variations.{$index}.voltage") }}">
            @error("variations.{$index}.voltage")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="variations_{{ $index }}_image_file" class="form-label">Variation Image</label>
            <input type="file" class="form-control @error("variations.{$index}.image_file") is-invalid @enderror" 
                   id="variations_{{ $index }}_image_file" name="variations[{{ $index }}][image_file]" 
                   accept="image/*">
            @error("variations.{$index}.image_file")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="variationImagePreview_{{ $index }}" class="mt-2"></div>
        </div>
    </div>
</div>