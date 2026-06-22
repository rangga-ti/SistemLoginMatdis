@component('mail::message')
<div style="text-align: center; margin-bottom: 25px;">
    <h1 style="color: #1e293b; font-size: 24px; font-weight: 800; margin-bottom: 5px; font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.5px;">
        Sistem Login MatDis
    </h1>
    <p style="color: #64748b; font-size: 14px; margin-top: 0;">Sistem Pengamanan Akses Masuk</p>
</div>

# Halo, {{ $name }}

Kami menerima permintaan untuk masuk ke akun Anda. Gunakan kode verifikasi di bawah ini untuk menyelesaikan proses autentikasi Anda:

@component('mail::panel')
<div style="text-align: center; padding: 10px 0;">
    <div style="font-size: 32px; font-weight: 800; letter-spacing: 6px; color: #4f46e5; font-family: monospace; display: inline-block;">
        {{ $otpCode }}
    </div>
</div>
@endcomponent

<div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; margin: 20px 0; border-radius: 4px;">
    <p style="color: #b45309; font-size: 13px; margin: 0; font-weight: 600;">
        ⚠️ Kode OTP ini bersifat rahasia dan hanya berlaku selama 10 menit (hingga {{ $expiresAt->format('H:i d-m-Y') }}). Jangan bagikan kode ini kepada siapa pun.
    </p>
</div>

Jika Anda tidak mencoba masuk atau tidak meminta kode ini, silakan abaikan email ini dengan aman.

Terima kasih,<br>
**Tim SistemLoginMatDis**
@endcomponent
