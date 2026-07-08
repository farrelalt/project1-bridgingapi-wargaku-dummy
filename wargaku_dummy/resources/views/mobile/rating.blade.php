<!DOCTYPE html>
<html>
<head>
    <title>Beri Rating</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}?v={{ time() }}">
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
                    <p class="nav-subtitle">Rating penyelesaian keluhan</p>
                </div>
            </div>

            <a href="{{ route('keluhan.show', $keluhanId) }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="container">
            <div class="page-card">
                <div class="page-header">
                    <div>
                        <h2>Beri Rating</h2>
                        <p>Nilai kualitas layanan terhadap keluhan yang sudah ditangani.</p>
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

                <form method="POST" action="{{ route('rating.store', $keluhanId) }}">
                    @csrf

                    <div class="form-grid">
                        @foreach([
                            'responsivitas' => 'Responsivitas',
                            'informasi' => 'Informasi',
                            'tindak_lanjut' => 'Tindak Lanjut',
                            'keramahan' => 'Keramahan',
                            'kepuasan' => 'Kepuasan',
                        ] as $name => $label)
                            <div class="form-group">
                                <label>{{ $label }}</label>
                                <select name="{{ $name }}" required>
                                    <option value="">Pilih Nilai</option>
                                    <option value="1">1 - Sangat Kurang</option>
                                    <option value="2">2 - Kurang</option>
                                    <option value="3">3 - Cukup</option>
                                    <option value="4">4 - Baik</option>
                                    <option value="5">5 - Sangat Baik</option>
                                </select>
                            </div>
                        @endforeach

                        <div class="form-group form-full">
                            <label>Kritik dan Saran</label>
                            <textarea name="kritik_saran" placeholder="Tulis kritik atau saran tambahan...">{{ old('kritik_saran') }}</textarea>
                        </div>

                        <div class="form-group form-full">
                            <button type="submit" class="btn btn-primary">Kirim Rating</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>