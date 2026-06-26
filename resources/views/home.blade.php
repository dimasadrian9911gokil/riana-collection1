@extends('layouts.app')

@section('title', 'Riana Collection')

@section('content')

<!-- HERO SECTION CAROUSEL -->
<section class="container my-4">
    <div id="heroCarousel" class="carousel slide shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            
            <!-- Slide 1 -->
            <div class="carousel-item active" style="background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 100%);">
                <div class="row align-items-center p-5" style="min-height: 400px;">
                    <div class="col-md-6 text-center text-md-start ps-md-5 position-relative z-index-1">
                        <span class="badge bg-white text-danger mb-3 px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-star text-warning"></i> Koleksi Terbaru</span>
                        <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">Beauty Starts Here ✨</h1>
                        <p class="fs-5 text-white mb-4 opacity-75">Temukan rangkaian perawatan kulit, makeup, dan parfum terbaik untuk memancarkan pesonamu.</p>
                        <a href="{{ route('products') }}" class="btn btn-light btn-lg px-5 rounded-pill fw-bold shadow-sm" style="color: #FF6699;">Belanja Sekarang</a>
                    </div>
                    <!-- Optional Image Placeholder (Hidden on mobile) -->
                    <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center opacity-75">
                        <i class="fas fa-spa text-white" style="font-size: 12rem; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));"></i>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);">
                <div class="row align-items-center p-5" style="min-height: 400px;">
                    <div class="col-md-6 text-center text-md-start ps-md-5 position-relative z-index-1">
                        <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-bolt text-danger"></i> Diskon Kilat</span>
                        <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">Flash Sale Up To 70%</h1>
                        <p class="fs-5 text-white mb-4 opacity-75">Jangan lewatkan penawaran eksklusif minggu ini. Stok sangat terbatas, borong sekarang juga!</p>
                        <a href="{{ route('flashsale') }}" class="btn btn-warning btn-lg px-5 rounded-pill fw-bold shadow-sm text-dark">Lihat Promo</a>
                    </div>
                    <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center opacity-75">
                        <i class="fas fa-tags text-white" style="font-size: 12rem; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));"></i>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="row align-items-center p-5" style="min-height: 400px;">
                    <div class="col-md-6 text-center text-md-start ps-md-5 position-relative z-index-1">
                        <span class="badge bg-success text-white mb-3 px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-leaf text-white"></i> Rekomendasi Ahli</span>
                        <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">Kenali Tipe Kulitmu</h1>
                        <p class="fs-5 text-white mb-4 opacity-75">Gunakan produk yang tepat untuk mendapatkan hasil maksimal. Kami punya solusi untuk semua jenis kulit.</p>
                        <a href="{{ route('categories') }}" class="btn btn-success btn-lg px-5 rounded-pill fw-bold shadow-sm">Cari Solusi</a>
                    </div>
                    <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center opacity-75">
                        <i class="fas fa-hand-sparkles text-white" style="font-size: 12rem; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));"></i>
                    </div>
                </div>
            </div>

        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- KATEGORI POPULER -->
<style>
    .category-card { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .category-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important; }
</style>
<section class="container my-5">
    <h2 class="mb-4 fw-bold text-center">Kategori Populer</h2>
    <div class="row text-center justify-content-center">
        @foreach([
            'Makeup' => ['icon' => '💄', 'slug' => 'makeup', 'bg' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)'], 
            'Skincare' => ['icon' => '🧴', 'slug' => 'skincare', 'bg' => 'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)'], 
            'Parfum' => ['icon' => '🌸', 'slug' => 'parfum', 'bg' => 'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)'], 
            'Gift Set' => ['icon' => '🎁', 'slug' => 'gift-set', 'bg' => 'linear-gradient(135deg, #f6d365 0%, #fda085 100%)'], 
            'Body Care' => ['icon' => '🛁', 'slug' => 'body-care', 'bg' => 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)'], 
            'Sunscreen' => ['icon' => '☀️', 'slug' => 'sunscreen', 'bg' => 'linear-gradient(135deg, #ff0844 0%, #ffb199 100%)']
        ] as $name => $data)
        <div class="col-md-2 col-6 mb-4">
            <a href="{{ $data['slug'] === 'all' ? route('products') : route('products', ['category' => $data['slug']]) }}" class="text-decoration-none">
                <div class="card p-4 shadow-sm h-100 border-0 rounded-4 category-card" style="background: {{ $data['bg'] }};">
                    <div style="font-size: 3rem; filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2));">{{ $data['icon'] }}</div>
                    <h6 class="mt-3 fw-bold text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.3); font-size: 1.1rem;">{{ $name }}</h6>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>

