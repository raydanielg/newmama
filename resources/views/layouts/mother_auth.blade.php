<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mamacare AI - Mama Access')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violetbrand: {
                            50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd',
                            400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9',
                            800: '#5b21b6', 900: '#4c1d95'
                        },
                        gold: {
                            50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d',
                            400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309',
                            800: '#92400e', 900: '#78350f'
                        }
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes simpleFadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes toastIn { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        @keyframes toastOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100%); } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        @keyframes spin { to { transform: rotate(360deg); } }

        .toast-in { animation: toastIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .toast-out { animation: toastOut 0.3s ease-in both; }
        .page-transition { animation: simpleFadeIn 0.4s ease-out both; }
        .logo-float { animation: float 3s ease-in-out infinite; }

        .loading-spinner {
            display: none;
            width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s linear infinite;
        }
        .btn-loading .loading-spinner { display: inline-block; }
        .btn-loading .btn-text, .btn-loading .btn-icon { display: none; }

        .form-section { display: none; }
        .form-section.active { display: block; }

        .ajax-loader {
            position: fixed; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #4c1d95, #fbbf24, #4c1d95);
            background-size: 200% 100%;
            animation: ajaxProgress 1s linear infinite;
            z-index: 9999; display: none;
        }
        @keyframes ajaxProgress { 0% { background-position: 100% 0; } 100% { background-position: -100% 0; } }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">

    {{-- Auth Background --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('flat-abstract-background-pattern-vector_822782-866.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-violet-900/90 via-violet-800/85 to-violet-700/80"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 24px 24px;"></div>
    </div>

    {{-- AJAX Progress Bar --}}
    <div id="ajaxLoader" class="ajax-loader"></div>

    {{-- Toast Container --}}
    <div id="toastContainer" class="fixed top-5 right-5 z-[60] flex flex-col gap-3 w-full max-w-sm pointer-events-none"></div>

    <main id="authMain" class="relative z-10 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Toast System --}}
    <script>
    (function() {
        const container = document.getElementById('toastContainer');

        function showToast(type, title, message) {
            const toast = document.createElement('div');
            toast.className = 'toast-in pointer-events-auto flex items-start gap-3 p-4 rounded-xl shadow-lg border backdrop-blur-sm';

            let iconSvg, bgClass, borderClass;
            if (type === 'success') {
                iconSvg = '<svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                bgClass = 'bg-emerald-50/95'; borderClass = 'border-emerald-200';
            } else if (type === 'error') {
                iconSvg = '<svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                bgClass = 'bg-red-50/95'; borderClass = 'border-red-200';
            } else if (type === 'warning') {
                iconSvg = '<svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                bgClass = 'bg-amber-50/95'; borderClass = 'border-amber-200';
            } else {
                iconSvg = '<svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                bgClass = 'bg-blue-50/95'; borderClass = 'border-blue-200';
            }

            toast.classList.add(...bgClass.split(' '), ...borderClass.split(' '));
            toast.innerHTML = iconSvg +
                '<div class="flex-1 min-w-0">' +
                    '<p class="text-sm font-semibold text-gray-800">' + title + '</p>' +
                    (message ? '<p class="text-sm text-gray-500 mt-0.5">' + message + '</p>' : '') +
                '</div>' +
                '<button onclick="this.parentElement.classList.add(\'toast-out\'); setTimeout(()=>this.parentElement.remove(), 300)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>';

            container.appendChild(toast);
            setTimeout(() => { toast.classList.add('toast-out'); setTimeout(() => toast.remove(), 300); }, 5000);
        }

        window.showToast = showToast;

        @if(session('success'))
            showToast('success', 'Success', '{{ addslashes(session('success')) }}');
        @endif
        @if(session('error'))
            showToast('error', 'Error', '{{ addslashes(session('error')) }}');
        @endif
        @if(session('warning'))
            showToast('warning', 'Warning', '{{ addslashes(session('warning')) }}');
        @endif
        @if(session('info'))
            showToast('info', 'Info', '{{ addslashes(session('info')) }}');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('error', 'Validation Error', '{{ addslashes($error) }}');
            @endforeach
        @endif
    })();
    </script>

    @stack('scripts')
</body>
</html>
