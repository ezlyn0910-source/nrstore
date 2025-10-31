<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/css/adminbase.css', 'resources/js/app.js'])
    @vite(['resources/sass/app.scss', 'resources/css/admindashboard.css', 'resources/js/app.js'])

    @yield('styles')

</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul class="nav-links">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('manageuser.index') }}" class="{{ request()->routeIs('manageuser.*') ? 'active' : '' }}">User Management</a></li>
            <li><a href="{{ route('manageproduct.index') }}" class="{{ request()->routeIs('manageproducts.*') ? 'active' : '' }}">Product Management</a></li>
            <li><a href="#">Order Management</a></li>
            <li><a href="#">Reports</a></li>
        </ul>
    </aside>

    <!-- Main -->
    <div class="main-content">
        <header>
            <h1>@yield('page_title', 'Dashboard')</h1>
            <div class="user-info">
                {{ Auth::user()->name ?? 'Admin' }}
            </div>
        </header>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
</body>
</html>
