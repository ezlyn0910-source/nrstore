@extends('layouts.app')

@section('styles')
<style>
/* Bid Page Styles */
:root {
    --primary-dark: #1a2412;
    --primary-green: #2d4a35;
    --accent-green: #2f6032;
    --rare-green: #357a38;
    --light-green: #4caf50;
    --light-bone: #f8f9fa;
    --dark-text: #1a2412;
    --light-text: #6b7c72;
    --white: #ffffff;
    --border-light: #e9ecef;
    --shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.bid-page {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Hero Section */
.bid-hero-section {
    position: relative;
    color: white;
    overflow: hidden;
    height: 50vh;
    min-height: 400px;
    width: 100%;
    margin: 0;
    padding: 0 !important;
}

.bid-hero-banner {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.hero-overlay-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    width: 100%;
}

.hero-overlay-text h1 {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 1rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

.hero-overlay-text p {
    font-size: 1.25rem;
    opacity: 0.9;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
}

/* Section Base Styles */
.bid-section {
    padding: 5rem 0;
    width: 100%;
}

.bid-section-light {
    background-color: #f8f9fa;
}

.bid-section-dark {
    background-color: #2d4a35;
    color: white;
}

.bid-section-lighter-green {
    background-color: #3a5a40;
    color: white;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Section Titles */
.section-title-container {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.title-part-black {
    color: #1a2412;
}

.title-part-white {
    color: white;
}

.title-part-green {
    color: var(--accent-green);
}

.title-underline {
    width: 80px;
    height: 4px;
    background: var(--accent-green);
    margin: 0 auto;
    border-radius: 2px;
}

.title-underline-white {
    background: white;
}

/* Brands Section (used on main bid page) */
.brands-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: nowrap;
    gap: 2rem;
}

.brand-card {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1.5rem 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.brand-card:hover {
    transform: translateY(-5px);
    text-decoration: none;
    color: inherit;
}

.brand-logo-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.3s ease;
}

.brand-card:hover .brand-logo-circle {
    border-color: var(--accent-green);
    box-shadow: 0 8px 25px rgba(218, 161, 18, 0.15);
}

.brand-logo-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.brand-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a2412;
}

/* Brand Auctions Page Styles */
.brand-hero-section {
    position: relative;
    color: white;
    overflow: hidden;
    height: 40vh;
    min-height: 300px;
    width: 100%;
    margin: 0;
    padding: 0 !important;
}

.brand-hero-banner {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.brand-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(26, 36, 18, 0.7), rgba(45, 74, 53, 0.7));
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 2rem;
}

.brand-hero-title {
    font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

.brand-hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
}

.brand-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    margin-top: 1rem;
    opacity: 0.9;
}

.brand-breadcrumb a {
    color: #4caf50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.brand-breadcrumb a:hover {
    color: white;
}

.brand-breadcrumb .separator {
    margin: 0 0.5rem;
}

/* Brand Auctions Layout */
.brand-main-content {
    width: 100%;
}

.brand-auctions-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    padding: 3rem 0;
}

/* Filters Section */
.brand-filters {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.filter-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-light);
}

.filter-section:last-child {
    margin-bottom: 0;
    border-bottom: none;
}

.filter-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 1rem;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.filter-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.filter-option input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent-green);
}

.filter-option label {
    cursor: pointer;
    color: var(--light-text);
    transition: color 0.3s ease;
}

.filter-option:hover label {
    color: var(--dark-text);
}

.price-range {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.price-inputs {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.price-input-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.price-label {
    font-size: 0.875rem;
    color: var(--light-text);
    font-weight: 500;
}

.price-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.3s ease;
}

.price-input:focus {
    outline: none;
    border-color: var(--accent-green);
}

.filter-btn {
    width: 100%;
    padding: 0.75rem;
    background: #2d4a35;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 0.5rem;
}

.filter-btn:hover {
    background: #2f6032;
    transform: translateY(-2px);
}

/* Products Grid */
.brand-products {
    width: 100%;
}

.brand-products-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.brand-product-card {
    display: grid;
    grid-template-columns: 200px 1fr auto;
    gap: 0;
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.product-image {
    width: 200px;
    height: 100%;
    border-radius: 1rem 0 0 1rem;
    overflow: hidden;
    margin: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.product-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 1.5rem;
}

.product-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 0.5rem;
}

.product-specs {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.product-spec {
    font-size: 0.875rem;
    color: var(--light-text);
}

.product-price-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 1rem;
    min-width: 180px;
    padding: 1.5rem;
}

/* Countdown Timer Styles */
.countdown-timer {
    margin-top: 1rem;
}

.countdown-label {
    font-size: 0.875rem;
    color: var(--light-text);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.countdown-display {
    display: flex;
    gap: 0;
    flex-wrap: wrap;
}

.countdown-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 55px;
}

.countdown-box {
    width: 40px;
    height: 40px;
    background: #1a2412;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    position: relative;
}

.countdown-value {
    font-size: 1rem;
    font-weight: bold;
    color: white;
    line-height: 1;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    font-family: "Courier New", monospace;
}

.countdown-label-small {
    font-size: 0.65rem;
    color: var(--light-text);
    text-transform: uppercase;
    font-weight: 500;
}

/* Products Header */
.brand-products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 0 0.5rem;
}

