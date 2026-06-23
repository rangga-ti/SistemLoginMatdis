<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP | Sistem Login MatDis</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset & Base Settings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: #fafbfc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* Main Container - Split Layout */
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 1000px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 4px 40px rgba(0, 0, 0, 0.03), 0 20px 80px rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
            animation: scaleUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px) scale(0.98);
        }

        @keyframes scaleUp {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Left Panel - Dark Minimalist Theme */
        .info-panel {
            flex: 1;
            background: #0f172a; 
            padding: 50px 40px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .info-panel::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.15);
            backdrop-filter: blur(10px);
        }
        
        .info-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -30px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 2px solid rgba(139, 92, 246, 0.1);
        }

        .floating-circle {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 2px dashed rgba(99, 102, 241, 0.4);
            border-radius: 50%;
            top: 20%;
            right: 15%;
            animation: spin 15s linear infinite;
        }

        @keyframes spin { 100% { transform: rotate(360deg); } }

        .info-panel h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            line-height: 1.25;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #ffffff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .info-panel p {
            font-size: 14px;
            font-weight: 400;
            color: #94a3b8;
            position: relative;
            z-index: 2;
            line-height: 1.6;
        }

        .project-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #c7d2fe;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
            width: max-content;
        }

        /* Right Panel - Form Container */
        .form-panel {
            flex: 1;
            padding: 50px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-panel h3 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
            letter-spacing: -0.3px;
        }

        .form-panel p.subtitle {
            color: #64748b;
            font-size: 13.5px;
            margin-bottom: 30px;
        }

        /* Elegant Alerts */
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .alert-error {
            background: #fff5f5;
            color: #e53e3e;
            border: 1px solid #fed7d7;
        }

        /* Form Inputs Modernization */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 12px;
            color: #475569;
            margin-bottom: 8px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        input {
            width: 100%;
            padding: 13px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            color: #0f172a;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        /* Khusus untuk input OTP agar lebih estetik */
        input.otp-input {
            text-align: center;
            letter-spacing: 12px;
            font-size: 20px;
            font-weight: 700;
        }

        input.otp-input::placeholder {
            letter-spacing: 0px;
            font-size: 14px;
            font-weight: 400;
            color: #94a3b8;
        }

        input:focus {
            background: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        /* Button Modernization */
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.2px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }

        button[type="submit"]:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.2);
        }

        /* Secondary Button (Kirim Ulang) */
        button[type="submit"].btn-secondary {
            background: #f1f5f9;
            color: #475569;
            box-shadow: none;
            margin-top: 5px;
        }

        button[type="submit"].btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .disabled-text {
            margin-top: 16px;
            color: #64748b;
            font-size: 13px;
            text-align: center;
            font-weight: 500;
        }

        /* Responsive Design for Mobile */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 450px;
            }
            .info-panel, .form-panel {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        
        <div class="info-panel">
            <div class="floating-circle"></div>
            <span class="project-badge">Autentikasi Keamanan</span>
            <h2>Verifikasi Dua Langkah</h2>
            <p>Sistem kami telah mengirimkan kode <i>One Time Password</i> (OTP) melalui Gmail. Silakan periksa kotak masuk email Anda untuk melanjutkan.</p>
        </div>

        <div class="form-panel">
            <h3>Masukkan Kode OTP</h3>
            <p class="subtitle">Validasi identitas Anda untuk mengamankan akun</p>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:15px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="form-group">
                    <label for="otp_code">Kode OTP (6 Digit)</label>
                    <input id="otp_code" type="text" name="otp_code" class="otp-input" value="{{ old('otp_code') }}" maxlength="6" placeholder="______" required autofocus autocomplete="off">
                </div>
                <button type="submit">Verifikasi</button>
            </form>

            @if (! empty($canResend) && $canResend)
                <form method="POST" action="{{ route('otp.resend') }}">
                    @csrf
                    <button type="submit" class="btn-secondary">Kirim Ulang OTP</button>
                </form>
            @elseif (! empty($otpExpired) && ! $canResend)
                <p class="disabled-text">OTP tidak tersedia untuk dikirim ulang saat ini.</p>
            @endif
        </div>

    </div>

</body>
</html>