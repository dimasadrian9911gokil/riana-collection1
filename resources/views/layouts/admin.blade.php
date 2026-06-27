<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Riana Collection')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-bg: #d81b60; /* Pink solid */
            --sidebar-bg-gradient: linear-gradient(180deg, #d81b60 0%, #ad1457 100%);
            --sidebar-color: #fce4ec;
            --sidebar-active-bg: #880e4f;
            --sidebar-active-color: #ffffff;
            --topbar-bg: #ffffff;
            --content-bg: #fdf5f7;
            --primary-color: #ff80ab;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            overflow-x: hidden;
        }
        
        /* SIDEBAR */
        #sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: var(--sidebar-bg-gradient);
            color: var(--sidebar-color);
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(216,27,96,0.2);
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.1);
            text-align: center;
        }
        #sidebar .sidebar-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
            font-size: 1.2rem;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.95rem;
            display: block;
            color: var(--sidebar-color);
            text-decoration: none;
            transition: 0.3s;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: var(--sidebar-active-color);
            background: var(--sidebar-active-bg);
            border-left: 4px solid var(--primary-color);
        }
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* MAIN CONTENT */
        #content {
            width: calc(100% - 250px);
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* TOPBAR */
        .topbar {
            background: var(--topbar-bg);
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-sidebar-toggle {
            background: transparent;
            border: none;
            font-size: 1.2rem;
            color: #555;
            cursor: pointer;
        }
        
        /* CARD STYLES */
        .admin-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: transform 0.2s;
        }
        .admin-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }
        
        /* Responsiveness */
        @media (max-width: 768px) {
            #sidebar { margin-left: -250px; }
            #sidebar.active { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
            #content.active { margin-left: 250px; }
        }
    </style>
</head>
<body>

    <div class="d-flex">
        <!-- SIDEBAR -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4><i class="fas fa-crown me-2 text-pink"></i>RC Admin</h4>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="fas fa-boxes me-2"></i> Manajemen Produk
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'fw-bold' : 'opacity-75' }}" href="{{ route('admin.categories.index') }}" style="font-size: 0.9rem;">
                        <i class="fas fa-angle-right me-2"></i> Kategori
                    </a>
                </li>
                <li class="nav-item ps-3 mb-2">
                    <a class="nav-link text-white {{ request()->routeIs('admin.brands.*') ? 'fw-bold' : 'opacity-75' }}" href="{{ route('admin.brands.index') }}" style="font-size: 0.9rem;">
                        <i class="fas fa-angle-right me-2"></i> Brand
                    </a>
                </li>
                <li class="nav-item ps-3 mb-2">
                    <a class="nav-link text-white {{ request()->routeIs('admin.flash_sales.*') ? 'fw-bold text-warning' : 'opacity-75' }}" href="{{ route('admin.flash_sales.index') }}" style="font-size: 0.9rem;">
                        <i class="fas fa-bolt me-2"></i> Flash Sale
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart me-2"></i> Pesanan Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.shipping_areas.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('admin.shipping_areas.index') }}">
                        <i class="fas fa-truck-fast me-2"></i> Ongkir Desa
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.vouchers.index') }}"><i class="fas fa-ticket-alt"></i> Manajemen Voucher</a>
                </li>
                <li class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.customers.index') }}"><i class="fas fa-users"></i> Data Pelanggan</a>
                </li>
                <li class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}"><i class="fas fa-chart-line"></i> Laporan Penjualan</a>
                </li>
            </ul>
        </nav>

        <!-- PAGE CONTENT -->
        <div id="content">
            <!-- TOPBAR -->
            <div class="topbar">
                <div>
                    <button type="button" id="sidebarCollapse" class="btn-sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="ms-3 fw-bold text-muted">Administrator Panel</span>
                </div>
                
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1a233a&color=fff' }}" alt="mdo" width="32" height="32" class="rounded-circle me-2">
                        <strong>{{ Auth::user()->name }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                        <li class="p-3">
                            <strong>{{ Auth::user()->name }}</strong><br>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="fas fa-user-cog me-2 text-secondary"></i> Pengaturan Akun</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- MAIN YIELD -->
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 untuk Notifikasi Real-time -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif
        
        @if($errors->any())
            Toast.fire({
                icon: 'error',
                title: "{{ $errors->first() }}"
            });
        @endif
    </script>
    
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Flatpickr untuk UI kalender yang lebih premium
        if(typeof flatpickr !== 'undefined') {
            flatpickr("input[type='datetime-local']", {
                enableTime: true,
                dateFormat: "Y-m-d\\TH:i",
                time_24hr: true,
                allowInput: true
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
