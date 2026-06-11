@extends('layouts.app')

@section('title', $product->name . ' - Riana Collection')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3 bg-light" style="border-bottom: 5px solid #dc3545;">
                <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 450px; object-fit: cover;" alt="{{ $product->name }}">
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2">
                <img src="{{ asset('storage/' . $product->image) }}" class="rounded border thumbnail-img active" style="width: 80px; height: 80px; object-fit: cover;" onclick="changeImage(this.src, this)">
                
                @foreach($product->images as $img)
                <img src="{{ asset('storage/' . $img->image_path) }}" class="rounded border thumbnail-img" style="width: 80px; height: 80px; object-fit: cover;" onclick="changeImage(this.src, this)">
                @endforeach
            </div>
        </div>

        <div class="col-md-7 ps-md-5">
            <span class="badge bg-danger mb-2 px-3 py-1">Official Store</span>
            <h1 class="fw-bold display-6 mb-3">{{ $product->name }}</h1>
            
            <div class="d-flex align-items-center mb-4 text-warning">
                ★★★★★ <span class="text-dark ms-2 fw-bold">4.9</span>
                <span class="text-muted mx-3">|</span>
                <span class="text-muted">18.5RB Terjual</span>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border-start border-5 border-danger">
                <h2 class="text-danger fw-bold mb-0" id="display-price">
                    Rp{{ number_format($product->final_price, 0, ',', '.') }}
                </h2>
                @if($product->is_flash_sale)
                    <del class="text-muted fs-5" id="original-price">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </del>
                @endif
            </div>

            <div class="mb-4">
                <label class="fw-bold mb-3 d-block">Pilih Variasi:</label>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($product->variants as $variant)
                        <input type="radio" class="btn-check" name="variant" id="var_{{$variant->id}}" 
                               data-price="{{ $variant->price_modifier + $product->final_price }}" 
                               data-stock="{{ $variant->stock }}" 
                               autocomplete="off" onchange="updateDetails(this)">
                        <label class="btn btn-outline-secondary px-4 py-2" for="var_{{$variant->id}}">{{ $variant->name }}</label>
                    @empty
                        <p class="text-muted fst-italic">Variasi belum tersedia.</p>
                    @endforelse
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center gap-3">
                <label class="fw-bold">Jumlah:</label>
                <div class="input-group" style="width: 140px;">
                    <button class="btn btn-outline-secondary" onclick="changeQty(-1)" type="button">-</button>
                    <input type="text" id="qty" class="form-control text-center" value="1" readonly>
                    <button class="btn btn-outline-secondary" onclick="changeQty(1)" type="button">+</button>
                </div>
                <span class="text-muted small">Tersisa <span id="stock-count">{{ $product->stock }}</span> stok</span>
            </div>

            <div class="d-flex gap-3">
                <button class="btn btn-outline-danger btn-lg px-4"><i class="fas fa-cart-plus me-2"></i>Masukkan Keranjang</button>
                <button class="btn btn-danger btn-lg px-5 shadow-sm">Beli Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script>
    function changeImage(src, element) { 
        document.getElementById('mainImage').src = src; 
        document.querySelectorAll('.thumbnail-img').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }
    
    function changeQty(val) {
        let qtyInput = document.getElementById('qty');
        let current = parseInt(qtyInput.value) || 1;
        let newValue = current + val;
        if (newValue >= 1) qtyInput.value = newValue;
    }

    function updateDetails(el) {
        const price = parseInt(el.getAttribute('data-price'));
        const stock = el.getAttribute('data-stock');

        const formattedPrice = new Intl.NumberFormat('id-ID', { 
            style: 'currency', currency: 'IDR', maximumFractionDigits: 0 
        }).format(price);
        
        document.getElementById('display-price').innerText = formattedPrice;
        document.getElementById('stock-count').innerText = stock;
        
        const originalPrice = document.getElementById('original-price');
        if (originalPrice) originalPrice.style.display = 'none';
    }
</script>

<style>
    .thumbnail-img { transition: 0.3s; opacity: 0.7; border: 2px solid transparent; cursor: pointer; }
    .thumbnail-img:hover, .thumbnail-img.active { opacity: 1; border: 2px solid #dc3545 !important; }
    .btn-check:checked + .btn-outline-secondary { background-color: #dc3545; color: white; border-color: #dc3545; }
</style>
@endsection