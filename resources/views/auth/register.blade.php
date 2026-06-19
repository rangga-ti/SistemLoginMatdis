<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi SistemLoginMatDis</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 420px; margin: 60px auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; }
        button { width: 100%; padding: 12px; background: #2563eb; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; }
        .error { background: #fee2e2; color: #991b1b; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrasi</h1>
        @if ($errors->any())
            <div class="error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.perform') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Ulangi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Pilih role</option>
                    <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Manager" {{ old('role') === 'Manager' ? 'selected' : '' }}>Manager</option>
                    <option value="Staff" {{ old('role') === 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="User" {{ old('role') === 'User' ? 'selected' : '' }}>User</option>
                </select>
            </div>

          

            <button type="submit">Daftar</button>
        </form>

        <p style="text-align:center; margin-top:18px;">
            <a href="{{ route('login') }}" style="color:#2563eb; text-decoration:none;">Sudah punya akun? Login</a>
        </p>
    </div>
</body>
</html>
