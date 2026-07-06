<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Wargaku Dummy</title>
</head>
<body>
    <h2>Dashboard Wargaku Dummy</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <p>Selamat datang di simulasi aplikasi Wargaku.</p>

    <ul>
        <li><a href="{{ route('keluhan.index') }}">Lihat Keluhan</a></li>
        <li><a href="{{ route('keluhan.create') }}">Buat Keluhan</a></li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>