@extends('layouts.app')

@section('styles')
    <style>
        .product-page {
            background-color: var(--light-bone);
            min-height: 100vh;
        }

        /* Hero Section - Reduced height */
        .hero-section {
            position: relative;
            color: var(--white);
            overflow: hidden;
            height: 40vh;
            min-height: 300px;
            width: 100%;
            margin: 0;
            padding: 0 !important;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-title-container {
            position: absolute;
            bottom: -14px;
            left: 0;
            right: 0;
            text-align: center;
        }

        .hero-title {
            font-size: 12rem;
            font-weight: bold;
            color: var(--white);
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
            margin: 0;
        }

        /* Content Overlap Section */
        .content-overlap-section {
            padding: 0;
            margin-top: -60px;
            position: relative;
            z-index: 10;
            max-width: 1500px !important;
            margin-left: auto;
            margin-right: auto;
        }

        .main-content-box {
            background: var(--white);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem 2.5rem 2rem !important;
            border: 1px solid var(--border-light);
            margin: 0 1rem !important;
        }

        /* Header Row */
        .products-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .page-main-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        /* Two Column Layout */
        .two-column-layout {
            display: flex;
            gap: 2rem;
        }

        /* Filters Sidebar */
        .filters-sidebar {
            width: 20% !important;
            min-width: 250px;
        }

        .filter-form {
            width: 100%;
        }

        .filter-section {
            margin-bottom: 1.5rem;
        }

        .filter-title {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        .filter-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-light);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .filter-select:focus {
            border-color: var(--primary-dark);
        }

        /* Type Filter */
        .type-category {
            margin-bottom: 1rem;
        }

        .type-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .type-option label {
            font-weight: 500;
            color: var(--dark-text);
            font-size: 0.875rem;
        }

        .sub-options {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-left: 1.5rem;
        }

        .sub-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--light-text);
            transition: color 0.2s ease;
        }

        .sub-option.enabled {
            color: var(--dark-text);
        }

        /* Apply Filters Button */
        .apply-filters-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary-dark);
            color: var(--white);
            border: none;
            border-radius: 0.5rem;
            margin-top: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .apply-filters-btn:hover {
            background: var(--primary-green);
            transform: translateY(-1px);
        }

        /* Products Main */
        .products-main {
            width: 75%;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1.5rem;
        }

        /* Product Card */
        .product-card {
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-gold);
        }

        .product-image-container {
            width: 100%;
            height: 220px !important;
            background-color: var(--light-bone);
            overflow: hidden;
            margin: 0;
            padding: 0;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            margin: 0;
            padding: 0;
            display: block;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-info {
            padding: 0.75rem;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.25rem;
            font-size: 1rem !important;
            line-height: 1.25;
        }

        .product-specs {
            margin-bottom: 0.75rem;
        }

        .product-processor {
            color: var(--light-text);
            font-size: 0.75rem;
            margin-bottom: 0.125rem;
        }

        .specs-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-ram-storage {
            color: var(--light-text);
            font-size: 0.75rem;
            margin: 0;
        }

        .product-price {
            font-weight: bold;
            color: var(--accent-gold);
            font-size: 1.25rem !important;
            margin: 0;
        }

        .product-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-cart,
        .btn-buy {
            flex: 1;
            padding: 0.4rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-cart {
            border: 1px solid var(--primary-dark);
            background: var(--white);
            color: var(--primary-dark);
        }

        .btn-cart:hover {
            background: var(--light-bone);
        }

        .btn-buy {
            background: var(--primary-dark);
            color: var(--white);
            border: none;
        }

        .btn-buy:hover {
            background: var(--primary-green);
        }

        /* Divider */
        .divider {
            border-top: 1px solid var(--border-light);
            margin: 2rem 0;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-numbers {
            display: flex;
            gap: 0.5rem;
        }

        .pagination-btn {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-light);
            border-radius: 2rem;
            font-size: 0.875rem;
            background: var(--white);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-btn:hover {
            background: var(--light-bone);
            border-color: var(--accent-gold);
        }

        .pagination-btn.active {
            background: var(--primary-dark);
            color: var(--white);
            border-color: var(--primary-dark);
        }

        /* Recommendations Section */
        .recommendations-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-light);
        }

        .recommendations-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .recommendations-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark-text);
        }

        .recommendations-controls {
            display: flex;
            gap: 0.5rem;
        }

        .slider-btn {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            border-radius: 50%;
            background: var(--white);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .slider-btn:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--white);
        }

        .recommendations-slider {
            display: flex;
            overflow-x: auto;
            gap: 1rem;
            padding-bottom: 1rem;
            scrollbar-width: none;
        }

        .recommendations-slider::-webkit-scrollbar {
            display: none;
        }

        /* Recommendation Card */
        .recommendation-card {
            flex: 0 0 auto;
            width: 350px;
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .recommendation-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-gold);
        }

        .recommendation-image-container {
            width: 100%;
            height: 240px;
            background-color: var(--light-bone);
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .recommendation-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            margin: 0;
            padding: 0;
            display: block;
        }

        .recommendation-card:hover .recommendation-image {
            transform: scale(1.05);
        }

        .recommendation-info {
            padding: 0.75rem;
        }

        .recommendation-name {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.25rem;
            font-size: 1.1rem;
        }

        .recommendation-price {
            font-weight: bold;
            color: var(--accent-gold);
            margin-bottom: 0.5rem;
            font-size: 1.15rem;
        }

        .recommendation-buttons {
            display: flex;
            gap: 0.25rem;
        }

        .btn-recommendation-cart,
        .btn-recommendation-buy {
            flex: 1;
            padding: 0.25rem 0.5rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.125rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-recommendation-cart {
            border: 1px solid var(--primary-dark);
            background: var(--white);
            color: var(--primary-dark);
        }

        .btn-recommendation-cart:hover {
            background: var(--light-bone);
        }

        .btn-recommendation-buy {
            background: var(--primary-dark);
            color: var(--white);
            border: none;
        }

        .btn-recommendation-buy:hover {
            background: var(--primary-green);
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none; /* hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .popup-container {
            background: #ffffff;
            border-radius: 1rem;
            width: 480px;
            max-width: 95vw;
            padding: 1.25rem 1.5rem 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .popup-close {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            border: none;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .popup-close:hover {
            background: var(--primary-green);
            transform: scale(1.1);
        }

        .popup-header-row {
            display: flex;
            gap: 0.75rem;
        }

        .popup-image-wrapper {
            width: 90px;
            height: 90px;
            border-radius: 0.5rem;
            background: #f3f4f6;
            overflow: hidden;
            flex-shrink: 0;
        }

        .popup-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .popup-main-info {
            flex: 1;
        }

        .popup-image-section {
            flex: 1;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .popup-brand-bg {
            position: absolute;
            font-size: 6rem;
            font-weight: 900;
            color: rgba(0, 0, 0, 0.03);
            transform: rotate(-45deg);
            white-space: nowrap;
            user-select: none;
        }

        .popup-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(20px 20px 30px rgba(0, 0, 0, 0.2));
            position: relative;
            z-index: 2;
        }

        .popup-details-section {
            flex: 1;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .popup-info {
            flex: 1;
        }

        .popup-stock {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .popup-section-label {
            margin-top: 1rem;
            margin-bottom: 0.35rem;
            font-size: 0.85rem;
            color: #111827;
        }

        .popup-variations-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .popup-variation-pill {
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            cursor: pointer;
        }

        .popup-variation-pill.selected {
            border-color: #2d4a35;
            background: #e5efe8;
            color: #1f2937;
        }

        .popup-variation-pill.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .popup-quantity-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .popup-quantity-control {
            display: inline-flex;
            border: 1px solid #d1d5db;
            border-radius: 999px;
            overflow: hidden;
            align-items: center;
        }

        .popup-qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #f9fafb;
            cursor: pointer;
            font-size: 1rem;
        }

        #popup-quantity-input {
            width: 42px;
            border: none;
            text-align: center;
            font-size: 0.9rem;
            outline: none;
        }

        .popup-footer {
            margin-top: 1.25rem;
        }

        #popup-confirm-btn {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 999px;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            background: #2d4a35;      /* theme color */
            color: #ffffff;
            cursor: pointer;
        }

        #popup-confirm-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .popup-title {
            font-size: 1.75rem;
            font-weight: bold;
            color: var(--dark-text);
            margin: 0 0 1.5rem 0;
            line-height: 1.3;
        }

        .popup-specs {
            margin-bottom: 2rem;
        }

        .spec-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .spec-bullet {
            width: 4px;
            height: 4px;
            background: var(--dark-text);
            border-radius: 50%;
            margin-right: 0.75rem;
        }

        .popup-product-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.35rem;
        }

        .popup-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d4a35;
            margin-bottom: 0.15rem;
        }

        .popup-description {
            font-size: 0.8rem;
            color: #6b7280; /* grey text */
            margin-bottom: 0.35rem;
        }

        .popup-buttons {
            display: flex;
            gap: 1rem;
            margin-top: auto;
        }

        .popup-cart-btn,
        .popup-buy-btn {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 2rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            font-weight: 500;
        }

        .popup-cart-btn {
            border: 1px solid var(--primary-dark);
            background: var(--white);
            color: var(--primary-dark);
        }

        .popup-cart-btn:hover {
            background: var(--light-bone);
            border-color: var(--primary-green);
        }

        .popup-buy-btn {
            background: var(--primary-dark);
            color: var(--white);
            border: none;
        }

        .popup-buy-btn:hover {
            background: var(--primary-green);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hero-title {
                font-size: 8rem;
            }
        }

        @media (max-width: 768px) {
            .two-column-layout {
                flex-direction: column;
            }

            .filters-sidebar {
                width: 100%;
                order: 2;
            }

            .products-main {
                width: 100%;
                order: 1;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .products-header-row {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .hero-section {
                height: 40vh;
                min-height: 250px;
            }

            .hero-title {
                font-size: 5rem;
            }

            .content-overlap-section {
                margin-top: -40px;
            }

            .main-content-box {
                margin: 0 1.5rem;
            }

            .popup-container {
                flex-direction: column;
                height: auto;
                max-height: 90vh;
            }

            .popup-image-section {
                height: 200px;
            }
        }

        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .main-content-box {
                padding: 1rem;
                margin: 0.75rem auto;
                width: calc(100% - 1.5rem);
            }

            .product-buttons {
                flex-direction: column;
            }

            .recommendation-card {
                width: 250px;
            }

            .pagination {
                flex-direction: column;
                gap: 1rem;
            }

            .hero-section {
                height: 35vh;
                min-height: 200px;
            }

            .hero-title {
                font-size: 3rem;
            }

            .content-overlap-section {
                margin-top: -30px;
            }
        }

        .product-image-container {
            position: relative;
        }

        /* Variation Modal Styles */
        .variation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .variation-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 1rem;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .variation-modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .variation-modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .variation-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .variation-modal-body {
            padding: 1.5rem;
        }

        .variation-option {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .variation-option:hover {
            border-color: #1f2937;
        }

        .variation-option.selected {
            border-color: #1f2937;
            background-color: #f8fafc;
        }

        .variation-specs {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .variation-price {
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
        }

        .variation-stock {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .variation-stock.in-stock {
            color: #10b981;
        }

        .variation-stock.out-of-stock {
            color: #ef4444;
        }

        .variation-modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 1rem;
        }

        .btn-confirm-variation {
            flex: 1;
            padding: 0.75rem;
            background: #1f2937;
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-confirm-variation:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .btn-cancel-variation {
            flex: 1;
            padding: 0.75rem;
            background: white;
            color: #1f2937;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .variation-loading {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        .no-variations {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }
    </style>
@endsection

@section('content')
<div class="product-page">
    <section class="hero-section" style="position: relative; height: 350px; background-color: #1f2937; overflow: hidden; margin-bottom: 0;">
        <img src="{{ asset('storage/images/productbanner.png') }}" alt="Products Banner" 
            style="width: 100%; height: 100%; object-fit: cover; opacity: 1;">
        <div style="position: absolute; bottom: 5px; left: 0; right: 0; text-align: center;">
            <h1 style="font-size: 14rem; font-weight: bold; color: white; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7); margin: 0;">
                Product
            </h1>
        </div>
    </section>

    <!-- White Box Container -->
    <section class="white-box-container" style="padding: 0; margin-top: -115px; position: relative; z-index: 10; margin-bottom: 2rem;">
        <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 2rem;">
            <div style="background: white; border-radius: 1.5rem; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); padding: 3rem 2.5rem 2rem; border: 1px solid #e5e7eb;">
                
                <!-- Header Row -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h2 style="font-size: 1.75rem; font-weight: 600; color: #1f2937;">Give All You Need</h2>
                    <div style="width: 400px; position: relative;">
                        <form method="GET" action="{{ url('/products') }}" style="display: flex; position: relative;">
                            <div style="position: relative; flex: 1;">
                                <!-- Search Icon -->
                                <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); z-index: 10;">
                                    <svg style="width: 1rem; height: 1rem; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <!-- Search Input -->
                                <input type="text" name="search" placeholder="Search products..." 
                                    value="{{ request('search') }}"
                                    style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; outline: none; transition: all 0.2s ease;">
                            </div>
                            <!-- Search Button - Overlapped -->
                            <button type="submit" style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 0.6rem 1.25rem; background: #2d4a35; color: white; border: none; border-radius: 2rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease; z-index: 5;">
                                Search
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div style="display: flex; gap: 2rem;">
                    <!-- Filters Sidebar -->
                    <div style="width: 20%;">
                        <form method="GET" action="{{ url('/products') }}">
                            <!-- Brand Filter -->
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Brand</h3>
                                <select name="brand" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
                                    <option value="">All Brands</option>
                                    @foreach($brandsList as $brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Filter -->
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Price</h3>
                                <select name="sort" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; outline: none;">
                                    <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default Sorting</option>
                                    <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" style="width: 100%; padding: 0.75rem; background: #2d4a35; color: white; border: none; border-radius: 2rem; margin-top: 1rem;">
                                Apply Filters
                            </button>
                        </form>
                    </div>

                    <!-- Products Main -->
                    <div style="width: 80%;">
                        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem;">
                            @forelse($products as $product)
                                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; transition: all 0.3s ease; padding: 0; margin: 0; position: relative;" 
                                    class="product-card"
                                    onclick="window.location.href='{{ url('/products/' . $product->slug) }}'"
                                    data-product-id="{{ $product->id }}">
                                    
                                    <div style="width: 100%; height: 230px; background-color: #f3f4f6; overflow: hidden; margin: 0; padding: 0; border-radius: 0.5rem 0.5rem 0 0; position: relative;">
                                        <img src="{{ asset('storage/' . $product->image) }}"   
                                            alt="{{ $product->name }}" 
                                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; margin: 0; padding: 0; display: block; border-radius: 0.5rem 0.5rem 0 0;">
                                    </div>

                                    <div style="padding: 0.75rem;">
                                        <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 1rem; line-height: 1.25;">
                                            {{ $product->name }}
                                        </h3>
                                        
                                        <!-- Product Price -->
                                        <div style="margin-bottom: 0.75rem;">
                                            <p style="font-weight: bold; color: #1f2937; font-size: 1.1rem; margin: 0;">
                                                @if($product->has_variations && $product->variations->count() > 0)
                                                    RM{{ number_format($product->min_price, 2) }} - RM{{ number_format($product->max_price, 2) }}
                                                @else
                                                    RM{{ number_format($product->price, 2) }}
                                                @endif
                                            </p>
                                        </div>

                                        <!-- Location -->
                                        <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                                            <svg style="width: 1rem; height: 1rem; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 22s8-5.686 8-12a8 8 0 10-16 0c0 6.314 8 12 8 12z"></path>
                                            </svg>
                                            <span style="color: #6b7280; font-size: 0.85rem;">Kuala Lumpur</span>
                                        </div>

                                        <!-- Buttons Row -->
                                        <div style="display: flex; gap: 0.5rem;">
                                            <button class="add-to-cart-btn" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                                data-product-description="{{ $product->short_description ?? '' }}"
                                                data-product-stock="{{ $product->stock ?? '' }}"
                                                data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                                style="flex: 1; border: 1px solid #2d4a35; background: white; color: #2d4a35; padding: 0.4rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem; transition: all 0.2s ease; cursor: pointer;">
                                                <span class="cart-btn-text">Add to Cart</span>
                                            </button>

                                            <button class="buy-now-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                                data-product-description="{{ $product->short_description ?? '' }}"
                                                data-product-stock="{{ $product->stock ?? '' }}"
                                                data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                                style="flex: 1; background: #2d4a35; color: white; padding: 0.75rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; border: none; transition: all 0.2s ease; cursor: pointer;">
                                                Buy Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <!-- Shown when no products are active -->
                                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; color: #6b7280; font-size: 1rem;">
                                    No products available at the moment.
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination directly under the grid --}}
                        @if($products->hasPages())
                            <div style="display: flex; justify-content: center; align-items: center; margin-top: 2rem; gap: 0.75rem; flex-wrap: wrap;">
                                {{-- Previous --}}
                                @if($products->onFirstPage())
                                    <span style="padding: 0.5rem 0.75rem; border-radius: 2rem; font-size: 0.875rem; background: #f3f4f6; color: #9ca3af; border: 1px solid #d1d5db;">
                                        Previous
                                    </span>
                                @else
                                    <a href="{{ $products->previousPageUrl() }}" style="padding: 0.5rem 0.75rem; border-radius: 2rem; font-size: 0.875rem; background: white; color: #2d4a35; border: 1px solid #d1d5db; text-decoration: none;">
                                        Previous
                                    </a>
                                @endif

                                {{-- Page numbers --}}
                                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    @if($page == $products->currentPage())
                                        <span style="padding: 0.5rem 0.75rem; border-radius: 2rem; font-size: 0.875rem; background: #2d4a35; color: white; border: 1px solid #2d4a35;">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" style="padding: 0.5rem 0.75rem; border-radius: 2rem; font-size: 0.875rem; background: white; color: #2d4a35; border: 1px solid #d1d5db; text-decoration: none;">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next --}}
                                @if($products->hasMorePages())
                                    <a href="{{ $products->nextPageUrl() }}" style="padding: 0.5rem 0.75rem; border-radius: 2rem; font-size: 0.875rem; background: white; color: #2d4a35; border: 1px solid #d1d5db; text-decoration: none;">
                                        Next
                                    </a>
                                @else
                                    <span style="padding: 0.5rem 0.75rem; border-radius: 2rem; font-size: 0.875rem; background: #f3f4f6; color: #9ca3af; border: 1px solid #d1d5db;">
                                        Next
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    </div>
                </div>

                <!-- Recommendations Section -->
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h2 style="font-size: 1.5rem; font-weight: bold; color: #1f2937;">Explore our recommendations</h2>
                        <div class="recommendation-controls" style="display: flex; gap: 0.5rem;">
                            <button class="recommendation-prev-btn" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 50%; background: white; transition: all 0.2s ease; cursor: pointer;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button class="recommendation-next-btn" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 50%; background: white; transition: all 0.2s ease; cursor: pointer;">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="recommendation-slider-container" style="position: relative;">
                    @if($recommendedProducts->count())
                        <div class="recommendation-slider" style="display: flex; overflow-x: auto; gap: 1rem; padding-bottom: 1rem; scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($recommendedProducts as $product)
                                <div style="flex: 0 0 auto; width: 300px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; transition: all 0.3s ease; position: relative;" class="product-card" data-product-id="{{ $product->id }}">
                                    <div style="width: 100%; height: 200px; background-color: #f3f4f6; overflow: hidden; margin: 0; padding: 0; position: relative;">
                                        <img src="{{ asset('storage/' . $product->image) }}"    
                                            alt="{{ $product->name }}" 
                                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; margin: 0; padding: 0; display: block;">
                                    </div>
                                    <div style="padding: 0.75rem;">
                                        <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 1rem;">
                                            {{ $product->name }}
                                        </h3>
                                        <p style="font-weight: bold; color: #1f2937; margin-bottom: 0.5rem; font-size: 1rem;">
                                            @if($product->has_variations && $product->variations->count() > 0)
                                                RM{{ number_format($product->min_price, 2) }} - RM{{ number_format($product->max_price, 2) }}
                                            @else
                                                RM{{ number_format($product->price, 2) }}
                                            @endif
                                        </p>

                                        <div style="display: flex; gap: 0.75rem;">
                                            <!-- Add to Cart + Buy Now buttons (unchanged) -->
                                            <button class="add-to-cart-btn" 
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $product->price }}"
                                                    data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                                    data-product-description="{{ $product->short_description ?? '' }}"
                                                    data-product-stock="{{ $product->stock ?? '' }}"
                                                    data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                                    style="flex: 1; border: 1px solid #2d4a35; background: white; color: #2d4a35; padding: 0.75rem 0.5rem; border-radius: 2rem; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.125rem; transition: all 0.2s ease; cursor: pointer;">
                                                <span class="cart-btn-text">Add to Cart</span>
                                            </button>
                                            <button class="buy-now-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $product->price }}"
                                                    data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                                    data-product-description="{{ $product->short_description ?? '' }}"
                                                    data-product-stock="{{ $product->stock ?? '' }}"
                                                    data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                                    style="flex: 1; background: #2d4a35; color: white; padding: 0.75rem 0.5rem; border-radius: 2rem; font-size: 0.8rem; border: none; transition: all 0.2s ease; cursor: pointer;">
                                                Buy Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem 1rem; color: #6b7280; font-size: 0.95rem;">
                            No products available at the moment.
                        </div>
                    @endif
                </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Product popup (center) -->
<div id="product-popup" class="popup-overlay">
    <div class="popup-container">
        <button type="button" class="popup-close">&times;</button>

        <div class="popup-header-row">
            <div class="popup-image-wrapper">
                <img id="popup-product-image" src="" alt="Product">
            </div>
            <div class="popup-main-info">
                <div class="popup-product-name" id="popup-product-name"></div>
                <div class="popup-description" id="popup-product-description"></div>
                <div class="popup-price" id="popup-product-price"></div>
                <div class="popup-stock" id="popup-product-stock"></div>
            </div>
        </div>

        <div id="popup-variations-section" style="display:none;">
            <div class="popup-section-label">Variations</div>
            <div class="popup-variations-list" id="popup-variations-list"></div>
        </div>

        <div class="popup-quantity-row">
            <span class="popup-section-label" style="margin-top:0;">Quantity</span>
            <div class="popup-quantity-control">
                <button type="button" class="popup-qty-btn" id="popup-qty-minus">−</button>
                <input type="text" id="popup-quantity-input" value="1">
                <button type="button" class="popup-qty-btn" id="popup-qty-plus">+</button>
            </div>
        </div>

        <div class="popup-footer">
            <button type="button" id="popup-confirm-btn">Add to Cart</button>
        </div>
    </div>
</div>

<!-- Variation Selection Modal -->
<div id="variation-modal" class="variation-modal">
    <div class="variation-modal-content">
        <div class="variation-modal-header">
            <h3 class="variation-modal-title">Select Variation</h3>
            <button class="variation-modal-close">&times;</button>
        </div>
        <div class="variation-modal-body" id="variation-modal-body">
            <!-- Variation options will be loaded here -->
        </div>
        <div class="variation-modal-footer">
            <button class="btn-cancel-variation">Cancel</button>
            <button class="btn-confirm-variation" disabled>Add to Cart</button>
        </div>
    </div>
</div>

<!-- Success Notification -->
<div id="cart-notification" style="display: none; position: fixed; top: 100px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); z-index: 10001; transition: all 0.3s ease;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="notification-message">Product added to cart!</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ----------------------
// Global helpers
// ----------------------
let selectedVariation = null;
let currentProductData = null;
let isBuyNow = false;

function showNotification(message, type = 'success') {
    const notification = document.getElementById('cart-notification');
    const messageElement = document.getElementById('notification-message');

    if (!notification || !messageElement) return;

    messageElement.textContent = message;
    notification.style.background = type === 'error' ? '#ef4444' : '#10b981';
    notification.style.display = 'block';

    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

function updateHeaderCartCount(count) {
    const cartBadge = document.querySelector('#cart-icon .action-badge');
    if (!cartBadge) return;

    if (count > 0) {
        cartBadge.textContent = count;
        cartBadge.style.display = 'flex';
    } else {
        cartBadge.style.display = 'none';
    }
}

// ----------------------
// Recommendation slider
// ----------------------
function initRecommendationSlider() {
    const slider  = document.querySelector('.recommendation-slider');
    const prevBtn = document.querySelector('.recommendation-prev-btn');
    const nextBtn = document.querySelector('.recommendation-next-btn');

    if (!slider || !prevBtn || !nextBtn) return;

    function getScrollAmount() {
        const card = slider.querySelector('.product-card');
        if (!card) return 320;
        const styles = window.getComputedStyle(slider);
        const gap = parseInt(styles.columnGap || styles.gap || '16', 10);
        return card.offsetWidth + gap;
    }

    prevBtn.addEventListener('click', function (e) {
        e.preventDefault();
        slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', function (e) {
        e.preventDefault();
        slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
    });
}

// ----------------------
// Variation modal
// ----------------------
function showVariationModal(productData, isBuyNowAction = false) {
    currentProductData = productData;
    isBuyNow = isBuyNowAction;

    const modal      = document.getElementById('variation-modal');
    const modalBody  = document.getElementById('variation-modal-body');
    const confirmBtn = document.querySelector('.btn-confirm-variation');
    const modalTitle = document.querySelector('.variation-modal-title');

    if (!modal || !modalBody || !confirmBtn || !modalTitle) return;

    modalTitle.textContent = isBuyNowAction ? 'Select Variation - Buy Now' : 'Select Variation';
    confirmBtn.textContent = isBuyNowAction ? 'Buy Now' : 'Add to Cart';

    selectedVariation = null;
    confirmBtn.disabled = true;

    modalBody.innerHTML = '<div class="variation-loading">Loading variations...</div>';
    modal.style.display = 'block';

    fetch(`/products/${productData.id}/variations`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.variations && data.variations.length > 0) {
                renderVariationOptions(data.variations, modalBody);
            } else {
                modalBody.innerHTML = '<div class="no-variations">No variations available for this product.</div>';
            }
        })
        .catch(() => {
            modalBody.innerHTML = '<div class="no-variations">Error loading variations. Please try again.</div>';
        });
}

