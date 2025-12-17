<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tradefluenza Payout System')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
    
    <style>
        :root {
            --bs-primary: #696cff;
            --bs-secondary: #8592a3;
            --bs-success: #71dd37;
            --bs-info: #03c3ec;
            --bs-warning: #ffab00;
            --bs-danger: #ff3e1d;
            --bs-dark: #233446;
            --bs-gray: #f5f5f9;
        }
        
        body {
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            background-color: #f5f5f9;
        }
        
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .layout-menu {
            width: 260px;
            background: #fff;
            box-shadow: 0 0.125rem 0.625rem rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .app-brand {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid #e7e7e7;
        }
        
        .app-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--bs-primary);
            text-decoration: none;
        }
        
        .menu-inner {
            padding: 1rem 0;
        }
        
        .menu-item {
            padding: 0.5rem 1.5rem;
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            color: #697a8d;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        
        .menu-link:hover, .menu-link.active {
            background-color: rgba(105, 108, 255, 0.08);
            color: var(--bs-primary);
        }
        
        .menu-icon {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        .layout-page {
            margin-left: 260px;
            width: calc(100% - 260px);
        }
        
        .layout-navbar {
            background: #fff;
            box-shadow: 0 0.125rem 0.625rem rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .content-wrapper {
            padding: 1.5rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.625rem rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e7e7e7;
            padding: 1.25rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .badge {
            padding: 0.375rem 0.75rem;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .btn {
            padding: 0.5rem 1.25rem;
            font-weight: 500;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #697a8d;
            border-bottom: 1px solid #e7e7e7;
        }
        
        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .menu-header {
            padding: 0.5rem 1.5rem;
            margin-top: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #a1acb8;
            text-transform: uppercase;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="layout-wrapper">
        <!-- Menu -->
        <aside class="layout-menu">
            <div class="app-brand">
                <a href="/" class="app-brand-text">Tradefluenza</a>
            </div>
            
            <div class="menu-inner">
                <div class="menu-header">DASHBOARDS</div>
                
                <ul class="menu-inner-list list-unstyled">
                    @auth
                        @if(auth()->user()->isAdmin())
                            {{-- Admin sees Admin Dashboard --}}
                            <li class="menu-item">
                                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-speedometer2"></i>
                                    <span>Admin Dashboard</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.propfirms.index') }}" class="menu-link {{ request()->routeIs('admin.propfirms.*') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-building"></i>
                                    <span>Manage Propfirms</span>
                                </a>
                            </li>
                        @else
                            {{-- Propfirm user sees only Propfirm Dashboard --}}
                            <li class="menu-item">
                                <a href="{{ route('propfirm.dashboard') }}" class="menu-link {{ request()->routeIs('propfirm.dashboard') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-building"></i>
                                    <span>Propfirm Dashboard</span>
                                </a>
                            </li>
                        @endif
                    @else
                        {{-- Guest sees login --}}
                        <li class="menu-item">
                            <a href="{{ route('login') }}" class="menu-link">
                                <i class="menu-icon bi bi-box-arrow-in-right"></i>
                                <span>Login</span>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </aside>
        
        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            <nav class="layout-navbar">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                    <div class="d-flex align-items-center gap-3">
                        @auth
                            <span class="text-muted">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <span class="badge bg-success">Admin</span>
                            @endif
                            <div class="avatar bg-primary text-white">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        @else
                            <span class="text-muted">Guest</span>
                            <div class="avatar bg-primary text-white">G</div>
                        @endauth
                    </div>
                </div>
            </nav>
            
            <!-- Content wrapper -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Core JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    @stack('scripts')
</body>
</html>
