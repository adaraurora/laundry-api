<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Laundry - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #1a1a2e; }
        .sidebar .nav-link { color: #ccc; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #16213e; }
        .brand { color: #fff; font-weight: bold; font-size: 1.3rem; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <nav class="col-md-2 sidebar p-3">
            <div class="brand mb-4">🧺 Laundry Admin</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                       href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-bag"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                       href="{{ route('admin.services.index') }}">
                        <i class="bi bi-tags"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Main Content --}}
        <main class="col-md-10 p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>