<!-- KENALI TIPE KULIT & SOLUSI -->
<section class="container my-5">
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">Kenali Tipe Kulitmu</h2>
            <p class="text-muted">Pahami jenis kulitmu untuk menemukan produk yang paling tepat.</p>
        </div>
        @php
        $skinTypes = [
            ['name' => 'Kulit Kering', 'icon' => '💧', 'color' => '#e3f2fd', 'border' => '#90caf9', 'desc' => 'Terasa kasar, kusam, dan rentan mengelupas. Butuh hidrasi ekstra.'],
            ['name' => 'Kulit Berminyak', 'icon' => '✨', 'color' => '#fff8e1', 'border' => '#ffe082', 'desc' => 'Mengkilap terutama di area T-zone, pori-pori besar. Butuh oil-control.'],
            ['name' => 'Kulit Sensitif', 'icon' => '🌸', 'color' => '#fce4ec', 'border' => '#f48fb1', 'desc' => 'Mudah memerah, gatal, atau iritasi. Hindari bahan kimia keras.'],
            ['name' => 'Kulit Kombinasi', 'icon' => '☁️', 'color' => '#f3e5f5', 'border' => '#ce93d8', 'desc' => 'Berminyak di T-zone, normal/kering di area pipi. Perawatan seimbang.']
        ];
        @endphp
        @foreach($skinTypes as $type)
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 border-0 rounded-4 skin-card" style="background-color: {{ $type['color'] }}; border-bottom: 4px solid {{ $type['border'] }} !important; transition: 0.3s; cursor: pointer;">
                <div class="card-body text-center p-4">
                    <div class="bg-white rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm mb-3" style="width: 70px; height: 70px; font-size: 2.5rem;">
                        {{ $type['icon'] }}
                    </div>
                    <h5 class="fw-bold">{{ $type['name'] }}</h5>
                    <p class="text-muted small mb-0">{{ $type['desc'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12 text-center mb-4 mt-3">
            <h2 class="fw-bold">Solusi Masalah Kulit</h2>
            <p class="text-muted">Temukan bahan aktif (Hero Ingredients) terbaik untuk mengatasi masalah kulitmu.</p>
        </div>
        @php
        $solutions = [
            ['icon' => '😖', 'title' => 'Jerawat & Beruntusan', 'ing' => 'Salicylic Acid (BHA)', 'desc' => 'Membersihkan pori tersumbat, mengurangi produksi minyak, dan melawan bakteri penyebab jerawat.', 'bg' => '#ffebee'],
            ['icon' => '🌑', 'title' => 'Flek Hitam & Bekas Jerawat', 'ing' => 'Vitamin C / Alpha Arbutin', 'desc' => 'Mencerahkan hiperpigmentasi, meratakan warna kulit, dan kaya akan antioksidan.', 'bg' => '#fff3e0'],
            ['icon' => '🥺', 'title' => 'Kulit Kusam & Tekstur', 'ing' => 'Niacinamide', 'desc' => 'Mencerahkan wajah secara keseluruhan, memperbaiki tekstur, dan menyamarkan pori-pori.', 'bg' => '#e8f5e9'],
            ['icon' => '💧', 'title' => 'Skin Barrier Rusak', 'ing' => 'Ceramide / Panthenol', 'desc' => 'Memperbaiki lapisan pelindung kulit, mengunci kelembapan, dan menenangkan kulit iritasi.', 'bg' => '#e0f7fa']
        ];
        @endphp
        @foreach($solutions as $sol)
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden hero-card">
                <div class="row g-0 h-100">
                    <div class="col-md-4 d-flex justify-content-center align-items-center p-4" style="background-color: {{ $sol['bg'] }};">
                        <div class="text-center">
                            <div style="font-size: 3rem;">{{ $sol['icon'] }}</div>
                            <span class="badge bg-dark mt-2 text-wrap">{{ $sol['title'] }}</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-4 d-flex flex-column justify-content-center h-100">
                            <h5 class="fw-bold text-pink mb-2"><i class="fas fa-flask me-2"></i>{{ $sol['ing'] }}</h5>
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">{{ $sol['desc'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- FLASH SALE -->
<section class="py-5 rainbow-flash-sale">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 text-white flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3 gap-md-4 flex-wrap">
                <div class="flash-sale-badge">
                    <small class="d-block text-uppercase" style="letter-spacing: 1px;">Online Special</small>
                    <div class="fs-2 fw-bold text-uppercase"><i class="fas fa-bolt"></i> Flash Sale</div>
                </div>
                
                <!-- COUNTDOWN TIMER -->
                <div class="d-flex gap-2 text-dark font-monospace fw-bold mt-2 mt-md-0" id="flashSaleTimer">
                    <div class="bg-white rounded-3 p-2 shadow text-center" style="min-width: 55px;">
                        <span id="fs-hours" class="fs-3 lh-1">00</span><br><small class="text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 1px;">JAM</small>
                    </div>
                    <div class="fs-3 text-white align-self-center">:</div>
                    <div class="bg-white rounded-3 p-2 shadow text-center" style="min-width: 55px;">
                        <span id="fs-minutes" class="fs-3 lh-1">00</span><br><small class="text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 1px;">MNT</small>
                    </div>
                    <div class="fs-3 text-white align-self-center">:</div>
                    <div class="bg-white rounded-3 p-2 shadow text-center" style="min-width: 55px;">
                        <span id="fs-seconds" class="fs-3 lh-1">00</span><br><small class="text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 1px;">DTK</small>
                    </div>
                </div>
            </div>

            <a href="{{ route('flashsale') }}" class="btn btn-light px-4 rounded-pill fw-bold shadow-sm" style="color: #FF6699;">See all</a>
        </div>
        <div class="row">
            @foreach($flashSaleItems as $item)
            @php $product = $item->product; @endphp
            <div class="col-md-3 mb-4">
                <div class="card h-100 border rounded-3 overflow-hidden" style="transition: 0.3s;">
                    <!-- Badge Diskon -->
                    <span class="position-absolute badge bg-danger" style="top: 10px; left: 10px; z-index: 2; padding: 6px 10px; border-radius: 4px;">
                        -{{ round((($product->price - $item->discount_price)/$product->price)*100) }}%
                    </span>
                    
                    <!-- Area Gambar (Abu-abu) -->
                    <div style="height: 220px; background-color: #e0e0e0;" class="d-flex align-items-center justify-content-center p-4 position-relative">
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                    </div>
                    
                    <div class="card-body d-flex flex-column p-3">
                        <!-- Kategori & Judul -->
                        <span class="text-muted mb-1" style="font-size: 0.8rem;">{{ $product->category->name ?? 'Skincare' }}</span>
                        <h6 class="fw-bold mb-2 text-dark text-truncate">{{ $product->name }}</h6>
                        
                        <!-- Rating Stars -->
                        <div class="text-warning mb-2" style="font-size: 0.85rem;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        
                        <!-- Harga -->
                        <div class="mb-2">
                            <h5 class="text-danger fw-bold mb-0">Rp{{ number_format($item->discount_price, 0, ',', '.') }}</h5>
                            <small class="text-muted text-decoration-line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                        </div>
                        
                        <!-- Progress Bar Stok -->
                        <div class="mt-auto pt-3">
                            <p class="text-danger mb-1" style="font-size: 0.8rem;">Stok tersisa {{ $product->stock }}</p>
                            <div class="progress mb-3" style="height: 8px; border-radius: 10px; background-color: #f1f1f1;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min(100, max(5, ($product->stock / max($product->stock + 10, 100)) * 100)) }}%; border-radius: 10px;"></div>
                            </div>
                            
                            <!-- Tombol Aksi -->
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-2 rounded-pill" style="border: 2px solid #FF6699; color: #FF6699; background: transparent; transition: 0.3s;" onmouseover="this.style.backgroundColor='#FF6699'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#FF6699';">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                <form action="{{ route('cart.store') }}" method="POST" class="w-100 m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="buy_now" value="1">
                                    <button type="submit" class="btn btn-sm w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 rounded-pill" style="background-color: #FF6699; color: white; border: none;">
                                        <i class="fas fa-shopping-bag"></i> Beli Sekarang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- PRODUK TERBARU -->
<section class="container my-5">
    <div class="d-flex justify-content-between mb-4">
        <h2 class="fw-bold">Produk Terbaru</h2>
        <a href="{{ route('products') }}" class="btn btn-outline-danger rounded-pill">Semua Produk</a>
    </div>
    <div class="row">
        @foreach($latestProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 product-card-aesthetic shadow-sm p-3 rounded-4">
                <div style="height: 200px;" class="d-flex align-items-center justify-content-center bg-white rounded mb-3">
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid p-2" style="max-height: 100%; object-fit: contain;">
                </div>
                <div class="card-body text-center p-0">
                    <h6 class="fw-bold text-truncate">{{ $product->name }}</h6>
                    <div class="text-warning small mb-2"><i class="fas fa-star"></i> 4.8</div>
                    <div class="text-danger fw-bold mb-3">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                        <!-- TOMBOL TAMBAH KERANJANG BARU -->
                        <form action="{{ route('cart.store') }}" method="POST" class="w-100">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                                <i class="fas fa-shopping-cart me-1"></i> Tambah Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>


<style>
.col-md-2-4 { flex: 0 0 auto; width: 20%; }
@media (max-width: 768px) { .col-md-2-4 { width: 50%; } }
.product-card-aesthetic { background-color: #fff0f5; border: 2px solid #f8bbd0 !important; transition: 0.3s; }
.product-card-aesthetic:hover { border-color: #ff6699 !important; box-shadow: 0 5px 15px rgba(255,102,153,0.2); }
.rainbow-flash-sale { background: linear-gradient(90deg, #ff9a9e, #fad0c4, #fbc2eb, #a18cd1, #ff9a9e); background-size: 300% 300%; animation: rainbowAnimation 8s ease infinite; }
@keyframes rainbowAnimation { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
.flash-sale-badge { background-color: #fbff00; color: #e83e8c; padding: 10px 20px; border-radius: 5px; font-weight: 800; transform: skewX(-5deg); display: inline-block; box-shadow: 3px 3px 0px rgba(0,0,0,0.1); }
.flash-sale-badge > div, .flash-sale-badge > small { transform: skewX(5deg); }

/* CUSTOM CARD HOVER EFFECTS */
.skin-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
.hero-card { transition: 0.3s; border: 1px solid #f0f0f0 !important; }
.hero-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; border-color: #ffb6c1 !important; }

/* BORDER GRADIENT FLASH SALE CARD */
.flash-sale-card {
    border: 3px solid transparent;
    background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #FFD700, #FF6699, #E91E63) border-box;
    transition: 0.3s;
}
.flash-sale-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(255, 102, 153, 0.4) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($activeFlashSale))
        // Set timer based on active flash sale end_time
        let countDownDate = new Date("{{ $activeFlashSale->end_time->toIso8601String() }}").getTime();
    @else
        // Default timer to end of today (23:59:59)
        let countDownDateObj = new Date();
        countDownDateObj.setHours(23, 59, 59, 0);
        let countDownDate = countDownDateObj.getTime();
    @endif
    
    let timerInterval = setInterval(function() {
        let now = new Date().getTime();
        let distance = countDownDate - now;
        
        // Reset to 0 if expired
        if (distance < 0) {
            clearInterval(timerInterval);
            distance = 0;
        }
        
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        let elHours = document.getElementById("fs-hours");
        let elMinutes = document.getElementById("fs-minutes");
        let elSeconds = document.getElementById("fs-seconds");
        
        if (elHours && elMinutes && elSeconds) {
            elHours.innerHTML = hours.toString().padStart(2, '0');
            elMinutes.innerHTML = minutes.toString().padStart(2, '0');
            elSeconds.innerHTML = seconds.toString().padStart(2, '0');
        }
    }, 1000);
});
</script>

@endsection