function renderVariationOptions(variations, container) {
    let html = '';

    variations.forEach(variation => {
        const specs = [];
        if (variation.processor) specs.push(variation.processor);
        if (variation.ram)       specs.push(variation.ram);
        if (variation.storage)   specs.push(variation.storage);
        if (variation.model)     specs.push(variation.model);

        const inStock    = variation.stock > 0;
        const stockText  = inStock ? `${variation.stock} in stock` : 'Out of stock';
        const stockClass = inStock ? 'in-stock' : 'out-of-stock';
        const disabled   = inStock ? '' : 'disabled';

        html += `
            <div class="variation-option ${disabled}"
                 data-variation-id="${variation.id}"
                 data-variation-price="${variation.price}"
                 data-variation-sku="${variation.sku || ''}"
                 data-variation-stock="${variation.stock}">
                <div class="variation-specs">${specs.join(' • ')}</div>
                <div class="variation-price">RM ${parseFloat(variation.price).toFixed(2)}</div>
                <div class="variation-stock ${stockClass}">${stockText}</div>
            </div>
        `;
    });

    container.innerHTML = html;

    container.querySelectorAll('.variation-option:not(.disabled)').forEach(opt => {
        opt.addEventListener('click', function () {
            container.querySelectorAll('.variation-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');

            selectedVariation = {
                id:    this.dataset.variationId,
                price: this.dataset.variationPrice,
                sku:   this.dataset.variationSku,
                stock: this.dataset.variationStock,
                specs: this.querySelector('.variation-specs').textContent
            };

            document.querySelector('.btn-confirm-variation').disabled = false;
        });
    });
}

