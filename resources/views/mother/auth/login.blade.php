<!DOCTYPE html>
<html lang="sw" class="h-full bg-[#fdfbf7]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamacare AI - Mama Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('{{ asset('flat-abstract-background-pattern-vector_822782-866.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top right, rgba(238, 242, 255, 0.4), transparent),
                        radial-gradient(circle at bottom left, rgba(253, 251, 247, 0.4), transparent);
            z-index: -1;
        }
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .btn-loading .loading-spinner {
            display: block;
        }
        .btn-loading .btn-text, .btn-loading i {
            display: none;
        }
        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-input:focus {
            transform: translateY(-1px);
        }
        .m-logo-animated {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6">
    <div class="w-full max-w-[420px] animate__animated animate__fadeIn">
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(30,64,175,0.05)] border border-white p-8 md:p-12 relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
            
            <!-- Logo & Header -->
            <div class="text-center mb-10 relative">
                <a href="{{ url('/') }}" class="inline-flex justify-center mb-6 m-logo-animated">
                    <img src="{{ asset('logo.svg') }}" alt="Mamacare AI" class="h-14 w-auto">
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Welcome Mama</h1>
                <p class="text-slate-500 text-sm leading-relaxed">Sign in to continue your motherhood journey with Mamacare AI.</p>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate__animated animate__headShake">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-emerald-600 text-xs"></i>
                    </div>
                    <p class="text-sm font-semibold text-emerald-700">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-center gap-3 animate__animated animate__shakeX">
                    <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-rose-600 text-xs"></i>
                    </div>
                    <p class="text-sm font-semibold text-rose-700">{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('mother.login') }}" method="POST" class="space-y-6" onsubmit="return handleLoginSubmit(this)">
                @csrf

                <!-- Phone Number -->
                <div class="space-y-2">
                    <label class="block text-[13px] font-extrabold text-slate-800 ml-1">Phone Number</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-phone-alt text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                        </div>
                        <input type="tel" name="login" 
                            class="form-input block w-full pl-11 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white transition-all text-sm font-medium" 
                            placeholder="e.g. 07XX XXX XXX" 
                            value="{{ old('login') }}" 
                            required>
                    </div>
                    @error('login')
                        <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <label class="block text-[13px] font-extrabold text-slate-800">Password</label>
                        <a href="{{ route('mother.forgot-password') }}" class="text-[11px] font-black text-indigo-600 hover:text-indigo-700 transition-colors uppercase tracking-wider">Forgot?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-shield-heart text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="form-input block w-full pl-11 pr-12 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white transition-all text-sm font-medium" 
                            placeholder="••••••••" 
                            required>
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-indigo-600 transition-colors">
                            <i class="fas fa-eye text-sm" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center ml-1">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded-lg border-slate-200 text-indigo-600 focus:ring-indigo-500/20 cursor-pointer transition-all">
                    <label for="remember_me" class="ml-2.5 block text-xs font-bold text-slate-500 cursor-pointer">Stay logged in</label>
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-200 active:scale-[0.98] flex items-center justify-center gap-3 group relative overflow-hidden">
                    <span class="btn-text">Sign In</span>
                    <i class="fas fa-chevron-right text-xs transition-transform group-hover:translate-x-1"></i>
                    <div class="loading-spinner"></div>
                </button>
            </form>

            <div class="mt-10">
                <div class="relative flex items-center justify-center">
                    <div class="w-full border-t border-slate-100"></div>
                    <span class="absolute bg-white px-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Or login with</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-8">
                    <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 px-4 py-3.5 bg-white border border-slate-100 rounded-2xl hover:bg-slate-50 transition-all font-bold text-slate-700 text-xs shadow-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span>Google</span>
                    </a>
                    <a href="{{ route('auth.apple') }}" class="flex items-center justify-center gap-3 px-4 py-3.5 bg-slate-900 border border-slate-900 rounded-2xl hover:bg-black transition-all font-bold text-white text-xs shadow-sm">
                        <i class="fab fa-apple text-sm"></i>
                        <span>Apple</span>
                    </a>
                </div>
            </div>

            <p class="mt-10 text-center text-[13px] font-medium text-slate-500">
                New to Mamacare? 
                <a href="{{ route('mother.register') }}" class="text-indigo-600 hover:text-indigo-700 font-black transition-colors underline underline-offset-4 decoration-indigo-200 hover:decoration-indigo-500">Create account</a>
            </p>
        </div>
    </div>

    <script>
        function handleLoginSubmit(form) {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('btn-loading');
            btn.disabled = true;
            return true;
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
