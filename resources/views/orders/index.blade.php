@extends('layouts.app')

@section('content')
<style>
.orders-page {
    background: #f8f9fa;
    min-height: 100vh;
}

/* Page Title */
.page-title-section {
    width: 100%;
    padding: 2.5rem 0;
    position: relative;
    z-index: 1;
}

.page-title-container {
    text-align: center;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.25rem 0;
    text-align: center;
}

/* Main Content */
.main-content-section {
    padding: 0;
    position: relative;
    margin-top: 0;
    z-index: 5;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Two Column Layout */
.orders-layout {
    display: grid;
    grid-template-columns: 230px 1fr;
    gap: 1.5rem;
    margin: 0 auto;
    padding: 0;
    max-width: 1200px;
}

/* Left Column - Categories */
.categories-column {
    padding: 0 !important;
    height: fit-content;
    position: relative;
    z-index: 1;
}

.categories-title {
    font-size: 1.375rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--dark-text);
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--primary-green);
}

.status-categories {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    align-items: flex-start;
    pointer-events: auto !important;
}

.status-category {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 1.5rem;
    background: var(--white);
    border: 2px solid var(--border-light);
    border-radius: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    width: 100%;
    font-size: 1rem;
    position: relative;
    z-index: 10;
    user-select: none;
    outline: none;
}

.status-category:hover {
    border-color: var(--primary-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.15);
}

.status-category:active {
    transform: translateY(0);
}

.status-category.active {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: var(--white);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(45, 74, 53, 0.25);
}

.status-text {
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
    flex: 1;
}

.status-count {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    background: #6b7280;
    border-radius: 50%;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--white);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.status-category.active .status-count {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary-green);
}

/* Right Column - Orders */
.orders-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-right: 0;
}

.orders-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Order Card */
.order-card {
    background: var(--white);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    overflow: hidden;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--primary-green);
}

/* Order Header */
.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-id-section {
    display: flex;
    flex-direction: column;
}

.order-id-label {
    font-size: 0.9rem;
    color: var(--grey-text);
    margin-bottom: 0.2rem;
    font-weight: 500;
}

.order-id-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--dark-text);
    letter-spacing: 0.5px;
    margin-bottom: 0.2rem;
}

