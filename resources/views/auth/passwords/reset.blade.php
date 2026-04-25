@extends('layouts.app')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-left-inner">
                <div class="auth-brand">Malkia Konnect</div>
                <h1 class="auth-title">Set a new password</h1>
                <p class="auth-subtitle">Choose a strong password to keep your account protected.</p>
                <p class="auth-tagline">Secure. Reliable. Built for your business.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-right-top">
                <a href="{{ url('/') }}" class="auth-back">Go Back</a>
            </div>

            <div class="auth-form-wrap">
                <div class="auth-form-brand">MALKIA KONNECT</div>
                <h2 class="auth-form-title">Reset Password</h2>

                <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control auth-input" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn auth-submit w-100">
                        <span class="btn-text">{{ __('Reset Password') }}</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>

                    <div class="auth-footer">© {{ date('Y') }} Malkia Konnect LTD. All Rights Reserved.</div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
