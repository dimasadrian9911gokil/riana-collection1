@extends('layouts.app')

@section('title', 'Flash Sale')

@section('content')

<div class="container py-5">

    <!-- HERO / BANNER -->
    <div class="p-4 p-md-5 rounded-4 text-white shadow-lg mb-5 position-relative overflow-hidden"
    style="background:linear-gradient(135deg,#FF0055,#FF6699);">
        <!-- Dekorasi Background -->
        <div class="position-absolute top-0 end-0 opacity-25" style="transform: translate(20%, -20%);">
            <i class="fas fa-bolt" style="font-size: 25rem;"></i>
        </div>

        <div class="row align-items-center position-relative z-index-1">
            <div class="col-md-8">
                <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill mb-3 shadow-sm">
                    <i class="fas fa-fire me-1"></i> Promo Terbatas!
                </span>
                <h1 class="display-5 display-md-3 fw-bolder mb-2" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    FLASH SALE
                </h1>
                <p class="fs-5 fs-md-4 mb-4 fw-light">
                    Diskon gila-gilaan hingga <strong>70%</strong> untuk produk pilihan terbaik.
                </p>
                
                <div class="d-inline-block bg-white text-dark p-3 rounded-4 shadow-sm text-center">
                    <p class="mb-1 text-muted fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">BERAKHIR DALAM</p>
                    <div id="flash-sale-countdown" class="d-flex gap-3 fw-bold fs-3" style="color: #FF0055;">
                        <div><span id="hours">00</span><small class="fs-6 d-block text-muted">Jam</small></div>:
                        <div><span id="minutes">00</span><small class="fs-6 d-block text-muted">Menit</small></div>:
                        <div><span id="seconds">00</span><small class="fs-6 d-block text-muted">Detik</small></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-center mt-4 mt-md-0">
                <div class="bg-white text-danger rounded-4 p-4 shadow-lg transform-hover" style="border: 4px dashed #FF6699;">
                    <h3 class="fw-bold mb-0 text-dark">UP TO</h3>
                    <h1 class="display-1 fw-bold" style="color: #FF0055; text-shadow: 2px 2px 0px rgba(255,0,85,0.1);">70%</h1>
                    <p class="mb-0 fs-4 fw-bold">OFF</p>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row mb-5 g-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100 rounded-4" style="background: #fff0f5;">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-white d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="fas fa-tags fs-3 text-danger"></i>
                    </div>
                    <h3 class="text-danger fw-bold">{{ $flashSaleItems->total() }}+</h3>
                    <p class="mb-0 text-muted fw-bold">Produk Promo</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100 rounded-4" style="background: #e8f5e9;">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-white d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="fas fa-percentage fs-3 text-success"></i>
                    </div>
                    <h3 class="text-success fw-bold">70%</h3>
                    <p class="mb-0 text-muted fw-bold">Diskon Maksimal</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100 rounded-4" style="background: #fff8e1;">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-white d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="fas fa-shopping-bag fs-3 text-warning"></i>
                    </div>
                    <h3 class="text-warning fw-bold">500+</h3>
                    <p class="mb-0 text-muted fw-bold">Produk Terjual</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100 rounded-4" style="background: #e3f2fd;">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-white d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fs-3 text-primary"></i>
                    </div>
                    <h3 class="text-primary fw-bold">24 Jam</h3>
                    <p class="mb-0 text-muted fw-bold">Promo Berlangsung</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR PRODUK FLASH SALE -->
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3">
        <div>
            <h3 class="fw-bold mb-0">Sedang Berlangsung</h3>
            <p class="text-muted mb-0">Segera checkout sebelum kehabisan!</p>
        </div>
        <span class="badge bg-danger fs-6 rounded-pill px-3 py-2"><i class="fas fa-fire me-1"></i> Hot Deals</span>
    </div>

    <div class="row">
        @forelse($flashSaleItems as $item)
        @php $product = $item->product; @endphp
        <div class="col-lg-3 col-md-4 col-6 mb-4">
            <div class="card h-100 border rounded-3 overflow-hidden" style="transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <!-- Badge Diskon -->
                <span class="position-absolute badge bg-danger" style="top: 10px; left: 10px; z-index: 2; padding: 6px 10px; border-radius: 4px; font-size: clamp(0.7rem, 2.5vw, 0.9rem);">
                    -{{ round((($product->price - $item->discount_price)/$product->price)*100) }}%
                </span>
                
                <!-- Area Gambar (Abu-abu) -->
                <div style="height: clamp(140px, 40vw, 220px); background-color: #e0e0e0;" class="d-flex align-items-center justify-content-center p-3 p-md-4 position-relative">
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                </div>
                
                <div class="card-body d-flex flex-column p-3">
                    <!-- Kategori & Judul -->
                    <span class="text-muted mb-1" style="font-size: 0.8rem;">{{ $product->category->name ?? 'Skincare' }}</span>
                    <h6 class="fw-bold mb-2 text-dark text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                    
                    <!-- Rating Stars -->
                    <div class="text-warning mb-2" style="font-size: 0.85rem;">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        <span class="text-muted ms-1" style="font-size: 0.75rem;">(4.8)</span>
                    </div>
                    
                    <!-- Harga -->
                    <div class="mb-2">
                        <h5 class="text-danger fw-bold mb-0" style="font-size: clamp(1rem, 4vw, 1.25rem);">Rp{{ number_format($item->discount_price, 0, ',', '.') }}</h5>
                        <small class="text-muted text-decoration-line-through" style="font-size: 0.75rem;">Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                    </div>
                    
                    <!-- Progress Bar Stok -->
                    <div class="mt-auto pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-danger fw-bold" style="font-size: 0.8rem;"><i class="fas fa-fire me-1"></i> Hampir Habis!</span>
                            <span class="text-muted" style="font-size: 0.8rem;">Sisa {{ $product->stock }}</span>
                        </div>
                        <div class="progress mb-3 shadow-sm" style="height: 10px; border-radius: 10px; background-color: #f1f1f1;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: {{ min(100, max(5, ($product->stock / max($product->stock + 10, 100)) * 100)) }}%; border-radius: 10px;"></div>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="d-flex flex-column gap-2 mt-3">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-secondary btn-sm w-100 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 6px;">
                                <i class="fas fa-eye me-1"></i> <span class="d-none d-sm-inline">Detail</span>
                            </a>
                            <form action="{{ route('cart.store') }}" method="POST" class="w-100 m-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-pink btn-sm w-100 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 6px;">
                                    <i class="fas fa-shopping-cart me-1"></i> <span class="d-none d-sm-inline">Beli Sekarang</span><span class="d-sm-none">Beli</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">Yah, belum ada produk Flash Sale saat ini.</h4>
            <p class="text-muted">Tunggu promo menarik dari kami selanjutnya!</p>
        </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-center mt-4">
        {{ $flashSaleItems->links() }}
    </div>

    <!-- MEMBER PROMO -->
    <div class="card border-0 rounded-4 mt-5 overflow-hidden" style="background: linear-gradient(90deg, #111, #333); color: white;">
        <div class="row g-0 align-items-center">
            <div class="col-md-8 p-4 p-md-5 text-center text-md-start">
                <h2 class="fw-bold text-warning mb-3">
                    <i class="fas fa-crown me-2"></i> Eksklusif Member Riana
                </h2>
                <p class="fs-6 fs-md-5 mb-0 opacity-75">
                    Gunakan kode voucher <strong>FLASHMEMBER</strong> saat checkout dan dapatkan tambahan diskon 10% khusus untuk produk bertanda Flash Sale!
                </p>
            </div>
            <div class="col-md-4 text-center p-4 d-none d-md-block">
                <i class="fas fa-gift text-warning opacity-25" style="font-size: 8rem; transform: rotate(15deg);"></i>
            </div>
        </div>
    </div>

</div>

<!-- SCRIPT COUNTDOWN -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(isset($activeFlashSale))
            let targetDate = new Date("{{ $activeFlashSale->end_time->toIso8601String() }}");
        @else
            let targetDate = new Date();
            targetDate.setHours(23, 59, 59, 0);
        @endif

        function updateCountdown() {
            let now = new Date();
            let difference = targetDate - now;

            if (difference <= 0) {
                difference = 0;
            }

            let hours = Math.floor((difference / (1000 * 60 * 60)) % 24);
            let minutes = Math.floor((difference / 1000 / 60) % 60);
            let seconds = Math.floor((difference / 1000) % 60);

            document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
            document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
            document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
</script>

@endsection