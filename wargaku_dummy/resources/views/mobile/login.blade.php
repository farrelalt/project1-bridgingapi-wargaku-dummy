<!DOCTYPE html>
<html>
<head>
    <title>Login Wargaku Dummy</title>
    <link rel="stylesheet" href="{{ asset('css/wargaku.css') }}">
</head>
<body>
    <div class="page-center">
        <div class="auth-card">
            <div class="brand">
                <div class="brand-logo">W</div>
                <div>
                    <h2 class="brand-title">Wargaku Dummy</h2>
                    <p class="brand-subtitle">Simulasi layanan pengaduan warga</p>
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

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="user" placeholder="Masukkan username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <p class="auth-footer">
                Belum punya akun?
                <a href="{{ route('register') }}">Register di sini</a>
            </p>
        </div>
    </div>
</body>
</html>