.order-date {
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.shipping-address {
    font-size: 0.85rem;
    color: #4b5563;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.order-status-badge {
    padding: 0.5rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 110px;
    text-align: center;
    display: inline-block;
    white-space: nowrap;
    box-sizing: border-box;
}

.order-status-badge.pending {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #f59e0b;
}

.order-status-badge.processing {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #3b82f6;
}

.order-status-badge.shipped {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.order-status-badge.delivered {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #22c55e;
}

.order-status-badge.paid {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #22c55e;
}

.order-status-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

/* Ensure filter empty state styles */
.filter-empty-state {
    margin-top: 2rem;
    animation: fadeIn 0.5s ease;
    grid-column: 1 / -1;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Make sure orders container handles empty state properly */
.orders-container {
    position: relative;
    min-height: 200px;
}

/* Ensure order cards have smooth transitions */
.order-card {
    transition: all 0.3s ease;
}

/* Debug styles (remove in production) */
.debug-border {
    border: 1px solid red !important;
}

/* Shipping Section */
.shipping-section {
    margin-bottom: 2rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 0.75rem;
    border-left: 4px solid var(--primary-green);
}

.shipping-label {
    font-size: 0.875rem;
    color: var(--grey-text);
    margin-bottom: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Order Items */
.order-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    gap: 0;
    padding: 0;
    background: var(--white);
    border: 2px solid var(--border-light);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    align-items: stretch;
}

.order-item:hover {
    border-color: var(--primary-green);
    transform: translateX(4px);
}

.item-image {
    width: 120px;
    height: 120px;
    flex-shrink: 0;
    border-radius: 0.75rem 0 0 0.75rem;
    overflow: hidden;
    border: none;
    border-right: 1px solid var(--border-light);
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.order-item:hover .item-image img {
    transform: scale(1.05);
}

.item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 1rem;
    gap: 0;
}

.item-top-row {
    margin-bottom: 0.5rem;
}

.item-name-specs {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.item-name {
    font-weight: 700;
    color: var(--dark-text);
    font-size: 1.125rem;
    margin: 0;
}

.item-specs {
    font-size: 0.875rem;
    color: var(--grey-text);
    margin: 0;
}

.item-bottom-row {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.item-price {
    font-size: 1rem;
    color: var(--dark-text);
    font-weight: 700;
}

.item-quantity {
    font-size: 0.875rem;
    color: var(--grey-text);
    font-weight: 600;
    margin: 0;
}

.item-total {
    font-size: 1rem;
    color: var(--primary-green);
    font-weight: 700;
    margin-left: auto;
}

/* Order Footer */
.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: #efefef;
    border-radius: 0 0 0.75rem 0.75rem;
    margin: 1rem -1.5rem -1.5rem -1.5rem;
    margin-top: 1rem;
    min-height: auto;
}

.order-total-section {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.order-total {
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark-text);
}

.item-count {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
    margin-left: 0.5rem;
}

.payment-status {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Order Actions */
.order-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.cancel-btn {
    padding: 0.5rem 1.25rem;
    background: #dc2626;
    color: var(--white);
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.cancel-btn:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.cancel-btn:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
    background: var(--white);
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 2px dashed var(--border-light);
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: 2rem;
    opacity: 0.7;
}

.empty-state h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--light-text);
    margin-bottom: 2.5rem;
    max-width: 400px;
    line-height: 1.6;
    font-size: 1.125rem;
}

.btn-primary {
    padding: 0.5rem 2rem;
    background: var(--primary-green);
    color: var(--white);
    border: none;
    border-radius: 2rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    display: inline-block;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(45, 74, 53, 0.4);
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
}

.pagination {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.pagination-btn,
.pagination-number {
    padding: 0.75rem 1.25rem;
    border: 2px solid var(--border-light);
    background: var(--white);
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.875rem;
}

.pagination-number.active {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.3);
}

.pagination-btn:hover,
.pagination-number:hover:not(.active) {
    border-color: var(--primary-green);
    transform: translateY(-1px);
}

/* Payment Status Badges */
.payment-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid;
    display: inline-block;
    margin-left: 0.25rem;
}

.payment-status-badge.paid { 
    background: #d1fae5; 
    color: #065f46; 
    border-color: #10b981;
}

.payment-status-badge.pending { 
    background: #fef3c7; 
    color: #92400e; 
    border-color: #f59e0b;
}

.payment-status-badge.failed { 
    background: #fee2e2; 
    color: #991b1b; 
    border-color: #ef4444;
}

/* CSS Variables */
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-gold: #daa112;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
    --grey-bg: #f5f5f5;
    --grey-text: #6b7280;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .orders-layout {
        grid-template-columns: 280px 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .page-title-container {
        padding: 1.5rem 0 1.5rem;
    }

    .page-title {
        font-size: 2.5rem;
    }

    .orders-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .orders-column {
        order: 1;
    }

    .order-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .order-footer {
        flex-direction: column;
        gap: 1.5rem;
        align-items: stretch;
    }

    .cancel-btn {
        align-self: center;
        width: 100%;
        max-width: 200px;
    }

    .order-item {
        flex-direction: column;
    }

    .item-image {
        width: 100%;
        height: 200px;
        border-radius: 0.75rem 0.75rem 0 0;
        border-right: none;
        border-bottom: 1px solid var(--border-light);
    }

    .item-details {
        gap: 1rem;
    }
    
    .item-bottom-row {
        justify-content: space-between;
    }
    
    .item-total {
        margin-left: 0;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 2rem;
    }

    .status-category {
        padding: 1rem 1.25rem;
    }

    .order-card {
        padding: 1.5rem;
    }

    .order-id-value {
        font-size: 1.25rem;
    }

    .order-total {
        font-size: 1.25rem;
    }

    .shipping-section {
        padding: 1rem;
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 4rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
    }

    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
}

/* Status badges for popup */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid;
    display: inline-block;
}

.status-badge.pending { 
    background: #fef3c7; 
    color: #92400e; 
    border-color: #f59e0b;
}

.status-badge.processing { 
    background: #dbeafe; 
    color: #1e40af; 
    border-color: #3b82f6;
}

.status-badge.shipped { 
    background: #d1fae5; 
    color: #065f46; 
    border-color: #10b981;
}

.status-badge.delivered { 
    background: #dcfce7; 
    color: #166534; 
    border-color: #22c55e;
}

.status-badge.cancelled { 
    background: #fee2e2; 
    color: #991b1b; 
    border-color: #ef4444;
}

