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

    @if($errors->any())
        <ul style="color: red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <div>
            <label>Username</label><br>
            <input type="text" name="user" required>
        </div>

        <br>

        <div>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>

        <br>

        <button type="submit">Login</button>
    </form>

    <p>
        Belum punya akun?
        <a href="{{ route('register') }}">Register di sini</a>
    </p>
</body>
</html>