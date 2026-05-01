<!DOCTYPE html>
<html lang="sw" class="h-full bg-[#fdfbf7]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamacare AI - Mama Access</title>
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
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at top right, rgba(238, 242, 255, 0.4), transparent),
                        radial-gradient(circle at bottom left, rgba(253, 251, 247, 0.4), transparent);
            z-index: -1;
        }
        .auth-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
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
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-loading .loading-spinner { display: block; }
        .btn-loading .btn-text, .btn-loading i { display: none; }
        
        .form-input {
            transition: all 0.3s ease;
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
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-white/50 p-8 md:p-10 relative overflow-hidden auth-card">
            <!-- Decorative Background Element -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
            
            <!-- Logo -->
            <div class="text-center mb-8 relative">
                <a href="{{ url('/') }}" class="inline-flex justify-center mb-4 m-logo-animated">
                    <img src="{{ asset('logo.svg') }}" alt="Mamacare AI" class="h-12 w-auto">
                </a>
            </div>

            <!-- Alerts -->
            <div id="alert-container">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate__animated animate__headShake">
                        <i class="fas fa-check text-emerald-600 text-xs"></i>
                        <p class="text-sm font-semibold text-emerald-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-center gap-3 animate__animated animate__shakeX">
                        <i class="fas fa-exclamation text-rose-600 text-xs"></i>
                        <p class="text-sm font-semibold text-rose-700">{{ session('error') }}</p>
                    </div>
                @endif
            </div>

            <!-- LOGIN SECTION -->
            <div id="login-section" class="form-section active animate__animated">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Welcome Mama</h1>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">Sign in to continue your motherhood journey.</p>
                </div>

                <form action="{{ route('mother.login') }}" method="POST" class="space-y-5" onsubmit="return handleSubmit(this)">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="block text-[12px] font-extrabold text-slate-800 ml-1">Phone Number</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-phone-alt text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                            </div>
                            <input type="tel" name="login" class="form-input block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white text-sm font-medium" placeholder="e.g. 07XX XXX XXX" value="{{ old('login') }}" required>
                        </div>
                        @error('login') <p class="mt-1 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between ml-1">
                            <label class="block text-[12px] font-extrabold text-slate-800">Password</label>
                            <button type="button" onclick="showSection('forgot-section')" class="text-[10px] font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-wider">Forgot?</button>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-shield-heart text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                            </div>
                            <input type="password" name="password" id="login-password" class="form-input block w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white text-sm font-medium" placeholder="••••••••" required>
                            <button type="button" onclick="togglePassword('login-password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center ml-1">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded-lg border-slate-200 text-indigo-600 focus:ring-indigo-500/20 cursor-pointer">
                        <label for="remember_me" class="ml-2.5 block text-xs font-bold text-slate-500 cursor-pointer">Stay logged in</label>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 active:scale-[0.98] flex items-center justify-center gap-3 group">
                        <span class="btn-text">Sign In</span>
                        <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        <div class="loading-spinner"></div>
                    </button>
                </form>

                <p class="mt-8 text-center text-[12px] font-medium text-slate-500">
                    New to Mamacare? 
                    <button onclick="showSection('register-section')" class="text-indigo-600 hover:text-indigo-700 font-black transition-colors underline underline-offset-4 decoration-indigo-200">Create account</button>
                </p>
            </div>

            <!-- REGISTER SECTION -->
            <div id="register-section" class="form-section animate__animated">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Join Us</h1>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">Start your personalized motherhood journey today.</p>
                </div>

                <form action="{{ route('mother.register') }}" method="POST" class="space-y-5" onsubmit="return handleSubmit(this)">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="block text-[12px] font-extrabold text-slate-800 ml-1">Full Name</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                            </div>
                            <input type="text" name="name" class="form-input block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white text-sm font-medium" placeholder="Enter your full name" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[12px] font-extrabold text-slate-800 ml-1">Phone or Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                            </div>
                            <input type="text" name="login" class="form-input block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white text-sm font-medium" placeholder="07XX XXX XXX or email" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[12px] font-extrabold text-slate-800 ml-1">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-shield-heart text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                            </div>
                            <input type="password" name="password" id="reg-password" class="form-input block w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white text-sm font-medium" placeholder="Create a password" required>
                            <button type="button" onclick="togglePassword('reg-password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 active:scale-[0.98] flex items-center justify-center gap-3 group mt-2">
                        <span class="btn-text">Create Account</span>
                        <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        <div class="loading-spinner"></div>
                    </button>
                </form>

                <p class="mt-8 text-center text-[12px] font-medium text-slate-500">
                    Already have an account? 
                    <button onclick="showSection('login-section')" class="text-indigo-600 hover:text-indigo-700 font-black transition-colors underline underline-offset-4 decoration-indigo-200">Sign in</button>
                </p>
            </div>

            <!-- FORGOT PASSWORD SECTION -->
            <div id="forgot-section" class="form-section animate__animated">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Reset Password</h1>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">We'll help you get back into your account.</p>
                </div>

                <div class="mb-6 p-4 bg-orange-50 border border-orange-100 rounded-2xl">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fab fa-whatsapp text-emerald-500 text-sm"></i>
                        <span class="text-[10px] font-black text-orange-800 uppercase tracking-widest">Nenosiri Mpya</span>
                    </div>
                    <p class="text-[11px] leading-relaxed text-orange-700/80 font-semibold">
                        Tutakutumia nenosiri mpya au maelekezo kupitia WhatsApp yako.
                    </p>
                </div>

                <form action="{{ route('mother.forgot-password') }}" method="POST" class="space-y-6" onsubmit="return handleSubmit(this)">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="block text-[12px] font-extrabold text-slate-800 ml-1">Phone or Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-slate-300 group-focus-within:text-indigo-600 transition-colors text-sm"></i>
                            </div>
                            <input type="text" name="login" class="form-input block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 focus:bg-white text-sm font-medium" placeholder="07XX XXX XXX or email" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 active:scale-[0.98] flex items-center justify-center gap-3 group">
                        <span class="btn-text">Send Reset Link</span>
                        <i class="fas fa-paper-plane text-xs transition-transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        <div class="loading-spinner"></div>
                    </button>
                </form>

                <p class="mt-8 text-center text-[12px] font-medium text-slate-500">
                    Remembered password? 
                    <button onclick="showSection('login-section')" class="text-indigo-600 hover:text-indigo-700 font-black transition-colors underline underline-offset-4 decoration-indigo-200">Sign in</button>
                </p>
            </div>

        </div>
    </div>

    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.form-section');
            const activeSection = document.getElementById(sectionId);
            
            sections.forEach(s => {
                s.classList.remove('active', 'animate__fadeInRight', 'animate__fadeInLeft');
                s.style.display = 'none';
            });
            
            activeSection.style.display = 'block';
            activeSection.classList.add('active', 'animate__fadeIn');
            
            // Clear alerts when switching
            const alertContainer = document.getElementById('alert-container');
            if (alertContainer) alertContainer.innerHTML = '';
        }

        function handleSubmit(form) {
            const btn = form.querySelector('button[type="submit"]');
            btn.classList.add('btn-loading');
            btn.disabled = true;
            return true;
        }

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const icon = event.currentTarget.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
