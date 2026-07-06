<!DOCTYPE html>
<html>
<head>
    <title>Detail Keluhan</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}">
</head>
<body>
    <div class="app-shell">
        <div class="navbar">
            <div class="nav-brand">
                <div class="brand-logo">W</div>
                <div>
                    <h1 class="nav-title">Wargaku Dummy</h1>
                    <p class="nav-subtitle">Detail pengaduan warga</p>
                </div>
            </div>

            <a href="{{ route('keluhan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="container">
            <div class="page-card">
                <div class="page-header">
                    <div>
                        <h2>Detail Keluhan</h2>
                        <p>Informasi lengkap mengenai keluhan yang dipilih.</p>
                    </div>
                </div>

                @if($keluhan)
                    <div class="detail-row">
                        <div class="detail-label">Judul</div>
                        <div class="detail-value">{{ $keluhan['judul'] ?? '-' }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Isi Keluhan</div>
                        <div class="detail-value">{{ $keluhan['konten'] ?? '-' }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge">{{ $keluhan['status'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Alamat</div>
                        <div class="detail-value">{{ $keluhan['alamat'] ?? '-' }}</div>
                    </div>

                    <div class="action-row">
                        <a href="{{ route('keluhan.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>

                        @if(Route::has('rating.create'))
                            <a href="{{ route('rating.create', $keluhan['id']) }}" class="btn btn-primary">Beri Rating</a>
                        @endif
                    </div>
                @else
                    <div class="alert alert-error">Data keluhan tidak ditemukan.</div>
                    <a href="{{ route('keluhan.index') }}" class="btn btn-secondary">Kembali</a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>