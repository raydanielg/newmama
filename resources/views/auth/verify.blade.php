@extends('layouts.app')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-left-inner">
                <div class="auth-brand">Malkia Konnect</div>
                <h1 class="auth-title">Verify your email</h1>
                <p class="auth-subtitle">We need to confirm your email address to secure your account.</p>
                <p class="auth-tagline">Secure. Reliable. Built for your business.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-right-top">
                <a href="{{ url('/') }}" class="auth-back">Go Back</a>
            </div>

            <div class="auth-form-wrap">
                <div class="auth-form-brand">MALKIA KONNECT</div>
                <h2 class="auth-form-title">Email Verification</h2>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <div class="mb-3">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                </div>

                <div class="mb-3">
                    {{ __('If you did not receive the email') }},
                </div>

                <form method="POST" action="{{ route('verification.resend') }}" class="auth-form">
                    @csrf
                    <button type="submit" class="btn auth-submit w-100">
                        <span class="btn-text">{{ __('Request another link') }}</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>
                </form>

                <div class="auth-footer">© {{ date('Y') }} Malkia Konnect LTD. All Rights Reserved.</div>
            </div>
        </div>
    </div>
</div>
@endsection
