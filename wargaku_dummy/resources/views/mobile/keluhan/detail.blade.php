<!DOCTYPE html>
<html>
<head>
    <title>Detail Keluhan</title>
</head>
<body>
    <h2>Detail Keluhan</h2>

    <a href="{{ route('keluhan.index') }}">Kembali</a>

    @if($keluhan)
        <p><strong>Judul:</strong> {{ $keluhan['judul'] ?? '-' }}</p>
        <p><strong>Isi:</strong> {{ $keluhan['konten'] ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $keluhan['status'] ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $keluhan['alamat'] ?? '-' }}</p>

        <a href="{{ route('rating.create', $keluhan['id']) }}">Beri Rating</a>
    @else
        <p>Data keluhan tidak ditemukan.</p>
    @endif
</body>
</html>