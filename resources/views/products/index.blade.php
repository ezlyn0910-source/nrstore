@extends('layouts.app')

/* ===== PRODUCT CSS ===== */

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
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .main-content-box {
            background: var(--white);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem 2rem 2rem;
            border: 1px solid var(--border-light);
            margin: 0 2rem;
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
            width: 25%;
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
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
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
            height: 150px;
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
            font-size: 0.875rem;
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
            font-size: 1rem;
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
            width: 300px;
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
            height: 200px;
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
            font-size: 1rem;
        }

        .recommendation-price {
            font-weight: bold;
            color: var(--accent-gold);
            margin-bottom: 0.5rem;
            font-size: 1rem;
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .popup-container {
            background: var(--white);
            border-radius: 1.5rem;
            width: 900px;
            max-width: 95vw;
            height: 550px;
            max-height: 90vh;
            display: flex;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .popup-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--primary-dark);
            color: var(--white);
            border: none;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: bold;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s ease;
        }

        .popup-close:hover {
            background: var(--primary-green);
            transform: scale(1.1);
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

        .popup-price {
            font-size: 2.25rem;
            color: var(--accent-gold);
            margin: 0 0 1.5rem 0;
            font-weight: bold;
        }

        .popup-description {
            color: var(--light-text);
            line-height: 1.6;
            margin: 0;
            font-size: 0.95rem;
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


/* ===== PRODUCT BLADE ===== */

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
    <section class="white-box-container" style="padding: 0; margin-top: -120px; position: relative; z-index: 10; margin-bottom: 2rem;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 3rem;">
            <div style="background: white; border-radius: 1.5rem; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); padding: 3rem 2rem 2rem; border: 1px solid #e5e7eb;">
                
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
                            <button type="submit" style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); padding: 0.6rem 1.25rem; background: #1f2937; color: white; border: none; border-radius: 2rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease; z-index: 5;">
                                Search
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div style="display: flex; gap: 2rem;">
                    <!-- Filters Sidebar -->
                    <div style="width: 25%;">
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

                            <!-- Type Filter -->
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem; text-transform: uppercase;">Type</h3>
                                
                                <!-- Laptop Type -->
                                <div style="margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <input type="checkbox" id="laptop-type" name="laptop_type_main" value="laptop">
                                        <label for="laptop-type" style="font-weight: 500; color: #374151; font-size: 0.875rem;">Laptop Type</label>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.4rem; margin-left: 1.5rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="all-type" disabled>
                                            <span style="color: #6b7280;">All Type</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="2-in-1" disabled>
                                            <span style="color: #6b7280;">2-in-1</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="notebook" disabled>
                                            <span style="color: #6b7280;">Notebook</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="ultrabook" disabled>
                                            <span style="color: #6b7280;">Ultrabook</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="gaming-laptop" disabled>
                                            <span style="color: #6b7280;">Gaming Laptop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="mobile-workstation" disabled>
                                            <span style="color: #6b7280;">Mobile Workstation</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="business-laptop" disabled>
                                            <span style="color: #6b7280;">Business Laptop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="laptop_type[]" value="student-laptop" disabled>
                                            <span style="color: #6b7280;">Student Laptop</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Desktop Type -->
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <input type="checkbox" id="desktop-type" name="desktop_type_main" value="desktop">
                                        <label for="desktop-type" style="font-weight: 500; color: #374151; font-size: 0.875rem;">Desktop Type</label>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.4rem; margin-left: 1.5rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="all-type" disabled>
                                            <span style="color: #6b7280;">All Type</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="aio" disabled>
                                            <span style="color: #6b7280;">All-in-One (AIO) Desktop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="gaming-desktop" disabled>
                                            <span style="color: #6b7280;">Gaming Desktop</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
                                            <input type="checkbox" name="desktop_type[]" value="workstation-desktop" disabled>
                                            <span style="color: #6b7280;">Workstation Desktop</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" style="width: 100%; padding: 0.75rem; background: #1f2937; color: white; border: none; border-radius: 2rem; margin-top: 1rem;">
                                Apply Filters
                            </button>
                        </form>
                    </div>

                    <!-- Products Main -->
                    <div style="width: 75%;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                            @foreach($products as $product)                       
                            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; transition: all 0.3s ease; padding: 0; margin: 0; position: relative;" class="product-card" data-product-id="{{ $product->id }}">
                                <div style="width: 100%; height: 150px; background-color: #f3f4f6; overflow: hidden; margin: 0; padding: 0; border-radius: 0.5rem 0.5rem 0 0; position: relative;">
                                    <img src="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"    
                                        alt="{{ $product->name }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; margin: 0; padding: 0; display: block; border-radius: 0.5rem 0.5rem 0 0;">
                                </div>
                                <div style="padding: 0.75rem;">
                                    <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 0.875rem; line-height: 1.25;">{{ $product->name }}</h3>
                                    
                                    <!-- Product Specs with Price -->
                                    <div style="margin-bottom: 0.75rem;">
                                        @if($product->processor)
                                        <p style="color: #6b7280; font-size: 0.75rem; margin-bottom: 0.125rem;">{{ $product->processor }}</p>
                                        @endif
                                        @if($product->ram && $product->storage)
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <p style="color: #6b7280; font-size: 0.75rem; margin: 0;">{{ $product->ram }} • {{ $product->storage }}</p>
                                            <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">
                                                @if($product->has_variations && $product->variations->count() > 0)
                                                    RM{{ number_format($product->min_price, 2) }} - RM{{ number_format($product->max_price, 2) }}
                                                @else
                                                    RM{{ number_format($product->price, 2) }}
                                                @endif
                                            </p>
                                        </div>
                                        @elseif($product->ram)
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <p style="color: #6b7280; font-size: 0.75rem; margin: 0;">{{ $product->ram }}</p>
                                            <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">
                                                @if($product->has_variations && $product->variations->count() > 0)
                                                    RM{{ number_format($product->min_price, 2) }} - RM{{ number_format($product->max_price, 2) }}
                                                @else
                                                    RM{{ number_format($product->price, 2) }}
                                                @endif
                                            </p>
                                        </div>
                                        @elseif($product->storage)
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <p style="color: #6b7280; font-size: 0.75rem; margin: 0;">{{ $product->storage }}</p>
                                            <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">
                                                @if($product->has_variations && $product->variations->count() > 0)
                                                    RM{{ number_format($product->min_price, 2) }} - RM{{ number_format($product->max_price, 2) }}
                                                @else
                                                    RM{{ number_format($product->price, 2) }}
                                                @endif
                                            </p>
                                        </div>
                                        @else
                                        <p style="font-weight: bold; color: #1f2937; font-size: 1rem; margin: 0;">
                                            @if($product->has_variations && $product->variations->count() > 0)
                                                RM{{ number_format($product->min_price, 2) }} - RM{{ number_format($product->max_price, 2) }}
                                            @else
                                                RM{{ number_format($product->price, 2) }}
                                            @endif
                                        </p>
                                        @endif
                                    </div>
                                    
                                    <!-- Variation Indicator -->
                                    @if($product->has_variations && $product->variations->count() > 0)
                                    <div style="margin-bottom: 0.5rem;">
                                        <span style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem;">
                                            {{ $product->variations->count() }} variations available
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <!-- Buttons Row -->
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="add-to-cart-btn" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                                data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                                style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.4rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem; transition: all 0.2s ease; cursor: pointer;">
                                            <span class="cart-btn-text">Add to Cart</span>
                                        </button>
                                        <button class="buy-now-btn"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $product->price }}"
                                            data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                            data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                            style="flex: 1; background: #1f2937; color: white; padding: 0.4rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; border: none; transition: all 0.2s ease; cursor: pointer;">
                                        Buy Now
                                    </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
                            <!-- Previous Button--> 
                            @if($products->onFirstPage())
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: #f3f4f6; color: #9ca3af; cursor: not-allowed;" disabled>
                               Previous
                            </button>
                            @else
                            <a href="{{ $products->previousPageUrl() }}" style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: white; color: #1f2937; text-decoration: none; transition: all 0.2s ease;">
                                Previous
                            </a>
                            @endif

                            <!-- Page Numbers-->
                            <div style="display: flex; gap: 0.5rem;">
                                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    @if($page == $products->currentPage())
                                    <span style="padding: 0.5rem 0.75rem; border: 1px solid #1f2937; border-radius: 2rem; font-size: 0.875rem; background: #1f2937; color: white;">{{ $page }}</span>
                                    @else
                                    <a href="{{ $url }}" style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: white; color: #1f2937; text-decoration: none; transition: all 0.2s ease;">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Next Button -->
                            @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: white; color: #1f2937; text-decoration: none; transition: all 0.2s ease;">Next</a>
                            @else
                            <button style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 2rem; font-size: 0.875rem; background: #f3f4f6; color: #9ca3af; cursor: not-allowed;" disabled>Next</button>
                            @endif
                        </div>
                        @endif
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
                        <div class="recommendation-slider" style="display: flex; overflow-x: auto; gap: 1rem; padding-bottom: 1rem; scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($recommendedProducts as $product)
                            <div style="flex: 0 0 auto; width: 300px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; transition: all 0.3s ease; position: relative;" class="product-card" data-product-id="{{ $product->id }}">
                                <div style="width: 100%; height: 200px; background-color: #f3f4f6; overflow: hidden; margin: 0; padding: 0; position: relative;">
                                    <img src="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"    
                                        alt="{{ $product->name }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; margin: 0; padding: 0; display: block;">
                                </div>
                                <div style="padding: 0.75rem;">
                                    <h3 style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; font-size: 1rem;">{{ $product->name }}</h3>
                                    <p style="font-weight: bold; color: #1f2937; margin-bottom: 0.5rem; font-size: 1rem;">
                                        @if($product->has_variations && $product->variations->count() > 0)
                                            RM{{ number_format($product->min_price, 2) }} - RM{{ number_format($product->max_price, 2) }}
                                        @else
                                            RM{{ number_format($product->price, 2) }}
                                        @endif
                                    </p>
                                    
                                    <!-- Variation Indicator -->
                                    @if($product->has_variations && $product->variations->count() > 0)
                                    <div style="margin-bottom: 0.5rem;">
                                        <span style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem;">
                                            {{ $product->variations->count() }} variations available
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <div style="display: flex; gap: 0.25rem;">
                                        <button class="add-to-cart-btn" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                                data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                                style="flex: 1; border: 1px solid #1f2937; background: white; color: #1f2937; padding: 0.25rem 0.5rem; border-radius: 2rem; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.125rem; transition: all 0.2s ease; cursor: pointer;">
                                            <svg style="width: 0.75rem; height: 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <span class="cart-btn-text">Add to Cart</span>
                                        </button>
                                        <button class="buy-now-btn"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $product->price }}"
                                            data-product-image="{{ asset(str_replace('storage/app/public/', 'storage/', $product->image)) }}"
                                            data-has-variations="{{ $product->has_variations ? '1' : '0' }}"
                                            style="flex: 1; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 2rem; font-size: 0.8rem; border: none; transition: all 0.2s ease; cursor: pointer;">
                                        Buy Now
                                    </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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


