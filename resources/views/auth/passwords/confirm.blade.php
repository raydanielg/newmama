@extends('layouts.app')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-left-inner">
                <div class="auth-brand">Malkia Konnect</div>
                <h1 class="auth-title">Confirm your password</h1>
                <p class="auth-subtitle">For your security, please confirm your password to continue.</p>
                <p class="auth-tagline">Secure. Reliable. Built for your business.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-right-top">
                <a href="{{ url('/') }}" class="auth-back">Go Back</a>
            </div>

            <div class="auth-form-wrap">
                <div class="auth-form-brand">MALKIA KONNECT</div>
                <h2 class="auth-form-title">Confirm Password</h2>

                <div class="mb-3">{{ __('Please confirm your password before continuing.') }}</div>

                <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn auth-submit w-100">
                        <span class="btn-text">{{ __('Confirm Password') }}</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a class="auth-link" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                        </div>
                    @endif

                    <div class="auth-footer">© {{ date('Y') }} Malkia Konnect LTD. All Rights Reserved.</div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
