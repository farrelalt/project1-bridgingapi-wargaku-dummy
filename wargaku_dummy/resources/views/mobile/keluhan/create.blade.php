<!DOCTYPE html>
<html>
<head>
    <title>Buat Keluhan</title>
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
                    <p class="nav-subtitle">Form pengaduan warga</p>
                </div>
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>/</span>
                <span class="current">Buat Keluhan</span>
            </div>

            <div class="page-card">
                <div class="page-header">
                    <div>
                        <h2>Buat Keluhan</h2>
                        <p>Lengkapi form berikut untuk mengirim simulasi keluhan warga.</p>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('keluhan.store') }}">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group form-full">
                            <label>Judul</label>
                            <input type="text" name="judul" placeholder="Contoh: Jalan rusak di depan kos" required>
                        </div>

                        <div class="form-group form-full">
                            <label>Isi Keluhan</label>
                            <textarea name="konten" placeholder="Tuliskan detail keluhan..." required></textarea>
                        </div>

                        <div class="form-section-label">Klasifikasi</div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item['id'] ?? $item['kategori_id'] ?? '' }}">
                                        {{ $item['nama_kategori'] ?? $item['nama'] ?? $item['name'] ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kecamatan</label>
                            <select name="kecamatan_id" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatan as $item)
                                    <option value="{{ $item['id'] ?? $item['kecamatan_id'] ?? '' }}">
                                        {{ $item['nama_kecamatan'] ?? $item['nama'] ?? $item['name'] ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Topik</label>
                            <select name="topik_id" required>
                                <option value="">Pilih Topik</option>
                                @foreach($topik as $item)
                                    <option value="{{ $item['id'] ?? $item['topik_id'] ?? '' }}">
                                        {{ $item['nama_topik'] ?? $item['nama'] ?? $item['name'] ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-section-label">Lokasi</div>

                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="latitude" placeholder="-7.2575">
                        </div>

                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" placeholder="112.7521">
                        </div>

                        <div class="form-group form-full">
                            <label>Alamat</label>
                            <textarea name="alamat" placeholder="Masukkan alamat lokasi keluhan"></textarea>
                        </div>

                        <div class="form-group form-full">
                            <button type="submit" class="btn btn-primary">Kirim Keluhan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>