.products-count {
    font-size: 1rem;
    color: var(--light-text);
    margin: 0;
}

.sort-select {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    background: white;
    color: var(--dark-text);
    margin: 0;
}

.current-bid {
    text-align: right;
}

.bid-label {
    font-size: 0.875rem;
    color: var(--light-text);
    margin-bottom: 0.25rem;
}

.bid-amount {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--accent-green);
}

.exclamation-mark {
    color: #dc2626;
    font-weight: 900;
    transform: skewX(10deg) scale(1.1);
    display: inline-block;
    margin-right: 0.125rem;
}

.auction-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--light-text);
}

.buy-now-btn {
    padding: 0.75rem 1.5rem;
    background: var(--primary-green);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    display: block;
}

.buy-now-btn:hover {
    background: #253c2a;
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

/* Live / Featured / Upcoming auctions styles (from main bid page) */
/* Keeping them in case reused elsewhere */

.live-auctions-slider-container {
    position: relative;
    overflow: hidden;
    padding: 1rem 0;
}

.live-auctions-track {
    display: flex;
    gap: 1.5rem;
    animation: slideInfinite 40s linear infinite;
    width: max-content;
}

.live-auctions-track:hover {
    animation-play-state: paused;
}

@keyframes slideInfinite {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-300px * 6 - 1.5rem * 6));
    }
}

.live-auction-card {
    flex: 0 0 300px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.bid-section-light .live-auction-card {
    background: white;
    border-color: #e9ecef;
}

.bid-section-dark .live-auction-card {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: white;
}

.live-auction-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.live-auction-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: #dc2626;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 2;
    opacity: 1;
    visibility: visible;
}

.live-auction-image {
    width: 100%;
    height: 200px;
    background: #f8f9fa;
    overflow: hidden;
}

.bid-section-dark .live-auction-image {
    background: rgba(255, 255, 255, 0.05);
}

.live-auction-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.live-auction-card:hover .live-auction-image img {
    transform: scale(1.05);
}

.live-auction-content {
    padding: 1.25rem;
}

