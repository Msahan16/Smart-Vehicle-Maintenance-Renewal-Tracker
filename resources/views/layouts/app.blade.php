<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Vehicle Tracker') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 512'><path fill='%23667eea' d='M171.3 96H224v96H111.3l30.4-75.9C146.5 104 158.2 96 171.3 96zM272 192V96h81.2c9.7 0 18.9 4.4 25 12l67.2 84H272zm256.2 1L428.2 68c-18.2-22.8-45.8-36-75-36H171.3c-39.3 0-74.6 23.9-89.1 60.3L40.6 196.4C16.8 205.8 0 228.9 0 256V368c0 17.7 14.3 32 32 32H65.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H385.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H608c17.7 0 32-14.3 32-32V320c0-65.2-48.8-119-111.8-127zM434.7 368a48 48 0 1 1 90.5 32 48 48 0 1 1 -90.5-32zM160 336a48 48 0 1 1 0 96 48 48 0 1 1 0-96z'/></svg>" type="image/svg+xml">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.25s ease;
        }

        /* Collapsed (icon-only) sidebar */
        .sidebar.collapsed {
            width: 72px;
            padding: 10px 6px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%); /* same purple gradient as expanded */
            border-right: 1px solid rgba(0,0,0,0.06);
        }

        .sidebar.collapsed .sidebar-brand h3,
        .sidebar.collapsed .sidebar-brand .brand-text,
        .sidebar.collapsed .sidebar-menu li a .label {
            display: none;
        }

        /* Brand icon adjustments when collapsed (white circular avatar) */
        .sidebar.collapsed .sidebar-brand {
            padding: 12px 6px 20px;
            text-align: center;
            border-bottom: none;
        }

        .sidebar.collapsed .sidebar-brand .icon {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        }

        .sidebar.collapsed .sidebar-brand .icon i {
            font-size: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-toggle {
            display: flex;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .menu-toggle:hover {
            opacity: 0.9;
        }

        .sidebar-brand {
            padding: 0 20px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-brand h3 {
            font-size: 20px;
            font-weight: 700;
            margin-top: 10px;
        }

        .sidebar-brand .icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .sidebar-brand .icon i {
            font-size: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }


        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            border-radius: 8px;
            margin: 6px 8px;
        }

        .sidebar-menu li a .label {
            display: inline-block;
            white-space: nowrap;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }

        .sidebar-menu li a i {
            width: 28px;
            text-align: center;
            font-size: 18px;
        }

        .icon-indicator {
            display: none;
        }

        /* collapsed look: center icons vertically and make active item green */
        .sidebar.collapsed .sidebar-menu li a {
            justify-content: center;
            padding: 10px 6px;
            margin: 6px 4px;
            background: transparent;
            border-left: none;
            color: #e6eef8;
        }

        .sidebar.collapsed .sidebar-menu li a i {
            color: #e6eef8;
            font-size: 18px;
        }

        .sidebar.collapsed .sidebar-menu li a:hover {
            background: rgba(255,255,255,0.03);
        }

        .sidebar.collapsed .sidebar-menu li a.active {
            background: transparent;
        }

        .sidebar .sidebar-menu li a.active,
        .sidebar .sidebar-menu li a:hover {
            background: rgba(255,255,255,0.06);
            border-left-color: rgba(255,255,255,0.08);
        }

        .sidebar .sidebar-menu li a.active .icon-indicator,
        .sidebar.collapsed .sidebar-menu li a.active .icon-indicator {
            display: inline-block;
            width: 8px;
            height: 36px;
            background: #10b981; /* green */
            border-radius: 6px;
            margin-right: 8px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* When sidebar is collapsed, reduce main content left margin */
        .main-content.collapsed {
            margin-left: 72px;
        }

        /* Header */
        .header {
            background: white;
            height: var(--header-height);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left h4 {
            margin: 0;
            color: #1f2937;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-badge {
            position: relative;
            cursor: pointer;
        }

        .notification-badge .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 10px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .stat-card.primary .icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.success .icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stat-card.warning .icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-card.danger .icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            margin: 10px 0 5px;
        }

        .stat-card p {
            color: #6b7280;
            margin: 0;
        }

        /* Alert Badges */
        .badge.safe {
            background: #10b981;
        }

        .badge.due-soon {
            background: #f59e0b;
        }

        .badge.overdue {
            background: #ef4444;
        }

        /* Cards */
        .card-custom {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-custom .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            font-weight: 600;
        }

        .card-custom .card-body {
            padding: 20px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 0 15px;
            }

            .content-area {
                padding: 15px;
            }

            .stat-card {
                margin-bottom: 15px;
            }

            .stat-card h3 {
                font-size: 24px;
            }

            .stat-card .icon {
                width: 50px;
                height: 50px;
                font-size: 22px;
            }

            .user-menu > div:last-child {
                display: none;
            }

            .header-right {
                gap: 10px;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .card-custom .card-header,
            .card-custom .card-body {
                padding: 15px;
            }

            h2 {
                font-size: 24px;
            }

            h4 {
                font-size: 18px;
            }

            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .btn-group .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .sidebar-brand h3 {
                font-size: 18px;
            }

            .sidebar-menu li a {
                padding: 12px 15px;
                font-size: 14px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-card h3 {
                font-size: 20px;
            }

            .stat-card p {
                font-size: 13px;
            }

            .notification-badge {
                display: none;
            }

            .menu-toggle {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }

            .theme-toggle {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }

        /* Mobile sidebar overlay */
        @media (max-width: 768px) {
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .sidebar {
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            }
        }
    </style>
    @livewireStyles
    @stack('styles')
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="icon">
                <i class="fas fa-car"></i>
            </div>
            <h3><span class="brand-text">Vehicle Tracker</span></h3>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="icon-indicator"></span>
                    <i class="fas fa-home"></i>
                    <span class="label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                    <span class="icon-indicator"></span>
                    <i class="fas fa-car"></i>
                    <span class="label">My Vehicles</span>
                </a>
            </li>
            <li>
                <a href="{{ route('maintenance.index') }}" class="{{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                    <span class="icon-indicator"></span>
                    <i class="fas fa-wrench"></i>
                    <span class="label">Maintenance</span>
                </a>
            </li>
            <li>
                <a href="{{ route('renewals.index') }}" class="{{ request()->routeIs('renewals.*') ? 'active' : '' }}">
                    <span class="icon-indicator"></span>
                    <i class="fas fa-bell"></i>
                    <span class="label">Renewals & Alerts</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <span class="icon-indicator"></span>
                    <i class="fas fa-user-cog"></i>
                    <span class="label">Profile & Settings</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <span class="icon-indicator"></span>
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="label">Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="header-right">
                <div class="notification-badge">
                    <i class="fas fa-bell fa-lg text-muted"></i>
                    @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                        <span class="badge bg-danger">{{ auth()->user()->notifications()->where('is_read', false)->count() }}</span>
                    @endif
                </div>
                <div class="user-menu">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 14px;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.querySelector('.main-content');

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            } else {
                // collapse to icon-only instead of fully hiding
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');

                // persist state so navigation (pagination / links) keeps it
                try {
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed ? '1' : '0');
                } catch (e) {
                    // ignore storage errors
                }
            }
        }

        // Apply persisted sidebar state on load
        function applySidebarState() {
            try {
                const val = localStorage.getItem('sidebarCollapsed');
                if (val === '1' && window.innerWidth > 768) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('collapsed');
                }
            } catch (e) {
                // ignore
            }
        }

        // Run on initial load
        applySidebarState();

        menuToggle.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking overlay on mobile
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });

        // Close sidebar when clicking a link on mobile
        const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });

        // SweetAlert2: intercept forms with class `swal-confirm`
        document.addEventListener('click', function(e) {
            const target = e.target.closest('[data-swal-confirm], .swal-confirm');
            if (!target) return;

            // If element is a button inside a form or an anchor that submits a form
            let form = null;
            if (target.matches('form')) form = target;
            else form = target.closest('form');

            const message = target.getAttribute('data-swal-message') || target.getAttribute('data-message') || 'Are you sure?';
            const title = target.getAttribute('data-swal-title') || 'Please confirm';

            if (form) {
                e.preventDefault();
                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // submit the form
                        form.submit();
                    }
                });
            }
        });

        // Display Laravel session flashes using SweetAlert2 (success / error / info)
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const flash = {
                    success: {!! json_encode(session('success')) !!},
                    error: {!! json_encode(session('error')) !!},
                    info: {!! json_encode(session('info')) !!},
                    message: {!! json_encode(session('message')) !!},
                    status: {!! json_encode(session('status')) !!}
                };

                if (flash.success) {
                    Swal.fire({icon: 'success', title: flash.success, timer: 2500, showConfirmButton: false});
                } else if (flash.error) {
                    Swal.fire({icon: 'error', title: flash.error, timer: 3500, showConfirmButton: false});
                } else if (flash.info) {
                    Swal.fire({icon: 'info', title: flash.info, timer: 2500, showConfirmButton: false});
                }
            } catch (e) {
                // ignore JSON/render issues
            }
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
