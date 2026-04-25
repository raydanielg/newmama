@extends('layouts.app')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-left-inner">
                <div class="auth-brand">Malkia Konnect</div>
                <h1 class="auth-title">Reset your password</h1>
                <p class="auth-subtitle">Enter your email and we’ll send you a password reset link.</p>
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

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn auth-submit w-100">
                        <span class="btn-text">{{ __('Send Password Reset Link') }}</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>

                    @if (Route::has('login'))
                        <div class="text-center mt-3">
                            <a class="auth-link" href="{{ route('login') }}">Back to Login</a>
                        </div>
                    @endif

                    <div class="auth-footer">© {{ date('Y') }} Malkia Konnect LTD. All Rights Reserved.</div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
