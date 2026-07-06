<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Wargaku Dummy</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}">
</head>
<body>
    <div class="app-shell">
        <div class="navbar">
            <div class="nav-brand">
                <div class="brand-logo">W</div>
                <div>
                    <h1 class="nav-title">Wargaku Dummy</h1>
                    <p class="nav-subtitle">Dashboard layanan pengaduan warga</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>

        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="hero-card">
                <h2>Selamat Datang</h2>
                <p>Ini adalah simulasi aplikasi Wargaku untuk mengirim dan memantau keluhan warga melalui Bridging API.</p>
            </div>

            <div class="grid">
                <div class="menu-card">
                    <h3>Lihat Keluhan</h3>
                    <p>Pantau daftar keluhan warga yang sudah dikirim melalui sistem simulasi Wargaku.</p>
                    <a href="{{ route('keluhan.index') }}" class="btn btn-secondary">Buka Daftar Keluhan</a>
                </div>

                <div class="menu-card">
                    <h3>Buat Keluhan</h3>
                    <p>Kirim laporan atau pengaduan baru dengan memilih kategori, kecamatan, dan topik keluhan.</p>
                    <a href="{{ route('keluhan.create') }}" class="btn btn-secondary">Buat Keluhan Baru</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>