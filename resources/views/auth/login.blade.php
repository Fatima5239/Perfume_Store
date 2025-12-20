<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | PERFUME AL WISSAM</title>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            min-height: 100vh;
            background:
                linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
                url("{{ asset('images/homeImage.jpeg') }}") center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.45);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn { from { opacity:0; transform:translateY(15px); } to { opacity:1; transform:translateY(0); } }

        .logo { text-align:center; margin-bottom:30px; }
        .logo img { height:70px; margin-bottom:12px; }
        .logo h2 { margin:0; font-size:20px; letter-spacing:4px; font-weight:700; color:#000; }
        .subtitle { font-size:13px; color:#777; margin-top:6px; }

        label { display:block; font-size:13px; font-weight:600; margin-bottom:6px; color:#222; }
        input { width:100%; padding:12px; border-radius:6px; border:1px solid #ccc; font-size:14px; transition:0.25s; }
        input:focus { border-color:#d4af37; outline:none; box-shadow:0 0 0 2px rgba(212,175,55,0.15); }
        .error { color:#c00; font-size:12px; margin-top:6px; }

        .remember-row { display:flex; align-items:center; justify-content:space-between; margin-top:18px; font-size:13px; }
        .remember-row label { display:flex; align-items:center; gap:6px; font-weight:400; }
        .remember-row a { text-decoration:none; color:#555; transition:0.2s; }
        .remember-row a:hover { color:#000; }

        .btn {
            width:100%;
            margin-top:25px;
            background:#000;
            color:#fff;
            border:none;
            padding:14px;
            font-size:15px;
            font-weight:600;
            border-radius:6px;
            cursor:pointer;
            transition:0.3s;
        }

        .btn:hover { background:#222; transform:translateY(-1px); }
        .footer-text { text-align:center; margin-top:25px; font-size:12px; color:#888; }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo">
            <img src="{{ asset('images/logoImage.png') }}" alt="PERFUME AL WISSAM">
            <h2>PERFUME AL WISSAM</h2>
            <div class="subtitle">Admin Control Panel</div>
        </div>

        <!-- Display errors -->
        @if ($errors->any())
            <div class="error" style="color: #c00; margin-bottom: 15px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <!-- Password -->
            <div style="margin-top:18px;">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <!-- Remember + Forgot -->
            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button class="btn" type="submit">Sign In</button>
        </form>

        <div class="footer-text">
            © {{ date('Y') }} Perfume Al Wissam
        </div>
    </div>
</body>
</html>