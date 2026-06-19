<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 420px; margin: 60px auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; }
        button { width: 100%; padding: 12px; background: #16a34a; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; }
        .error { background: #fee2e2; color: #991b1b; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Masukkan Kode OTP</h1>

        @if ($errors->any())
            <div class="error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf
            <div class="form-group">
                <label for="otp_code">Kode OTP</label>
                <input id="otp_code" type="text" name="otp_code" value="{{ old('otp_code') }}" maxlength="6" required>
            </div>
            <button type="submit">Verifikasi</button>
        </form>
        @if (! empty($canResend) && $canResend)
            <form method="POST" action="{{ route('otp.resend') }}" style="margin-top:12px;">
                @csrf
                <button type="submit" style="background:#2563eb;">Kirim Ulang OTP</button>
            </form>
        @elseif (! empty($otpExpired) && ! $canResend)
            <p style="margin-top:12px; color:#666;">OTP tidak tersedia untuk dikirim ulang saat ini.</p>
        @endif
    </div>
</body>
</html>
