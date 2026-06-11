<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Riana Collection</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }
        body{ font-family:'Segoe UI',sans-serif; background:#f4f4f4; overflow-x:hidden; }

        /* PANEL KIRI */
        .left-panel{ min-height:100vh; background:linear-gradient(135deg, #d84f7b, #ea6d94, #f5a5bf); position:relative; overflow:hidden; }
        .left-panel::before{ content:''; width:700px; height:700px; border-radius:50%; position:absolute; left:-200px; top:-150px; background:rgba(255,255,255,.08); }
        .left-panel::after{ content:''; width:280px; height:280px; border-radius:50%; position:absolute; right:80px; bottom:60px; background:rgba(255,255,255,.10); }
        .logo-title{ font-size:95px; font-weight:900; color:white; text-align:center; line-height:1; letter-spacing:3px; text-shadow:0 5px 15px rgba(0,0,0,.15); }
        .tagline{ color:white; font-size:55px; font-weight:700; text-align:center; line-height:1.2; }
        .btn-back{ color:white; border:2px solid white; padding:10px 25px; border-radius:30px; text-decoration:none; }
        .btn-back:hover{ color:white; }
        .language{ border:2px solid rgba(255,255,255,.5); border-radius:30px; overflow:hidden; }
        .language span{ padding:10px 20px; display:inline-block; color:white; cursor:pointer; }
        .language .active{ background:white; color:#333; }
        .footer-links{ position:absolute; bottom:20px; left:30px; }
        .footer-links a{ color:white; text-decoration:none; margin-right:15px; font-size:14px; }

        /* PANEL KANAN */
        .right-panel{ min-height:100vh; display:flex; justify-content:center; align-items:center; background:#f5f5f5; }
        .register-card{ width:100%; max-width:520px; max-height:90vh; overflow-y:auto; background:linear-gradient(180deg, #fff9fb 0%, #ffeef4 50%, #ffdfe9 100%); border-radius:25px; padding:35px; border:1px solid rgba(255,255,255,.8); box-shadow:0 15px 40px rgba(216,79,123,.18); }
        .register-card::-webkit-scrollbar{ width:6px; }
        .register-card::-webkit-scrollbar-thumb{ background:#ccc; border-radius:20px; }
        .top-line{ height:8px; border-radius:20px; margin-bottom:20px; background:linear-gradient(90deg, #d84f7b, #f06292, #ffb6c1); }
        .form-control{ border-radius:12px; padding:14px; border: 1px solid #ffb6c1; background-color: #fff9fb; transition: all 0.3s; font-size: 0.95rem; }
        .form-control:focus { border-color: #f06292; box-shadow: 0 0 0 4px rgba(240, 98, 146, 0.15); outline: none; background-color: #ffffff; }
        
        /* Style Tambahan untuk tombol mata */
        .input-group-text { cursor: pointer; background: #fff9fb; border-color: #ffb6c1; border-left: none; border-radius: 0 12px 12px 0; padding: 0 15px; }
        .input-group .form-control { border-right: none; border-radius: 12px 0 0 12px; }
        .input-group:focus-within .input-group-text { border-color: #f06292; background-color: #ffffff; }

        .btn-register{ background:linear-gradient(90deg, #d84f7b, #f06292); color:white; border:none; width:100%; padding:14px; border-radius:12px; font-weight:700; transition: all 0.3s; box-shadow: 0 8px 20px rgba(216,79,123,.25); }
        .btn-register:hover{ transform: translateY(-3px); box-shadow: 0 12px 25px rgba(216,79,123,.35); color:white; }

        @media(max-width:992px){ .logo-title{ font-size:60px; } .tagline{ font-size:35px; } .left-panel{ min-height:500px; } }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7 left-panel">
            <div class="p-4 d-flex justify-content-between">
                <a href="{{ route('login') }}" class="btn-back">← Kembali</a>
                <div class="language"><span class="active">ID</span><span>EN</span></div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center h-75">
                <div class="logo-title">RIANA</div><div class="logo-title">COLLECTION</div>
                <div class="tagline mt-4">Satu akun untuk<br>akses semua layanan</div>
                <div class="mt-5 text-white fs-3">Beauty • Skincare • Makeup • Self Care</div>
            </div>
            <div class="footer-links">
                <a href="#">Tentang Kami</a> <a href="#">Ketentuan Layanan</a> <a href="#">Kebijakan Privasi</a> <a href="#">Pusat Bantuan</a> <a href="#">Kontak Kami</a>
            </div>
        </div>

        <div class="col-lg-5 right-panel">
            <div class="register-card">
                <div class="top-line"></div>
                <h2 class="text-center fw-bold mb-1">Daftar Akun</h2>
                <p class="text-center text-muted mb-4">Bergabung dengan Riana Collection</p>

                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.9rem;">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error) 
                                <li>{{ $error }}</li> 
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control" required></div>
                    <div class="mb-3"><label>Nomor Handphone</label><input type="text" name="phone" class="form-control" required></div>
                    
                    <div class="mb-3"><label>Jenis Kelamin</label><br>
                        <input type="radio" name="gender" value="Perempuan"> Perempuan &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="gender" value="Laki-laki"> Laki-laki
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="input-group-text" onclick="togglePassword('password', this)">👁️</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">👁️</span>
                        </div>
                    </div>

                    <hr>
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" name="agree_integrity" value="1" required>
                        <label class="form-check-label">Saya telah membaca dan menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#integrityModal">Pakta Integritas</a></label>
                    </div>
                    <button type="submit" class="btn-register">Daftar Akun</button>
                    <div class="text-center mt-3">Sudah punya akun? <a href="{{ route('login') }}">Login</a></div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('modals.integrity') 

<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "🙈";
        } else {
            input.type = "password";
            icon.textContent = "👁️";
        }
    }
</script>
</body>
</html>