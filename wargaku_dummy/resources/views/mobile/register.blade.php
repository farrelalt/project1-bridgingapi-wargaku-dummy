<!DOCTYPE html>
<html>
<head>
    <title>Register Wargaku Dummy</title>
</head>
<body>
    <h2>Register Wargaku Dummy</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul style="color: red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register.process') }}">
        @csrf

        <div>
            <label>Username</label><br>
            <input type="text" name="user" value="{{ old('user') }}" required>
        </div>

        <br>

        <div>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>

        <br>

        <div>
            <label>Nama Lengkap</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <br>

        <div>
            <label>NIK</label><br>
            <input type="text" name="nik" value="{{ old('nik') }}" required>
        </div>

        <br>

        <div>
            <label>No HP</label><br>
            <input type="text" name="phone" value="{{ old('phone') }}" required>
        </div>

        <br>

        <button type="submit">Register</button>
    </form>

    <p>
        Sudah punya akun?
        <a href="{{ route('login') }}">Login di sini</a>
    </p>
</body>
</html>