/* ===== PRODUCT SCRIPT ===== */

@push('scripts')
<script>

// Global variables for variation selection
let selectedVariation = null;
let currentProductData = null;
let isBuyNow = false; // Flag to track if it's Buy Now or Add to Cart

function updateHeaderCartCount(count) {
    const cartBadge = document.querySelector('#cart-icon .action-badge');
    if (cartBadge) {
        if (count > 0) {
            cartBadge.textContent = count;
            cartBadge.style.display = 'flex';
        } else {
            cartBadge.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing product page');

    // Simple and reliable slider functionality
    function initializeSlider() {
        const slider = document.querySelector('.recommendation-slider');
        const prevBtn = document.querySelector('.recommendation-prev-btn');
        const nextBtn = document.querySelector('.recommendation-next-btn');
        
        console.log('Looking for slider elements:', {
            slider: slider,
            prevBtn: prevBtn,
            nextBtn: nextBtn
        });

        if (slider && prevBtn && nextBtn) {
            console.log('✅ Slider elements found - initializing');
            
            const scrollAmount = 320;
            
            // Remove any existing event listeners
            prevBtn.replaceWith(prevBtn.cloneNode(true));
            nextBtn.replaceWith(nextBtn.cloneNode(true));
            
            // Get fresh references after cloning
            const newPrevBtn = document.querySelector('.recommendation-prev-btn');
            const newNextBtn = document.querySelector('.recommendation-next-btn');
            
            newPrevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('⬅️ Previous button clicked');
                slider.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
            
            newNextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('➡️ Next button clicked');
                slider.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
            
            // Add hover effects
            [newPrevBtn, newNextBtn].forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.background = '#f8fafc';
                    this.style.borderColor = '#1f2937';
                    this.style.transform = 'scale(1.05)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.background = 'white';
                    this.style.borderColor = '#d1d5db';
                    this.style.transform = 'scale(1)';
                });
                
                btn.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                btn.addEventListener('mouseup', function() {
                    this.style.transform = 'scale(1.05)';
                });
            });
            
            console.log('✅ Slider initialized successfully');
            
        } else {
            console.log('❌ Slider elements not found');
            // Try alternative selectors
            const alternativeSlider = document.querySelector('[style*="overflow-x: auto"]');
            const alternativeButtons = document.querySelectorAll('.recommendation-controls button');
            
            if (alternativeSlider && alternativeButtons.length === 2) {
                console.log('🔄 Found alternative elements, initializing...');
                alternativeButtons[0].addEventListener('click', () => {
                    alternativeSlider.scrollBy({ left: -320, behavior: 'smooth' });
                });
                alternativeButtons[1].addEventListener('click', () => {
                    alternativeSlider.scrollBy({ left: 320, behavior: 'smooth' });
                });
            }
        }
    }

    // Initialize slider first
    initializeSlider();

    // Add to Cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            const productImage = this.getAttribute('data-product-image');
            const hasVariations = this.getAttribute('data-has-variations') === '1';
            
            const productData = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage
            };
            
            // If product has variations, show variation modal
            if (hasVariations) {
                showVariationModal(productData, false); // false for Add to Cart
            } else {
                // Direct add to cart for products without variations
                addToCartDirect(productData, this);
            }
        });
    });

    // Buy Now functionality
    document.querySelectorAll('.buy-now-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Buy now clicked');
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            const productImage = this.getAttribute('data-product-image');
            const hasVariations = this.getAttribute('data-has-variations') === '1';
            
            const productData = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage
            };
            
            if (hasVariations) {
                showVariationModal(productData, true);
            } else {
                processBuyNowDirect(productData, this);
            }
        });
    });

    // Variation modal event handlers
    const modalClose = document.querySelector('.variation-modal-close');
    const modalCancel = document.querySelector('.btn-cancel-variation');
    const modalConfirm = document.querySelector('.btn-confirm-variation');
    const variationModal = document.getElementById('variation-modal');

    if (modalClose) modalClose.addEventListener('click', closeVariationModal);
    if (modalCancel) modalCancel.addEventListener('click', closeVariationModal);
    if (modalConfirm) modalConfirm.addEventListener('click', processVariationAction);
    if (variationModal) {
        variationModal.addEventListener('click', function(e) {
            if (e.target === this) closeVariationModal();
        });
    }

    // Type filter functionality
    const laptopTypeCheckbox = document.getElementById('laptop-type');
    const desktopTypeCheckbox = document.getElementById('desktop-type');
    const laptopSubOptions = document.querySelectorAll('input[name="laptop_type[]"]');
    const desktopSubOptions = document.querySelectorAll('input[name="desktop_type[]"]');

    function toggleSubOptions(mainCheckbox, subOptions) {
        if (mainCheckbox.checked) {
            subOptions.forEach(option => {
                option.disabled = false;
                option.parentElement.style.color = '#374151';
            });
        } else {
            subOptions.forEach(option => {
                option.disabled = true;
                option.checked = false;
                option.parentElement.style.color = '#6b7280';
            });
        }
    }

    if (laptopTypeCheckbox) {
        laptopTypeCheckbox.addEventListener('change', function() {
            toggleSubOptions(laptopTypeCheckbox, laptopSubOptions);
        });
    }

    if (desktopTypeCheckbox) {
        desktopTypeCheckbox.addEventListener('change', function() {
            toggleSubOptions(desktopTypeCheckbox, desktopSubOptions);
        });
    }

    // Product card hover effects and popup functionality
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
            const img = card.querySelector('img');
            if (img) img.style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'none';
            const img = card.querySelector('img');
            if (img) img.style.transform = 'scale(1)';
        });

        // Add click event to product cards for popup
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                return;
            }
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.querySelector('h3').textContent;
            const priceElement = this.querySelector('p[style*="font-weight: bold"]');
            const price = priceElement ? priceElement.textContent.replace('RM', '') : '0.00';
            const image = this.querySelector('img').src;
            const hasVariations = this.querySelector('.add-to-cart-btn').getAttribute('data-has-variations') === '1';
            
            let processor = '';
            let ram = '';
            let storage = '';
            
            const specsContainer = this.querySelector('div[style*="margin-bottom: 0.75rem"]');
            if (specsContainer) {
                const processorElement = specsContainer.querySelector('p[style*="color: #6b7280"][style*="margin-bottom: 0.125rem"]');
                if (processorElement) {
                    processor = processorElement.textContent.trim();
                }
                
                const ramStorageElement = specsContainer.querySelector('p[style*="color: #6b7280"]:not([style*="margin-bottom: 0.125rem"])');
                if (ramStorageElement) {
                    const ramStorageText = ramStorageElement.textContent.trim();
                    if (ramStorageText.includes('•')) {
                        const parts = ramStorageText.split('•');
                        ram = parts[0].trim();
                        storage = parts[1].trim();
                    } else {
                        ram = ramStorageText;
                    }
                }
            }
            
            const product = {
                id: productId,
                name: productName,
                price: price,
                image: image,
                processor: processor,
                ram: ram,
                storage: storage,
                brand: productName.split(' ')[0] || 'Brand',
                description: 'High-performance device designed for professionals and enthusiasts alike.',
                has_variations: hasVariations
            };
            
            showProductPopup(product);
        });
    });
    console.log('🎉 Product page initialization complete');
});

