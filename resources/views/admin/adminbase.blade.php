<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">


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

    <style>
        /* Minimalist Color Theme */
        :root {
            --primary-dark: #1a2412;
            --primary-green: #2d4a35;
            --accent-gold: #DAA112;
            --light-bone: #f8f9fa;
            --dark-text: #1a2412;
            --light-text: #6b7c72;
            --white: #ffffff;
            --border-light: #e9ecef;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bone);
            margin: 0;
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--primary-dark);
            color: var(--white);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            text-align: center;
            padding: 1.5rem 0;
            background-color: var(--primary-green);
            margin: 0;
            color: var(--accent-gold);
            font-weight: 600;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin-top: 1rem;
        }

        .nav-links li {
            margin: 0.5rem 0;
        }

        .nav-links a {
            color: var(--white);
            text-decoration: none;
            display: block;
            padding: 0.8rem 1.5rem;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: var(--primary-green);
            color: var(--accent-gold);
        }

        /* Header */
        header {
            background-color: var(--white);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: var(--dark-text);
            font-size: 1.25rem;
            margin: 0;
        }

        header .user-info {
            color: var(--light-text);
            font-size: 0.9rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            padding: 2rem;
            flex: 1;
            overflow-y: auto;
        }
    </style>
</body>
</html>
