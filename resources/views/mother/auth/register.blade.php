<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamacare AI - Tengeneza Akaunti</title>
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

            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-info-circle"></i>
                    Muhimu!
                </div>
                <p class="info-box-text">
                    Ukidhani hujasajili hapo awali, tafadhali <a href="{{ route('join') }}" style="color: #ec4899; font-weight: 500;">jiunge hapa</a> kwanza kupata MK Number yako.
                </p>
            </div>

            <form action="{{ route('mother.register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">MK Number Yako</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" name="mk_number" class="form-input" placeholder="MFANO: MK-00001" value="{{ old('mk_number') }}" required>
                    </div>
                    @error('mk_number')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nambari ya WhatsApp</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" name="whatsapp_number" class="form-input" placeholder="07XXXXXXXX" value="{{ old('whatsapp_number') }}" required>
                    </div>
                    @error('whatsapp_number')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nenosiri (Password)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-input" placeholder="Weka nenosiri (angalau herufi 6)" required>
                    </div>
                    @error('password')
                        <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Thibitisha Nenosiri</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Weka nenosiri tena" required>
                    </div>
                </div>

                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus"></i>
                    <span>TENGENEZA AKAUNTI</span>
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="{{ route('mother.login') }}">Log in</a>
            </div>
    </div>
</body>
</html>
