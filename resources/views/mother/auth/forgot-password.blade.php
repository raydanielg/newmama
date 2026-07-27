@extends('layouts.mother_auth')

@section('title', 'Mamacare AI - Reset Password')

@section('content')
<div class="w-full max-w-md" style="animation: simpleFadeIn 0.4s ease-out both;">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-rosebrand-600 to-rosebrand-700 px-8 py-8 text-center">
            <div class="w-16 h-16 mx-auto bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 logo-float">
                <img src="{{ asset('logo.svg') }}" alt="Mamacare AI" class="w-10 h-10 object-contain">
            </div>
            <h2 class="text-2xl font-extrabold text-white">Reset Password</h2>
            <p class="text-rosebrand-100 text-sm mt-1">We'll help you get back into your account</p>
        </div>

        {{-- Form --}}
        <div class="p-8">

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
                    <label for="login" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone or Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border @error('login') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-rosebrand-500 focus:ring-2 focus:ring-rosebrand-200 outline-none transition-all text-sm"
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

            {{-- Social Login --}}
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 px-4 py-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all font-semibold text-gray-700 text-xs shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>Google</span>
                </a>
                <a href="{{ route('auth.apple') }}" class="flex items-center justify-center gap-3 px-4 py-3 bg-gray-900 border border-gray-900 rounded-lg hover:bg-black transition-all font-semibold text-white text-xs shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                    <span>Apple</span>
                </a>
            </div>

            {{-- Login link --}}
            <p class="mt-8 text-center text-sm text-gray-500">
                Remember your password?
                <a href="{{ route('mother.login') }}" class="font-semibold text-rosebrand-600 hover:text-rosebrand-700 transition-colors">Sign in</a>
            </p>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-gray-400">&copy; {{ date('Y') }} Mamacare AI. All rights reserved.</p>
</div>
@endsection

@push('scripts')
<script>
    function handleSubmit(form) {
        const btn = form.querySelector('button[type="submit"]');
        btn.classList.add('btn-loading');
        btn.disabled = true;
        return true;
    }
</script>
@endpush