.live-auction-name {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.bid-section-light .live-auction-name {
    color: #1a2412;
}

.bid-section-dark .live-auction-name {
    color: white;
}

.live-auction-condition {
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.bid-section-light .live-auction-condition {
    color: #6b7c72;
}

.bid-section-dark .live-auction-condition {
    color: rgba(255, 255, 255, 0.8);
}

.live-auction-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.live-auction-price {
    font-size: 1.25rem;
    font-weight: bold;
    color: #f8f9fa;
}

.live-auction-bids {
    font-size: 0.875rem;
}

.bid-section-light .live-auction-bids {
    color: #6b7c72;
}

.bid-section-dark .live-auction-bids {
    color: rgba(255, 255, 255, 0.8);
}

.live-auction-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.bid-section-light .live-auction-time {
    color: #2d4a35;
}

.bid-section-dark .live-auction-time {
    color: #dc2626;
}

/* Featured Auctions */
.featured-auctions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.featured-auction-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.bid-section-dark .featured-auction-card {
    background: rgba(255, 255, 255, 0.1);
}

.featured-auction-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.featured-auction-image {
    width: 100%;
    height: 200px;
    background: #f8f9fa;
    overflow: hidden;
}

.bid-section-dark .featured-auction-image {
    background: rgba(255, 255, 255, 0.05);
}

.featured-auction-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.featured-auction-card:hover .featured-auction-image img {
    transform: scale(1.05);
}

.featured-auction-content {
    padding: 1.5rem;
}

.featured-auction-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.bid-section-light .featured-auction-name {
    color: #1a2412;
}

.bid-section-dark .featured-auction-name {
    color: white;
}

.featured-auction-description {
    font-size: 0.875rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.bid-section-light .featured-auction-description {
    color: #6b7c72;
}

.bid-section-dark .featured-auction-description {
    color: rgba(255, 255, 255, 0.8);
}

.featured-auction-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.featured-auction-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--accent-green);
}

.featured-auction-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.bid-section-light .featured-auction-time {
    color: #2d4a35;
}

.bid-section-dark .featured-auction-time {
    color: var(--accent-green);
}

/* Upcoming Auctions */
.upcoming-auctions-container {
    position: relative;
    overflow: hidden;
    padding: 1rem 0;
}

.upcoming-auctions-track {
    display: flex;
    gap: 1.5rem;
    width: max-content;
}

.upcoming-auction-card {
    flex: 0 0 320px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.bid-section-light .upcoming-auction-card {
    background: white;
    border-color: #e9ecef;
}

.bid-section-dark .upcoming-auction-card {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: white;
}

.upcoming-auction-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.upcoming-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: #316534;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 2;
}

.upcoming-auction-image {
    width: 100%;
    height: 180px;
    background: #f8f9fa;
    overflow: hidden;
}

.bid-section-dark .upcoming-auction-image {
    background: rgba(255, 255, 255, 0.05);
}

.upcoming-auction-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.upcoming-auction-card:hover .upcoming-auction-image img {
    transform: scale(1.05);
}

.upcoming-auction-content {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.upcoming-auction-name {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.bid-section-light .upcoming-auction-name {
    color: #1a2412;
}

.bid-section-dark .upcoming-auction-name {
    color: white;
}

.upcoming-auction-specs {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.upcoming-auction-spec {
    font-size: 0.875rem;
    color: #6b7c72;
}

.bid-section-dark .upcoming-auction-spec {
    color: rgba(255, 255, 255, 0.8);
}

.upcoming-auction-price {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 0.5rem;
    text-align: center;
    margin: 0.5rem 0;
}

.bid-section-dark .upcoming-auction-price {
    background: rgba(255, 255, 255, 0.05);
}

.price-label {
    font-size: 0.875rem;
    color: #6b7c72;
    margin-bottom: 0.25rem;
}

.bid-section-dark .price-label {
    color: rgba(255, 255, 255, 0.8);
}

.price-tba {
    font-size: 1.25rem;
    font-weight: bold;
    color: #316534;
}

.upcoming-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #316534;
    font-weight: 500;
    padding: 0.5rem 0;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
}

.bid-section-dark .upcoming-date {
    color: #316534;
    border-color: rgba(255, 255, 255, 0.2);
}

.reminder-btn {
    width: 100%;
    padding: 0.75rem;
    background: #316534;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.reminder-btn:hover {
    background: #2d4a35;
    transform: translateY(-2px);
}

.reminder-btn.reminder-set {
    background: #6b7c72;
}

.reminder-btn.reminder-set:hover {
    background: #5a6a60;
}

/* Responsive adjustments */
@media (max-width: 968px) {
    .brand-auctions-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .brand-filters {
        position: static;
    }

    .brand-product-card {
        grid-template-columns: 150px 1fr;
    }

    .product-price-info {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .bid-hero-section {
        height: 40vh;
        min-height: 300px;
    }

    .hero-overlay-text h1 {
        font-size: 2.5rem;
    }

    .bid-section {
        padding: 3rem 0;
    }

    .section-title {
        font-size: 2rem;
    }

    .live-auction-card {
        flex: 0 0 280px;
    }

    .featured-auctions-grid {
        grid-template-columns: 1fr;
    }

    .brand-hero-title {
        font-size: 2.5rem;
    }

    .brand-products-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .sort-select {
        align-self: flex-end;
    }

    .brand-product-card {
        grid-template-columns: 1fr;
    }

    .product-image {
        width: 100%;
        height: 200px;
        border-radius: 1rem 1rem 0 0;
    }

    .product-price-info {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-radius: 0 0 1rem 1rem;
    }

    .brands-row {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
    }

    .brand-card {
        flex: 0 0 calc(50% - 1.5rem);
    }

    .countdown-display {
        gap: 0.375rem;
    }

    .countdown-unit {
        min-width: 50px;
    }

    .countdown-box {
        width: 50px;
        height: 50px;
    }

    .countdown-value {
        font-size: 1.125rem;
    }

    .countdown-label-small {
        font-size: 0.7rem;
    }

    .upcoming-auction-card {
        flex: 0 0 280px;
    }

    .upcoming-auction-image {
        height: 160px;
    }
}

@media (max-width: 480px) {
    .bid-hero-section {
        height: 35vh;
        min-height: 250px;
    }

    .hero-overlay-text h1 {
        font-size: 2rem;
    }

    .brand-card {
        flex: 0 0 100%;
    }

    .brands-row {
        gap: 1rem;
    }

    .brand-hero-title {
        font-size: 2rem;
    }

    .brand-hero-subtitle {
        font-size: 1rem;
    }

    .brand-products-header {
        flex-direction: column;
        gap: 1rem;
    }

    .products-count,
    .sort-select {
        width: 100%;
        text-align: center;
    }

    .sort-select {
        align-self: stretch;
    }

    .countdown-display {
        gap: 0.25rem;
    }

    .countdown-unit {
        min-width: 45px;
    }

    .countdown-box {
        width: 45px;
        height: 45px;
    }

    .countdown-value {
        font-size: 1rem;
    }

    .countdown-label-small {
        font-size: 0.65rem;
    }

    .upcoming-auction-card {
        flex: 0 0 260px;
    }

    .upcoming-auction-image {
        height: 140px;
    }
}
</style>
@endsection

@section('content')
<div class="bid-page">
    <!-- Hero Section -->
    <section class="brand-hero-section">
        <div class="brand-hero-banner"
             style="background: linear-gradient(135deg, #316534 0%, #2d4a35 50%, #1a2412 100%);">
        </div>
        <div class="brand-hero-overlay">
            <h1 class="brand-hero-title">Auction Categories</h1>
            <p class="brand-hero-subtitle">
                Discover amazing {{ $brandData['name'] ?? 'Brand' }} products through competitive bidding
            </p>
            <div class="brand-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">></span>
                <a href="{{ route('bid.index') }}">Bid</a>
                <span class="separator">></span>
                <a href="{{ route('bid.index') }}#brands">Categories</a>
                <span class="separator">></span>
                <span>{{ $brandData['name'] ?? 'Brand' }}</span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="brand-main-content">
        <div class="container">
            <div class="brand-auctions-layout">
                <!-- Filters Sidebar -->
                <div class="brand-filters">
                    <div class="filter-section">
                        <h3 class="filter-title">Price Range</h3>
                        <div class="price-range">
                            <div class="price-inputs">
                                <div class="price-input-group">
                                    <label class="price-label" for="minPrice">Minimum Price (RM)</label>
                                    <input type="number" class="price-input" placeholder="0" id="minPrice" min="0">
                                </div>
                                <div class="price-input-group">
                                    <label class="price-label" for="maxPrice">Maximum Price (RM)</label>
                                    <input type="number" class="price-input" placeholder="10000" id="maxPrice" min="0">
                                </div>
                            </div>
                            <button class="filter-btn">Apply Price Filter</button>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h3 class="filter-title">Product Type</h3>
                        <div class="filter-options">
                            <div class="filter-option">
                                <input type="checkbox" id="laptops" name="productType">
                                <label for="laptops">Laptops</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="desktops" name="productType">
                                <label for="desktops">Desktops</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="aio" name="productType">
                                <label for="aio">All-in-One PCs</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="accessories" name="productType">
                                <label for="accessories">Accessories</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h3 class="filter-title">Condition</h3>
                        <div class="filter-options">
                            <div class="filter-option">
                                <input type="checkbox" id="new" name="condition">
                                <label for="new">New</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="refurbished" name="condition">
                                <label for="refurbished">Refurbished</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="used" name="condition">
                                <label for="used">Used - Good</label>
                            </div>
                        </div>
                    </div>

                    <button class="filter-btn">Apply All Filters</button>
                </div>

                <!-- Products List -->
                <div class="brand-products">
                    <div class="brand-products-header">
                        <div class="products-count">
                            {{ $auctions->count() }} {{ $brandData['name'] ?? 'Brand' }} Products Available
                        </div>
                        <select class="sort-select">
                            <option>Sort by: Latest</option>
                            <option>Sort by: Price Low to High</option>
                            <option>Sort by: Price High to Low</option>
                            <option>Sort by: Ending Soonest</option>
                        </select>
                    </div>

                    <div class="brand-products-grid">
                        @forelse($auctions as $auction)
                            <div class="brand-product-card">
                                <!-- Product Image -->
                                <div class="product-image">
                                    <img src="{{ asset($auction->image) }}"
                                         alt="{{ $auction->product_name }}">
                                </div>

                                <!-- Product Info -->
                                <div class="product-info">
                                    <h3 class="product-name">{{ $auction->product_name }}</h3>

                                    <div class="product-specs">
                                        @if(!empty($auction->short_description))
                                            <div class="product-spec">{{ $auction->short_description }}</div>
                                        @elseif(!empty($auction->spec))
                                            <div class="product-spec">{{ $auction->spec }}</div>
                                        @endif

                                        @if(!empty($auction->processor))
                                            <div class="product-spec">{{ $auction->processor }}</div>
                                        @endif

                                        @if(!empty($auction->memory) || !empty($auction->storage))
                                            <div class="product-spec">
                                                @if(!empty($auction->memory))
                                                    {{ $auction->memory }}
                                                @endif
                                                @if(!empty($auction->memory) && !empty($auction->storage))
                                                    ,
                                                @endif
                                                @if(!empty($auction->storage))
                                                    {{ $auction->storage }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Countdown Timer -->
                                    <div class="countdown-timer">
                                        <div class="countdown-label">Auction Ends In:</div>
                                        <div
                                            class="countdown-display"
                                            @if(!empty($auction->end_time))
                                                data-end-date="{{ \Carbon\Carbon::parse($auction->end_time)->format('Y-m-d\TH:i:s') }}"
                                            @endif
                                        >
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-months>0</span>
                                                </div>
                                                <span class="countdown-label-small">Months</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-weeks>0</span>
                                                </div>
                                                <span class="countdown-label-small">Weeks</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-days>0</span>
                                                </div>
                                                <span class="countdown-label-small">Days</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-hours>00</span>
                                                </div>
                                                <span class="countdown-label-small">Hours</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-minutes>00</span>
                                                </div>
                                                <span class="countdown-label-small">Minutes</span>
                                            </div>
                                            <div class="countdown-unit">
                                                <div class="countdown-box">
                                                    <span class="countdown-value" data-seconds>00</span>
                                                </div>
                                                <span class="countdown-label-small">Seconds</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price & Actions -->
                                <div class="product-price-info">
                                    <div class="current-bid">
                                        <div class="bid-label">Current Bid</div>
                                        <div class="bid-amount">
                                            <span class="exclamation-mark">!</span>
                                            RM{{ number_format($auction->current_bid ?? $auction->starting_price ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="auction-time">
                                        <i class="fas fa-clock"></i>
                                        @if(!empty($auction->time_left))
                                            <span>{{ $auction->time_left }}</span>
                                        @elseif(!empty($auction->end_time))
                                            <span>Ends {{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</span>
                                        @else
                                            <span>Ongoing</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('bid.show', $auction->id) }}" class="buy-now-btn">
                                        Place Bid
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center mt-4" style="width: 100%;">
                                No auctions available for {{ $brandData['name'] ?? 'this brand' }} at the moment.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Countdown Timer Function
function updateCountdownTimers() {
    const countdowns = document.querySelectorAll('.countdown-display');
    
    countdowns.forEach(countdown => {
        const endDateAttr = countdown.dataset.endDate;
        if (!endDateAttr) return;

        const endDate = new Date(endDateAttr).getTime();
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            countdown.innerHTML = '<div style="color: #dc2626; font-weight: 600;">Auction Ended</div>';
            return;
        }
        
        // Calculate time units
        const months = Math.floor(distance / (1000 * 60 * 60 * 24 * 30));
        const weeks = Math.floor((distance % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24 * 7));
        const days = Math.floor((distance % (1000 * 60 * 60 * 24 * 7)) / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Update display
        const monthsEl = countdown.querySelector('[data-months]');
        const weeksEl = countdown.querySelector('[data-weeks]');
        const daysEl = countdown.querySelector('[data-days]');
        const hoursEl = countdown.querySelector('[data-hours]');
        const minutesEl = countdown.querySelector('[data-minutes]');
        const secondsEl = countdown.querySelector('[data-seconds]');
        
        if (monthsEl) monthsEl.textContent = months;
        if (weeksEl) weeksEl.textContent = weeks;
        if (daysEl) daysEl.textContent = days;
        if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
        if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
        if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
    });
}

// Initialize countdown timers
document.addEventListener('DOMContentLoaded', function() {
    updateCountdownTimers();
    setInterval(updateCountdownTimers, 1000);
});
</script>
@endpush