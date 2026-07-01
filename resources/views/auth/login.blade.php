<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Riana Collection</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }
        body{ font-family:'Segoe UI',sans-serif; background:#f4f4f4; overflow-x:hidden; }

        /* PANEL KIRI */
        .left-panel{ min-height:100vh; background:linear-gradient(135deg, #d84f7b, #ea6d94, #f5a5bf); position:relative; overflow:hidden; }
        .left-panel::before{ content:''; width:700px; height:700px; border-radius:50%; position:absolute; left:-200px; top:-150px; background:rgba(255,255,255,.08); }
        .left-panel::after{ content:''; width:280px; height:280px; border-radius:50%; position:absolute; right:80px; bottom:60px; background:rgba(255,255,255,.10); }
        .logo-title{ font-size:95px; font-weight:900; color:white; text-align:center; line-height:1; letter-spacing:3px; text-shadow:0 5px 15px rgba(0,0,0,.15); }
        .footer-links{ position:absolute; bottom:20px; left:30px; }
        .footer-links a{ color:white; text-decoration:none; margin-right:15px; font-size:14px; }

        /* PANEL KANAN */
        .right-panel{ min-height:100vh; display:flex; justify-content:center; align-items:center; background:#f5f5f5; }
        .login-card{ width:100%; max-width:500px; background:linear-gradient(180deg, #fff9fb 0%, #ffeef4 50%, #ffdfe9 100%); border-radius:25px; padding:35px; border:1px solid rgba(255,255,255,.8); box-shadow:0 15px 40px rgba(216,79,123,.18); }
        .top-line{ height:8px; border-radius:20px; margin-bottom:20px; background:linear-gradient(90deg, #d84f7b, #f06292, #ffb6c1); }
        .form-control{ border-radius:12px; padding:14px; border: 1px solid #ffb6c1; background-color: #fff9fb; transition: all 0.3s; font-size: 0.95rem; }
        .form-control:focus { border-color: #f06292; box-shadow: 0 0 0 4px rgba(240, 98, 146, 0.15); outline: none; background-color: #ffffff; }
        
        /* CSS Tambahan untuk tombol mata */
        #togglePassword { cursor: pointer; border-left: none; background: #fff9fb; border-color: #ffb6c1; border-radius: 0 12px 12px 0; padding: 0 15px; }
        .input-group .form-control { border-right: none; border-radius: 12px 0 0 12px; }
        .input-group:focus-within #togglePassword { border-color: #f06292; background-color: #ffffff; }

        .btn-login{ background:linear-gradient(90deg, #d84f7b, #f06292); color:white; border:none; width:100%; padding:14px; border-radius:12px; font-weight:700; transition: all 0.3s; box-shadow: 0 8px 20px rgba(216,79,123,.25); }
        .btn-login:hover{ transform: translateY(-3px); box-shadow: 0 12px 25px rgba(216,79,123,.35); color:white; }

        @media(max-width:992px){
            .left-panel { min-height: 200px !important; border-radius: 0 0 30px 30px; padding-bottom: 20px; display: block !important; }
            .logo-title { font-size: 50px; margin-top: 10px; }
            .footer-links { display: none !important; }
            .h-75 { height: auto !important; margin-top: 40px; }
            .right-panel { background: #f4f4f4; padding: 15px; margin-top: -30px; z-index: 10; align-items: flex-start; }
            .login-card { padding: 30px 20px; border-radius: 20px; background: #ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7 left-panel">
            <div class="d-flex flex-column justify-content-center align-items-center h-75">
                <div class="logo-title">RIANA</div>
                <div class="logo-title">COLLECTION</div>
            </div>
            <div class="footer-links">
                <a href="#">Tentang Kami</a>
                <a href="#">Ketentuan Layanan</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Pusat Bantuan</a>
                <a href="#">Kontak Kami</a>
            </div>
        </div>

        <div class="col-lg-5 right-panel">
            <div class="login-card">

                <div class="top-line d-none d-lg-block"></div>
                <h2 class="text-center fw-bold mb-1">Login</h2>
                <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">Masuk ke akun Riana Collection</p>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autocomplete="email" autofocus>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">👁️</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Ingat Saya</label>
                        </div>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Lupa Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">Login</button>

                    <div class="text-center mt-4">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Toggle tipe input antara password dan text
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Ganti ikon mata
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });
</script>

</body>
</html>