<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Vehicle Tracker') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 512'><path fill='%231e40af' d='M171.3 96H224v96H111.3l30.4-75.9C146.5 104 158.2 96 171.3 96zM272 192V96h81.2c9.7 0 18.9 4.4 25 12l67.2 84H272zm256.2 1L428.2 68c-18.2-22.8-45.8-36-75-36H171.3c-39.3 0-74.6 23.9-89.1 60.3L40.6 196.4C16.8 205.8 0 228.9 0 256V368c0 17.7 14.3 32 32 32H65.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H385.3c7.6 45.4 47.1 80 94.7 80s87.1-34.6 94.7-80H608c17.7 0 32-14.3 32-32V320c0-65.2-48.8-119-111.8-127zM434.7 368a48 48 0 1 1 90.5 32 48 48 0 1 1 -90.5-32zM160 336a48 48 0 1 1 0 96 48 48 0 1 1 0-96z'/></svg>" type="image/svg+xml">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            /* Core Brand Colors */
            --brand-blue: #134e7f;
            --brand-green: #10b981;
            
            /* Theme Mappings */
            --sidebar-bg: var(--brand-blue);
            --primary-color: var(--brand-blue);
            --secondary-color: #64748b;
            
            /* Status Colors */
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f1f5f9;
            color: #334155;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg); /* Solid Blue, no gradient */
            color: white;
            padding: 20px 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.25s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.05);
        }

        /* Collapsed (icon-only) sidebar */
        .sidebar.collapsed {
            width: 72px;
            padding: 10px 6px;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar.collapsed .sidebar-brand h3,
        .sidebar.collapsed .sidebar-brand .brand-text,
        .sidebar.collapsed .sidebar-menu li a .label {
            display: none;
        }

        /* Brand icon adjustments when collapsed */
        .sidebar.collapsed .sidebar-brand {
            padding: 12px 6px 20px;
            text-align: center;
            border-bottom: none;
        }

        .sidebar.collapsed .sidebar-brand .icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            box-shadow: none;
        }

        .sidebar.collapsed .sidebar-brand .icon i {
            font-size: 20px;
            color: white;
            background: none;
            -webkit-text-fill-color: white;
        }

        .menu-toggle {
            display: flex;
            background: transparent;
            border: 1px solid #e2e8f0;
            color: #64748b;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
        }

        .menu-toggle:hover {
            background: #f8fafc;
            color: var(--brand-blue);
        }

        .sidebar-brand {
            padding: 0 24px 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-brand h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sidebar-brand .icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
            flex-shrink: 0;
        }

        .sidebar-brand .icon i {
            font-size: 16px;
            color: white;
            background: none;
            -webkit-text-fill-color: white;
        }


        .sidebar-menu {
            list-style: none;
            padding: 0 12px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.2s;
            border-left: none;
            border-radius: 8px;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar-menu li a .label {
            display: inline-block;
            white-space: nowrap;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: none;
        }

        .sidebar-menu li a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .icon-indicator {
            display: none;
        }

        /* collapsed look */
        .sidebar.collapsed .sidebar-menu li a {
            justify-content: center;
            padding: 12px 0;
            margin: 4px 6px;
        }

        .sidebar.collapsed .sidebar-menu li a i {
            font-size: 18px;
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

        .main-content.collapsed {
            margin-left: 72px;
        }

        /* Header */
        .header {
            background: white;
            height: var(--header-height);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: none;
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
            color: #1e293b;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .notification-badge {
            position: relative;
            cursor: pointer;
            color: #64748b;
            transition: color 0.2s;
        }

        .notification-badge:hover {
            color: var(--brand-blue);
        }

        .notification-badge .badge {
            position: absolute;
            top: -6px;
            right: -6px;
            font-size: 10px;
            background: var(--brand-green);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: #f1f5f9;
            color: var(--brand-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Stats Cards - Cleaner Look */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); /* Subtle shadow */
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }

        .stat-card.primary .icon {
            background: #eff6ff;
            color: var(--brand-blue);
        }

        .stat-card.success .icon {
            background: #ecfdf5;
            color: var(--brand-green);
        }

        .stat-card.warning .icon {
            background: #fffbeb;
            color: var(--warning-color);
        }

        .stat-card.danger .icon {
            background: #fef2f2;
            color: var(--danger-color);
        }

        .stat-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin: 8px 0 4px;
            color: #0f172a;
        }

        .stat-card p {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            margin: 0;
        }

        /* Alert Badges */
        .badge {
            font-weight: 500;
            padding: 0.5em 0.8em;
        }

        .badge.safe {
            background: #dcfce7;
            color: #166534;
        }

        .badge.due-soon {
            background: #fef3c7;
            color: #92400e;
        }

        .badge.overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Cards - Clean White Headers */
        .card-custom {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-custom .card-header {
            background: white; /* Clean white header */
            color: #0f172a; /* Dark text */
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 24px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        
        .card-custom .card-header i {
            color: var(--brand-blue); /* Icon color matches brand */
        }

        .card-custom .card-body {
            padding: 24px;
        }

        /* Buttons - Green Primary Action */
        .btn-primary {
            background: var(--brand-green);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            color: white;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #059669; /* Darker green on hover */
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }

        .btn-outline-primary {
            color: var(--brand-blue);
            border-color: var(--brand-blue);
            border-radius: 8px;
        }

        .btn-outline-primary:hover {
            background: var(--brand-blue);
            color: white;
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
