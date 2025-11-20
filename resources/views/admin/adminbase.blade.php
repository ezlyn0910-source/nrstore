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
            <li><a href="{{ route('admin.manageuser.index') }}" class="{{ request()->routeIs('manageuser.*') ? 'active' : '' }}">User Management</a></li>
            <li><a href="{{ route('admin.manageproduct.index') }}" class="{{ request()->routeIs('manageproduct.*') ? 'active' : '' }}">Product Management</a></li>
            <li><a href="{{ route('admin.manageorder.index') }}" class="{{ request()->routeIs('manageorder.*') ? 'active' : '' }}">Order Management</a></li>
            <li><a href="{{ route('admin.managebid.index') }}" class="{{ request()->routeIs('managebid.*') ? 'active' : '' }}">Bid Management</a></li>
            <li><a href="{{ route('admin.managereport.index')}}" class="{{ request()->routeIs('managereport.*') ? 'active' : '' }}">Reports</a></li>
        </ul>
    </aside>

    <!-- Main -->
    <div class="main-content">
        <header>
            <h1>@yield('page_title', 'Dashboard')</h1>
            <div class="user-info">
                {{ Auth::user()->name ?? 'Admin' }}
            
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf 
                    <a href="{{ route('logout') }}"
                    class="header-link"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </form>
            </div>
        </header>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
</body>
</html>