function showVariationModal(productData, isBuyNowAction = false) {
    console.log('Show variation modal:', productData, isBuyNow);

    currentProductData = productData;
    isBuyNow = isBuyNowAction;
    const modal = document.getElementById('variation-modal');
    const modalBody = document.getElementById('variation-modal-body');
    const confirmBtn = document.querySelector('.btn-confirm-variation');
    const modalTitle = document.querySelector('.variation-modal-title');
    
    // Update modal title based on action
    modalTitle.textContent = isBuyNowAction ? 'Select Variation - Buy Now' : 'Select Variation';
    confirmBtn.textContent = isBuyNowAction ? 'Buy Now' : 'Add to Cart';
    
    // Reset selection
    selectedVariation = null;
    confirmBtn.disabled = true;
    
    // Show loading
    modalBody.innerHTML = '<div class="variation-loading">Loading variations...</div>';
    modal.style.display = 'block';
    
    // Fetch variations
    fetch(`/products/${productData.id}/variations`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.variations.length > 0) {
                renderVariationOptions(data.variations, modalBody);
            } else {
                modalBody.innerHTML = '<div class="no-variations">No variations available for this product.</div>';
            }
        })
        .catch(error => {
            console.error('Error loading variations:', error);
            modalBody.innerHTML = '<div class="no-variations">Error loading variations. Please try again.</div>';
        });
}

