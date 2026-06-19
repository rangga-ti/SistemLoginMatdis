<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Sistem Login MatDis</title>
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

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
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

        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        input, select {
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

        .password-container input {
            padding-right: 46px;
        }

        input:focus, select:focus {
            background: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2214%22%20height%3D%2214%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23475569%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22M6%209l6%206%206-6%22%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: #4f46e5;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
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

        /* Footer / Links */
        .form-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }

        .form-footer a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .form-footer a:hover {
            color: #312e81;
            text-decoration: underline;
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
            <span class="project-badge">Tugas Matematika Diskrit</span>
            <h2>Bergabunglah Bersama Kami</h2>
            <p>Daftarkan akun baru Anda untuk mendapatkan hak akses ke dalam sistem validasi dengan peran pengguna yang spesifik.</p>
        </div>

        <div class="form-panel">
            <h3>Buat Akun</h3>
            <p class="subtitle">Silakan isi formulir di bawah untuk mendaftar</p>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:15px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.perform') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: user@mahasiswa.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input id="password" type="password" name="password" placeholder="Buat password yang kuat" required>
                        <button type="button" class="toggle-password" aria-label="Tampilkan password">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Ulangi Password</label>
                    <div class="password-container">
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ketik ulang password Anda" required>
                        <button type="button" class="toggle-password" aria-label="Tampilkan password">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Role Akses</label>
                    <select id="role" name="role" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role Anda</option>
                        <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Manager" {{ old('role') === 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Staff" {{ old('role') === 'Staff' ? 'selected' : '' }}>Staff</option>
                        <option value="User" {{ old('role') === 'User' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                <button type="submit">Daftar Akun</button>
            </form>

            <div class="form-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>

    </div>

    <script>
        // Menggunakan querySelectorAll karena sekarang ada dua field password
        const togglePasswords = document.querySelectorAll('.toggle-password');

        togglePasswords.forEach(function(toggleButton) {
            toggleButton.addEventListener('click', function () {
                // Cari input password yang berada satu level di dalam container yang sama
                const passwordInput = this.previousElementSibling;
                
                // Tukar tipe input antara password dan text
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Tukar ikon SVG (Mata Terbuka / Mata Dicoret)
                if (type === 'text') {
                    this.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    `;
                    this.setAttribute('aria-label', 'Sembunyikan password');
                } else {
                    this.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    `;
                    this.setAttribute('aria-label', 'Tampilkan password');
                }
            });
        });
    </script>
</body>
</html>