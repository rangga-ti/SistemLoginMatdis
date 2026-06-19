# Dokumentasi Logika Sistem — Alur UI → Route → Controller → Aksi

Dokumentasi ringkas yang menjelaskan apa yang terjadi ketika pengguna menekan tombol atau submit form pada UI, file terkait, method yang dipanggil, dan efek pada database.

**Lokasi file penting**
- Controller utama: `app/Http/Controllers/AuthController.php`
- Model user: `app/Models/User.php`
- Route: `routes/web.php`
- Views: `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/auth/otp.blade.php`, `resources/views/dashboard.blade.php`

---

## 1) Login (tekan tombol "Kirim OTP")
- View: `resources/views/auth/login.blade.php`
- Form `POST` ke route: `login.perform` → `POST /login` (lihat `routes/web.php`)

Sequence:
1. Browser mengirim `POST /login` ke `AuthController::authenticate(Request $request)`.
2. `authenticate()` melakukan validasi input: `email`, `password`, `role`, `badge_id`.
3. Cari user: `User::where('email', $request->email)->first()`.
4. Cek apakah akun sedang terkunci: `locked_until` future → tolak, beri error.
5. Cek kecocokan kredensial: `Hash::check($password, $user->password)` dan `role` dan `badge_id` harus sama, serta `validBadgeForRole()` harus true.
   - Jika gagal: panggil `registerFailure($user)` untuk menambah `failed_login_attempts` dan kemungkinan set `locked_until` bila >=3, lalu kembalikan error.
6. Jika sukses: generate OTP 6 digit (`generateOtp()`), ambil secret HMAC saat ini (`getCurrentOtpKey()`), simpan `otp_code = hash_hmac('sha256', $otp, $secret)` dan `otp_expires_at = now()->addMinutes(10)`, reset `failed_login_attempts = 0`.
7. Kirim OTP ke email:
   - Jika `MAILTRAP_API_TOKEN` terisi, controller mencoba `App\Services\MailtrapApiMailer::send()` dengan template `resources/views/emails/otp_api.blade.php`.
   - Jika tidak, atau API gagal, gunakan `Mail::to($user->email)->send(new OtpCodeMail($user, $otp))` yang merender `resources/views/emails/otp.blade.php`.
8. Simpan `auth_user_id` di session (`$request->session()->put('auth_user_id', $user->id)`) lalu redirect ke route `otp.show`.

Efek DB: update `users` → `otp_code` (HMAC), `otp_expires_at`, `failed_login_attempts`.

---

## 2) OTP (masukkan kode dan submit)
- View: `resources/views/auth/otp.blade.php`
- Form `POST` ke route: `otp.verify` → `POST /otp`

Sequence:
1. Form mengirim `POST /otp` ke `AuthController::verifyOtp(Request $request)`.
2. `verifyOtp()` validasi `otp_code` (6 digits).
3. Ambil user dari session `auth_user_id`. Jika tidak ada → redirect ke login.
4. Periksa `locked_until` lagi (jika akun terkunci, tolak).
5. Loop melalui semua keys dari `getOtpKeys()`:
   - Hitung `hash_hmac('sha256', $providedOtp, $key)` dan bandingkan dengan `hash_equals` ke `user->otp_code`.
6. Jika tidak cocok, atau `otp_expires_at` sudah lewat → `registerFailure($user)` dan kembalikan error.
7. Jika cocok dan belum expired: panggil `Auth::login($user)` untuk menandai user sudah terautentikasi.
8. Bersihkan `otp_code`, `otp_expires_at`, `failed_login_attempts`, `locked_until`; hapus `auth_user_id` dari session; redirect ke `dashboard`.

Efek DB: pada keberhasilan → hapus `otp_code`/`otp_expires_at` dan reset attempt counters.

---

## 3) Registrasi (tekan tombol "Daftar")
- View: `resources/views/auth/register.blade.php`
- Form `POST` ke route: `register.perform` → `POST /register`

