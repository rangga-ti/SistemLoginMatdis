# Logika Himpunan, Relasi, dan Kombinatorika

Dokumen ini merangkum model himpunan, relasi, dan perhitungan kombinatorika yang digunakan di SistemLoginMatDis.

1) Notasi himpunan

- U: himpunan semua pengguna (tabel `users`).
- R: himpunan role = {Admin, Manager, Staff, User}.
- B_role: himpunan badge valid untuk sebuah role; tiap role memiliki pola `XXX-000` dengan 1000 kemungkinan (000–999).
- OTP: ruang OTP valid = {000000..999999} (10^6 kemungkinan).

2) Relasi

- ValidRoleBadge ⊆ R × B, relasi yang memetakan setiap `role` ke subset badge yang valid. Contoh: (Admin, ADM-123) ∈ ValidRoleBadge.
- AuthRelation ⊆ U × Credentials, dimana Credentials = (email, password, role, badge_id, otp).
  - User u ∈ U dapat mengautentikasi jika dan hanya jika:
    - (role, badge_id) ∈ ValidRoleBadge,
    - password valid untuk u,
    - otp ∈ OTP dan cocok (HMAC) dengan yang dihasilkan.

3) Kombinatorika

- Kombinasi badge per role: 1000.
- Jumlah role: 4 → total badge combinations = 4 × 1000 = 4,000.
- Ruang OTP: 10^6 = 1,000,000.
- Kombinasi badge × OTP = 4,000 × 1,000,000 = 4,000,000,000.

4) Probabilitas dan serangan

- Probabilitas menebak OTP dengan satu tebakan: 1 / 10^6 = 0.0001%.
- Probabilitas sukses setelah N tebakan (aproksimasi): N / 10^6.
- Karena validasi juga memerlukan badge + role + password, ruang serangan praktis perlu menebak password/role/badge juga.

5) Decision tree cabang yang relevan

- Cabang pertama: user ditemukan? (ya/tidak)
- Cabang kedua: akun terkunci? (ya/tidak)
- Cabang ketiga: validasi password, role, badge (ya/tidak)
- Cabang keempat: kirim OTP dan verifikasi (berhasil/gagal)

6) Implementasi di kode

- ValidRoleBadge diimplementasikan melalui fungsi `validBadgeForRole` di `app/Http/Controllers/AuthController.php`.
- Kombinatorika tersedia via `App\Services\AuthCombinatorics`.
