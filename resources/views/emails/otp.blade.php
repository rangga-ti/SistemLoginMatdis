@component('mail::message')
# Halo {{ $name }},

Berikut adalah kode OTP Anda untuk login ke SistemLoginMatDis:

@component('mail::panel')
{{ $otpCode }}
@endcomponent

Kode ini berlaku hingga {{ $expiresAt->format('H:i d-m-Y') }}.

Jika Anda tidak melakukan permintaan ini, abaikan pesan ini.

Terima kasih,
Tim SistemLoginMatDis
@endcomponent