/* Spinner animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Add some styles for the filter empty state */
.filter-empty-state {
    margin-top: 2rem;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Make sure buttons are properly clickable */
.status-category {
    cursor: pointer !important;
}

/* Additional styles for the filtering system */
.no-orders-state {
    margin-top: 2rem;
}

.show-all-btn {
    margin-top: 1rem;
}

/* Ensure buttons are properly styled */
.status-category:focus {
    outline: 2px solid var(--primary-green);
    outline-offset: 2px;
}

/* Fix for pagination styling */
.pagination .page-link {
    padding: 0.75rem 1.25rem;
    border: 2px solid var(--border-light);
    background: var(--white);
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    color: var(--dark-text);
    display: block;
}

.pagination .page-item.active .page-link {
    background: var(--primary-green);
    color: var(--white);
    border-color: var(--primary-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 74, 53, 0.3);
}

.pagination .page-link:hover {
    border-color: var(--primary-green);
    transform: translateY(-1px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .orders-layout {
        grid-template-columns: 1fr;
    }
    
    .categories-column {
        order: 2;
        margin-top: 2rem;
    }
    
    .status-categories {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .status-category {
        width: auto;
        min-width: 140px;
    }
}

@media (max-width: 480px) {
    .status-category {
        min-width: 120px;
        padding: 0.5rem 1rem;
    }
    
    .status-text {
        font-size: 0.8rem;
    }
    
    .status-count {
        width: 24px;
        height: 24px;
        font-size: 0.75rem;
    }
}

/* Add these styles to your existing CSS file */
.order-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    padding: 20px;
    overflow-y: auto;
}

.order-popup-content {
    background: white;
    border-radius: 12px;
    width: 95%;
    max-width: 1200px;
    max-height: 90vh;
    overflow-y: auto;
    animation: popupFadeIn 0.3s ease;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

@keyframes popupFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: var(--primary-dark);
    color: white;
    border-radius: 12px 12px 0 0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.popup-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.close-popup {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.3s;
}

.close-popup:hover {
    background: rgba(255, 255, 255, 0.2);
}

.popup-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding: 2rem;
}

.popup-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.popup-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-green);
}

/* Order Details Column */
.order-details-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.info-value {
    font-size: 1rem;
    color: var(--dark-text);
    font-weight: 600;
}

/* Invoice Column */
.invoice-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.invoice-header {
    text-align: center;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--primary-green);
}

.invoice-title {
    color: var(--primary-dark);
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
}

.invoice-number {
    color: #6b7280;
    font-size: 0.875rem;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.invoice-table th {
    background: #e9ecef;
    padding: 0.75rem;
    text-align: left;
    font-weight: 600;
    color: var(--dark-text);
    border-bottom: 2px solid #dee2e6;
}

.invoice-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #dee2e6;
}

.invoice-table .text-right {
    text-align: right;
}

.invoice-table .text-left {
    text-align: left;
}

.invoice-table .text-center {
    text-align: center;
}

.invoice-totals {
    margin-top: 1rem;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    font-size: 0.875rem;
}

.total-row.grand-total {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--primary-dark);
    border-top: 2px solid #dee2e6;
    margin-top: 0.5rem;
    padding-top: 0.75rem;
}

.order-actions-popup {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

.btn-print {
    background: var(--primary-green);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    flex: 1;
}

.btn-print:hover {
    background: var(--primary-dark);
}

.btn-download {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    flex: 1;
}

.btn-download:hover {
    background: #5a6268;
}

/* Status Timeline */
.status-timeline {
    position: relative;
    padding-left: 1.5rem;
}

.status-timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #6b7280;
    border: 2px solid white;
}

.timeline-item.completed::before {
    background: var(--primary-green);
}

