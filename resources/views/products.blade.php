@extends('layouts.app')

@section('title', 'Produk - Riana Collection')

@section('content')

<div class="container py-4">

    <!-- HERO HEADER -->
    <div class="p-5 rounded-4 text-white mb-4 shadow-sm" style="background:linear-gradient(135deg,#ff5c8d,#ff91b0);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold display-5">Produk Kecantikan Terbaik ✨</h1>
                <p class="fs-5 mb-0">Temukan skincare, makeup, parfum, dan bodycare favoritmu.</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="#produk" class="btn btn-light btn-lg shadow-sm">Belanja Sekarang</a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- SIDEBAR FILTER -->
        <div class="col-lg-3">
            @include('components.product-filter')
        </div>

        <!-- MAIN PRODUCT GRID -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0" id="produk">Semua Produk</h2>
                
                <!-- SORTING FORM -->
                <form action="{{ route('products') }}" method="GET" class="d-flex align-items-center">
                    {{-- Pertahankan filter saat sortir --}}
                    @foreach(request()->except(['sort', 'page']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)<input type="hidden" name="{{ $key }}[]" value="{{ $v }}">@endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <select name="sort" class="form-select shadow-sm" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Termurah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </form>
            </div>

            <div class="row">
                @forelse($products as $product)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 transition-card">
                        <div class="position-relative overflow-hidden bg-light">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}" 
                                 style="height: 250px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-product.jpg') }}';">
                            
                            @if($product->is_flash_sale)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-3 py-2 shadow-sm">FLASH SALE</span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted text-uppercase fw-bold">{{ $product->category->name ?? 'Produk' }}</small>
                            <h6 class="fw-bold mt-2 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                            
                            <div class="mt-auto pt-2">
                                <span class="text-danger fw-bold fs-5">Rp{{ number_format($product->final_price ?? $product->price, 0, ',', '.') }}</span>
                                @if($product->is_flash_sale)
                                <br><small class="text-decoration-line-through text-muted">Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                                @endif
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-secondary rounded-pill">
                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                </a>
                                <!-- FORM TAMBAH KERANJANG (POST METHOD) -->
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-danger w-100 rounded-pill">
                                        <i class="fas fa-shopping-cart me-1"></i> Tambah Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 border rounded bg-light">
                        <h5 class="text-muted">Oops! Produk tidak ditemukan.</h5>
                        <p class="text-muted mb-3">Coba gunakan filter lain atau cari kata kunci yang berbeda.</p>
                        <a href="{{ route('products') }}" class="btn btn-danger">Reset Semua Filter</a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .transition-card { transition: transform 0.2s; }
    .transition-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>

@endsection