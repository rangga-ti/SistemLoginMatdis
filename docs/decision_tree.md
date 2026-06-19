## Decision Tree - Alur Otentikasi SistemLoginMatDis

Berikut diagram decision tree terperinci (Mermaid) yang menggambarkan semua cabang penting: validasi input, pengecekan akun terkunci, validasi credentials (password, role, badge), pengiriman OTP, verifikasi OTP, lockout, dan sukses login.

```mermaid
flowchart TD
  %% Start
  Start([Start Login])

  %% Input validation
  Start --> ValidateInput{Validate Input}
  ValidateInput -->|missing/invalid| ReturnInputError[Return Input Error]
  ValidateInput -->|ok| FindUser[Lookup user by email]

  %% User existence
  FindUser -->|not found| ReturnUserNotFound[Return: "Akun tidak ditemukan"]
  FindUser -->|found| CheckLocked{Is user locked?}

  %% Locked branch
  CheckLocked -->|yes| ReturnLocked[Return: "Akun terkunci sementara"]
  CheckLocked -->|no| ValidateCreds{Password + Role + Badge OK?}

  %% Credential validation
  ValidateCreds -->|no| RegisterFailure[Increment failed_login_attempts]
  RegisterFailure -->|reached >= 3| SetLock[Set locked_until = now + 10 minutes]
  RegisterFailure -->|still <3| ReturnCredsError[Return: "Email/password/role/badge tidak sesuai"]
  SetLock --> ReturnLocked

  ValidateCreds -->|yes| GenerateOTP[Generate 6-digit OTP]

  %% OTP signing & send
  GenerateOTP --> SignOTP[Sign OTP with current HMAC key]
  SignOTP --> SaveHashedOTP[Store hashed OTP + otp_expires_at = now + 10m]
  SaveHashedOTP --> SendEmail[Send OTP plaintext via email (Mailtrap/SMPP etc)]
  SendEmail --> ShowOtpForm[Show OTP input form to user]

  %% OTP verification
  ShowOtpForm --> SubmitOtp[User submits OTP]
  SubmitOtp --> VerifyOtp{Verify OTP}
  VerifyOtp -->|expired| RegisterFailureOtp[Register failure; return expired message]
  VerifyOtp -->|mismatch| RegisterFailureOtp
  VerifyOtp -->|match| LoginSuccess[Auth::login(user)]

  RegisterFailureOtp -->|failed>=3| SetLock
  RegisterFailureOtp -->|failed<3| ReturnOtpError[Return "Kode OTP tidak valid atau sudah kadaluarsa"]

  LoginSuccess --> ClearOtp[Clear otp fields and reset failures]
  ClearOtp --> RedirectDashboard[Redirect to /dashboard]

  %% Notes
  classDef note fill:#f8f9fa,stroke:#b3c0d1,color:#1f2937;
  Notes([Notes:\n• Badge patterns per role: ADM-###, MGR-###, STF-###, USR-###\n• OTP HMAC verification tries all keys in `OTP_KEYS` (rotate support)\n• Lockout: 3 failures → lock 10 minutes\n• OTP lifetime: 10 minutes]):::note
  Start --> Notes

  style ReturnInputError fill:#fee2e2,stroke:#fca5a5
  style ReturnUserNotFound fill:#fee2e2,stroke:#fca5a5
  style ReturnLocked fill:#fde68a,stroke:#f59e0b
  style ReturnCredsError fill:#fee2e2,stroke:#fca5a5
  style ReturnOtpError fill:#fee2e2,stroke:#fca5a5
  style LoginSuccess fill:#d1fae5,stroke:#10b981

```

File ini menjelaskan alur keputusan utama dan kondisi yang memicu cabang baru di decision tree. Bila ingin, saya bisa menambahkan versi PNG yang dirender atau menjabarkan setiap simpul (node) menjadi uraian tertulis langkah-per-langkah di `docs/`.
File ini menjelaskan alur keputusan utama dan kondisi yang memicu cabang baru di decision tree.

Render ke PNG/SVG
------------------

Saya juga menyediakan sumber Mermaid murni di `docs/decision_tree.mmd` sehingga kamu dapat merender diagram menjadi PNG atau SVG.

Contoh perintah (memerlukan Node.js dan `@mermaid-js/mermaid-cli`):

```bash
# install mermaid-cli (sekali saja)
npx -y @mermaid-js/mermaid-cli@10.0.0 -v

# render PNG
npx @mermaid-js/mermaid-cli -i docs/decision_tree.mmd -o docs/decision_tree.png

# render SVG
npx @mermaid-js/mermaid-cli -i docs/decision_tree.mmd -o docs/decision_tree.svg
```

Windows PowerShell (contoh):

```powershell
npx @mermaid-js/mermaid-cli -i docs/decision_tree.mmd -o docs/decision_tree.png
```

Jika mau, saya bisa mencoba merender di lingkungan ini jika kamu mengizinkan saya menginstal dependensi, namun biasanya lebih cepat dan aman jika kamu menjalankan perintah di mesin pengembang atau CI.

Uraian node terperinci tersedia di `docs/decision_tree_nodes.md`.
