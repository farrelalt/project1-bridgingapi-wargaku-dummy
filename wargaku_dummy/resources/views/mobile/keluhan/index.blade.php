<!DOCTYPE html>
<html>
<head>
    <title>Daftar Keluhan</title>
</head>
<body>
    <h2>Daftar Keluhan</h2>

    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
    <br><br>
    <a href="{{ route('keluhan.create') }}">Buat Keluhan Baru</a>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if(count($keluhan) > 0)
        <ul>
            @foreach($keluhan as $item)
                <li>
                    <strong>{{ $item['judul'] ?? 'Tanpa Judul' }}</strong><br>
                    Status: {{ $item['status'] ?? '-' }}<br>
                    <a href="{{ route('keluhan.show', $item['id']) }}">Detail</a>
                </li>
                <hr>
            @endforeach
        </ul>
    @else
        <p>Belum ada data keluhan.</p>
    @endif
</body>
</html>