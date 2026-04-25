<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mamacare AI - Umesahau Nenosiri?</title>
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

        .forgot-container {
            width: 100%;
            max-width: 340px;
        }

        .forgot-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(30, 64, 175, 0.25);
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .logo-img {
            width: 120px;
            height: auto;
            max-height: 120px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #1e40af;
            text-align: center;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
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
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #ec4899;
            background: white;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 24px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(236, 72, 153, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
            color: #9ca3af;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .alt-btn {
            width: 100%;
            padding: 14px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .alt-btn:hover {
            border-color: #ec4899;
            color: #ec4899;
            background: #fdf2f8;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .info-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-box-title {
            font-size: 14px;
            font-weight: 600;
            color: #c2410c;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box-text {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }

        @media (max-width: 480px) {
            .forgot-card {
                padding: 24px;
            }

            .welcome-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="logo-section">
                    <img src="{{ asset('meetup_3669956.png') }}" alt="Mamacare AI" class="logo-img">
                    <span class="logo-text">Mamacare AI</span>
                </div>
                <h1 class="welcome-title">Umesahau Nenosiri? 🤔</h1>
                <p class="welcome-subtitle">Hakuna shida! Tutakusaidia kurudia upya</p>
            </div>

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-whatsapp" style="color: #25d366;"></i>
                    Nenosiri Mpya
                </div>
                <p class="info-box-text">
                    Tutakutumia nenosiri mpya au maelekezo ya kurejesha kupitia WhatsApp yako. Hakikisha nambari yako iko sawa.
                </p>
            </div>

            <form action="{{ route('mother.forgot-password') }}" method="POST">
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

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    <span>TUMA OMBI</span>
                </button>
            </form>

            <div class="divider">AU</div>

            <a href="{{ route('mother.login') }}" class="alt-btn">
                <i class="fas fa-arrow-left"></i>
                <span>RUDI KWENYE LOGIN</span>
            </a>
        </div>
    </div>
</body>
</html>
