<!DOCTYPE html>
<html>
<head>
    <title>Login Wargaku Dummy</title>
</head>
<body>
    <h2>Login Wargaku Dummy</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <div>
            <label>User</label>
            <input type="text" name="user" required>
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>

    <p>
        Belum punya akun?
        <a href="{{ route('register') }}">Register</a>
    </p>
</body>
</html>