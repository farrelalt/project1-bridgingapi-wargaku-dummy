<!DOCTYPE html>
<html>
<head>
    <title>Daftar Keluhan</title>
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
                    <p class="nav-subtitle">Daftar pengaduan warga</p>
                </div>
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
        </div>

        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>/</span>
                <span class="current">Daftar Keluhan</span>
            </div>

            <div class="page-card">
                <div class="page-header">
                    <div>
                        <h2>Daftar Keluhan</h2>
                        <p>Pantau daftar keluhan warga yang sudah masuk ke sistem simulasi Wargaku.</p>
                    </div>

                    <a href="{{ route('keluhan.create') }}" class="btn btn-primary" style="width: auto;">
                        Buat Keluhan Baru
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @if(count($keluhan) > 0)
                    <div class="keluhan-list">
                        @foreach($keluhan as $item)
                            @php
                                $statusRaw = strtolower($item['status'] ?? '-');
                                $statusClass = match(true) {
                                    str_contains($statusRaw, 'selesai') => 'status-selesai',
                                    str_contains($statusRaw, 'proses') => 'status-proses',
                                    str_contains($statusRaw, 'tunda') => 'status-tunda',
                                    str_contains($statusRaw, 'tolak') => 'status-ditolak',
                                    default => '',
                                };
                            @endphp
                            <div class="keluhan-card {{ $statusClass }}">
                                <div class="keluhan-card-top">
                                    <h3 class="keluhan-title">
                                        {{ $item['judul'] ?? 'Tanpa Judul' }}
                                    </h3>
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $item['status'] ?? '-' }}
                                    </span>
                                </div>

                                @if(isset($item['alamat']))
                                    <div class="keluhan-meta">
                                        {{ $item['alamat'] }}
                                    </div>
                                @endif

                                <div class="action-row">
                                    <a href="{{ route('keluhan.show', $item['id']) }}" class="btn btn-secondary">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">&#128203;</div>
                        <h3>Belum Ada Keluhan</h3>
                        <p>Data keluhan belum tersedia. Buat keluhan baru untuk mencoba alur simulasi Wargaku.</p>

                        <a href="{{ route('keluhan.create') }}" class="btn btn-primary" style="width: auto;">
                            Buat Keluhan Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>