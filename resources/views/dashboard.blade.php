<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .container { max-width: 720px; margin: 40px auto; padding: 24px; background: #fff; border-radius: 14px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }
        a.button { display: inline-block; margin-top: 20px; padding: 12px 18px; background: #dc2626; color: #fff; border-radius: 10px; text-decoration: none; }
        .meta { padding: 16px; border-radius: 12px; background: #eef2ff; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard SistemLoginMatDis</h1>
        <p>Selamat datang, {{ auth()->user()->name }}.</p>

        <div class="meta">
            <p><strong>Role:</strong> {{ auth()->user()->role }}</p>
            <p><strong>Badge ID:</strong> {{ auth()->user()->badge_id }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        </div>

        <a class="button" href="{{ route('logout.perform') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

        <form id="logout-form" action="{{ route('logout.perform') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
</body>
</html>
