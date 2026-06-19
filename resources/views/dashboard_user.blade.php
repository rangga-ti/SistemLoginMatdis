<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
</head>
<body>
    <h1>Dashboard User</h1>
    <p>Selamat datang, {{ $user->name }} ({{ $user->badge_id }})</p>
    <p>Ini halaman khusus untuk User.</p>
    <a href="{{ route('logout.perform') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    <form id="logout-form" action="{{ route('logout.perform') }}" method="POST" style="display:none;">@csrf</form>
</body>
</html>
