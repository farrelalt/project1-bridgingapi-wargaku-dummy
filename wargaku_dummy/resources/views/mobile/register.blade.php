<!DOCTYPE html>
<html>
<head>
    <title>Register Wargaku Dummy</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}?v={{ time() }}">
</head>
<body>
    <div class="page-center">
        <div class="auth-card register-card">
            <div class="brand">
                <div class="brand-logo">W</div>
                <div>
                    <h2 class="brand-title">Daftar Akun</h2>
                    <p class="brand-subtitle">Buat akun simulasi Wargaku</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

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

            <form method="POST" action="{{ route('register.process') }}">
                @csrf

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="user" value="{{ old('user') }}" placeholder="Contoh: ahdan123" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                </div>

                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required>
                </div>

                <div class="form-group">
                    <label>Kecamatan</label>
                    <select name="kecamatan_id" required>
                        <option value="">Pilih Kecamatan</option>
                        <option value="1" {{ old('kecamatan_id') == '1' ? 'selected' : '' }}>Gubeng</option>
                        <option value="2" {{ old('kecamatan_id') == '2' ? 'selected' : '' }}>Sukolilo</option>
                        <option value="3" {{ old('kecamatan_id') == '3' ? 'selected' : '' }}>Rungkut</option>
                        <option value="4" {{ old('kecamatan_id') == '4' ? 'selected' : '' }}>Wonokromo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kelurahan</label>
                    <select name="kelurahan_id" required>
                        <option value="">Pilih Kelurahan</option>
                        <option value="1" {{ old('kelurahan_id') == '1' ? 'selected' : '' }}>Kertajaya</option>
                        <option value="2" {{ old('kelurahan_id') == '2' ? 'selected' : '' }}>Mojo</option>
                        <option value="3" {{ old('kelurahan_id') == '3' ? 'selected' : '' }}>Gebang Putih</option>
                        <option value="4" {{ old('kelurahan_id') == '4' ? 'selected' : '' }}>Keputih</option>
                        <option value="5" {{ old('kelurahan_id') == '5' ? 'selected' : '' }}>Rungkut Kidul</option>
                        <option value="6" {{ old('kelurahan_id') == '6' ? 'selected' : '' }}>Kalirungkut</option>
                        <option value="7" {{ old('kelurahan_id') == '7' ? 'selected' : '' }}>Wonokromo</option>
                        <option value="8" {{ old('kelurahan_id') == '8' ? 'selected' : '' }}>Darmo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <p class="auth-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>