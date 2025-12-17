@extends('layouts.app')

@section('styles')
<style>
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
    --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 10px 36px rgba(0, 0, 0, 0.12);
}

/* PAGE BASE */
.policy-page {
    background-color: var(--light-bone);
    min-height: 100vh;
}

/* HERO */
.policy-hero {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-green));
    padding: 90px 0 110px;
    color: var(--white);
    text-align: center;
}

.policy-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 0.1px;
}

.policy-hero p {
    font-size: 1.25rem;
    color: rgba(255,255,255,0.85);
}

/* CONTAINER */
.policy-container {
    max-width: 1200px;
    margin: -70px auto 90px;
    padding: 0 20px;
}

.policy-card {
    background: var(--white);
    border-radius: 20px;
    box-shadow: var(--shadow);
    display: grid;
    grid-template-columns: 280px 1fr;
    overflow: hidden;
}

/* SIDEBAR */
.policy-sidebar {
    background: #f4f7f5;
    border-right: 1px solid var(--border-light);
    padding: 35px 28px;
}

.policy-nav a {
    display: block;
    padding: 11px 14px;
    margin-bottom: 6px;
    border-radius: 2rem;
    text-decoration: none;
    color: var(--dark-text);
}

.policy-nav a.active {
    background: var(--accent-green);
    color: var(--white);
}

/* CONTENT */
.policy-content {
    padding: 48px 56px;
}

.policy-content h2 {
    font-size: 2.5rem;
    color: var(--primary-dark);
    margin-bottom: 14px;
}

.policy-content h3 {
    font-size: 2rem;
    color: var(--primary-dark);
    margin-top: 40px;
    margin-bottom: 10px;
}

@media (max-width: 992px) {
    .policy-card {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="policy-page">

    <!-- HERO -->
    <section class="policy-hero">
        <div class="container">
            <h1>@yield('policy_title')</h1>
            <p>@yield('policy_subtitle')</p>
        </div>
    </section>

    <!-- CARD -->
    <div class="policy-container">
        <div class="policy-card">

            <aside class="policy-sidebar">
                <nav class="policy-nav">
                    <a href="{{ route('shipping.info') }}"
                       class="{{ request()->routeIs('shipping.info') ? 'active' : '' }}">
                        Shipping Information
                    </a>

                    <a href="{{ route('return.policy') }}"
                       class="{{ request()->routeIs('return.policy') ? 'active' : '' }}">
                        Returns & Refunds
                    </a>

                    <a href="{{ route('privacy.policy') }}"
                       class="{{ request()->routeIs('privacy.policy') ? 'active' : '' }}">
                        Privacy Policy
                    </a>

                    <a href="{{ route('terms.conditions') }}"
                       class="{{ request()->routeIs('terms.conditions') ? 'active' : '' }}">
                        Terms & Conditions
                    </a>
                </nav>
            </aside>

            <main class="policy-content">
                @yield('policy_content')
            </main>

        </div>
    </div>
</div>
@endsection
