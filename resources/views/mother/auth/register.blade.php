<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamacare AI - Create Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 340px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-img {
            width: 80px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.2));
        }

        .welcome-title {
            font-size: 26px;
            font-weight: 600;
            color: white;
            margin-bottom: 6px;
            text-align: center;
        }

        .welcome-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            text-align: center;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: rgba(255,255,255,0.95);
            color: #1f2937;
        }

        .form-input:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background: #111;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 20px;
        }

        .register-btn:hover {
            background: #000;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: rgba(255,255,255,0.7);
        }

        .login-link a {
            color: white;
            font-weight: 500;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
        }

        .social-login {
            margin-top: 24px;
        }

        .social-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.6);
            font-size: 12px;
        }

        .social-divider::before,
        .social-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.3);
        }

        .social-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .social-btn {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .social-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .google-btn {
            background: white;
            color: #374151;
        }

        .google-btn:hover {
            background: #f9fafb;
        }

        .apple-btn {
            background: #000;
            color: white;
        }

        .apple-btn:hover {
            background: #1a1a1a;
        }

        .social-icon {
            width: 18px;
            height: 18px;
        }

        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: rgba(220, 252, 231, 0.95);
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: rgba(254, 226, 226, 0.95);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-info {
            background: rgba(219, 234, 254, 0.95);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 0 16px;
            }
            
            .welcome-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('meetup_3669956.png') }}" alt="Mamacare AI" class="logo-img">
            <h1 class="welcome-title">Create Account</h1>
            <p class="welcome-subtitle">Sign up to Mamacare AI</p>
        </div>

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('mother.register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Enter your full name" value="{{ old('name') }}" required>
                    @error('name')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phone or Email</label>
                    <input type="text" name="login" class="form-input" placeholder="e.g. 07XX XXX XXX or email" value="{{ old('login') }}" required>
                    @error('login')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-input" id="password" placeholder="Create a password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="register-btn">Create Account</button>
            </form>

            <div class="social-login">
                <div class="social-divider">
                    <span>Or sign up with</span>
                </div>
                <div class="social-buttons">
                    <a href="{{ route('auth.google') }}" class="social-btn google-btn">
                        <svg class="social-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Google</span>
                    </a>
                    <a href="{{ route('auth.apple') }}" class="social-btn apple-btn">
                        <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4-4.95-4.91-4.22-12.28 1.39-12.63 1.25-.08 2.2.44 2.96.44.76 0 2.17-.54 3.24-.46.92.08 2.05.39 2.82 1.34-2.55 1.55-2.12 5.39.52 6.44-.57 1.54-1.31 3.08-2.55 4.87z"/>
                            <path d="M12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                        </svg>
                        <span>Apple</span>
                    </a>
                </div>
            </div>

            <div class="login-link">
                Already have an account? <a href="{{ route('mother.login') }}">Log in</a>
            </div>
    </div>

    <script>
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
