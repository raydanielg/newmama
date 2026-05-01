<!DOCTYPE html>
<html lang="sw" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamacare AI - Umesahau Nenosiri?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
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
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">
    <div class="w-full max-w-[400px] animate__animated animate__fadeInUp">
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 md:p-10">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center rotate-45 transform transition-transform hover:rotate-90 animate__animated animate__bounceIn animate__delay-1s">
                        <i class="fas fa-key text-white text-2xl -rotate-45"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Forgot Password?</h1>
                <p class="text-slate-500 mt-2 text-sm font-medium">Reset your Mamacare AI password</p>
            </div>

            <!-- Alerts -->
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-center gap-3 animate__animated animate__shakeX">
                    <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-rose-600 text-sm"></i>
                    </div>
                    <p class="text-sm font-medium text-rose-700">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate__animated animate__headShake">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-emerald-600 text-sm"></i>
                    </div>
                    <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Info Box -->
            <div class="mb-8 p-4 bg-orange-50 border border-orange-100 rounded-2xl animate__animated animate__fadeIn animate__delay-1s">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fab fa-whatsapp text-emerald-500"></i>
                    <span class="text-xs font-bold text-orange-800 uppercase tracking-wider">Nenosiri Mpya</span>
                </div>
                <p class="text-[13px] leading-relaxed text-orange-700/80 font-medium">
                    Tutakutumia nenosiri mpya au maelekezo ya kurejesha kupitia WhatsApp yako. Hakikisha nambari yako iko sawa.
                </p>
            </div>

            <form action="{{ route('mother.forgot-password') }}" method="POST" class="space-y-5" onsubmit="return handleForgotSubmit(this)">
                @csrf

                <!-- Phone or Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone or Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                        </div>
                        <input type="text" name="login" 
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-[15px]" 
                            placeholder="07XX XXX XXX or email" 
                            value="{{ old('login') }}" 
                            required>
                    </div>
                    @error('login')
                        <p class="mt-1.5 text-xs font-medium text-rose-500 animate__animated animate__fadeIn">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 group">
                    <span class="btn-text">Send Reset Link</span>
                    <i class="fas fa-paper-plane text-sm transition-transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                    <div class="loading-spinner"></div>
                </button>
            </form>

            <script>
                function handleForgotSubmit(form) {
                    const btn = document.getElementById('submitBtn');
                    btn.classList.add('btn-loading');
                    btn.disabled = true;
                    return true;
                }
            </script>

            <!-- Social Login -->
            <div class="mt-8">
                <div class="relative mb-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-100"></div>
                    </div>
                    <div class="relative flex justify-center text-sm font-medium">
                        <span class="bg-white px-4 text-slate-400">Or continue with</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 px-4 py-3 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all font-semibold text-slate-700 text-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span>Google</span>
                    </a>
                    <a href="{{ route('auth.apple') }}" class="flex items-center justify-center gap-3 px-4 py-3 bg-slate-900 border border-slate-900 rounded-xl hover:bg-black transition-all font-semibold text-white text-sm">
                        <i class="fab fa-apple text-lg"></i>
                        <span>Apple</span>
                    </a>
                </div>
            </div>

            <p class="mt-8 text-center text-sm font-medium text-slate-500">
                Remember your password? 
                <a href="{{ route('mother.login') }}" class="text-emerald-600 hover:text-emerald-700 font-bold transition-colors">Log in</a>
            </p>
        </div>
    </div>
</body>
</html>