.timeline-date {
    font-size: 0.75rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.timeline-status {
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 0.25rem;
}

.timeline-notes {
    font-size: 0.875rem;
    color: #6b7280;
}

/* Responsive Design */
@media (max-width: 992px) {
    .popup-body {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .order-info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .order-popup-content {
        width: 100%;
        max-height: 95vh;
    }
    
    .popup-header {
        padding: 1rem;
    }
    
    .popup-body {
        padding: 1rem;
    }
    
    .order-actions-popup {
        flex-direction: column;
    }
}

/* Add to your existing CSS */
.order-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    padding: 20px;
    overflow-y: auto;
}

.order-popup-content {
    background: white;
    border-radius: 12px;
    width: 95%;
    max-width: 1200px;
    max-height: 90vh;
    overflow-y: auto;
    animation: popupFadeIn 0.3s ease;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

@keyframes popupFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: var(--primary-dark);
    color: white;
    border-radius: 12px 12px 0 0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.popup-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.close-popup {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.3s;
}

.close-popup:hover {
    background: rgba(255, 255, 255, 0.2);
}

.popup-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding: 2rem;
}

@media (max-width: 992px) {
    .popup-body {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .order-info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .order-popup-content {
        width: 100%;
        max-height: 95vh;
    }
    
    .popup-header {
        padding: 1rem;
    }
    
    .popup-body {
        padding: 1rem;
    }
    
    .order-actions-popup {
        flex-direction: column;
    }
}

/* Ensure order card is clickable */
.order-card {
    cursor: pointer !important;
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

</style>

<div class="orders-page">

    <!-- Debug Section (remove after testing) -->
    <div style="display: none; background: #f0f0f0; padding: 10px; margin: 10px; border-radius: 5px;">
        <h4>Debug Info:</h4>
        @php
            $statuses = [];
            foreach($orders as $order) {
                $statuses[] = $order->status;
            }
        @endphp
        <p>All Statuses in Database: {{ implode(', ', array_unique($statuses)) }}</p>
        <p>Order Count: {{ $orders->count() }}</p>
    </div>
    
    <section class="page-title-section">
        <div class="container">
            <div class="page-title-container">
                <h1 class="page-title">Orders</h1>
            </div>
        </div>
    </section>

    <section class="main-content-section">
        <div class="container">
            <div class="orders-layout">
                <div class="categories-column">
                    <div class="status-categories">
                        <button type="button" class="status-category active" data-status="all">
                            <span class="status-text">All Orders</span>
                            <span class="status-count">{{ $orders->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="processing">
                            <span class="status-text">Processing</span>
                            <span class="status-count">{{ $orders->where('status', 'processing')->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="shipped">
                            <span class="status-text">Shipped</span>
                            <span class="status-count">{{ $orders->where('status', 'shipped')->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="delivered">
                            <span class="status-text">Delivered</span>
                            <span class="status-count">{{ $orders->where('status', 'delivered')->count() }}</span>
                        </button>

                        <button type="button" class="status-category" data-status="cancelled">
                            <span class="status-text">Cancelled</span>
                            <span class="status-count">{{ $orders->where('status', 'cancelled')->count() }}</span>
                        </button>
                    </div>
                </div>

                <div class="orders-column">
                    <div class="orders-container">
                        @if(isset($isGuest) && $isGuest)
                            <div class="empty-state">
                                <div class="empty-icon">🔒</div>
                                <h3>Please Log In</h3>
                                <p>You need to be logged in to view your orders.</p>
                                <a href="{{ route('login') }}" class="btn-primary">Log In</a>
                            </div>
                        @elseif($orders->count() > 0)
                            @foreach($orders as $order)
                            <div class="order-card" onclick="openOrderPopup({{ $order->id }})">
                                <div class="order-header">
                                    <div class="order-id-section">
                                        <div class="order-id-label">Order ID</div>
                                        <div class="order-id-value">#{{ $order->order_number }}</div>
                                        <div class="order-date">
                                            Ordered on: {{ $order->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="shipping-address">
                                            Deliver to: 
                                            @if($order->shippingAddress)
                                                @php
                                                    $addressParts = [
                                                        $order->shippingAddress->full_name,
                                                        $order->shippingAddress->address_line_1,
                                                        $order->shippingAddress->address_line_2,
                                                        $order->shippingAddress->city,
                                                        $order->shippingAddress->state,
                                                        $order->shippingAddress->postal_code,
                                                        $order->shippingAddress->country
                                                    ];
                                                    $addressLine = implode(', ', array_filter($addressParts, function($part) {
                                                        return !empty($part);
                                                    }));
                                                @endphp
                                                {{ $addressLine }}
                                                @if($order->shippingAddress->phone)
                                                    <br><small>Phone: {{ $order->shippingAddress->phone }}</small>
                                                @endif
                                            @else
                                                Address not available
                                            @endif
                                        </div>
                                    </div>
                                    <div class="order-status-badge {{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </div>

                                <div class="order-items">
                                    @foreach($order->orderItems as $item)
                                    <div class="order-item">
                                        <div class="item-image">
                                            @if($item->product && $item->product->main_image_url)
                                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}">
                                            @else
                                                <img src="{{ asset('images/default-product.png') }}" alt="Product Image">
                                            @endif
                                        </div>
                                        <div class="item-details">
                                            <div class="item-top-row">
                                                <div class="item-name-specs">
                                                    <span class="item-name">{{ $item->product_name ?? ($item->product->name ?? 'Product Not Available') }}</span>
                                                    <span class="item-specs">
                                                        @if($item->variation)
                                                            @php
                                                                $specs = [];
                                                                if ($item->variation->processor) $specs[] = $item->variation->processor;
                                                                if ($item->variation->ram) $specs[] = $item->variation->ram;
                                                                if ($item->variation->storage) $specs[] = $item->variation->storage;
                                                                if (!empty($specs)) {
                                                                    echo '('.implode(' | ', $specs).')';
                                                                }
                                                            @endphp
                                                        @elseif($item->product)
                                                            @if($item->product->processor || $item->product->ram || $item->product->storage)
                                                                @php
                                                                    $specs = [];
                                                                    if ($item->product->processor) $specs[] = $item->product->processor;
                                                                    if ($item->product->ram) $specs[] = $item->product->ram;
                                                                    if ($item->product->storage) $specs[] = $item->product->storage;
                                                                    if (!empty($specs)) {
                                                                        echo '('.implode(' | ', $specs).')';
                                                                    }
                                                                @endphp
                                                            @elseif($item->product->specifications)
                                                                ({{ $item->product->specifications }})
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="item-bottom-row">
                                                <div class="item-price">RM{{ number_format($item->price, 2) }}</div>
                                                <div class="item-quantity">Qty: {{ $item->quantity }}</div>
                                                <div class="item-total">RM{{ number_format($item->price * $item->quantity, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="order-footer">
                                    <div class="order-total-section">
                                        <div class="order-total">
                                            Total: RM{{ number_format($order->total_amount, 2) }}
                                            <span class="item-count">
                                                ({{ $order->orderItems->sum('quantity') }} 
                                                @if($order->orderItems->sum('quantity') == 1)
                                                    item
                                                @else
                                                    items
                                                @endif
                                                )
                                            </span>
                                        </div>
                                        @if($order->payment_status)
                                        <div class="payment-status">
                                            Payment: <span class="payment-status-badge {{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="order-actions">
                                        @if(in_array($order->status, ['processing']))
                                            <button class="cancel-btn" onclick="event.stopPropagation()" data-order-id="{{ $order->id }}">
                                                Cancel Order
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">📦</div>
                                <h3>No Orders Yet</h3>
                                <p>You haven't placed any orders. Start shopping to see your orders here.</p>
                                <a href="{{ route('products.index') }}" class="btn-primary">Start Shopping</a>
                            </div>
                        @endif
                    </div>

                    @if($orders->count() > 0 && method_exists($orders, 'hasPages') && $orders->hasPages())
                    <div class="pagination-container">
                        <div class="pagination">
                            {{ $orders->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Order Details Popup -->
    <div class="order-popup-overlay" id="orderPopup">
        <div class="order-popup-content">
            <div class="popup-header">
                <h2 id="popupOrderNumber">Order Details</h2>
                <button class="close-popup" onclick="closeOrderPopup()">&times;</button>
            </div>
            <div class="popup-body">
                <div id="popupLoading" style="display:none; text-align:center; padding:20px;">
                    Loading order details...
                </div>
                <!-- Left Column - Order Details -->
                <div class="order-details-column">
                    <!-- Order Information -->
                    <div class="popup-section">
                        <h3 class="popup-section-title">Order Information</h3>
                        <div class="order-info-grid">
                            <div class="info-item">
                                <span class="info-label">Order Number</span>
                                <span class="info-value" id="popupOrderNum"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Order Date</span>
                                <span class="info-value" id="popupOrderDate"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Order Status</span>
                                <span class="info-value" id="popupOrderStatus"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Payment Status</span>
                                <span class="info-value" id="popupPaymentStatus"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tracking Number</span>
                                <span class="info-value" id="popupTrackingNumber"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Payment Method</span>
                                <span class="info-value" id="popupPaymentMethod"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="popup-section">
                        <h3 class="popup-section-title">Shipping Address</h3>
                        <div id="popupShippingAddress">Loading...</div>
                    </div>

                    <!-- Billing Address -->
                    <div class="popup-section">
                        <h3 class="popup-section-title">Billing Address</h3>
                        <div id="popupBillingAddress">Loading...</div>
                    </div>

                    <!-- Order Status Timeline -->
                    <div class="popup-section">
                        <h3 class="popup-section-title">Order Status History</h3>
                        <div class="status-timeline" id="popupStatusTimeline">
                            <div class="timeline-item">
                                <div class="timeline-date">Loading...</div>
                                <div class="timeline-status">Loading status</div>
                                <div class="timeline-notes">Loading notes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Invoice -->
                <div class="invoice-column">
                    <!-- Invoice Header -->
                    <div class="invoice-header">
                        <h3 class="invoice-title">INVOICE</h3>
                        <div class="invoice-number" id="popupInvoiceNumber"></div>
                    </div>

                    <!-- Customer Information -->
                    <div class="popup-section">
                        <h3 class="popup-section-title">Customer Information</h3>
                        <div id="popupCustomerInfo">Loading...</div>
                    </div>

                    <!-- Order Items -->
                    <div class="popup-section">
                        <h3 class="popup-section-title">Order Items</h3>
                        <table class="invoice-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="popupOrderItems">
                                <!-- Items will be populated here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Invoice Totals -->
                    <div class="popup-section">
                        <h3 class="popup-section-title">Invoice Summary</h3>
                        <div class="invoice-totals">
                            <div class="total-row">
                                <span>Subtotal:</span>
                                <span id="popupSubtotal">RM 0.00</span>
                            </div>
                            <div class="total-row">
                                <span>Shipping Cost:</span>
                                <span id="popupShippingCost">RM 0.00</span>
                            </div>
                            <div class="total-row">
                                <span>Tax Amount:</span>
                                <span id="popupTaxAmount">RM 0.00</span>
                            </div>
                            <div class="total-row">
                                <span>Discount:</span>
                                <span id="popupDiscountAmount">-RM 0.00</span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Total Amount:</span>
                                <span id="popupGrandTotal">RM 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Actions -->
                    <div class="order-actions-popup">
                        <button class="btn-print" onclick="printInvoice()">
                            <i class="fas fa-print"></i> Print Invoice
                        </button>
                        <button class="btn-download" onclick="downloadInvoice()">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Store the current order ID globally
let currentOrderId = null;

async function openOrderPopup(orderId) {

    try {
        const res = await fetch(`/orders/${orderId}/details`);
        if (!res) throw new Error('No response from server');
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();
        populateOrderPopup(data);
    } catch (err) {
        console.error('Failed to load order details:', err);
        alert('Failed to load order details.');
    }
    
    console.log('Opening order popup for order ID:', orderId);
    currentOrderId = orderId;
    const url = `/orders/${orderId}/details-popup`;
    const popup = document.getElementById('orderPopup');
    popup.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    showPopupLoading();

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log('ORDER DATA:', data);
            hidePopupLoading();
            populateOrderPopup(data);
        })
        .catch(err => {
            hidePopupLoading();
            alert('Error Loading Order Details');
        });
    
    // Fetch order details
    fetch(`/orders/${orderId}/details-popup`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })

    .then(data => {
        console.log('ORDER DATA:', data); // 🔍 WAJIB
        hidePopupLoading();
        populateOrderPopup(data);
    })

    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Order data received:', data);
        
        if (data.success) {
            populateOrderPopup(data);
        } else {
            throw new Error(data.message || 'Failed to load order details');
        }
    })
    .catch(error => {
        console.error('Error fetching order details:', error);
        showPopupError(error.message || 'Failed to load order details');
    });
}

function showPopupLoading() {
    document.getElementById('popupLoading').style.display = 'block';
}

function hidePopupLoading() {
    document.getElementById('popupLoading').style.display = 'none';
}

function showPopupError(message) {
    const popupBody = document.querySelector('.popup-body');
    popupBody.innerHTML = `
        <div style="display: flex; justify-content: center; align-items: center; min-height: 400px; width: 100%; grid-column: 1 / -1;">
            <div style="text-align: center; padding: 2rem;">
                <div style="color: #dc2626; font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                <h4 style="color: #dc2626; margin-bottom: 1rem;">Error Loading Order Details</h4>
                <p style="color: #6b7280; margin-bottom: 1.5rem;">${message}</p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button onclick="closeOrderPopup()" style="background: #6b7280; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 0.5rem; cursor: pointer;">Close</button>
                    <button onclick="openOrderPopup(${currentOrderId})" style="background: #2d4a35; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 0.5rem; cursor: pointer;">Try Again</button>
                </div>
            </div>
        </div>
    `;
}

function populateOrderPopup(data) {

    console.log('POPUP DATA:', data);

    // 🔒 SAFETY CHECK
    if (!data || !data.order) {
        console.error('Order data missing', data);
        document.getElementById('popupLoading').style.display = 'none';
        alert('Failed to load order details');
        return;
    }

    const order = data.order;

    // ❌ MATIKAN LOADING SEAWAL MUNGKIN
    document.getElementById('popupLoading').style.display = 'none';

    /* ===============================
       ORDER HEADER
    =============================== */
    document.getElementById('popupOrderNumber').textContent =
        `Order #${order.order_number ?? '-'}`;

    document.getElementById('popupOrderNum').textContent =
        order.order_number ?? '-';

    document.getElementById('popupOrderDate').textContent =
        order.created_at
            ? new Date(order.created_at).toLocaleString()
            : '-';

    /* ===============================
       STATUS
    =============================== */
    const statusElement = document.getElementById('popupOrderStatus');
    statusElement.textContent = order.status_label ?? '-';
    statusElement.className = 'status-badge';
    if (order.status) {
        statusElement.classList.add(order.status);
    }

    const paymentStatusElement = document.getElementById('popupPaymentStatus');
    paymentStatusElement.textContent = order.payment_status_label ?? '-';
    paymentStatusElement.className = 'payment-status-badge';
    if (order.payment_status) {
        paymentStatusElement.classList.add(order.payment_status);
    }

    document.getElementById('popupTrackingNumber').textContent =
        order.tracking_number ?? 'Not assigned yet';

    document.getElementById('popupPaymentMethod').textContent =
        order.payment_method_label ?? order.payment_method ?? '-';

    /* ===============================
       SHIPPING ADDRESS
    =============================== */
    const shippingAddress = data.shipping_address;
    document.getElementById('popupShippingAddress').innerHTML = shippingAddress
        ? `
            <strong>${shippingAddress.full_name ?? ''}</strong><br>
            ${shippingAddress.address_line_1 ?? ''}<br>
            ${shippingAddress.address_line_2 ? shippingAddress.address_line_2 + '<br>' : ''}
            ${shippingAddress.city ?? ''}, ${shippingAddress.state ?? ''} ${shippingAddress.postal_code ?? ''}<br>
            ${shippingAddress.country ?? ''}<br>
            ${shippingAddress.phone ? 'Phone: ' + shippingAddress.phone : ''}
          `
        : '—';

    /* ===============================
       BILLING ADDRESS
    =============================== */
    const billingAddress = data.billing_address;
    document.getElementById('popupBillingAddress').innerHTML = billingAddress
        ? `
            <strong>${billingAddress.full_name ?? ''}</strong><br>
            ${billingAddress.address_line_1 ?? ''}<br>
            ${billingAddress.address_line_2 ? billingAddress.address_line_2 + '<br>' : ''}
            ${billingAddress.city ?? ''}, ${billingAddress.state ?? ''} ${billingAddress.postal_code ?? ''}<br>
            ${billingAddress.country ?? ''}<br>
            ${billingAddress.phone ? 'Phone: ' + billingAddress.phone : ''}
          `
        : '—';

    /* ===============================
       CUSTOMER INFO
    =============================== */
    document.getElementById('popupCustomerInfo').innerHTML = data.user
        ? `
            <strong>${data.user.name ?? 'N/A'}</strong><br>
            ${data.user.email ?? ''}<br>
            ${data.user.phone ? 'Phone: ' + data.user.phone : ''}
          `
        : '—';

    document.getElementById('popupInvoiceNumber').textContent =
        order.order_number ? `INV-${order.order_number}` : '-';

    /* ===============================
       ORDER ITEMS
    =============================== */
    const itemsTbody = document.getElementById('popupOrderItems');
    itemsTbody.innerHTML = '';

    if (Array.isArray(data.items) && data.items.length > 0) {
        data.items.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="text-left">
                    <strong>${item.product_name ?? 'Product'}</strong>
                    ${item.variation_name ? `<br><small>${item.variation_name}</small>` : ''}
                </td>
                <td class="text-center">${item.quantity ?? 0}</td>
                <td class="text-right">RM ${(item.price ?? 0).toFixed(2)}</td>
                <td class="text-right">RM ${(item.total ?? 0).toFixed(2)}</td>
            `;
            itemsTbody.appendChild(row);
        });
    } else {
        itemsTbody.innerHTML =
            `<tr><td colspan="4" class="text-center">No items found</td></tr>`;
    }

    /* ===============================
       TOTALS
    =============================== */
    document.getElementById('popupSubtotal').textContent =
        `RM ${(data.subtotal ?? 0).toFixed(2)}`;

    document.getElementById('popupShippingCost').textContent =
        `RM ${(order.shipping_cost ?? 0).toFixed(2)}`;

    document.getElementById('popupTaxAmount').textContent =
        `RM ${(order.tax_amount ?? 0).toFixed(2)}`;

    document.getElementById('popupDiscountAmount').textContent =
        `-RM ${(order.discount_amount ?? 0).toFixed(2)}`;

    document.getElementById('popupGrandTotal').textContent =
        `RM ${(order.total_amount ?? 0).toFixed(2)}`;

    /* ===============================
       STATUS TIMELINE
    =============================== */
    const statusTimeline = document.getElementById('popupStatusTimeline');
    statusTimeline.innerHTML = '';

    if (Array.isArray(data.status_history) && data.status_history.length > 0) {
        data.status_history.forEach(history => {
            const timelineItem = document.createElement('div');
            timelineItem.className = 'timeline-item completed';
            timelineItem.innerHTML = `
                <div class="timeline-date">${new Date(history.created_at).toLocaleString()}</div>
                <div class="timeline-status">${history.status.toUpperCase()}</div>
                <div class="timeline-notes">${history.notes ?? 'Status updated'}</div>
            `;
            statusTimeline.appendChild(timelineItem);
        });
    } else {
        statusTimeline.innerHTML = `
            <div class="timeline-item completed">
                <div class="timeline-date">${new Date(order.created_at).toLocaleString()}</div>
                <div class="timeline-status">ORDER CREATED</div>
                <div class="timeline-notes">Order was placed</div>
            </div>
        `;
    }
}


function closeOrderPopup() {
    const popup = document.getElementById('orderPopup');
    popup.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function printInvoice() {
    // Create a print-friendly version
    const printWindow = window.open('', '_blank');
    const orderNumber = document.getElementById('popupOrderNum').textContent;
    
    printWindow.document.write(`
        <html>
        <head>
            <title>Invoice - Order ${orderNumber}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .invoice-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .invoice-details { margin-bottom: 20px; }
                .invoice-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .invoice-table th, .invoice-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .invoice-table th { background-color: #f2f2f2; }
                .totals { margin-top: 30px; text-align: right; }
                .total-row { margin-bottom: 10px; }
                .grand-total { font-size: 1.2em; font-weight: bold; }
                @media print {
                    body { margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="invoice-header">
                <h1>INVOICE</h1>
                <h3>Order #${orderNumber}</h3>
            </div>
            
            <div class="invoice-details">
                <p><strong>Order Date:</strong> ${document.getElementById('popupOrderDate').textContent}</p>
                <p><strong>Status:</strong> ${document.getElementById('popupOrderStatus').textContent}</p>
                <p><strong>Payment Status:</strong> ${document.getElementById('popupPaymentStatus').textContent}</p>
            </div>
            
            ${document.querySelector('.popup-body').innerHTML}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}

function downloadInvoice() {
    alert('PDF download functionality would be implemented here. This would typically generate a PDF invoice on the server.');
    // In a real implementation, you would:
    // window.open(`/orders/${currentOrderId}/invoice-pdf`, '_blank');
}

// Close popup when clicking outside
document.getElementById('orderPopup').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOrderPopup();
    }
});

// Close popup with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeOrderPopup();
    }
});

// Add spinner animation CSS if not already present
if (!document.querySelector('#spinner-css')) {
    const spinnerCSS = document.createElement('style');
    spinnerCSS.id = 'spinner-css';
    spinnerCSS.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(spinnerCSS);
}
</script>

@endsection