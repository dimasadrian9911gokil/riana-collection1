@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')

<!-- HERO BANNER -->
<section class="position-relative py-5 mb-5" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); overflow: hidden;">
    <!-- Dekorasi Bulat -->
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 250px; height: 250px; top: -50px; left: -50px;"></div>
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 150px; height: 150px; bottom: 20px; right: 10%;"></div>
    
    <div class="container position-relative z-index-1 text-center py-4 py-md-5">
        <span class="badge bg-white text-primary px-4 py-2 fs-6 rounded-pill mb-3 shadow-sm" style="letter-spacing: 1px;">
            <i class="fas fa-list-ul me-2"></i> KATEGORI LENGKAP
        </span>
        <h1 class="display-5 display-md-3 fw-bold text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
            Kategori Beauty Store ✨
        </h1>
        <p class="fs-5 fs-md-4 text-white opacity-75 fw-light mx-auto" style="max-width: 600px;">
            Temukan berbagai macam pilihan skincare, makeup, parfum, dan bodycare untuk sempurnakan penampilanmu.
        </p>
    </div>
</section>

<div class="container pb-5">

    <!-- SEMUA KATEGORI (DINAMIS DARI DATABASE) -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 border-bottom pb-3 gap-3">
        <div>
            <h3 class="fw-bold mb-0"><i class="fas fa-th-large text-pink me-2"></i>Pilihan Kategori</h3>
            <p class="text-muted mb-0">Temukan produk sesuai kebutuhanmu.</p>
        </div>
        
        <!-- Search & Filter Form -->
        <form action="{{ route('categories') }}" method="GET" class="d-flex gap-2" style="max-width: 400px; width: 100%;">
            <div class="input-group flex-grow-1 shadow-sm">
                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 25px 0 0 25px;"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kategori..." value="{{ request('search') }}" style="border-radius: 0 25px 25px 0;">
            </div>
            <select name="sort" class="form-select shadow-sm" onchange="this.form.submit()" style="width: auto; min-width: 150px; border-radius: 25px;">
                <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>A - Z</option>
                <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Z - A</option>
                <option value="products" {{ request('sort') == 'products' ? 'selected' : '' }}>Produk Terbanyak</option>
            </select>
        </form>
    </div>

    <div class="row g-4 mb-5">
        @php
        // Pemetaan Ikon berdasarkan nama kategori (opsional, jika tidak ada pakai default)
        $iconMap = [
            'skincare' => '🧴',
            'makeup' => '💄',
            'parfum' => '🌸',
            'body care' => '🛁',
            'sunscreen' => '☀️',
            'gift set' => '🎁',
            'hair care' => '💇‍♀️',
            'aksesoris' => '🎀'
        ];
        
        $gradients = [
            'linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%)',
            'linear-gradient(120deg, #fccb90 0%, #d57eeb 100%)',
            'linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%)',
            'linear-gradient(120deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%)',
            'linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%)'
        ];
        @endphp

        @forelse($categories as $index => $category)
        @php
            $key = strtolower($category->name);
            $icon = $iconMap[$key] ?? '✨';
            $bg = $gradients[$index % count($gradients)];
        @endphp
        <div class="col-lg-3 col-md-4 col-6">
            <a href="{{ route('products', ['category' => $category->slug]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center p-3 p-md-4 h-100 rounded-4 category-card overflow-hidden position-relative">
                    <!-- Garis aksen -->
                    <div class="position-absolute top-0 start-0 w-100" style="height: 5px; background: {{ $bg }};"></div>
                    
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 70px; height: 70px; background: #f8f9fa; border-radius: 20px; font-size: 2.5rem; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        {{ $icon }}
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $category->name }}</h5>
                    <p class="text-muted small mb-0">{{ $category->products_count ?? 0 }} Produk</p>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada kategori yang ditambahkan.</p>
        </div>
        @endforelse
    </div>

    <!-- TIPE KULIT (FILTER BY TIPE KULIT) -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mt-5">
        <div class="row g-0">
            <div class="col-md-4 text-white p-4 p-md-5 d-flex flex-column justify-content-center text-center text-md-start" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3 class="fw-bold mb-3"><i class="fas fa-search me-2"></i>Cari Berdasarkan Tipe Kulit</h3>
                <p class="mb-0 opacity-75">Tidak semua skincare cocok untuk semua orang. Temukan produk yang paling sesuai dengan jenis kulitmu di sini.</p>
            </div>
            <div class="col-md-8 p-4 p-md-5 bg-white">
                <div class="row text-center g-3 g-md-4">
                    @foreach(['Kering' => ['icon' => '💧', 'color' => '#e3f2fd', 'text' => '#1976d2'], 
                              'Berminyak' => ['icon' => '✨', 'color' => '#fff8e1', 'text' => '#fbc02d'], 
                              'Sensitif' => ['icon' => '🌿', 'color' => '#e8f5e9', 'text' => '#388e3c'], 
                              'Kombinasi' => ['icon' => '🌸', 'color' => '#fce4ec', 'text' => '#d81b60']] as $name => $data)
                    <div class="col-md-3 col-6">
                        <a href="{{ route('products', ['skin_type' => strtolower($name)]) }}" class="text-decoration-none">
                            <div class="card border-0 p-3 h-100 rounded-4 skin-type-card" style="background-color: {{ $data['color'] }}; color: {{ $data['text'] }};">
                                <div class="fs-1 mb-2">{{ $data['icon'] }}</div>
                                <h6 class="fw-bold mb-0">Kulit {{ $name }}</h6>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- PROMO BANNER BOTTOM -->
    <div class="card border-0 rounded-4 mt-5 overflow-hidden text-center text-white" style="background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);">
        <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold mb-3"><i class="fas fa-bolt text-warning me-2"></i> Promo Brand Minggu Ini</h2>
            <p class="fs-5 opacity-75 mb-4">Diskon hingga 70% untuk produk pilihan dari brand favorit.</p>
            <a href="{{ route('flashsale') }}" class="btn btn-light btn-lg rounded-pill fw-bold text-danger px-5 shadow">
                Ke Halaman Flash Sale
            </a>
        </div>
    </div>

</div>

<style>
    .text-pink { color: #FF6699; }
    .category-card {
        transition: all 0.3s ease;
    }
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
    }
    .skin-type-card {
        transition: all 0.3s ease;
    }
    .skin-type-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>

@endsection