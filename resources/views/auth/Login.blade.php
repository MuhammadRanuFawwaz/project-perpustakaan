<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- LINK CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-box">

        <!-- LOGO SEKOLAH -->
        <img src="{{ asset('images/Smkn1Tarumajaya.png') }}" class="logo">

        <h2>Perpustakaan</h2>
        <p>Silakan masuk ke akun Anda</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- ERROR MESSAGE -->
            @if ($errors->any())
            <div style="color:red; margin-bottom:10px;">
                {{ $errors->first() }}
            </div>
            @endif

            <input type="text" name="email" placeholder="Username atau Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>
        </form>

        <div class="footer">
            © 2026 SMKN 1 TARUMAJAYA
        </div>

    </div>

</body>

</html>