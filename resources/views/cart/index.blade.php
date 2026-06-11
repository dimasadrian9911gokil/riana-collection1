@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')

<div class="cart-bg py-5" style="background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%); min-height: 100vh;">
    <div class="container">
        <!-- Breadcrumb / Header -->
        <div class="mb-4">
            <h3 class="fw-bold text-dark"><i class="fas fa-shopping-cart text-pink me-2"></i>Keranjang Belanja</h3>
            <p class="text-muted">Pastikan barang pesananmu sudah benar sebelum checkout ya!</p>
        </div>

        <div class="row g-4">
            <!-- CART ITEMS LIST -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-pink-soft border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4" style="border-bottom-color: #f8bbd0 !important;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="selectAll" checked>
                            <label class="form-check-label fw-bold" for="selectAll">
                                Pilih Semua ({{ $cartItems->count() }} Produk)
                            </label>
                        </div>
                        <form action="{{ route('cart.destroy', 'all') }}" method="POST" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm text-danger fw-bold btn-link text-decoration-none" onclick="return confirm('Yakin ingin menghapus semua produk?')">Hapus Semua</button>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        @forelse($cartItems as $index => $item)
                        <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="row align-items-center">
                                <!-- Checkbox -->
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input item-checkbox" type="checkbox" value="{{ $item->id }}" checked>
                                    </div>
                                </div>
                                <!-- Image -->
                                <div class="col-3 col-md-2">
                                    <div class="ratio ratio-1x1 border rounded-3 overflow-hidden bg-light">
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="object-fit-cover" alt="{{ $item->product->name }}">
                                    </div>
                                </div>
                                <!-- Details -->
                                <div class="col-8 col-md-5">
                                    <span class="badge bg-pink-soft text-pink mb-2">Terlaris</span>
                                    <h6 class="fw-bold text-dark mb-1 lh-base">{{ $item->product->name }}</h6>
                                    <p class="text-muted mb-2 small">Varian: {{ $item->variant ?? 'Standard' }}</p>
                                    <h5 class="fw-bold text-price mb-0">Rp{{ number_format($item->product->price, 0, ',', '.') }}</h5>
                                </div>
                                <!-- Actions -->
                                <div class="col-12 col-md-4 mt-3 mt-md-0 d-flex flex-md-column justify-content-between align-items-end h-100">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn text-muted p-0 hover-danger" onclick="return confirm('Hapus produk ini?')">
                                            <i class="far fa-trash-alt fs-5"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center border rounded-3 mt-auto bg-white" style="width: 110px;">
                                        @csrf @method('PATCH')
                                        <button type="submit" name="action" value="decrease" class="btn btn-sm btn-link text-dark text-decoration-none px-3">-</button>
                                        <input type="text" value="{{ $item->qty }}" class="form-control form-control-sm border-0 text-center fw-bold p-0 bg-transparent" readonly>
                                        <button type="submit" name="action" value="increase" class="btn btn-sm btn-link text-pink fw-bold text-decoration-none px-3">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart" width="120" class="opacity-50 mb-4">
                            <h5 class="fw-bold text-dark">Keranjang belanjamu kosong</h5>
                            <p class="text-muted">Yuk, cari produk skincare favoritmu sekarang!</p>
                            <a href="{{ route('products') }}" class="btn btn-pink rounded-pill px-4 py-2 fw-bold mt-2 text-white">Mulai Belanja</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- SUMMARY BOX -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 100px; background: linear-gradient(to bottom right, #ffffff, #fff5f8); border: 1px solid #ffe4e1 !important; box-shadow: 0 10px 25px rgba(233, 30, 99, 0.1) !important;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                        
                        <!-- Promo Code / Voucher -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark fs-6">Makin hemat pakai promo!</span>
                            </div>
                            
                            @if($bestVoucher)
                            <div class="alert bg-pink-soft border-pink-light d-flex align-items-center p-3 mb-0 shadow-sm rounded-3">
                                <div class="bg-white rounded-circle p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-ticket-alt text-pink fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-dark mb-1 lh-1">{{ $bestVoucher->code }}</h6>
                                    <small class="text-success fw-bold">Diskon otomatis terpasang!</small>
                                </div>
                            </div>
                            @else
                            <div class="alert bg-light border d-flex align-items-center p-3 mb-0 rounded-3">
                                <i class="fas fa-info-circle text-muted me-3 fs-5"></i>
                                <small class="text-muted">Pilih atau tambah produk lagi untuk menikmati promo otomatis.</small>
                            </div>
                            @endif
                        </div>

                        <hr class="text-muted opacity-25 mb-4">
                        
                        <!-- Rincian -->
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Harga ({{ $cartItems->sum('qty') }} barang)</span>
                            <span class="fw-medium text-dark">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Diskon Barang</span>
                            <span class="fw-bold text-success">-Rp{{ number_format($discount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr class="text-muted opacity-25 my-4">
                        
                        <!-- Grand Total -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold text-dark mb-0 fs-5">Total Belanja</h6>
                            <h4 class="fw-bold text-price mb-0">Rp{{ number_format(max(0, $subtotal - ($discount ?? 0)), 0, ',', '.') }}</h4>
                        </div>
                        
                        <!-- Checkout Button -->
                        <div class="d-grid mt-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-gradient-pink btn-lg rounded-pill shadow-sm py-3 fw-bold text-white border-0">
                                Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-pink { color: #FF6699; }
    .text-price { color: #E91E63; }
    .bg-pink-soft { background-color: #fff0f5; }
    .btn-pink { background-color: #E91E63; border-color: #E91E63; transition: 0.3s; }
    .btn-pink:hover { background-color: #c2185b; border-color: #c2185b; }
    
    .btn-gradient-pink {
        background: linear-gradient(45deg, #FF6699, #E91E63);
        background-size: 200% auto;
        transition: 0.5s;
    }
    .btn-gradient-pink:hover {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4) !important;
    }
    
    .hover-danger:hover { color: #dc3545 !important; }
    
    .form-check-input:checked {
        background-color: #E91E63;
        border-color: #E91E63;
    }
    
    .form-check-input { cursor: pointer; width: 1.2rem; height: 1.2rem; }
    
    /* Remove outline from input */
    .input-group select:focus {
        border-color: #ced4da;
        box-shadow: none;
    }
</style>

<script>
    // Toggle Select All
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection