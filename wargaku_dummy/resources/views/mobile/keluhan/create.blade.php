<!DOCTYPE html>
<html>
<head>
    <title>Buat Keluhan</title>
</head>
<body>
    <h2>Buat Keluhan</h2>

    <a href="{{ route('keluhan.index') }}">Kembali</a>

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('keluhan.store') }}">
        @csrf

        <div>
            <label>Judul</label>
            <input type="text" name="judul" required>
        </div>

        <div>
            <label>Isi Keluhan</label>
            <textarea name="konten" required></textarea>
        </div>

        <div>
            <label>Kategori</label>
            <select name="kategori_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $item)
                    <option value="{{ $item['id'] }}">{{ $item['nama'] ?? $item['name'] ?? '-' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Kecamatan</label>
            <select name="kecamatan_id" required>
                <option value="">Pilih Kecamatan</option>
                @foreach($kecamatan as $item)
                    <option value="{{ $item['id'] }}">{{ $item['nama'] ?? $item['name'] ?? '-' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Topik</label>
            <select name="topik_id" required>
                <option value="">Pilih Topik</option>
                @foreach($topik as $item)
                    <option value="{{ $item['id'] }}">{{ $item['nama'] ?? $item['name'] ?? '-' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Alamat</label>
            <textarea name="alamat"></textarea>
        </div>

        <div>
            <label>Latitude</label>
            <input type="text" name="latitude">
        </div>

        <div>
            <label>Longitude</label>
            <input type="text" name="longitude">
        </div>

        <button type="submit">Kirim Keluhan</button>
    </form>
</body>
</html>