Sequence:
1. `AuthController::register(Request $request)` menerima request.
2. Validasi field: `name`, `email` (unique), `password` (confirmed, min 8), `role`, `badge_id`.
3. Cek `validBadgeForRole($role, $badge_id)` — jika tidak valid, kembalikan error.
4. Buat user: `User::create([...])` menyimpan `name,email,password(hashed),role,badge_id` dan inisialisasi `failed_login_attempts=0`.
5. Redirect ke `login` dengan status sukses.

Efek DB: `users` INSERT.

---

## 4) Logout
- View action (dashboard): `resources/views/dashboard.blade.php` link/button ke route `logout.perform` (POST)
- Route: `POST /logout` → `AuthController::logout(Request $request)`

Sequence:
1. `logout()` memanggil `Auth::logout()` lalu `session()->invalidate()` dan `session()->regenerateToken()`.
2. Redirect ke `login`.

Efek DB: tidak ada perubahan otomatis di tabel `users` (hanya session/guard dihapus).

---

## 5) Helper methods & perilaku penting di `AuthController`
- `generateOtp()` — buat angka 6-digit random, zero-padded.
- `getOtpKeys()` — baca `config('otp.keys')` atau fallback ke `config('app.key')` bila kosong.
- `getCurrentOtpKey()` — pilih key indeks `config('otp.current_index', 0)`.
- `validBadgeForRole(string $role, string $badgeId)` — regex cek format badge per role:
  - Admin: `/^ADM-\d{3}$/`
  - Manager: `/^MGR-\d{3}$/`
  - Staff: `/^STF-\d{3}$/`
  - User: `/^USR-\d{3}$/`
- `registerFailure(User $user)` — increment `failed_login_attempts`; jika mencapai 3, set `locked_until = now()->addMinutes(10)` dan reset `failed_login_attempts`.

---

## 6) Model `User` — fields yang dipakai oleh auth flow
- `name`, `email`, `password` (hashed), `role`, `badge_id`
- `failed_login_attempts` (int)
- `locked_until` (datetime|null)
- `otp_code` (string|null) — disimpan sebagai HMAC sha256 dari OTP
- `otp_expires_at` (datetime|null)

Lokasi model: `app/Models/User.php` (lihat atribut `$fillable` dan `$casts`).

---

## 7) Pengiriman email
- Dua jalur pengiriman:
  - Mailtrap Send API: ketika `MAILTRAP_API_TOKEN` ada, controller gunakan `App\Services\MailtrapApiMailer::send()` (
    file: `app/Services/MailtrapApiMailer.php`)
  - Fallback: `Mail::to(...)->send(new OtpCodeMail(...))` (Mailable `app/Mail/OtpCodeMail.php`)
- Template yang dipakai:
  - API: `resources/views/emails/otp_api.blade.php`
  - Mailable: `resources/views/emails/otp.blade.php`

---

## 8) Catatan alur error/kegagalan
- Salah password/role/badge → `registerFailure()` dipanggil, user bisa dikunci setelah 3 kali.
- OTP salah atau expired → `registerFailure()` dipanggil.
- Jika Mailtrap API gagal, sistem akan fallback ke `Mail::to()`.

---

## 9) Rujukan cepat (file utama)
- `routes/web.php` — definisi route (login/register/otp/logout/dashboard)
- `app/Http/Controllers/AuthController.php` — implementasi logika auth/otp
- `app/Services/MailtrapApiMailer.php` — pengirim via API
- `resources/views/auth/*.blade.php` — UI forms
- `resources/views/emails/*.blade.php` — template email
- `app/Models/User.php` — field user

---

Jika mau, saya bisa:
- Tambahkan diagram sequence (Mermaid) untuk tiap alur (login → otp → dashboard).
- Buat versi yang lebih teknis (potongan kode per langkah dengan penunjuk baris/file).
- Ekspor dokumentasi ini ke PDF atau tampilkan langsung di wiki repo.

File ini dibuat: `docs/logic_flow.md`
