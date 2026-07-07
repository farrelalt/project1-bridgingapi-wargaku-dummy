<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Wargaku Dummy</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}">
</head>
<body>
    <div class="app-shell">
        <div class="gov-strip">
            <span class="dot"></span>
            Pemerintah Kota Surabaya &middot; Dinas Komunikasi dan Informatika
        </div>

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
                <button type="submit" class="btn btn-danger">Keluar</button>
            </form>
        </div>

        <div class="photo-hero">
            <img src="{{ asset('images/civic-illustration.svg') }}" alt="Ilustrasi Kota Surabaya">
            <div class="photo-hero-content">
                <span class="photo-hero-eyebrow">Layanan Publik Digital</span>
                <h1>Wargaku, Suara Warga Surabaya</h1>
            </div>
        </div>

        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="hero-card">
                <h2>Selamat datang kembali</h2>
                <p>Simulasi aplikasi Wargaku untuk mengirim dan memantau keluhan warga melalui Bridging API.</p>
            </div>

            <div class="grid">
                <div class="menu-card">
                    <div class="menu-card-icon">&#128203;</div>
                    <h3>Daftar Keluhan</h3>
                    <p>Pantau daftar keluhan warga yang sudah dikirim melalui sistem simulasi Wargaku.</p>
                    <a href="{{ route('keluhan.index') }}" class="btn btn-secondary">Buka Daftar Keluhan</a>
                </div>

                <div class="menu-card">
                    <div class="menu-card-icon">&#9997;</div>
                    <h3>Buat Keluhan</h3>
                    <p>Kirim laporan atau pengaduan baru dengan memilih kategori, kecamatan, dan topik keluhan.</p>
                    <a href="{{ route('keluhan.create') }}" class="btn btn-secondary">Buat Keluhan Baru</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>