function processVariationAction() {
    if (!selectedVariation || !currentProductData) {
        showNotification('Please select a variation', 'error');
        return;
    }

    const confirmBtn   = document.querySelector('.btn-confirm-variation');
    const originalText = confirmBtn.textContent;

    confirmBtn.textContent = 'Processing...';
    confirmBtn.disabled    = true;

    const requestData = {
        product_id:    currentProductData.id,
        variation_id:  selectedVariation.id,
        product_name:  currentProductData.name,
        price:         parseFloat(selectedVariation.price),
        quantity:      1,
        image:         currentProductData.image,
        specs:         selectedVariation.specs,
        sku:           selectedVariation.sku
    };

    fetch("{{ route('buy-now') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('Redirecting to checkout...', 'success');
            setTimeout(() => { window.location.href = data.redirect_url; }, 500);
        } else {
            showNotification(data.message || 'Failed to process product.', 'error');
        }
    })
    .catch(() => {
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        confirmBtn.textContent = originalText;
        confirmBtn.disabled    = false;
    });
}

function closeVariationModal() {
    const modal = document.getElementById('variation-modal');
    if (modal) modal.style.display = 'none';
    selectedVariation  = null;
    currentProductData = null;
    isBuyNow           = false;
}

// ----------------------
// Add to Cart / Buy Now
// ----------------------
function addToCartDirect(productData, button) {
    const labelSpan   = button.querySelector('.cart-btn-text');
    const originalTxt = labelSpan ? labelSpan.textContent : button.textContent;

    if (labelSpan) labelSpan.textContent = 'Adding...'; else button.textContent = 'Adding...';
    button.disabled = true;

    const requestData = {
        product_id:   productData.id,
        product_name: productData.name,
        price:        parseFloat(productData.price),
        quantity:     1,
        image:        productData.image
    };

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart successfully!');
            updateHeaderCartCount(data.cart_count || 0);
        } else {
            showNotification(data.message || 'Failed to add product to cart.', 'error');
        }
    })
    .catch(() => {
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        if (labelSpan) labelSpan.textContent = originalTxt; else button.textContent = originalTxt;
        button.disabled = false;
    });
}

