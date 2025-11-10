// Enhanced Product Form Manager
class ProductFormManager {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 5;
        this.variationCounter = 0;
        this.removedGalleryIndexes = new Set();
        this.init();
    }

    init() {
        console.log('Initializing Enhanced Product Form Manager...');
        this.bindEvents();
        this.updateNavigation();
        this.initializeCharacterCount();
        this.initializeFromOldData();
        this.setupDragAndDrop();
    }

    bindEvents() {
        // Navigation
        document.getElementById('nextBtn')?.addEventListener('click', () => this.nextStep());
        document.getElementById('prevBtn')?.addEventListener('click', () => this.prevStep());

        // Slug generation
        document.getElementById('generateSlug')?.addEventListener('click', () => this.generateSlug());
        document.getElementById('name')?.addEventListener('blur', () => {
            const slugInput = document.getElementById('slug');
            if (slugInput && !slugInput.value) {
                this.generateSlug();
            }
        });

        // Image previews
        document.getElementById('main_image')?.addEventListener('change', (e) => this.previewImage(e, 'mainImagePreview'));
        document.getElementById('product_images')?.addEventListener('change', (e) => this.previewMultipleImages(e, 'galleryPreview'));

        // Variations
        document.getElementById('has_variations')?.addEventListener('change', (e) => this.toggleVariations(e.target.checked));
        document.getElementById('addVariation')?.addEventListener('click', () => this.addVariation());

        // Form submission
        document.getElementById('productForm')?.addEventListener('submit', (e) => this.handleSubmit(e));

        // Real-time validation
        this.setupRealTimeValidation();

        // Price formatting
        this.setupPriceFormatting();
    }

    setupPriceFormatting() {
        const priceInputs = document.querySelectorAll('input[type="number"][step="0.01"]');
        priceInputs.forEach(input => {
            input.addEventListener('blur', (e) => {
                const value = parseFloat(e.target.value);
                if (!isNaN(value)) {
                    e.target.value = value.toFixed(2);
                }
            });
        });
    }

    setupDragAndDrop() {
        const uploadAreas = document.querySelectorAll('.file-upload-area');
        
        uploadAreas.forEach(area => {
            const fileInput = area.querySelector('input[type="file"]');
            if (!fileInput) return;

            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.classList.add('drag-over');
            });

            area.addEventListener('dragleave', (e) => {
                e.preventDefault();
                area.classList.remove('drag-over');
            });

            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.classList.remove('drag-over');
                
                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            });
        });
    }

    initializeFromOldData() {
        // Initialize variation counter from existing variations
        const variationsList = document.getElementById('variationsList');
        if (variationsList) {
            const existingVariations = variationsList.querySelectorAll('.variation-item');
            this.variationCounter = existingVariations.length;
            
            existingVariations.forEach((variation) => {
                const variationId = variation.id.replace('variation_', '');
                const imageInput = document.getElementById(`variations_${variationId}_image_file`);
                if (imageInput) {
                    imageInput.addEventListener('change', (e) => this.previewVariationImage(e, variationId));
                }
            });
        }

        // Initialize from old form data if exists
        this.initializeFromOldFormData();
    }

    initializeFromOldFormData() {
        // Check if we have old form data (from validation errors)
        const form = document.getElementById('productForm');
        if (!form) return;

        // If there are validation errors, show appropriate steps
        const errorFields = form.querySelectorAll('.is-invalid');
        if (errorFields.length > 0) {
            // Find the step with the first error
            let errorStep = 1;
            errorFields.forEach(field => {
                const fieldStep = this.getFieldStep(field);
                if (fieldStep > errorStep) errorStep = fieldStep;
            });
            
            // Navigate to the step with errors
            this.currentStep = errorStep;
            this.updateNavigation();
        }
    }

    getFieldStep(field) {
        // Determine which step a field belongs to
        const formStep = field.closest('.form-step');
        if (formStep) {
            return parseInt(formStep.dataset.step) || 1;
        }
        return 1;
    }

    // Step Navigation
    nextStep() {
        if (this.validateStep(this.currentStep)) {
            this.currentStep++;
            this.updateNavigation();
            this.scrollToTop();
        }
    }

    prevStep() {
        this.currentStep--;
        this.updateNavigation();
        this.scrollToTop();
    }

    updateNavigation() {
        // Update progress indicator
        const progressSteps = document.querySelectorAll('.progress-step');
        progressSteps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.toggle('active', stepNumber === this.currentStep);
            step.classList.toggle('completed', stepNumber < this.currentStep);
        });

        // Update form steps
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });
        
        const currentStepElement = document.querySelector(`.form-step[data-step="${this.currentStep}"]`);
        if (currentStepElement) {
            currentStepElement.classList.add('active');
        }

        // Update buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        if (prevBtn) prevBtn.disabled = this.currentStep === 1;
        
        if (this.currentStep === this.totalSteps) {
            if (nextBtn) nextBtn.classList.add('d-none');
            if (submitBtn) submitBtn.classList.remove('d-none');
        } else {
            if (nextBtn) nextBtn.classList.remove('d-none');
            if (submitBtn) submitBtn.classList.add('d-none');
        }
    }

    validateStep(step) {
        const stepElement = document.querySelector(`.form-step[data-step="${step}"]`);
        if (!stepElement) return true;

        let isValid = true;

        // Validate required fields
        const requiredFields = stepElement.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim() && field.offsetParent !== null) { // Only validate visible fields
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        // Step-specific validation
        switch (step) {
            case 1:
                isValid = this.validateStep1() && isValid;
                break;
            case 2:
                isValid = this.validateStep2() && isValid;
                break;
            case 4:
                isValid = this.validateStep4() && isValid;
                break;
            case 5:
                isValid = this.validateStep5() && isValid;
                break;
        }

        if (!isValid) {
            this.showToast('Please fix the errors before proceeding', 'error');
            this.highlightInvalidFields(stepElement);
        }

        return isValid;
    }

    highlightInvalidFields(stepElement) {
        const invalidFields = stepElement.querySelectorAll('.is-invalid');
        if (invalidFields.length > 0) {
            invalidFields[0].scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
    }

    validateStep1() {
        let isValid = true;
        const name = document.getElementById('name');
        const category = document.getElementById('category_id');

        if (name && !name.value.trim()) {
            this.showFieldError(name, 'Product name is required');
            isValid = false;
        } else if (name && name.value.trim().length < 2) {
            this.showFieldError(name, 'Product name must be at least 2 characters long');
            isValid = false;
        }

        if (category && !category.value) {
            this.showFieldError(category, 'Please select a category');
            isValid = false;
        }

        return isValid;
    }

    validateStep2() {
        let isValid = true;
        const price = document.getElementById('price');
        const stock = document.getElementById('stock_quantity');

        if (price && (!price.value || parseFloat(price.value) < 0)) {
            this.showFieldError(price, 'Please enter a valid price (minimum 0)');
            isValid = false;
        }

        if (stock && (stock.value === '' || parseInt(stock.value) < 0)) {
            this.showFieldError(stock, 'Please enter a valid stock quantity (minimum 0)');
            isValid = false;
        }

        return isValid;
    }

    validateStep4() {
        let isValid = true;
        const mainImage = document.getElementById('main_image');
        const mainImagePreview = document.getElementById('mainImagePreview');

        // Check if main image is required but not provided
        if (mainImage && mainImage.hasAttribute('required') && !mainImage.files.length) {
            // Check if we're editing and already have an image
            if (!mainImagePreview.querySelector('img')) {
                this.showFieldError(mainImage, 'Main image is required');
                isValid = false;
            }
        }

        // Validate main image file if provided
        if (mainImage && mainImage.files.length > 0) {
            const file = mainImage.files[0];
            if (!this.validateImageFile(file)) {
                this.showFieldError(mainImage, 'Please select a valid image file (JPG, PNG, max 2MB)');
                isValid = false;
            }
        }

        // Validate gallery images if provided
        const galleryInput = document.getElementById('product_images');
        if (galleryInput && galleryInput.files.length > 0) {
            for (let file of galleryInput.files) {
                if (!this.validateImageFile(file)) {
                    this.showFieldError(galleryInput, 'One or more gallery images are invalid (JPG, PNG, max 2MB each)');
                    isValid = false;
                    break;
                }
            }
        }

        return isValid;
    }

    validateStep5() {
        const hasVariations = document.getElementById('has_variations');
        if (hasVariations && hasVariations.checked) {
            const variationsList = document.getElementById('variationsList');
            const variations = variationsList?.querySelectorAll('.variation-item') || [];
            
            if (variations.length === 0) {
                this.showToast('Please add at least one variation when variations are enabled', 'error');
                return false;
            }

            // Validate each variation
            let allVariationsValid = true;
            variations.forEach((variation, index) => {
                const skuInput = variation.querySelector('input[name*="[sku]"]');
                const stockInput = variation.querySelector('input[name*="[stock]"]');
                
                if (skuInput && !skuInput.value.trim()) {
                    this.showFieldError(skuInput, 'SKU is required for variation');
                    allVariationsValid = false;
                }
                
                if (stockInput && (!stockInput.value || parseInt(stockInput.value) < 0)) {
                    this.showFieldError(stockInput, 'Valid stock quantity is required for variation');
                    allVariationsValid = false;
                }
            });

            return allVariationsValid;
        }
        return true;
    }

    // Slug Generation
    generateSlug() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput && nameInput.value) {
            const slug = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            this.clearFieldError(slugInput);
        }
    }

    // Image Handling
    previewImage(event, previewContainerId) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById(previewContainerId);
        
        if (!previewContainer) return;

        if (file && this.validateImageFile(file)) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewContainer.innerHTML = `
                    <div class="image-preview-container">
                        <img src="${e.target.result}" alt="Preview" class="img-thumbnail rounded" 
                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        <button type="button" class="btn-remove-image" onclick="productFormManager.removeMainImage()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
            this.clearFieldError(event.target);
        } else {
            previewContainer.innerHTML = '';
            if (file) {
                this.showFieldError(event.target, 'Invalid image file. Please select JPG, PNG, or GIF under 2MB.');
            }
        }
    }

    removeMainImage() {
        const mainImageInput = document.getElementById('main_image');
        const previewContainer = document.getElementById('mainImagePreview');
        
        if (mainImageInput) mainImageInput.value = '';
        if (previewContainer) previewContainer.innerHTML = '';
    }

    previewMultipleImages(event, previewContainerId) {
        const files = event.target.files;
        const previewContainer = document.getElementById(previewContainerId);
        if (!previewContainer) return;

        let validFiles = true;

        Array.from(files).forEach((file, index) => {
            if (this.validateImageFile(file)) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6 mb-3';
                    col.innerHTML = `
                        <div class="image-preview-container">
                            <img src="${e.target.result}" alt="Gallery image ${index + 1}" 
                                 class="img-thumbnail rounded w-100" style="height: 150px; object-fit: cover;">
                            <button type="button" class="btn-remove-image" 
                                    onclick="productFormManager.removeGalleryImage(this, ${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            } else {
                validFiles = false;
            }
        });

        if (!validFiles) {
            this.showFieldError(event.target, 'Some files are invalid. Please select valid images (JPG, PNG, max 2MB each)');
        } else {
            this.clearFieldError(event.target);
        }
    }

    removeGalleryImage(button, index) {
        // Mark this index for removal
        this.removedGalleryIndexes.add(index);
        
        // Remove the preview
        const previewContainer = button.closest('.col-md-3, .col-sm-4, .col-6');
        if (previewContainer) {
            previewContainer.remove();
        }
        
        // Update the file input (this is a simplified approach)
        this.updateFileInputAfterRemoval();
    }

    updateFileInputAfterRemoval() {
        // In a real implementation, you would need to reconstruct the FileList
        // This is a complex task, so we'll rely on server-side handling
        console.log('Gallery images removed:', Array.from(this.removedGalleryIndexes));
    }

    validateImageFile(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (!validTypes.includes(file.type)) {
            console.log('Invalid file type:', file.type);
            return false;
        }

        if (file.size > maxSize) {
            console.log('File too large:', file.size);
            return false;
        }

        return true;
    }

    // Variations Management
    toggleVariations(show) {
        const container = document.getElementById('variationsContainer');
        if (container) {
            if (show) {
                container.classList.remove('d-none');
                if (this.variationCounter === 0) {
                    this.addVariation();
                }
            } else {
                container.classList.add('d-none');
                // Clear all variations when toggled off
                document.getElementById('variationsList').innerHTML = '';
                this.variationCounter = 0;
            }
        }
    }

    addVariation() {
        this.variationCounter++;
        const template = document.getElementById('variationTemplate');
        const variationsList = document.getElementById('variationsList');
        
        if (!template || !variationsList) return;

        const variationHtml = template.innerHTML
            .replace(/TEMPLATE_INDEX/g, this.variationCounter)
            .replace(/TEMPLATE_NUMBER/g, this.variationCounter);

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = variationHtml;
        
        const variationElement = tempDiv.firstElementChild;
        variationsList.appendChild(variationElement);

        // Add event listener for variation image preview
        const imageInput = document.getElementById(`variations_${this.variationCounter}_image_file`);
        if (imageInput) {
            imageInput.addEventListener('change', (e) => this.previewVariationImage(e, this.variationCounter));
        }

        // Scroll to the new variation
        variationElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    removeVariation(index) {
        const variation = document.getElementById(`variation_${index}`);
        if (variation) {
            // Add exit animation
            variation.style.opacity = '0';
            variation.style.transform = 'translateY(-20px)';
            variation.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                variation.remove();
                this.renumberVariations();
            }, 300);
        }
    }

    renumberVariations() {
        const variations = document.querySelectorAll('.variation-item');
        this.variationCounter = variations.length;
        
        variations.forEach((variation, index) => {
            const numberSpan = variation.querySelector('.variation-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    previewVariationImage(event, variationIndex) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById(`variationImagePreview_${variationIndex}`);
        
        if (!previewContainer) return;

        if (file && this.validateImageFile(file)) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewContainer.innerHTML = `
                    <div class="image-preview-container">
                        <img src="${e.target.result}" alt="Variation Preview" 
                             class="variation-image-preview">
                        <button type="button" class="btn-remove-image" 
                                onclick="productFormManager.removeVariationImage('${variationIndex}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
            this.clearFieldError(event.target);
        } else {
            previewContainer.innerHTML = '';
            if (file) {
                this.showFieldError(event.target, 'Invalid image file');
            }
        }
    }

    removeVariationImage(variationIndex) {
        const imageInput = document.getElementById(`variations_${variationIndex}_image_file`);
        const previewContainer = document.getElementById(`variationImagePreview_${variationIndex}`);
        
        if (imageInput) imageInput.value = '';
        if (previewContainer) previewContainer.innerHTML = '';
    }

    // Character Count
    initializeCharacterCount() {
        const description = document.getElementById('description');
        const counter = document.getElementById('descriptionCount');
        
        if (description && counter) {
            description.addEventListener('input', () => {
                const count = description.value.length;
                counter.textContent = `${count}/1000`;
                counter.classList.toggle('text-danger', count > 1000);
                
                if (count > 1000) {
                    this.showFieldError(description, 'Description must be 1000 characters or less');
                } else {
                    this.clearFieldError(description);
                }
            });
            
            // Initialize count
            counter.textContent = `${description.value.length}/1000`;
        }
    }

    // Real-time Validation
    setupRealTimeValidation() {
        const form = document.getElementById('productForm');
        if (!form) return;

        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                if (input.value.trim()) {
                    this.clearFieldError(input);
                }
            });
        });
    }

    validateField(field) {
        if (field.hasAttribute('required') && !field.value.trim()) {
            this.showFieldError(field, 'This field is required');
            return false;
        }

        // Field-specific validation
        if (field.type === 'email' && field.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                return false;
            }
        }

        if (field.type === 'number' && field.value) {
            const min = parseFloat(field.getAttribute('min'));
            const max = parseFloat(field.getAttribute('max'));
            const value = parseFloat(field.value);

            if (!isNaN(min) && value < min) {
                this.showFieldError(field, `Value must be at least ${min}`);
                return false;
            }

            if (!isNaN(max) && value > max) {
                this.showFieldError(field, `Value must be at most ${max}`);
                return false;
            }
        }

        this.clearFieldError(field);
        return true;
    }

    // Utility Methods
    showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        let feedback = field.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback d-flex align-items-center';
            field.parentNode.insertBefore(feedback, field.nextSibling);
        }
        
        feedback.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
        feedback.style.display = 'flex';
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
    }

    showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.alert-toast').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show alert-toast position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }

    scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Form Submission
    async handleSubmit(event) {
        event.preventDefault();
        
        // Validate all steps before submission
        let allStepsValid = true;
        for (let step = 1; step <= this.totalSteps; step++) {
            if (!this.validateStep(step)) {
                if (allStepsValid) {
                    this.currentStep = step;
                    this.updateNavigation();
                }
                allStepsValid = false;
            }
        }

        if (!allStepsValid) {
            this.showToast('Please fix all validation errors before submitting', 'error');
            return;
        }

        const form = event.target;
        const submitBtn = document.getElementById('submitBtn');
        
        if (submitBtn) {
            this.setButtonLoading(submitBtn, true);
            
            try {
                // Additional final validation
                if (this.performFinalValidation()) {
                    // Add hidden field for removed gallery images
                    this.removedGalleryIndexes.forEach(index => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'removed_gallery_images[]';
                        input.value = index;
                        form.appendChild(input);
                    });

                    form.submit();
                } else {
                    this.showToast('Please fix the validation errors', 'error');
                    this.setButtonLoading(submitBtn, false);
                }
            } catch (error) {
                console.error('Form submission error:', error);
                this.showToast('An error occurred while creating the product', 'error');
                this.setButtonLoading(submitBtn, false);
            }
        } else {
            form.submit();
        }
    }

    performFinalValidation() {
        // Final comprehensive validation
        const requiredFields = document.querySelectorAll('#productForm [required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim() && field.offsetParent !== null) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            }
        });

        return isValid;
    }

    setButtonLoading(button, isLoading) {
        if (isLoading) {
            button.disabled = true;
            button.classList.add('btn-loading');
        } else {
            button.disabled = false;
            button.classList.remove('btn-loading');
        }
    }
}

// Initialize the form manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.productFormManager = new ProductFormManager();
});

// Global functions for template usage
window.removeVariation = function(index) {
    if (window.productFormManager) {
        window.productFormManager.removeVariation(index);
    }
};

window.previewVariationImage = function(event, index) {
    if (window.productFormManager) {
        window.productFormManager.previewVariationImage(event, index);
    }
};