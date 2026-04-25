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
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 48px 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 28px;
            color: white;
            font-weight: 700;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
        }

        .welcome-subtitle {
            font-size: 14px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #1e40af;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .login-btn:hover {
            background: #1e3a8a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .signup-link {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #6b7280;
        }

        .signup-link a {
            color: #1e40af;
            font-weight: 500;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .forgot-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
        }

        .forgot-link a {
            color: #6b7280;
            text-decoration: none;
        }

        .forgot-link a:hover {
            color: #1e40af;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">M</div>
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