function processBuyNowDirect(productData, button) {
    const originalText = button.textContent;
    button.textContent = 'Processing...';
    button.disabled    = true;

    const requestData = {
        product_id:   productData.id,
        product_name: productData.name,
        price:        parseFloat(productData.price),
        quantity:     1,
        image:        productData.image
    };

    fetch('/buy-now', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('Redirecting to checkout...', 'success');
            setTimeout(() => { window.location.href = data.redirect_url; }, 500);
        } else if (data.requires_variation) {
            showNotification('Please select a variation for this product.', 'error');
            showVariationModal(productData, true);
        } else {
            showNotification(data.message || 'Failed to process product.', 'error');
        }
    })
    .catch(() => {
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled    = false;
    });
}

// ----------------------
// Center popup (image + variations + quantity)
// ----------------------
let popupAction = 'cart'; // 'cart' or 'buy'
let popupHasVariations = false;
let popupSelectedVariation = null;

function openProductPopup(productData, hasVariations, isBuyNowAction) {
    currentProductData   = productData;
    popupHasVariations   = hasVariations;
    popupSelectedVariation = null;
    popupAction          = isBuyNowAction ? 'buy' : 'cart';

    const overlay   = document.getElementById('product-popup');
    const imgEl     = document.getElementById('popup-product-image');
    const nameEl    = document.getElementById('popup-product-name');
    const descEl    = document.getElementById('popup-product-description');
    const priceEl   = document.getElementById('popup-product-price');
    const stockEl   = document.getElementById('popup-product-stock');
    const qtyInput  = document.getElementById('popup-quantity-input');
    const confirm   = document.getElementById('popup-confirm-btn');
    const varSec    = document.getElementById('popup-variations-section');
    const varList   = document.getElementById('popup-variations-list');

    if (!overlay || !imgEl || !nameEl || !priceEl || !stockEl || !qtyInput || !confirm || !varSec || !varList) return;

    imgEl.src       = productData.image || '';
    nameEl.textContent  = productData.name || '';
    descEl.textContent  = productData.description || '';
    priceEl.textContent = productData.price ? `RM${parseFloat(productData.price).toFixed(2)}` : '';
    stockEl.textContent = productData.stock ? `Stock: ${productData.stock}` : '';

    qtyInput.value = 1;

    confirm.textContent = popupAction === 'buy' ? 'Buy Now' : 'Add to Cart';
    confirm.disabled    = hasVariations; // enable only after variation selected when needed

    // reset variations
    varList.innerHTML = '';
    varSec.style.display = 'none';

    if (hasVariations) {
        varSec.style.display = 'block';
        confirm.disabled = true;
        fetch(`/products/${productData.id}/variations`)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.variations && data.variations.length) {
                    renderPopupVariations(data.variations);
                } else {
                    varList.innerHTML = '<span style="font-size:0.8rem;color:#6b7280;">No variations available.</span>';
                    confirm.disabled = false;
                }
            })
            .catch(() => {
                varList.innerHTML = '<span style="font-size:0.8rem;color:#ef4444;">Failed to load variations.</span>';
            });
    }

    overlay.style.display = 'flex';
}

