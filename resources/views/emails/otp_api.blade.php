<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode OTP</title>
  </head>
  <body style="font-family:Arial,Helvetica,sans-serif; background:#f6f6f6; padding:30px;">
    <div style="max-width:600px; margin:0 auto; background:#fff; padding:24px; border-radius:8px;">
      <h2>Halo {{ $name }},</h2>
      <p>Berikut adalah kode OTP Anda untuk login ke SistemLoginMatDis:</p>
      <div style="font-size:22px; font-weight:700; padding:12px 16px; background:#f3f4f6; border-left:4px solid #111; display:inline-block;">{{ $otp }}</div>
      <p style="margin-top:18px;">Kode ini berlaku hingga {{ $expiresAt->format('H:i d-m-Y') }}.</p>
      <p>Jika Anda tidak melakukan permintaan ini, abaikan pesan ini.</p>
      <p>Terima kasih,<br>Tim SistemLoginMatDis</p>
    </div>
  </body>
</html>
