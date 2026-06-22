# Dokumentasi Skema Hashing & Enkripsi

Dokumen ini menjelaskan skema hashing dan kriptografi sederhana yang dipakai di proyek, bagaimana cara kerjanya, serta rekomendasi keamanan.

## Ringkasan singkat implementasi saat ini
- Password pengguna: disimpan menggunakan Laravel `Hash::make()` (lihat `AuthController::register`).
- OTP: disimpan sebagai HMAC-SHA256 dari nilai OTP 6-digit: `hash_hmac('sha256', $otp, $secret)` (lihat `AuthController::authenticate`).
- Verifikasi OTP: mencoba semua keys dari `getOtpKeys()` lalu membandingkan HMAC dengan `hash_equals()` untuk menghindari timing attacks (lihat `AuthController::verifyOtp`).
- OTP di-generate dengan `random_int(0, 999999)` dan di-pad menjadi 6 digit.

---

## 1) Password hashing (`Hash::make`)
- Lokasi: `AuthController::register()`
- Cara kerja:
  - `Hash::make($password)` menggunakan adaptor hashing yang diatur di config (`config/hashing.php`). Pada instalasi Laravel standar ini biasanya `bcrypt` (atau `argon2` jika dikonfigurasi).
  - Fungsi ini secara internal men-generate salt unik per-hash dan menyimpan informasi cost/algorithm di string hasil hash.
  - Untuk verifikasi, gunakan `Hash::check($plain, $hash)`; fungsi itu melakukan per-algorithm verification.

- Keunggulan:
  - Adaptive (cost dapat dinaikkan seiring waktu).
  - Salted oleh library — tidak perlu menyimpan salt secara manual.

- Rekomendasi:
  - Jika performa server memadai, gunakan `argon2id` untuk perlindungan lebih baik terhadap GPU cracking.
  - Jaga `config/hashing.php` agar cost (work factor) tidak terlalu rendah.
  - Selalu gunakan `Hash::check()` untuk verifikasi, jangan bandingkan string secara langsung.

---

## 2) HMAC-SHA256 untuk OTP
- Lokasi: `AuthController::authenticate()` (penyimpanan) dan `AuthController::verifyOtp()` (verifikasi).
- Cara kerja:
  - Sistem membuat OTP 6-digit.
  - OTP tidak disimpan dalam bentuk plaintext; melainkan dihitung HMAC menggunakan secret key: `hash_hmac('sha256', $otp, $secret)`.
  - Nilai HMAC disimpan di `users.otp_code` dan waktu kadaluarsa di `users.otp_expires_at`.
  - Saat user memasukkan OTP, aplikasi menghitung HMAC dari input menggunakan secret(s) yang tersedia dan membandingkan hasilnya ke HMAC yang tersimpan.
  - Perbandingan menggunakan `hash_equals()` untuk mencegah timing attacks.

- Mengapa HMAC?
  - Jika database bocor, attacker tidak langsung dapat melihat OTP asli.
  - HMAC mengandalkan secret key (disimpan terpisah, mis. di env / secret manager). Tanpa key, attacker tidak bisa menukar HMAC menjadi OTP.

- Key rotation:
  - Implementasi mendukung beberapa keys — `getOtpKeys()` mengembalikan array keys (misal dari `OTP_KEYS`), dan verifikasi mencoba semua keys sehingga rotation tidak memutus verifikasi untuk OTP yang dibuat dengan key lama.
  - Terdapat artisan helper `otp:rotate` yang menambahkan key baru ke `OTP_KEYS`.

- Rekomendasi:
  - Simpan keys di place yang aman (env, vault), jangan commit ke repo (kecuali repo aman/internal seperti tim kamu memutuskan).
  - Batasi jumlah keys yang aktif; bersihkan keys lama setelah masa transisi.
  - Hapus / set `otp_code` ke null segera setelah OTP digunakan untuk mencegah replay.

---

## 3) Timing-safe comparison (`hash_equals`)
- Lokasi: `AuthController::verifyOtp()`
- Keterangan:
  - `hash_equals($a, $b)` melakukan perbandingan string yang tahan terhadap timing attack.
  - Sangat penting digunakan saat membandingkan nilai kriptografis (HMAC, password hash, token).

---

## 4) Randomness untuk OTP (`random_int`)
- `random_int(0, 999999)` menghasilkan integer kriptografi-secure (CSPRNG) yang cocok untuk OTP.
- Jangan gunakan `mt_rand` atau `rand` untuk OTP karena tidak kriptografis-aman.

---

## 5) Entropy dan panjang OTP
- 6-digit → ruang 10^6 (1.000.000) kombinasi.
- Kelemahan: peluang tebakan sukses per coba adalah 1/1.000.000; ini relatif kecil tetapi bila attacker bisa coba banyak kali, risiko meningkat.
- Mitigasi yang ada di sistem ini: lockout setelah 3 kegagalan per akun.
- Rekomendasi tambahan: pertimbangkan memperpanjang OTP (mis. 7-8 digit) atau gunakan TOTP (RFC 6238) jika ingin interoperabilitas.

---

## 6) Ringkas: alur penyimpanan & verifikasi OTP (kode sampel)
- Penyimpanan (simplified):

```php
$otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$secret = $this->getCurrentOtpKey();
$user->otp_code = hash_hmac('sha256', $otp, $secret);
$user->otp_expires_at = now()->addMinutes(10);
$user->save();
```

- Verifikasi (simplified):

```php
$provided = $request->otp_code;
foreach ($this->getOtpKeys() as $k) {
    $h = hash_hmac('sha256', $provided, $k);
    if (hash_equals($h, $user->otp_code ?? '')) {
        // accepted
    }
}
```

Referensi penuh ada di `app/Http/Controllers/AuthController.php`.

---

## 7) Rekomendasi penguatan (prioritas)
1. Hapus `otp_code` segera setelah verifikasi sukses (sudah dilakukan).
2. Gunakan queue untuk pengiriman email (agar pengiriman gagal tidak memblokir request).
3. Terapkan rate-limiting per-IP dan per-user (Laravel throttle middleware) untuk mengurangi brute-force.
4. Simpan OTP keys di secret manager (Vault, AWS Secrets Manager) untuk produksi.
5. Pertimbangkan TOTP jika ingin kompatibilitas aplikasi pihak ketiga (Google Authenticator).

---

## 8) Referensi file utama
- `app/Http/Controllers/AuthController.php` — implementasi OTP/password/verification
- `resources/views/emails/otp.blade.php` — template email OTP
- `routes/web.php` — route terkait login/otp

---

Jika kamu setuju, saya bisa menambahkan ringkasan ini ke `docs/logic_flow.md` juga, atau buatkan diagram Mermaid yang menjelaskan alur HMAC/OTP secara visual.