function renderPopupVariations(variations) {
    const varList = document.getElementById('popup-variations-list');
    const confirm = document.getElementById('popup-confirm-btn');
    const priceEl = document.getElementById('popup-product-price');
    const stockEl = document.getElementById('popup-product-stock');

    varList.innerHTML = '';
    popupSelectedVariation = null;
    if (confirm) confirm.disabled = true;

    variations.forEach(v => {
        const specs = [];
        if (v.processor) specs.push(v.processor);
        if (v.ram)       specs.push(v.ram);
        if (v.storage)   specs.push(v.storage);
        if (v.model)     specs.push(v.model);

        const pill = document.createElement('button');
        pill.type  = 'button';
        pill.className = 'popup-variation-pill';
        if (v.stock <= 0) {
            pill.classList.add('disabled');
        }

        pill.textContent = specs.length ? specs.join(' / ') : (v.name || 'Variation');

        pill.addEventListener('click', () => {
            if (v.stock <= 0) return;
            varList.querySelectorAll('.popup-variation-pill').forEach(p => p.classList.remove('selected'));
            pill.classList.add('selected');

            popupSelectedVariation = {
                id:    v.id,
                price: v.price,
                sku:   v.sku || '',
                stock: v.stock,
                specs: specs.join(' / ')
            };

            if (priceEl) priceEl.textContent = `RM${parseFloat(v.price).toFixed(2)}`;
            if (stockEl) stockEl.textContent = `Stock: ${v.stock}`;

            if (confirm) confirm.disabled = false;
        });

        varList.appendChild(pill);
    });
}

