<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MamaCare - Mother Dashboard')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --secondary: #10b981;
            --success: #059669;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --gray: #64748b;
            --light: #f8fafc;
            --white: #ffffff;
            --sidebar-width: 280px;
            --header-height: 80px;
            --radius-xl: 24px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fdfbf7;
            background-image: url('{{ asset('flat-abstract-background-pattern-vector_822782-866.jpg') }}');
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--dark);
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.9) 0%, rgba(241, 245, 249, 0.9) 100%);
            z-index: -1;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--white);
            border-right: 1px solid #e2e8f0;
            padding: 32px 24px;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding-bottom: 32px;
            margin-bottom: 24px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--dark);
        }

        .sidebar-logo img {
            height: 40px;
            width: auto;
        }

        .sidebar-logo span {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .user-profile-mini {
            background: var(--light);
            border-radius: var(--radius-lg);
            padding: 16px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #e2e8f0;
        }

        .user-avatar-circle {
            width: 44px;
            height: 44px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }

        .user-info-text {
            flex: 1;
            min-width: 0;
        }

        .user-info-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info-id {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
        }

        /* Navigation */
        .nav-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray);
            margin: 24px 0 12px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            text-decoration: none;
            color: var(--slate-700);
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .nav-link i {
            width: 20px;
            font-size: 16px;
            color: var(--gray);
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-link:hover i {
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .nav-link.active i {
            color: var(--white);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .emergency-action {
            margin-top: 40px;
            padding: 20px;
            background: linear-gradient(135deg, #fff1f2 0%, #fee2e2 100%);
            border-radius: var(--radius-lg);
            border: 1px solid #fecaca;
            text-align: center;
        }

        .emergency-action h4 {
            font-size: 13px;
            font-weight: 800;
            color: #991b1b;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .emergency-btn-red {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--danger);
            color: white;
            padding: 12px;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
            transition: all 0.2s ease;
        }

        .emergency-btn-red:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
        }

        .top-navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .welcome-greeting h1 {
            font-size: 28px;
            font-weight: 900;
            color: var(--dark);
            letter-spacing: -1px;
        }

        .welcome-greeting p {
            font-size: 14px;
            color: var(--gray);
            font-weight: 500;
        }

        .top-nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .action-icon-btn {
            width: 44px;
            height: 44px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-700);
            text-decoration: none;
            position: relative;
            transition: all 0.2s ease;
        }

        .action-icon-btn:hover {
            background: var(--light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .notification-dot {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border: 2px solid white;
            border-radius: 50%;
        }

        /* Generic Components */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow-lg);
        }

        .btn-modern {
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-modern-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-modern-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; padding: 24px; }
            .menu-toggle-btn { display: flex !important; }
        }

        .menu-toggle-btn {
            display: none;
            width: 44px;
            height: 44px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-md);
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('mother.dashboard') }}" class="sidebar-logo">
                <img src="{{ asset('logo.svg') }}" alt="Logo">
                <span>MamaCare</span>
            </a>
        </div>

        @if(isset($mother))
        <div class="user-profile-mini">
            <div class="user-avatar-circle">
                {{ substr($mother->full_name, 0, 1) }}
            </div>
            <div class="user-info-text">
                <div class="user-info-name">{{ $mother->full_name }}</div>
                <div class="user-info-id">{{ $mother->mk_number }}</div>
            </div>
        </div>
        @endif

        <nav>
            <div class="nav-label">Core Portal</div>
            <a href="{{ route('mother.dashboard') }}" class="nav-link {{ request()->routeIs('mother.dashboard') ? 'active' : '' }}">
                <i class="fas fa-grid-2"></i>
                <span>Portal Home</span>
            </a>
            <a href="{{ route('mother.profile') }}" class="nav-link {{ request()->routeIs('mother.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i>
                <span>My Profile</span>
            </a>

            <div class="nav-label">Care Journey</div>
            <a href="{{ route('mother.appointments') }}" class="nav-link {{ request()->routeIs('mother.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-heart"></i>
                <span>Appointments</span>
                @if(isset($mother) && $mother->upcoming_appointments->count() > 0)
                    <span class="nav-badge">{{ $mother->upcoming_appointments->count() }}</span>
                @endif
            </a>
            <a href="{{ route('mother.health-data') }}" class="nav-link {{ request()->routeIs('mother.health-data*') ? 'active' : '' }}">
                <i class="fas fa-chart-medical"></i>
                <span>Health Monitoring</span>
            </a>
            <a href="{{ route('mother.daily-log') }}" class="nav-link {{ request()->routeIs('mother.daily-log*') ? 'active' : '' }}">
                <i class="fas fa-notes-medical"></i>
                <span>Daily Log</span>
            </a>
            <a href="{{ route('mother.checklist') }}" class="nav-link {{ request()->routeIs('mother.checklist*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check"></i>
                <span>Checklist</span>
            </a>

            <div class="nav-label">Support & Learning</div>
            <a href="{{ route('mother.alerts') }}" class="nav-link {{ request()->routeIs('mother.alerts*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Smart Alerts</span>
                @if(isset($mother) && $mother->unread_alerts_count > 0)
                    <span class="nav-badge">{{ $mother->unread_alerts_count }}</span>
                @endif
            </a>
            <a href="{{ route('mother.education') }}" class="nav-link {{ request()->routeIs('mother.education*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i>
                <span>Mama Academy</span>
            </a>
        </nav>

        <div class="emergency-action">
            <h4>Need Help?</h4>
            <a href="{{ route('mother.emergency') }}" class="emergency-btn-red">
                <i class="fas fa-phone-alt animate-pulse"></i>
                SOS Emergency
            </a>
        </div>
    </aside>

    <main class="main-wrapper">
        <header class="top-navbar">
            <div class="welcome-greeting">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button class="menu-toggle-btn" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1>MamaCare Portal</h1>
                        <p>{{ now()->format('l, jS F Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="top-nav-actions">
                <a href="{{ route('mother.alerts') }}" class="action-icon-btn">
                    <i class="fas fa-bell"></i>
                    @if(isset($mother) && $mother->unread_alerts_count > 0)
                        <span class="notification-dot"></span>
                    @endif
                </a>
                <a href="{{ route('mother.profile') }}" class="action-icon-btn">
                    <i class="fas fa-cog"></i>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-modern btn-modern-primary" style="background: var(--dark);">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate__animated animate__fadeInDown">
                <i class="fas fa-check-circle text-emerald-600"></i>
                <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-center gap-3 animate__animated animate__shakeX">
                <i class="fas fa-exclamation-circle text-rose-600"></i>
                <p class="text-sm font-bold text-rose-700">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.menu-toggle-btn');
            if (window.innerWidth <= 1024 && 
                sidebar.classList.contains('open') && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
