<!DOCTYPE html>
<html>
<head>
    <title>Register Wargaku Dummy</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}">
</head>
<body>
    <div class="page-center">
        <div class="auth-card">
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
                    <label>No HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required>
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