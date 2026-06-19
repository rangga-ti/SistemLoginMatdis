# Decision Tree — Node Explanations

Berikut uraian langkah-demi-langkah untuk setiap node pada `decision_tree.mmd`.

1) Start Login
- Titik masuk ketika user membuka form login.

2) Validate Input
- Periksa keberadaan dan format `email`, `password`, `role`, `badge_id`.
- Jika salah satu tidak valid atau kosong, kembalikan pesan kesalahan input.

3) Lookup user by email
- Cari record user di tabel `users` berdasarkan `email`.

4) Is user locked?
- Jika `locked_until` ada dan waktu sekarang < `locked_until`, tolak akses.

5) Password + Role + Badge OK?
- Periksa `password` (Hash::check), `role` exact match, `badge_id` exact match, dan pola `badge_id` sesuai role via `validBadgeForRole`.
- Jika salah: increment `failed_login_attempts`. Jika mencapai 3 maka set `locked_until = now + 10 minutes`.

6) Generate OTP
- Buat kode 6-digit acak.

7) Sign OTP with current HMAC key
- Ambil kunci pertama dari `OTP_KEYS` (konfigurasi `OTP_CURRENT`) lalu buat HMAC-SHA256 dari OTP.

8) Store hashed OTP + otp_expires_at
- Simpan hash OTP di `users.otp_code` dan `otp_expires_at = now + 10 minutes`.

9) Send OTP plaintext via email
- Kirim email dengan isi OTP plaintext (untuk user). Hanya hash yang disimpan di DB.

10) Show OTP input form
- Arahkan user ke form untuk memasukkan OTP.

11) Verify OTP
- Ketika menerima OTP dari user, buat HMAC dari input menggunakan setiap kunci di `OTP_KEYS` dan cocokkan dengan `users.otp_code` menggunakan `hash_equals` untuk mencegah timing attack. Jika cocok dan belum kadaluarsa, autentikasi berhasil.

12) Register failure / Return OTP error
- Jika verifikasi gagal, increment `failed_login_attempts`. Terapkan lockout jika mencapai 3.

13) Login success
- Panggil `Auth::login($user)`, bersihkan field OTP, reset `failed_login_attempts`, hapus `locked_until`.

14) Redirect to /dashboard
- Arahkan user yang berhasil ke halaman dashboard sesuai role.

Catatan operasional: setiap langkah penting dicatat ke log (`storage/logs/laravel.log`) dengan tag `[Auth]`.