function closeProductPopup() {
    const overlay = document.getElementById('product-popup');
    if (overlay) overlay.style.display = 'none';
    currentProductData   = null;
    popupSelectedVariation = null;
    popupHasVariations   = false;
    popupAction          = 'cart';
}

function handlePopupConfirm() {
    if (!currentProductData) return;

    const qtyInput = document.getElementById('popup-quantity-input');
    const confirm  = document.getElementById('popup-confirm-btn');
    let quantity   = parseInt(qtyInput.value, 10);
    if (isNaN(quantity) || quantity < 1) quantity = 1;

    const originalText = confirm.textContent;
    confirm.textContent = 'Processing...';
    confirm.disabled    = true;

    if (popupAction === 'cart') {
        addToCartFromPopup(currentProductData, popupSelectedVariation, quantity, confirm, originalText);
    } else {
        buyNowFromPopup(currentProductData, popupSelectedVariation, quantity, confirm, originalText);
    }
}

function addToCartFromPopup(productData, variation, button, originalText) {
    const requestData = {
        product_id:   productData.id,
        product_name: productData.name,
        price:        parseFloat(variation ? variation.price : productData.price),
        quantity:     parseInt(document.getElementById('popup-quantity-input').value, 10) || 1,
        image:        productData.image
    };

    if (variation) {
        requestData.variation_id = variation.id;
        requestData.specs        = variation.specs;
        requestData.sku          = variation.sku;
    }

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart successfully!');
            updateHeaderCartCount(data.cart_count || 0);
            closeProductPopup();
        } else {
            showNotification(data.message || 'Failed to add product to cart.', 'error');
        }
    })
    .catch(() => {
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled    = false;
    });
}

