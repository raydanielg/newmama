@extends('layouts.mother_auth')

@section('title', 'Mamacare AI - Mama Access')

@section('content')
<div class="w-full max-w-md" style="animation: simpleFadeIn 0.4s ease-out both;">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

        {{-- Dynamic Header --}}
        <div id="auth-header" class="bg-gradient-to-br from-violet-600 to-violet-700 px-8 py-8 text-center">
            <div class="w-16 h-16 mx-auto bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 logo-float">
                <img src="{{ asset('logo.svg') }}" alt="Mamacare AI" class="w-10 h-10 object-contain">
            </div>
            <h2 id="auth-title" class="text-2xl font-extrabold text-white">Welcome Mama</h2>
            <p id="auth-subtitle" class="text-violet-100 text-sm mt-1">Sign in to continue your motherhood journey</p>
        </div>

        {{-- Form Body --}}
        <div class="p-8">

            {{-- LOGIN SECTION --}}
            <div id="login-section" class="form-section active">
                <form method="POST" action="{{ route('mother.login') }}" class="space-y-5" onsubmit="return handleSubmit(this)">
                    @csrf

                    {{-- Phone Number --}}
                    <div>
                        <label for="login" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input id="login" type="tel" name="login" value="{{ old('login') }}" required autofocus
                                class="w-full pl-11 pr-4 py-2.5 rounded-lg border @error('login') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all text-sm"
                                placeholder="e.g. 07XX XXX XXX">
                        </div>
                        @error('login')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="login-password" class="block text-sm font-semibold text-gray-700">Password</label>
                            <button type="button" onclick="showSection('forgot-section')" class="text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors">Forgot password?</button>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="login-password" type="password" name="password" required
                                class="w-full pl-11 pr-12 py-2.5 rounded-lg border @error('password') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all text-sm"
                                placeholder="Enter your password">
                            <button type="button" onclick="togglePassword('login-password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-violet-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="login-password-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                            <span class="text-sm text-gray-600">Stay logged in</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-3 text-sm font-bold text-gray-900 bg-gradient-to-r from-gold-300 to-gold-400 hover:from-gold-400 hover:to-gold-500 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <span class="btn-text">Sign In</span>
                        <svg class="w-5 h-5 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <div class="loading-spinner"></div>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-400">or</span></div>
                </div>

                {{-- Register link --}}
                <p class="text-center text-sm text-gray-500">
                    New to Mamacare?
                    <button onclick="showSection('register-section')" class="font-semibold text-violet-600 hover:text-violet-700 transition-colors">Create account</button>
                </p>
            </div>

            {{-- REGISTER SECTION --}}
            <div id="register-section" class="form-section">
                <form method="POST" action="{{ route('mother.register') }}" class="space-y-5" onsubmit="return handleSubmit(this)">
                    @csrf

                    {{-- Full Name --}}
                    <div>
                        <label for="reg-name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="reg-name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full pl-11 pr-4 py-2.5 rounded-lg border @error('name') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all text-sm"
                                placeholder="Enter your full name">
                        </div>
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Phone or Email --}}
                    <div>
                        <label for="reg-login" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone or Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input id="reg-login" type="text" name="login" value="{{ old('login') }}" required
                                class="w-full pl-11 pr-4 py-2.5 rounded-lg border @error('login') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all text-sm"
                                placeholder="07XX XXX XXX or email">
                        </div>
                        @error('login')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="reg-password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="reg-password" type="password" name="password" required
                                class="w-full pl-11 pr-12 py-2.5 rounded-lg border @error('password') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all text-sm"
                                placeholder="Create a strong password">
                            <button type="button" onclick="togglePassword('reg-password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-violet-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="reg-password-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-3 text-sm font-bold text-gray-900 bg-gradient-to-r from-gold-300 to-gold-400 hover:from-gold-400 hover:to-gold-500 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <span class="btn-text">Create Account</span>
                        <svg class="w-5 h-5 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <div class="loading-spinner"></div>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-400">or</span></div>
                </div>

                <p class="text-center text-sm text-gray-500">
                    Already have an account?
                    <button onclick="showSection('login-section')" class="font-semibold text-violet-600 hover:text-violet-700 transition-colors">Sign in</button>
                </p>
            </div>

            {{-- FORGOT PASSWORD SECTION --}}
            <div id="forgot-section" class="form-section">
                {{-- Info Box --}}
                <div class="mb-6 p-4 bg-amber-50 border border-amber-100 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-amber-800">Nenosiri Mpya</p>
                        <p class="text-xs text-amber-700 mt-0.5">Tutakutumia nenosiri mpya au maelekezo kupitia WhatsApp yako.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('mother.forgot-password') }}" class="space-y-5" onsubmit="return handleSubmit(this)">
                    @csrf

                    {{-- Phone or Email --}}
                    <div>
                        <label for="forgot-login" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone or Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input id="forgot-login" type="text" name="login" value="{{ old('login') }}" required autofocus
                                class="w-full pl-11 pr-4 py-2.5 rounded-lg border @error('login') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all text-sm"
                                placeholder="07XX XXX XXX or email">
                        </div>
                        @error('login')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-3 text-sm font-bold text-gray-900 bg-gradient-to-r from-gold-300 to-gold-400 hover:from-gold-400 hover:to-gold-500 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <span class="btn-text">Send Reset Link</span>
                        <svg class="w-5 h-5 btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <div class="loading-spinner"></div>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-400">or</span></div>
                </div>

                <p class="text-center text-sm text-gray-500">
                    Remembered password?
                    <button onclick="showSection('login-section')" class="font-semibold text-violet-600 hover:text-violet-700 transition-colors">Sign in</button>
                </p>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <p class="mt-6 text-center text-xs text-gray-400">&copy; {{ date('Y') }} Mamacare AI. All rights reserved.</p>
</div>
@endsection

@push('scripts')
<script>
    const sectionConfig = {
        'login-section':    { title: 'Welcome Mama',      subtitle: 'Sign in to continue your motherhood journey' },
        'register-section': { title: 'Join Mamacare',     subtitle: 'Start your personalized motherhood journey' },
        'forgot-section':   { title: 'Reset Password',    subtitle: 'We\'ll help you get back into your account' },
    };

    function showSection(sectionId) {
        const sections = document.querySelectorAll('.form-section');
        const activeSection = document.getElementById(sectionId);

        sections.forEach(s => { s.classList.remove('active'); });
        activeSection.classList.add('active');

        const config = sectionConfig[sectionId];
        if (config) {
            document.getElementById('auth-title').textContent = config.title;
            document.getElementById('auth-subtitle').textContent = config.subtitle;
        }
    }

    function handleSubmit(form) {
        const btn = form.querySelector('button[type="submit"]');
        btn.classList.add('btn-loading');
        btn.disabled = true;
        return true;
    }

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }
</script>
@endpush
