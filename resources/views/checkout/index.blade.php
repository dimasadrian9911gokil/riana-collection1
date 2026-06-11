@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<div class="checkout-bg py-5" style="background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%); min-height: 100vh;">
    <div class="container">
        <div class="mb-4">
            <h3 class="fw-bold text-dark"><i class="fas fa-receipt text-pink me-2"></i> Checkout Pesanan</h3>
            <p class="text-muted mb-0">Selesaikan pembayaranmu untuk segera mendapatkan produk impian!</p>
        </div>

        {{-- Pesan Error --}}
        @if(session('error'))
            <div class="alert alert-danger rounded-4 shadow-sm border-0">{{ session('error') }}</div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm" onsubmit="return disableButton(event)">
            @csrf
            @if($bestVoucher)
                <input type="hidden" name="voucher_id" value="{{ $bestVoucher->id }}">
            @endif

            @if(isset($isBuyNow) && $isBuyNow)
                <input type="hidden" name="buy_now_product_id" value="{{ $cartItems[0]->product_id }}">
                <input type="hidden" name="buy_now_qty" value="{{ $cartItems[0]->qty }}">
                <input type="hidden" name="buy_now_variant" value="{{ $cartItems[0]->variant }}">
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- Alamat --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marker-alt text-pink me-2"></i> Alamat Pengiriman</h5>
                            <a href="{{ route('address.index') }}" class="btn btn-outline-pink btn-sm rounded-pill px-3 fw-bold">Kelola Alamat</a>
                        </div>
                        <div class="card-body p-4 bg-pink-soft-light rounded-bottom-4">
                            @if($alamatUtama = Auth::user()->addresses()->where('is_default', 1)->first())
                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="fw-bold mb-0 text-dark me-2">{{ $alamatUtama->recipient_name }}</h6>
                                    <span class="badge bg-gradient-pink text-white px-3 py-1 rounded-pill" style="font-size: 0.75rem;">Utama</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex text-dark align-items-center"><i class="fas fa-phone-alt text-pink me-3" style="width: 16px;"></i> {{ $alamatUtama->phone }}</div>
                                    <div class="d-flex text-muted lh-base align-items-start"><i class="fas fa-map-marker-alt text-pink me-3 mt-1" style="width: 16px;"></i> <span>{{ $alamatUtama->address }}, {{ $alamatUtama->district }}, {{ $alamatUtama->city }}</span></div>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0 rounded-3 border-0 shadow-sm">Anda belum memiliki alamat utama. <a href="{{ route('address.index') }}" class="fw-bold text-danger text-decoration-none">Atur sekarang</a>.</div>
                            @endif
                        </div>
                    </div>

                    {{-- Produk --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 rounded-top-4">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-shopping-bag text-pink me-2"></i> Produk Dibeli</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($cartItems as $item)
                            <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <div class="ratio ratio-1x1 border rounded-3 overflow-hidden bg-light shadow-sm">
                                            <img src="{{ asset('storage/' . ($item->product->image ?? 'default.png')) }}" class="object-fit-cover" alt="Product">
                                        </div>
                                    </div>
                                    <div class="col-9 col-md-6">
                                        <h6 class="fw-bold mb-1 text-dark">{{ $item->product->name }}</h6>
                                        <span class="badge bg-light text-muted border mb-2">Varian: {{ $item->variant ?? 'Standard' }}</span>
                                        <p class="text-muted mb-0 small">Jumlah: <strong>{{ $item->qty }}</strong></p>
                                    </div>
                                    <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                                        <h5 class="fw-bold text-price mb-0">Rp{{ number_format($item->product->price * $item->qty, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kurir & Pembayaran --}}
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-header bg-white border-bottom py-3 rounded-top-4">
                                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-truck text-pink me-2"></i> Pengiriman</h5>
                                </div>
                                <div class="card-body p-4">
                                    <label class="form-label text-muted small fw-bold">Pilih Metode Pengiriman</label>
                                    <select name="courier" id="courierSelect" class="form-select border-pink-light shadow-none py-2 rounded-3 mb-2" onchange="updateTotal()" required>
                                        <option value="JNE" data-cost="15000">JNE Regular (Rp15.000)</option>
                                        <option value="J&T" data-cost="12000">J&T Express (Rp12.000)</option>
                                        <option value="ShopeeXpress" data-cost="14000">Shopee Xpress (Rp14.000)</option>
                                        <option value="Pickup" data-cost="0">Ambil di Toko (Gratis)</option>
                                    </select>
                                    <div id="pickupInfo" class="alert alert-info mt-3 mb-0" style="display: none; background-color: #f0f8ff; border-left: 3px solid #0dcaf0; font-size: 0.85rem;">
                                        <strong>Lokasi Pengambilan:</strong><br>Riana Collection, Jl. Gatot Subroto Bengkalis (Dekat Momoyo Bengkalis).
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-header bg-white border-bottom py-3 rounded-top-4">
                                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-wallet text-pink me-2"></i> Metode Pembayaran</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="payment-options">
                                        @foreach(['BCA' => 'Transfer BCA', 'BNI' => 'Transfer BNI', 'Mandiri' => 'Transfer Mandiri', 'DANA' => 'E-Wallet DANA', 'QRIS' => 'Scan QRIS'] as $value => $label)
                                        <div class="form-check custom-radio mb-3 border rounded-3 p-3 d-flex align-items-center bg-white shadow-sm transition-all">
                                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" value="{{ $value }}" id="{{ $value }}" onchange="updateTotal()" required>
                                            <label class="form-check-label flex-grow-1 fw-bold text-dark cursor-pointer" for="{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                        @endforeach
                                        <div class="form-check custom-radio border rounded-3 p-3 d-flex align-items-center bg-white shadow-sm transition-all">
                                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" value="COD" id="COD" onchange="updateTotal()" required>
                                            <label class="form-check-label flex-grow-1 fw-bold text-dark cursor-pointer d-flex justify-content-between align-items-center" for="COD">
                                                <span>Bayar di Tempat (COD)</span> 
                                                <span class="badge bg-pink-soft text-pink px-2 py-1 rounded-pill border border-pink-light">Bebas Admin!</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="col-lg-4">
                    {{-- Form Voucher Manual --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-ticket-alt text-pink me-2"></i> Punya Kode Voucher?</h6>
                            @if(session('voucher_success'))
                                <div class="alert alert-success py-2 small border-0 mb-3">{{ session('voucher_success') }}</div>
                            @endif
                            @if(session('voucher_error'))
                                <div class="alert alert-danger py-2 small border-0 mb-3">{{ session('voucher_error') }}</div>
                            @endif
                            <div class="input-group">
                                <input type="text" id="manualVoucherCode" class="form-control shadow-none border-pink-light" placeholder="Masukkan kode promo" value="{{ request('voucher_code') ?? ($bestVoucher ? $bestVoucher->code : '') }}">
                                <button type="button" class="btn btn-pink text-white fw-bold px-3" onclick="applyVoucher()">Gunakan</button>
                            </div>
                            <small class="text-muted mt-2 d-block border-bottom pb-3 mb-3" style="font-size: 0.75rem;">Sistem akan mencari promo terbaik secara otomatis jika dibiarkan kosong.</small>

                            @if(isset($allVouchers) && $allVouchers->count() > 0)
                                <h6 class="fw-bold text-dark fs-6 mb-2">Atau Pilih Promo Tersedia:</h6>
                                <div class="list-group">
                                    @foreach($allVouchers as $v)
                                        @php $eligible = $subtotal >= $v->min_spend; @endphp
                                        <div class="list-group-item d-flex justify-content-between align-items-center p-2 {{ $eligible ? '' : 'bg-light' }}">
                                            <div>
                                                <span class="fw-bold d-block {{ $eligible ? 'text-dark' : 'text-muted' }}" style="font-size: 0.85rem;">{{ $v->code }}</span>
                                                <small class="{{ $eligible ? 'text-success' : 'text-danger' }}" style="font-size: 0.75rem;">
                                                    Potongan {{ $v->discount_type == 'percentage' ? $v->discount_amount . '%' : 'Rp' . number_format($v->discount_amount, 0, ',', '.') }}
                                                    (Min. Rp{{ number_format($v->min_spend, 0, ',', '.') }})
                                                </small>
                                            </div>
                                            @if($eligible)
                                                <button type="button" class="btn btn-sm btn-outline-pink py-1 px-2" style="font-size: 0.75rem;" onclick="document.getElementById('manualVoucherCode').value='{{ $v->code }}'; applyVoucher();">Pakai</button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-secondary py-1 px-2 border-0" disabled style="font-size: 0.7rem;">Belum Memenuhi Syarat</button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm sticky-top rounded-4" style="top: 100px; background: linear-gradient(to bottom right, #ffffff, #fff5f8); border: 1px solid #ffe4e1 !important;">
                        <div class="card-header border-bottom py-4 rounded-top-4" style="background: linear-gradient(45deg, #FF6699, #E91E63);">
                            <h5 class="fw-bold mb-0 text-white"><i class="fas fa-file-invoice-dollar me-2"></i> Ringkasan Belanja</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span>Subtotal Produk</span>
                                <span class="text-dark fw-medium">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span>Ongkos Kirim</span>
                                <span id="shippingCost" class="text-dark fw-medium">Rp15.000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span>Biaya Admin</span>
                                <span id="adminFeeDisplay" class="text-dark fw-medium">-</span>
                            </div>
                            @if($bestVoucher)
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-success"><i class="fas fa-tag me-1"></i> Diskon ({{ $bestVoucher->code }})</span>
                                <span class="text-success fw-bold">-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <hr class="text-muted opacity-25 my-4">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-dark mb-0 fs-5">Total Tagihan</h6>
                                <h3 class="fw-bold text-price mb-0" id="totalAmount">Rp{{ number_format($subtotal + 15000 - $discount, 0, ',', '.') }}</h3>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" id="submitBtn" class="btn btn-gradient-pink btn-lg rounded-pill shadow-sm py-3 fw-bold text-white border-0">
                                    <i class="fas fa-check-circle me-1"></i> Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .text-pink { color: #FF6699; }
    .text-price { color: #E91E63; }
    .bg-pink-soft { background-color: #fff0f5; }
    .bg-pink-soft-light { background-color: #fffafb; }
    .border-pink-light { border-color: #ffb3c6 !important; }
    
    .btn-outline-pink { border: 1px solid #FF6699; color: #FF6699; transition: 0.3s; }
    .btn-outline-pink:hover { background: #FF6699; color: white; border-color: #FF6699; }
    
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
    
    .bg-gradient-pink {
        background: linear-gradient(45deg, #FF6699, #E91E63);
    }
    
    .transition-all { transition: all 0.2s ease-in-out; }
    .cursor-pointer { cursor: pointer; }
    
    /* Custom Radio Buttons */
    .custom-radio { cursor: pointer; }
    .custom-radio:hover { border-color: #FF6699 !important; background-color: #fffafb !important; }
    .custom-radio input[type="radio"]:checked + label { color: #E91E63 !important; }
    .custom-radio:has(input[type="radio"]:checked) {
        border-color: #E91E63 !important;
        background-color: #fff0f5 !important;
        box-shadow: 0 4px 10px rgba(233, 30, 99, 0.1) !important;
    }
    .form-check-input:checked {
        background-color: #E91E63;
        border-color: #E91E63;
    }
</style>

<script>
    function applyVoucher() {
        let code = document.getElementById('manualVoucherCode').value;
        let url = new URL(window.location.href);
        if (code.trim() !== '') {
            url.searchParams.set('voucher_code', code);
        } else {
            url.searchParams.delete('voucher_code');
        }
        window.location.href = url.toString();
    }

    window.onload = function() {
        updateTotal();
    };

    function updateTotal() {
        const subtotal = {{ $subtotal }};
        const discount = {{ $discount }};
        const courierSelect = document.getElementById('courierSelect');
        const courierValue = courierSelect.value;
        const shippingCost = parseInt(courierSelect.options[courierSelect.selectedIndex].getAttribute('data-cost'));
        
        // Menampilkan atau menyembunyikan info pickup
        const pickupInfo = document.getElementById('pickupInfo');
        if (pickupInfo) {
            pickupInfo.style.display = (courierValue === 'Pickup') ? 'block' : 'none';
        }
        
        // Cek pembayaran mana yang dipilih
        const paymentRadios = document.getElementsByName('payment_method');
        let selectedPayment = null;
        for (let radio of paymentRadios) {
            if (radio.checked) {
                selectedPayment = radio.value;
                break;
            }
        }

        let adminFee = 0;
        let adminFeeText = '-';

        if (selectedPayment) {
            if (selectedPayment === 'COD') {
                adminFee = 0;
                adminFeeText = 'Gratis';
            } else {
                adminFee = 2000;
                adminFeeText = 'Rp' + adminFee.toLocaleString('id-ID');
            }
        }
        
        const total = subtotal + shippingCost + adminFee - discount;

        document.getElementById('shippingCost').innerText = 'Rp' + shippingCost.toLocaleString('id-ID');
        
        // Update admin display
        const adminDisplay = document.getElementById('adminFeeDisplay');
        adminDisplay.innerText = adminFeeText;
        if (adminFeeText === 'Gratis') {
            adminDisplay.classList.add('text-success', 'fw-bold');
            adminDisplay.classList.remove('text-dark');
        } else {
            adminDisplay.classList.remove('text-success', 'fw-bold');
            adminDisplay.classList.add('text-dark');
        }

        document.getElementById('totalAmount').innerText = 'Rp' + total.toLocaleString('id-ID');
    }

    function disableButton(event) {
        const btn = document.getElementById('submitBtn');
        
        // Pastikan metode pembayaran sudah dipilih
        const paymentRadios = document.getElementsByName('payment_method');
        let selectedPayment = false;
        for (let radio of paymentRadios) {
            if (radio.checked) {
                selectedPayment = true;
                break;
            }
        }

        if(!selectedPayment) {
            alert('Mohon pilih metode pembayaran terlebih dahulu!');
            event.preventDefault();
            return false;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
        return true;
    }
</script>
@endsection