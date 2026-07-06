<!DOCTYPE html>
<html>
<head>
    <title>Daftar Keluhan</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}">

    <style>
        .keluhan-list {
            display: grid;
            gap: 16px;
        }

        .keluhan-card {
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
            background: #ffffff;
            transition: 0.2s ease;
        }

        .keluhan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        }

        .keluhan-title {
            margin: 0 0 8px;
            color: var(--primary-dark);
            font-size: 18px;
        }

        .keluhan-meta {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 42px 20px;
            background: var(--primary-soft);
            border-radius: 20px;
            color: var(--primary-dark);
        }

        .empty-state h3 {
            margin: 0 0 8px;
        }

        .empty-state p {
            margin: 0 0 18px;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <div class="app-shell">
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
                            <div class="keluhan-card">
                                <h3 class="keluhan-title">
                                    {{ $item['judul'] ?? 'Tanpa Judul' }}
                                </h3>

                                <div class="keluhan-meta">
                                    Status:
                                    <span class="status-badge">
                                        {{ $item['status'] ?? '-' }}
                                    </span>
                                </div>

                                @if(isset($item['alamat']))
                                    <div class="keluhan-meta">
                                        Lokasi: {{ $item['alamat'] }}
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