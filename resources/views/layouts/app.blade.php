<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Riana Collection')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-pink: #FF6699;
            --soft-pink: #fce4ec;
            --border-pink: #f8bbd0;
        }

        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        .promo-bar { 
            background: linear-gradient(45deg, #d81b60, #ff4081, #ff80ab, #f50057);
            background-size: 400% 400%;
            animation: gradientBG 8s ease infinite;
            color: white; 
            overflow: hidden; 
            white-space: nowrap; 
            font-weight: 600; 
            font-size: 0.95rem; 
            letter-spacing: 1px;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .promo-text { display: inline-block; padding-left: 100%; animation: marquee 35s linear infinite; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-100%); } }
        
        .navbar { z-index: 1050; position: relative; } /* Z-index lebih tinggi dari konten */
        
        /* NAVBAR MEWAH STYLING */
        .navbar-mewah {
            background: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px -10px rgba(216, 27, 96, 0.15) !important;
        }
        .navbar-mewah::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ff80ab, #d81b60, #ff80ab, transparent);
            opacity: 0.8;
        }

        .nav-link { 
            font-weight: 600; 
            color: #333; 
            transition: 0.3s; 
            font-size: 0.95rem; 
            white-space: nowrap; 
            display: flex; 
            align-items: center; 
        }
        .nav-link i { margin-right: 6px !important; }
        .nav-link:hover { color: var(--primary-pink) !important; transform: translateY(-2px); }
        .search-box { width: 100%; max-width: 220px; border-radius: 20px; border: 1px solid #ddd; }
        .profile-img { width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--primary-pink); }
        .profile-header { background: linear-gradient(135deg, #fff5f8, #ffe6ee); }
        
        /* FIX DROPDOWN: Memastikan dropdown tidak tumpah dan z-index aman */
        .dropdown-menu { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 10px !important; 
        }
        
        .btn-danger { background: var(--primary-pink); border: none; }
        .btn-danger:hover { background: #ff4d88; }
        
        .badge-pink-soft {
            background-color: var(--soft-pink) !important;
            color: #d81b60 !important;
            border: 1px solid var(--border-pink);
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .back-top {
            position: fixed; right: 20px; bottom: 20px; width: 50px; height: 50px;
            border: none; border-radius: 50%; background: var(--primary-pink);
            color: white; display: none; z-index: 999; transition: 0.3s; box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        /* BRAND LOGO STYLING */
        .logo-brand { display: flex; align-items: center; text-decoration: none; }
        .logo-brand .brand-text {
            background: linear-gradient(135deg, #d81b60, #ff80ab);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
            font-size: clamp(1rem, 4vw, 1.4rem);
            letter-spacing: 1px;
        }
        .brand-icon-wrapper {
            background: linear-gradient(135deg, #d81b60, #ff80ab);
            color: white;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(216,27,96,0.3);
        }
        
        .map-container { min-height: 400px; }
        @media (max-width: 768px) { .map-container { min-height: 250px; } }
    </style>
</head>
<body>

    <div class="promo-bar py-2">
        <div class="promo-text">
            ✨ SELAMAT DATANG DI RIANA COLLECTION - PUSAT KECANTIKAN TERLENGKAP ✨ &nbsp;&nbsp;&nbsp; 💖 DAPATKAN POTONGAN HARGA SPESIAL HINGGA 70% UNTUK PRODUK SKINCARE PILIHAN! 💖 &nbsp;&nbsp;&nbsp; 🚚 NIKMATI GRATIS ONGKIR KE SELURUH INDONESIA TANPA MINIMAL BELANJA! 🚚 &nbsp;&nbsp;&nbsp; 🎁 DAFTAR SEKARANG DAN KLAIM VOUCHER DISKON KHUSUS MEMBER BARU! 🎁 &nbsp;&nbsp;&nbsp; 💄 TAMPIL CANTIK MAKSIMAL SETIAP HARI DENGAN KOLEKSI MAKEUP TERBAIK KAMI 💄
        </div>
    </div>

    <nav class="navbar navbar-expand-xl navbar-mewah sticky-top py-3">
        <div class="container-fluid px-3 px-xl-5">
            <a class="navbar-brand logo-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Riana Collection" class="me-2 shadow-sm" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%; border: 1px solid #ff80ab;">
                <span class="brand-text">RIANA COLLECTION</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active-pink text-pink' : '' }}" href="{{ route('home') }}"><i class="fas fa-home me-1" style="color: #4facfe;"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('categories') ? 'active-pink text-pink' : '' }}" href="{{ route('categories') }}"><i class="fas fa-th-large me-1" style="color: #c471ed;"></i> Kategori</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('brands') ? 'active-pink text-pink' : '' }}" href="{{ route('brands') }}"><i class="fas fa-tags me-1" style="color: #f6d365;"></i> Brand</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('flashsale') ? 'active-pink text-pink' : '' }}" href="{{ route('flashsale') }}"><i class="fas fa-bolt me-1 text-warning"></i> Flash Sale</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('products') ? 'active-pink text-pink' : '' }}" href="{{ route('products') }}"><i class="fas fa-shopping-bag me-1" style="color: #ff758c;"></i> Produk</a></li>
                    <li class="nav-item ms-2"><a class="nav-link fw-bold text-info" href="{{ route('ai.analyzer') }}"><i class="fas fa-robot me-1"></i> AI Analyzer <span class="badge bg-danger rounded-pill ms-1" style="font-size:0.6rem;">Baru!</span></a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active-pink text-pink' : '' }}" href="{{ Auth::user()->hasRole('admin') ? route('admin.dashboard') : route('dashboard') }}"><i class="fas fa-tachometer-alt me-1" style="color: #43e97b;"></i> Dashboard</a></li>
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-xl-center align-items-start mt-3 mt-xl-0 border-xl-0 border-top pt-xl-0 pt-3">
                    <li class="nav-item mb-2 mb-xl-0 mx-xl-2">
                        <form action="{{ route('products') }}" method="GET" class="d-flex w-100">
                            <input type="text" name="search" class="form-control search-box" placeholder="Cari produk...">
                        </form>
                    </li>
                    
                    <li class="nav-item mb-2 mb-xl-0 mx-xl-2">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart fs-5" style="color: #d81b60;"></i>
                            @if(($cartCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    @auth
                    <!-- Notification Bell with Polling -->
                    <li class="nav-item mb-2 mb-xl-0 mx-xl-2 dropdown">
                        <a class="nav-link position-relative dropdown-toggle" href="#" id="navbarNotification" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="markNotificationsAsRead()">
                            <i class="fas fa-bell fs-5"></i>
                            <span id="notificationBadgeCounter" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; display: none;">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 notification-dropdown p-0" aria-labelledby="navbarNotification" style="width: 320px; max-height: 400px; overflow-y: auto;" id="notificationDropdownMenu">
                            <li class="p-3 border-bottom text-center">
                                <h6 class="mb-0 fw-bold">Notifikasi</h6>
                            </li>
                            <!-- AJAX akan mengisi area ini -->
                            <li id="notificationEmptyState" class="p-3 text-center text-muted">
                                <small>Belum ada notifikasi baru</small>
                            </li>
                            <li><a class="dropdown-item text-center fw-bold py-2 bg-light text-primary" href="{{ route('notifications.index') }}">Lihat Semua Notifikasi</a></li>
                        </ul>
                    </li>
                    @endauth

                    @auth
                    {{-- DROPDOWN DIPERBAIKI --}}
                    <li class="nav-item mb-2 mb-xl-0 mx-xl-2 dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=E91E63&color=fff' }}" class="profile-img me-2 shadow-sm border border-light rounded-circle" style="width:35px; height:35px; object-fit:cover;">
                            <span class="fw-bold" style="color: var(--primary-pink);">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li class="profile-header p-3">
                                <strong>{{ Auth::user()->name }}</strong><br>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>
                            <li><a class="dropdown-item py-2" href="{{ Auth::user()->hasRole('admin') ? route('admin.dashboard') : route('dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('orders.index') }}"><i class="fas fa-box-open me-2 text-warning"></i> Pesanan Saya</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('wishlist.index') }}"><i class="fas fa-heart me-2 text-danger"></i> Wishlist</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('voucher.index') }}"><i class="fas fa-ticket-alt me-2 text-success"></i> Voucher & Promo</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('notifications.index') }}"><i class="fas fa-bell me-2 text-info"></i> Notifikasi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="fas fa-user-cog me-2 text-secondary"></i> Pengaturan Akun & Alamat</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-danger ms-2">Login</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="min-vh-100">
        @yield('content')
    </main>

    <!-- TENTANG KAMI & LOKASI TOKO (Dipindahkan ke Master Layout agar muncul di semua halaman) -->
    <section class="container my-5 pb-5">
        <div class="row rounded-4 shadow-sm overflow-hidden border bg-white" style="border-color: #fce4ec !important;">
            <!-- Info Kontak -->
            <div class="col-md-5 p-4 p-lg-5 d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #fff0f5 0%, #ffe6f0 100%);">
                <h2 class="fw-bold mb-4" style="color: #FF6699;">Tentang Riana Collection</h2>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    Selamat datang di <strong>Riana Collection</strong>! Kami adalah toko kosmetik dan perawatan kulit terpercaya yang berlokasi di Bengkalis. Kami menyediakan produk kecantikan original untuk memenuhi segala kebutuhan penampilan Anda.
                </p>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-white rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; flex-shrink: 0; color: #FF6699;">
                        <i class="fas fa-map-marker-alt fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold mb-0 text-dark">Alamat Toko</h6>
                        <small class="text-muted">Jl. Antara Damon, Bengkalis Sub-District, Bengkalis Regency, Riau 28713</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-white rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; flex-shrink: 0; color: #FF6699;">
                        <i class="fas fa-clock fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold mb-0 text-dark">Jam Operasional</h6>
                        <small class="text-muted">Buka Setiap Hari · Tutup pukul 22.00</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; flex-shrink: 0; color: #FF6699;">
                        <i class="fas fa-phone-alt fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold mb-0 text-dark">Telepon / WhatsApp</h6>
                        <small class="text-muted fw-bold">0811-6060-0608</small>
                    </div>
                </div>
            </div>
            
            <!-- Google Maps Embed -->
            <div class="col-md-7 p-0 map-container">
                <iframe width="100%" height="100%" style="border:0;" loading="lazy" allowfullscreen src="https://maps.google.com/maps?q=Riana%20collection,%20Jl.%20Antara%20Damon,%20Bengkalis&t=&z=16&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
        </div>
    </section>

    <footer class="border-top mt-0 py-5" style="background-color: var(--soft-pink);">
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h4 class="fw-bold" style="color: var(--primary-pink);">Riana Collection</h4>
                    <p class="text-muted mb-0">Beauty, Makeup & Skincare Store</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="mb-2">Metode Pembayaran</h6>
                    <div class="d-flex justify-content-center justify-content-md-end flex-wrap gap-2">
                        <span class="badge badge-pink-soft">BCA</span>
                        <span class="badge badge-pink-soft">BNI</span>
                        <span class="badge badge-pink-soft">Mandiri</span>
                        <span class="badge badge-pink-soft">DANA</span>
                        <span class="badge badge-pink-soft">QRIS</span>
                    </div>
                </div>
            </div>
            <div class="text-center text-muted mt-4">© 2026 Riana Collection. All rights reserved.</div>
        </div>
    </footer>

    <button class="back-top" id="backTop">↑</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btn = document.getElementById('backTop');
        window.onscroll = () => btn.style.display = window.scrollY > 300 ? 'block' : 'none';
        btn.onclick = () => window.scrollTo({ top:0, behavior:'smooth' });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // System Notifikasi Realtime (AJAX Polling)
        let fetchedNotificationIds = [];
        let isFetchingNotifications = false;
        let isInitialLoad = true; // Tambahkan flag penanda load pertama

        function fetchNotifications() {
            if(isFetchingNotifications) return;
            isFetchingNotifications = true;

            fetch('/api/notifications/unread')
                .then(res => res.json())
                .then(data => {
                    if(data.count !== undefined) {
                        // Update Badge Counter
                        const badge = document.getElementById('notificationBadgeCounter');
                        if(data.count > 0) {
                            badge.style.display = 'inline-block';
                            badge.innerText = data.count > 99 ? '99+' : data.count;
                        } else {
                            badge.style.display = 'none';
                        }

                        // Update Dropdown List
                        const menu = document.getElementById('notificationDropdownMenu');
                        const emptyState = document.getElementById('notificationEmptyState');
                        
                        if(!menu) return;

                        // Hapus list notifikasi lama dari DOM (kecuali header dan footer)
                        document.querySelectorAll('.dynamic-notification-item').forEach(el => el.remove());

                        if(data.notifications && data.notifications.length > 0) {
                            if(emptyState) emptyState.style.display = 'none';
                            
                            data.notifications.reverse().forEach(notif => {
                                // Cek jika ini notifikasi baru yang belum pernah di-fetch
                                if(!fetchedNotificationIds.includes(notif.id)) {
                                    fetchedNotificationIds.push(notif.id);
                                    // Munculkan Toast HANYA jika ini bukan load halaman pertama
                                    if(!isInitialLoad) {
                                        showNotificationToast(notif.data);
                                    }
                                }

                                // Tentukan link dinamis
                                const linkUrl = notif.data.url || (notif.data.order_id ? '/orders/' + notif.data.order_id : '/notifications');

                                // Render ke dropdown
                                const li = document.createElement('li');
                                li.className = 'dynamic-notification-item';
                                li.innerHTML = `
                                    <a class="dropdown-item py-3 border-bottom bg-light" href="${linkUrl}">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 bg-white p-2 rounded-circle shadow-sm me-3 ${notif.data.color || 'text-primary'}">
                                                <i class="fas ${notif.data.icon || 'fa-bell'}"></i>
                                            </div>
                                            <div>
                                                <p class="mb-1 fw-bold text-dark text-wrap" style="font-size: 0.9rem;">${notif.data.title || 'Notifikasi'}</p>
                                                <p class="mb-0 text-muted text-wrap" style="font-size: 0.8rem;">${notif.data.message}</p>
                                            </div>
                                        </div>
                                    </a>
                                `;
                                // Insert setelah header
                                if (menu.children.length > 1) {
                                    menu.insertBefore(li, menu.children[1]);
                                } else {
                                    menu.appendChild(li);
                                }
                            });
                        } else {
                            if(emptyState) emptyState.style.display = 'block';
                        }
                        
                        // Setelah fetch pertama selesai, matikan flag awal
                        if (isInitialLoad) {
                            isInitialLoad = false;
                        }
                    }
                })
                .catch(err => console.error('Fetch Notification Error:', err))
                .finally(() => isFetchingNotifications = false);
        }

    function showNotificationToast(data) {
        if (!data || !data.message) return;
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-start',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'info',
            title: data.title || 'Notifikasi Baru',
            text: data.message
        });
    }

    function markNotificationsAsRead() {
        const badge = document.getElementById('notificationBadgeCounter');
        if(badge && badge.style.display !== 'none') {
            fetch('/api/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                badge.style.display = 'none';
            }).catch(e => console.log(e));
        }
    }

    // Jalankan polling setiap 10 detik
    @auth
    fetchNotifications();
    setInterval(fetchNotifications, 10000);
    @endauth

</script>
</body>
</html>