function renderVariationOptions(variations, container) {
    let html = '';
    
    variations.forEach(variation => {
        const specs = [];
        if (variation.processor) specs.push(variation.processor);
        if (variation.ram) specs.push(variation.ram);
        if (variation.storage) specs.push(variation.storage);
        if (variation.model) specs.push(variation.model);
        
        const isInStock = variation.stock > 0;
        const stockText = isInStock ? `${variation.stock} in stock` : 'Out of stock';
        const stockClass = isInStock ? 'in-stock' : 'out-of-stock';
        
        html += `
            <div class="variation-option ${!isInStock ? 'disabled' : ''}" 
                 data-variation-id="${variation.id}" 
                 data-variation-price="${variation.price}"
                 data-variation-sku="${variation.sku || ''}"
                 data-variation-sku="${variation.sku || ''}"  data-variation-stock="${variation.stock}">
                <div class="variation-specs">
                    ${specs.join(' • ')}
                </div>
                <div class="variation-price">
                    RM ${parseFloat(variation.price).toFixed(2)}
                </div>
                <div class="variation-stock ${stockClass}">
                    ${stockText}
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    
    // Add click handlers
    container.querySelectorAll('.variation-option:not(.disabled)').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            container.querySelectorAll('.variation-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Store selected variation data
            selectedVariation = {
                id: this.getAttribute('data-variation-id'),
                price: this.getAttribute('data-variation-price'),
                sku: this.getAttribute('data-variation-sku'),
                stock: this.getAttribute('data-variation-stock'),
                specs: this.querySelector('.variation-specs').textContent
            };
            
            // Enable confirm button
            document.querySelector('.btn-confirm-variation').disabled = false;
        });
    });
}

function processVariationAction() {
    console.log('Process variation action');

    if (!selectedVariation || !currentProductData) return;
    
    const confirmBtn = document.querySelector('.btn-confirm-variation');
    const originalText = confirmBtn.textContent;
    
    // Show loading
    confirmBtn.textContent = 'Processing...';
    confirmBtn.disabled = true;
    
    const requestData = {
        product_id: currentProductData.id,
        variation_id: selectedVariation.id,
        product_name: currentProductData.name,
        price: parseFloat(selectedVariation.price),
        quantity: 1,
        image: currentProductData.image,
        specs: selectedVariation.specs,
        sku: selectedVariation.sku
    };
    
    // Use Buy Now endpoint for both Add to Cart and Buy Now in variation modal
    const url = "{{ route('buy-now') }}";
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Redirecting to checkout...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 500);
        } else {
            if (data.requires_variation) {
                showNotification('Please select a variation for this product.', 'error');
            } else {
                showNotification(data.message || 'Failed to process product.', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error processing action:', error);
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        confirmBtn.textContent = originalText;
        confirmBtn.disabled = false;
    });
}

function closeVariationModal() {
    console.log('Close variation modal');
    
    const modal = document.getElementById('variation-modal');
    modal.style.display = 'none';
    selectedVariation = null;
    currentProductData = null;
    isBuyNow = false;
}

function showNotification(message, type = 'success') {
    console.log('Notification:', message);

    const notification = document.getElementById('cart-notification');
    const messageElement = document.getElementById('notification-message');
    
    messageElement.textContent = message;
    if (type === 'error') {
        notification.style.background = '#ef4444';
    } else {
        notification.style.background = '#10b981';
    }
    
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

function processBuyNowDirect(productData, button) {
    console.log('Buy now direct:', productData);

    const originalText = button.textContent;
    button.textContent = 'Processing...';
    button.disabled = true;
    
    const requestData = {
        product_id: productData.id,
        product_name: productData.name,
        price: parseFloat(productData.price),
        quantity: 1,
        image: productData.image
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Redirecting to checkout...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 500);
        } else {
            if (data.requires_variation) {
                showNotification('Please select a variation for this product.', 'error');
                // Show variation modal for products that require variation selection
                showVariationModal(productData, true);
            } else {
                showNotification(data.message || 'Failed to process product.', 'error');
            }
            button.textContent = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
        button.textContent = originalText;
        button.disabled = false;
    });
}

function addToCartDirect(productData, button) {
    const originalText = button.querySelector('.cart-btn-text').textContent;
    button.querySelector('.cart-btn-text').textContent = 'Adding...';
    button.disabled = true;
    
    const requestData = {
        product_id: productData.id,
        product_name: productData.name,
        price: parseFloat(productData.price),
        quantity: 1,
        image: productData.image
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart successfully!');
            updateHeaderCartCount(data.cart_count);
            
            setTimeout(() => {
                button.querySelector('.cart-btn-text').textContent = 'Added!';
                setTimeout(() => {
                    button.querySelector('.cart-btn-text').textContent = originalText;
                    button.disabled = false;
                }, 1000);
            }, 500);
        } else {
            showNotification(data.message || 'Failed to add product to cart.', 'error');
            button.querySelector('.cart-btn-text').textContent = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
        button.querySelector('.cart-btn-text').textContent = originalText;
        button.disabled = false;
    });
}

function toggleFavorite(productId, button, addToFavorite) {
    const url = addToFavorite ? '/favorites/add' : '/favorites/remove';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (addToFavorite) {
                button.classList.add('favorited');
                showNotification('Product added to favorites!');
            } else {
                button.classList.remove('favorited');
                showNotification('Product removed from favorites!');
            }
        } else {
            showNotification(data.message || 'Operation failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function showProductPopup(product) {
    const existingPopup = document.querySelector('.popup-overlay');
    if (existingPopup) {
        existingPopup.remove();
    }

    const popup = document.createElement('div');
    popup.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        backdrop-filter: blur(5px);
    `;

    // Variation selection section HTML
    const variationSection = product.has_variations ? `
        <div style="margin-bottom: 1.5rem;">
            <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 0.75rem;">Select Variation</h4>
            <div id="popup-variations-container" style="max-height: 150px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem;">
                <div style="text-align: center; color: #6b7280; padding: 1rem;">Loading variations...</div>
            </div>
        </div>
    ` : '';

    popup.innerHTML = `
        <div style="
            background: white;
            border-radius: 1.5rem;
            width: 900px;
            max-width: 95vw;
            height: 600px;
            max-height: 90vh;
            display: flex;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        ">
            <!-- Close Button -->
            <button onclick="this.closest('.popup-overlay').remove()" style="
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: #1f2937;
                color: white;
                border: none;
                width: 2.5rem;
                height: 2.5rem;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                font-weight: bold;
                cursor: pointer;
                z-index: 10;
                transition: all 0.2s ease;
            ">×</button>

            <!-- Left Side - Product Image -->
            <div style="
                flex: 1;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
                padding: 2rem;
            ">
                <!-- Brand Logo Background -->
                <div style="
                    position: absolute;
                    font-size: 6rem;
                    font-weight: 900;
                    color: rgba(0, 0, 0, 0.03);
                    transform: rotate(-45deg);
                    white-space: nowrap;
                    user-select: none;
                ">${product.brand}</div>
                
                <!-- Product Image -->
                <img src="${product.image}" alt="${product.name}" style="
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    filter: drop-shadow(20px 20px 30px rgba(0, 0, 0, 0.2));
                    position: relative;
                    z-index: 2;
                ">
            </div>

            <!-- Right Side - Product Details -->
            <div style="
                flex: 1;
                padding: 2.5rem 2rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            ">
                <!-- Product Info -->
                <div>
                    <!-- Product Name -->
                    <h2 style="
                        font-size: 1.75rem;
                        font-weight: bold;
                        color: #1f2937;
                        margin: 0 0 1.5rem 0;
                        line-height: 1.3;
                    ">${product.name}</h2>

                    <!-- All Specs Display -->
                    <div style="margin-bottom: 1rem;">
                        ${product.processor ? `
                        <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                            <div style="width: 4px; height: 4px; background: #1f2937; border-radius: 50%; margin-right: 0.75rem;"></div>
                            <span style="color: #1f2937; font-size: 0.95rem;">${product.processor}</span>
                        </div>
                        ` : ''}
                        
                        ${product.ram ? `
                        <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                            <div style="width: 4px; height: 4px; background: #1f2937; border-radius: 50%; margin-right: 0.75rem;"></div>
                            <span style="color: #1f2937; font-size: 0.95rem;">${product.ram}</span>
                        </div>
                        ` : ''}
                        
                        ${product.storage ? `
                        <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                            <div style="width: 4px; height: 4px; background: #1f2937; border-radius: 50%; margin-right: 0.75rem;"></div>
                            <span style="color: #1f2937; font-size: 0.95rem;">${product.storage}</span>
                        </div>
                        ` : ''}
                    </div>

                    <!-- Variation Selection -->
                    ${variationSection}

                    <!-- Price -->
                    <p style="
                        font-size: 2.25rem;
                        color: #1f2937;
                        margin: 0 0 1rem 0;
                        font-weight: bold;
                    " id="popup-price">RM${parseFloat(product.price).toFixed(2)}</p>

                    <!-- Description -->
                    <p style="
                        color: #6b7280;
                        line-height: 1.6;
                        margin: 0;
                        font-size: 0.95rem;
                    ">${product.description}</p>
                </div>

                <!-- Buttons - Fixed Position -->
                <div style="display: flex; gap: 1rem; margin-top: auto;">
                    <button class="popup-add-to-cart" 
                            data-product-id="${product.id}"
                            data-product-name="${product.name}"
                            data-product-price="${product.price}"
                            data-product-image="${product.image}"
                            data-has-variations="${product.has_variations}"
                            ${product.has_variations ? 'disabled' : ''}
                            style="
                        flex: 1;
                        border: 1px solid #1f2937;
                        background: white;
                        color: #1f2937;
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
                        ${product.has_variations ? 'opacity: 0.5; cursor: not-allowed;' : ''}
                    ">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Add to Cart
                    </button>
                    <button class="popup-buy-now"
                            data-product-id="${product.id}"
                            data-product-name="${product.name}"
                            data-product-price="${product.price}"
                            data-product-image="${product.image}"
                            data-has-variations="${product.has_variations}"
                            ${product.has_variations ? 'disabled' : ''}
                            style="
                        flex: 1;
                        background: #1f2937;
                        color: white;
                        padding: 0.875rem 1.5rem;
                        border-radius: 2rem;
                        font-size: 1rem;
                        border: none;
                        transition: all 0.2s ease;
                        cursor: pointer;
                        font-weight: 500;
                        ${product.has_variations ? 'opacity: 0.5; cursor: not-allowed;' : ''}
             @push('scripts')
<script>
// Global variables
let selectedVariation = null;
let currentProductData = null;
let isBuyNow = false;

// Utility Functions
function showNotification(message, type = 'success') {
    const notification = document.getElementById('cart-notification');
    const messageElement = document.getElementById('notification-message');
    
    if (!notification || !messageElement) {
        console.error('Notification elements not found');
        return;
    }
    
    messageElement.textContent = message;
    notification.style.background = type === 'error' ? '#ef4444' : '#10b981';
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

function updateHeaderCartCount(count) {
    const cartBadge = document.querySelector('#cart-icon .action-badge');
    if (cartBadge) {
        if (count > 0) {
            cartBadge.textContent = count;
            cartBadge.style.display = 'flex';
        } else {
            cartBadge.style.display = 'none';
        }
    }
}

// Variation Modal Functions
function showVariationModal(productData, isBuyNowAction = false) {
    currentProductData = productData;
    isBuyNow = isBuyNowAction;
    const modal = document.getElementById('variation-modal');
    const modalBody = document.getElementById('variation-modal-body');
    const confirmBtn = document.querySelector('.btn-confirm-variation');
    const modalTitle = document.querySelector('.variation-modal-title');
    
    if (!modal || !modalBody || !confirmBtn || !modalTitle) {
        console.error('Variation modal elements not found');
        return;
    }
    
    modalTitle.textContent = isBuyNowAction ? 'Select Variation - Buy Now' : 'Select Variation';
    confirmBtn.textContent = isBuyNowAction ? 'Buy Now' : 'Add to Cart';
    
    selectedVariation = null;
    confirmBtn.disabled = true;
    
    modalBody.innerHTML = '<div class="variation-loading">Loading variations...</div>';
    modal.style.display = 'block';
    
    fetch(`/products/${productData.id}/variations`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success && data.variations && data.variations.length > 0) {
                renderVariationOptions(data.variations, modalBody);
            } else {
                modalBody.innerHTML = '<div class="no-variations">No variations available for this product.</div>';
            }
        })
        .catch(error => {
            console.error('Error loading variations:', error);
            modalBody.innerHTML = '<div class="no-variations">Error loading variations. Please try again.</div>';
        });
}

function renderVariationOptions(variations, container) {
    let html = '';
    
    variations.forEach(variation => {
        const specs = [];
        if (variation.processor) specs.push(variation.processor);
        if (variation.ram) specs.push(variation.ram);
        if (variation.storage) specs.push(variation.storage);
        if (variation.model) specs.push(variation.model);
        
        const isInStock = variation.stock > 0;
        const stockText = isInStock ? `${variation.stock} in stock` : 'Out of stock';
        const stockClass = isInStock ? 'in-stock' : 'out-of-stock';
        const disabledClass = !isInStock ? 'disabled' : '';
        
        html += `
            <div class="variation-option ${disabledClass}" 
                 data-variation-id="${variation.id}" 
                 data-variation-price="${variation.price}"
                 data-variation-sku="${variation.sku || ''}"
                 data-variation-stock="${variation.stock}">
                <div class="variation-specs">
                    ${specs.join(' • ')}
                </div>
                <div class="variation-price">
                    RM ${parseFloat(variation.price).toFixed(2)}
                </div>
                <div class="variation-stock ${stockClass}">
                    ${stockText}
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    
    container.querySelectorAll('.variation-option:not(.disabled)').forEach(option => {
        option.addEventListener('click', function() {
            container.querySelectorAll('.variation-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            this.classList.add('selected');
            
            selectedVariation = {
                id: this.getAttribute('data-variation-id'),
                price: this.getAttribute('data-variation-price'),
                sku: this.getAttribute('data-variation-sku'),
                stock: this.getAttribute('data-variation-stock'),
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
    
    const confirmBtn = document.querySelector('.btn-confirm-variation');
    const originalText = confirmBtn.textContent;
    
    confirmBtn.textContent = 'Processing...';
    confirmBtn.disabled = true;
    
    const requestData = {
        product_id: currentProductData.id,
        variation_id: selectedVariation.id,
        product_name: currentProductData.name,
        price: parseFloat(selectedVariation.price),
        quantity: 1,
        image: currentProductData.image,
        specs: selectedVariation.specs,
        sku: selectedVariation.sku
    };
    
    const url = "{{ route('buy-now') }}";
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Redirecting to checkout...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 500);
        } else {
            showNotification(data.message || 'Failed to process product.', 'error');
        }
    })
    .catch(error => {
        console.error('Error processing action:', error);
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        confirmBtn.textContent = originalText;
        confirmBtn.disabled = false;
    });
}

function closeVariationModal() {
    const modal = document.getElementById('variation-modal');
    if (modal) {
        modal.style.display = 'none';
    }
    selectedVariation = null;
    currentProductData = null;
    isBuyNow = false;
}

// Cart Functions
function addToCartDirect(productData, button) {
    const originalText = button.querySelector('.cart-btn-text') ? button.querySelector('.cart-btn-text').textContent : button.textContent;
    
    if (button.querySelector('.cart-btn-text')) {
        button.querySelector('.cart-btn-text').textContent = 'Adding...';
    } else {
        button.textContent = 'Adding...';
    }
    button.disabled = true;
    
    const requestData = {
        product_id: productData.id,
        product_name: productData.name,
        price: parseFloat(productData.price),
        quantity: 1,
        image: productData.image
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart successfully!');
            updateHeaderCartCount(data.cart_count);
            
            setTimeout(() => {
                if (button.querySelector('.cart-btn-text')) {
                    button.querySelector('.cart-btn-text').textContent = 'Added!';
                    setTimeout(() => {
                        button.querySelector('.cart-btn-text').textContent = originalText;
                        button.disabled = false;
                    }, 1000);
                } else {
                    button.textContent = 'Added!';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 1000);
                }
            }, 500);
        } else {
            showNotification(data.message || 'Failed to add product to cart.', 'error');
            if (button.querySelector('.cart-btn-text')) {
                button.querySelector('.cart-btn-text').textContent = originalText;
            } else {
                button.textContent = originalText;
            }
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
        if (button.querySelector('.cart-btn-text')) {
            button.querySelector('.cart-btn-text').textContent = originalText;
        } else {
            button.textContent = originalText;
        }
        button.disabled = false;
    });
}

function processBuyNowDirect(productData, button) {
    const originalText = button.textContent;
    button.textContent = 'Processing...';
    button.disabled = true;
    
    const requestData = {
        product_id: productData.id,
        product_name: productData.name,
        price: parseFloat(productData.price),
        quantity: 1,
        image: productData.image
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Redirecting to checkout...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 500);
        } else {
            if (data.requires_variation) {
                showNotification('Please select a variation for this product.', 'error');
                showVariationModal(productData, true);
            } else {
                showNotification(data.message || 'Failed to process product.', 'error');
            }
            button.textContent = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
        button.textContent = originalText;
        button.disabled = false;
    });
}

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing product page');
    
    // Add to Cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Add to cart clicked');
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            const productImage = this.getAttribute('data-product-image');
            const hasVariations = this.getAttribute('data-has-variations') === '1';
            
            const productData = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage
            };
            
            if (hasVariations) {
                showVariationModal(productData, false);
            } else {
                addToCartDirect(productData, this);
            }
        });
    });

    // Buy Now buttons
    document.querySelectorAll('.buy-now-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Buy now clicked');
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            const productImage = this.getAttribute('data-product-image');
            const hasVariations = this.getAttribute('data-has-variations') === '1';
            
            const productData = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage
            };
            
            if (hasVariations) {
                showVariationModal(productData, true);
            } else {
                processBuyNowDirect(productData, this);
            }
        });
    });

    // Variation modal events
    const modalClose = document.querySelector('.variation-modal-close');
    const modalCancel = document.querySelector('.btn-cancel-variation');
    const modalConfirm = document.querySelector('.btn-confirm-variation');
    
    if (modalClose) modalClose.addEventListener('click', closeVariationModal);
    if (modalCancel) modalCancel.addEventListener('click', closeVariationModal);
    if (modalConfirm) modalConfirm.addEventListener('click', processVariationAction);

    // Close modal on outside click
    const modal = document.getElementById('variation-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeVariationModal();
            }
        });
    }

    // Product card hover effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
            const img = card.querySelector('img');
            if (img) img.style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'none';
            const img = card.querySelector('img');
            if (img) img.style.transform = 'scale(1)';
        });
    });

    console.log('Product page initialization complete');
});
</script>
@endpush