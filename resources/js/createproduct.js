// Enhanced Product Form Manager
class ProductFormManager {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 5;
        this.variationCounter = 0;
        this.formData = new FormData();
        this.init();
    }

    init() {
        console.log('Initializing Product Form Manager...');
        this.bindEvents();
        this.updateNavigation();
        this.initializeCharacterCount();
        this.initializeFromOldData();
    }

    bindEvents() {
        // Navigation
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        
        if (nextBtn) nextBtn.addEventListener('click', () => this.nextStep());
        if (prevBtn) prevBtn.addEventListener('click', () => this.prevStep());

        // Slug generation
        const generateSlugBtn = document.getElementById('generateSlug');
        const nameInput = document.getElementById('name');
        
        if (generateSlugBtn) generateSlugBtn.addEventListener('click', () => this.generateSlug());
        if (nameInput) nameInput.addEventListener('blur', () => {
            const slugInput = document.getElementById('slug');
            if (slugInput && !slugInput.value) {
                this.generateSlug();
            }
        });

        // Image previews
        const mainImageInput = document.getElementById('main_image');
        const galleryInput = document.getElementById('product_images');
        
        if (mainImageInput) mainImageInput.addEventListener('change', (e) => this.previewImage(e, 'mainImagePreview'));
        if (galleryInput) galleryInput.addEventListener('change', (e) => this.previewMultipleImages(e, 'galleryPreview'));

        // Variations
        const hasVariationsCheckbox = document.getElementById('has_variations');
        const addVariationBtn = document.getElementById('addVariation');
        
        if (hasVariationsCheckbox) hasVariationsCheckbox.addEventListener('change', (e) => this.toggleVariations(e.target.checked));
        if (addVariationBtn) addVariationBtn.addEventListener('click', () => this.addVariation());

        // Form submission
        const form = document.getElementById('productForm');
        if (form) form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Real-time validation
        this.setupRealTimeValidation();
    }

    initializeFromOldData() {
        // Initialize variation counter from existing variations
        const variationsList = document.getElementById('variationsList');
        if (variationsList) {
            const existingVariations = variationsList.querySelectorAll('.variation-item');
            this.variationCounter = existingVariations.length;
            
            // Add event listeners to existing variation image inputs
            existingVariations.forEach((variation, index) => {
                const variationId = variation.id.replace('variation_', '');
                const imageInput = document.getElementById(`variations_${variationId}_image_file`);
                if (imageInput) {
                    imageInput.addEventListener('change', (e) => this.previewVariationImage(e, variationId));
                }
            });
        }
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
        const formSteps = document.querySelectorAll('.form-step');
        formSteps.forEach(step => {
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

        const requiredFields = stepElement.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
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
        }

        return isValid;
    }

    validateStep1() {
        let isValid = true;
        const name = document.getElementById('name');
        const category = document.getElementById('category_id');

        if (name && !name.value.trim()) {
            this.showFieldError(name, 'Product name is required');
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

        if (price && (!price.value || parseFloat(price.value) <= 0)) {
            this.showFieldError(price, 'Please enter a valid price');
            isValid = false;
        }

        if (stock && (!stock.value || parseInt(stock.value) < 0)) {
            this.showFieldError(stock, 'Please enter a valid stock quantity');
            isValid = false;
        }

        return isValid;
    }

    validateStep4() {
        let isValid = true;
        const mainImage = document.getElementById('main_image');

        if (mainImage && !mainImage.files.length) {
            this.showFieldError(mainImage, 'Main image is required');
            isValid = false;
        } else if (mainImage && mainImage.files.length) {
            const file = mainImage.files[0];
            if (!this.validateImageFile(file)) {
                this.showFieldError(mainImage, 'Please select a valid image file (JPG, PNG, max 2MB)');
                isValid = false;
            }
        }

        return isValid;
    }

    validateStep5() {
        const hasVariations = document.getElementById('has_variations');
        if (hasVariations && hasVariations.checked) {
            const variationsList = document.getElementById('variationsList');
            if (variationsList && variationsList.children.length === 0) {
                this.showToast('Please add at least one variation when variations are enabled', 'error');
                return false;
            }
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
                        <img src="${e.target.result}" alt="Preview" class="img-thumbnail rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        <button type="button" class="btn-remove-image" onclick="this.closest('.image-preview-container').remove(); document.getElementById('main_image').value = '';">
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

    previewMultipleImages(event, previewContainerId) {
        const files = event.target.files;
        const previewContainer = document.getElementById(previewContainerId);
        if (!previewContainer) return;

        previewContainer.innerHTML = '';
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
                            <button type="button" class="btn-remove-image" onclick="this.closest('.col-md-3').remove(); productFormManager.updateFileInput(${index})">
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

    updateFileInput(removeIndex) {
        // Note: This is a simplified version. In production, you might want to use a more robust solution
        // that actually updates the FileList object, which requires more complex handling.
        console.log('File removal would be handled here for index:', removeIndex);
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
    }

    removeVariation(index) {
        const variation = document.getElementById(`variation_${index}`);
        if (variation) {
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
                             class="img-thumbnail rounded" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                        <button type="button" class="btn-remove-image" 
                                onclick="this.closest('.image-preview-container').remove(); 
                                         document.getElementById('variations_${variationIndex}_image_file').value = '';">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = '';
            if (file) {
                this.showFieldError(event.target, 'Invalid image file');
            }
        }
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
                if (input.hasAttribute('required') && !input.value.trim()) {
                    this.showFieldError(input, 'This field is required');
                } else {
                    this.clearFieldError(input);
                }
            });
            
            input.addEventListener('input', () => {
                if (input.value.trim()) {
                    this.clearFieldError(input);
                }
            });
        });
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
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.remove();
        }
    }

    showToast(message, type = 'info') {
        // Simple toast implementation - you can replace with a proper toast library
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Form Submission
    async handleSubmit(event) {
        event.preventDefault();
        
        // Validate all steps before submission
        for (let step = 1; step <= this.totalSteps; step++) {
            if (!this.validateStep(step)) {
                this.currentStep = step;
                this.updateNavigation();
                this.showToast('Please fix all validation errors before submitting', 'error');
                return;
            }
        }

        const form = event.target;
        const submitBtn = document.getElementById('submitBtn');
        
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating Product...';
            submitBtn.disabled = true;
            
            try {
                // Additional final validation can be added here
                form.submit();
            } catch (error) {
                console.error('Form submission error:', error);
                this.showToast('An error occurred while creating the product', 'error');
                if (submitBtn) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }
        } else {
            form.submit();
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