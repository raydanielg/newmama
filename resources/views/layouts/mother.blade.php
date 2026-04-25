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
            --primary: #ec4899;
            --primary-dark: #db2777;
            --primary-light: #fce7f3;
            --secondary: #3b82f6;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1f2937;
            --gray: #6b7280;
            --light: #f3f4f6;
            --white: #ffffff;
            --sidebar-width: 280px;
            --header-height: 70px;
            --radius: 16px;
            --radius-sm: 8px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #fef7ff 0%, #fdf2f8 50%, #fff1f2 100%);
            min-height: 100vh;
            color: var(--dark);
            line-height: 1.6;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--white) 0%, #fdf2f8 100%);
            border-right: 1px solid rgba(236, 72, 153, 0.1);
            padding: 24px 20px;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            text-align: center;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            margin-bottom: 24px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            color: var(--primary-dark);
        }

        .sidebar-logo i {
            font-size: 32px;
        }

        .sidebar-logo span {
            font-size: 24px;
            font-weight: 700;
        }

        .user-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: var(--radius);
            padding: 20px;
            color: var(--white);
            margin-bottom: 24px;
            box-shadow: var(--shadow-lg);
        }

        .user-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .user-mk {
            font-size: 12px;
            opacity: 0.9;
            font-family: monospace;
        }

        .user-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }

        .pregnancy-progress {
            margin-top: 16px;
        }

        .progress-bar {
            height: 8px;
            background: rgba(255,255,255,0.3);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--white);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 12px;
            margin-top: 6px;
            opacity: 0.9;
        }

        /* Navigation */
        .nav-section {
            margin-bottom: 8px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray);
            padding: 16px 12px 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--dark);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .nav-item:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .nav-item.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .nav-item i {
            width: 24px;
            text-align: center;
            font-size: 16px;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: var(--white);
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
        }

        .nav-item.active .nav-badge {
            background: var(--white);
            color: var(--primary-dark);
        }

        /* Emergency Button */
        .emergency-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-top: 16px;
            box-shadow: var(--shadow);
        }

        .emergency-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .emergency-btn i {
            font-size: 18px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 24px 32px;
            min-height: 100vh;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            color: var(--primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--dark);
            border: 1px solid rgba(0,0,0,0.1);
        }

        .btn-secondary:hover {
            background: var(--light);
        }

        /* Cards */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(236, 72, 153, 0.08);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary);
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }

        /* KPI Cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .kpi-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(236, 72, 153, 0.08);
            transition: all 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .kpi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .kpi-icon.pink { background: var(--primary-light); color: var(--primary); }
        .kpi-icon.blue { background: #dbeafe; color: var(--secondary); }
        .kpi-icon.green { background: #dcfce7; color: var(--success); }
        .kpi-icon.orange { background: #ffedd5; color: var(--warning); }
        .kpi-icon.red { background: #fee2e2; color: var(--danger); }

        .kpi-trend {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .kpi-trend.up { background: #dcfce7; color: var(--success); }
        .kpi-trend.down { background: #fee2e2; color: var(--danger); }

        .kpi-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .kpi-label {
            font-size: 14px;
            color: var(--gray);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: #ffedd5;
            color: #92400e;
            border-left: 4px solid var(--warning);
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            font-size: 14px;
        }

        th {
            font-weight: 600;
            color: var(--gray);
            border-bottom: 2px solid rgba(0,0,0,0.05);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        td {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        tr:hover td {
            background: var(--light);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-pink { background: var(--primary-light); color: var(--primary-dark); }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-orange { background: #ffedd5; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-gray { background: #f3f4f6; color: #6b7280; }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: var(--radius-sm);
            font-size: 14px;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary);
            color: var(--white);
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 100%;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 80px 16px 24px;
            }

            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .grid-2, .grid-3, .grid-4 {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('mother.dashboard') }}" class="sidebar-logo">
                <i class="fas fa-heart-pulse"></i>
                <span>MamaCare</span>
            </a>
        </div>

        @if(isset($mother))
        <div class="user-card">
            <div class="user-name">{{ $mother->full_name }}</div>
            <div class="user-mk">{{ $mother->mk_number }}</div>
            <div class="user-status">
                <i class="fas fa-circle" style="font-size: 8px;"></i>
                {{ $mother->status_label }}
            </div>
            @if($mother->status === 'pregnant' && $mother->weeks_pregnant)
            <div class="pregnancy-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ min(100, ($mother->weeks_pregnant / 40) * 100) }}%"></div>
                </div>
                <div class="progress-text">Week {{ $mother->weeks_pregnant }} of 40</div>
            </div>
            @endif
        </div>
        @endif

        <nav>
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="{{ route('mother.dashboard') }}" class="nav-item {{ request()->routeIs('mother.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('mother.profile') }}" class="nav-item {{ request()->routeIs('mother.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Health Tracking</div>
                <a href="{{ route('mother.appointments') }}" class="nav-item {{ request()->routeIs('mother.appointments*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                    @if(isset($mother) && $mother->upcoming_appointments->count() > 0)
                        <span class="nav-badge">{{ $mother->upcoming_appointments->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('mother.health-data') }}" class="nav-item {{ request()->routeIs('mother.health-data*') ? 'active' : '' }}">
                    <i class="fas fa-heartbeat"></i>
                    <span>Health Data</span>
                </a>
                <a href="{{ route('mother.daily-log') }}" class="nav-item {{ request()->routeIs('mother.daily-log*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Daily Log</span>
                </a>
                <a href="{{ route('mother.checklist') }}" class="nav-item {{ request()->routeIs('mother.checklist*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Checklist</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Support</div>
                <a href="{{ route('mother.alerts') }}" class="nav-item {{ request()->routeIs('mother.alerts*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Alerts</span>
                    @if(isset($mother) && $mother->unread_alerts_count > 0)
                        <span class="nav-badge">{{ $mother->unread_alerts_count }}</span>
                    @endif
                </a>
                <a href="{{ route('mother.education') }}" class="nav-item {{ request()->routeIs('mother.education*') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span>Education</span>
                </a>
            </div>
        </nav>

        <a href="{{ route('mother.emergency') }}" class="emergency-btn">
            <i class="fas fa-phone-alt"></i>
            Emergency Help
        </a>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success animate-fade-in">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error animate-fade-in">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error animate-fade-in">
                <i class="fas fa-exclamation-triangle"></i>
                <ul style="margin: 0; padding-left: 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
            const toggle = document.querySelector('.menu-toggle');
            if (window.innerWidth <= 768 && 
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
