@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')

<style>
    .wishlist-bg {
        background-color: #f8f9fa;
        min-height: calc(100vh - 300px);
    }
    .wishlist-card {
        border: none;
        border-radius: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        background: #ffffff;
        overflow: hidden;
    }
    .wishlist-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(233, 30, 99, 0.15) !important;
    }
    .btn-gradient-pink {
        background: linear-gradient(45deg, #FF6699, #E91E63);
        background-size: 200% auto;
        color: white;
        transition: 0.5s;
        border: none;
    }
    .btn-gradient-pink:hover {
        background-position: right center;
        color: white;
        box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
    }
    .btn-remove-wishlist {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.9);
        color: #dc3545;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-remove-wishlist:hover {
        background: #dc3545;
        color: white;
    }
</style>

<!-- HERO BANNER -->
<section class="position-relative py-5" style="background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%); overflow: hidden;">
    <!-- Dekorasi Bulat -->
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 300px; height: 300px; top: -100px; left: -50px;"></div>
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 200px; height: 200px; bottom: -50px; right: 5%;"></div>
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 100px; height: 100px; top: 20%; left: 40%;"></div>
    
    <div class="container position-relative z-index-1 text-center py-4">
        <span class="badge bg-white text-danger px-4 py-2 fs-6 rounded-pill mb-3 shadow-sm" style="letter-spacing: 1px;">
            <i class="fas fa-heart me-2"></i> DAFTAR FAVORIT
        </span>
        <h1 class="display-5 fw-bold text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
            Wishlist Saya ✨
        </h1>
        <p class="fs-5 text-white opacity-75 fw-light mx-auto" style="max-width: 600px;">
            Simpan semua produk kecantikan impianmu di sini sebelum kamu melakukan checkout.
        </p>
    </div>
</section>

<div class="wishlist-bg pb-5 pt-4">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3 flex-wrap gap-3">
            <h3 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="fas fa-box-open text-pink me-2"></i> Koleksi Tersimpan
                <span class="badge bg-danger ms-3 rounded-pill fs-6" style="background: linear-gradient(45deg, #FF6699, #E91E63) !important;">{{ $wishlists->count() }} Produk</span>
            </h3>
            
            <form action="{{ route('wishlist.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <input type="text" name="search" class="form-control rounded-pill pe-4 ps-3 shadow-sm" style="border: 1.5px solid #FF6699; font-size: 0.9rem; min-width: 200px;" placeholder="Cari di wishlist..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-link position-absolute p-0 text-pink shadow-none" style="top: 50%; right: 15px; transform: translateY(-50%); text-decoration: none;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select name="sort" class="form-select rounded-pill px-3 shadow-sm" style="border: 1.5px solid #FF6699; font-size: 0.9rem; min-width: 150px; cursor: pointer;" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>✨ Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>⏳ Terlama</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💸 Termurah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💎 Termahal</option>
                </select>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            @forelse($wishlists as $wishlist)
            <div class="col-md-4 col-lg-3">
                <div class="card wishlist-card shadow-sm h-100 position-relative">
                    
                    <!-- Form Hapus -->
                    <form action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-remove-wishlist" onclick="return confirm('Hapus produk ini dari wishlist?')" title="Hapus dari Wishlist">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>

                    <a href="{{ route('products.show', $wishlist->product->slug ?? $wishlist->product->id) }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="{{ asset('storage/' . ($wishlist->product->image ?? 'default.png')) }}" class="object-fit-cover w-100 h-100" alt="{{ $wishlist->product->name }}">
                        </div>
                        
                        <div class="card-body d-flex flex-column p-4">
                            <h6 class="fw-bold text-truncate mb-2">{{ $wishlist->product->name }}</h6>
                            <h5 class="fw-bold text-danger mb-4">Rp{{ number_format($wishlist->product->price, 0, ',', '.') }}</h5>
                            
                            <div class="mt-auto">
                                <!-- Form Tambah ke Keranjang -->
                                <form action="{{ route('cart.store') }}" method="POST" class="d-grid" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $wishlist->product_id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="btn btn-gradient-pink rounded-pill py-2 fw-bold w-100">
                                        <i class="fas fa-shopping-cart me-2"></i> Tambah Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white p-5 rounded-4 shadow-sm mx-auto" style="max-width: 500px;">
                    <i class="fas fa-heart-broken fa-5x text-pink mb-4 opacity-50"></i>
                    <h4 class="fw-bold text-dark">Wishlist masih kosong!</h4>
                    <p class="text-muted mb-4">Kamu belum menambahkan produk apapun ke daftar keinginanmu. Yuk jelajahi koleksi kami dan temukan produk impianmu sekarang!</p>
                    <a href="{{ route('products') }}" class="btn btn-gradient-pink rounded-pill px-4 py-2 fw-bold text-white text-decoration-none shadow">
                        Mulai Belanja Sekarang
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
