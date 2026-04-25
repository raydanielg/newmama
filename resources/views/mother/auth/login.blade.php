<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamacare AI - Mama Login</title>
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

        .login-container {
            width: 100%;
            max-width: 340px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
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

        .login-btn {
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

        .login-btn:hover {
            background: #000;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: rgba(255,255,255,0.7);
        }

        .signup-link a {
            color: white;
            font-weight: 500;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
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

        .forgot-link {
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
        }

        .forgot-link a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
        }

        .forgot-link a:hover {
            color: white;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 0 16px;
            }
            
            .welcome-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
            <div class="login-header">
                <img src="{{ asset('meetup_3669956.png') }}" alt="Mamacare AI" class="logo-img">
                <h1 class="welcome-title">Welcome back</h1>
                <p class="welcome-subtitle">Log in to Mamacare AI</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

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

            <form action="{{ route('mother.login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="login" class="form-input" placeholder="e.g. 07XX XXX XXX" value="{{ old('login') }}" required>
                    @error('login')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-input" id="password" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="login-btn">Continue</button>
            </form>

            <div class="forgot-link">
                <a href="{{ route('mother.forgot-password') }}">Forgot password?</a>
            </div>

            <div class="signup-link">
                Don't have an account? <a href="{{ route('mother.register') }}">Sign up</a>
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