function buyNowFromPopup(productData, variation, quantity, button, originalText) {
    const requestData = {
        product_id:   productData.id,
        product_name: productData.name,
        price:        parseFloat(variation ? variation.price : productData.price),
        quantity:     quantity,
        image:        productData.image
    };

    if (variation) {
        requestData.variation_id = variation.id;
        requestData.specs        = variation.specs;
        requestData.sku          = variation.sku;
    }

    fetch('/buy-now', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('Redirecting to checkout...', 'success');
            closeProductPopup();
            setTimeout(() => { window.location.href = data.redirect_url; }, 500);
        } else {
            showNotification(data.message || 'Failed to process product.', 'error');
        }
    })
    .catch(() => {
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled    = false;
    });
}

// ----------------------
// DOM Ready
// ----------------------
document.addEventListener('DOMContentLoaded', function () {
    initRecommendationSlider();

    // Add to Cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const productData = {
                id:          this.dataset.productId,
                name:        this.dataset.productName,
                price:       this.dataset.productPrice,
                image:       this.dataset.productImage,
                description: this.dataset.productDescription || '',
                stock:       this.dataset.productStock || ''
            };
            const hasVariations = this.dataset.hasVariations === '1';
            openProductPopup(productData, hasVariations, false);
        });
    });

    // Buy Now buttons
    document.querySelectorAll('.buy-now-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const productData = {
                id:          this.dataset.productId,
                name:        this.dataset.productName,
                price:       this.dataset.productPrice,
                image:       this.dataset.productImage,
                description: this.dataset.productDescription || '',
                stock:       this.dataset.productStock || ''
            };
            const hasVariations = this.dataset.hasVariations === '1';
            openProductPopup(productData, hasVariations, true);
        });
    });

        // Popup close + background click
    const popupOverlay = document.getElementById('product-popup');
    const popupClose   = document.querySelector('#product-popup .popup-close');
    if (popupClose) {
        popupClose.addEventListener('click', closeProductPopup);
    }
    if (popupOverlay) {
        popupOverlay.addEventListener('click', function (e) {
            if (e.target === popupOverlay) closeProductPopup();
        });
    }

    // Quantity buttons
    const qtyMinus = document.getElementById('popup-qty-minus');
    const qtyPlus  = document.getElementById('popup-qty-plus');
    const qtyInput = document.getElementById('popup-quantity-input');

    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', () => {
            let v = parseInt(qtyInput.value, 10) || 1;
            v = Math.max(1, v - 1);
            qtyInput.value = v;
        });
        qtyPlus.addEventListener('click', () => {
            let v = parseInt(qtyInput.value, 10) || 1;
            qtyInput.value = v + 1;
        });
    }

    // Confirm button
    const popupConfirm = document.getElementById('popup-confirm-btn');
    if (popupConfirm) {
        popupConfirm.addEventListener('click', handlePopupConfirm);
    }

    // Modal close buttons
    const modalClose   = document.querySelector('.variation-modal-close');
    const modalCancel  = document.querySelector('.btn-cancel-variation');
    const modalConfirm = document.querySelector('.btn-confirm-variation');
    const modal        = document.getElementById('variation-modal');

    if (modalClose)  modalClose.addEventListener('click', closeVariationModal);
    if (modalCancel) modalCancel.addEventListener('click', closeVariationModal);
    if (modalConfirm) modalConfirm.addEventListener('click', processVariationAction);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === this) closeVariationModal();
        });
    }
});
</script>
@endpush