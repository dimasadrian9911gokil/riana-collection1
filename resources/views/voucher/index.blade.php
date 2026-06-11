@extends('layouts.app')

@section('title', 'Klaim Voucher Diskon - Riana Collection')

@section('content')

<div class="cart-bg py-5">
    <div class="container">
        {{-- Header --}}
        <div class="text-center mb-5 animate__animated animate__fadeInDown">
            <h1 class="fw-bold display-5 mb-3" style="color: var(--primary-pink);">
                <i class="fas fa-gift me-2 text-warning"></i> Voucher & Promo Eksklusif
            </h1>
            <p class="text-muted fs-5">Klaim voucher di bawah ini dan nikmati potongan harga spesial untuk pembelian Anda!</p>
        </div>

        {{-- Daftar Voucher --}}
        <div class="row g-4">
            @forelse($vouchers as $index => $voucher)
            <div class="col-md-6 col-lg-4 animate__animated animate__zoomIn" style="animation-delay: {{ $index * 0.1 }}s">
                @php $isUsed = in_array($voucher->id, $usedVoucherIds); @endphp
                <div class="card voucher-card border-0 h-100 shadow-sm position-relative overflow-hidden {{ $isUsed ? 'opacity-75' : '' }}" style="{{ $isUsed ? 'filter: grayscale(100%);' : '' }}">
                    {{-- Hiasan Zigzag Atas --}}
                    <div class="voucher-sawtooth-top"></div>
                    
                    <div class="card-body p-4 d-flex flex-column z-1 position-relative">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-tag me-1"></i> Promo Terbatas
                            </div>
                            @if(!$isUsed)
                            <button class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-circle copy-btn" data-code="{{ $voucher->code }}" title="Salin Kode" style="width: 35px; height: 35px;">
                                <i class="fas fa-copy"></i>
                            </button>
                            @endif
                        </div>
                        
                        <h4 class="fw-bold mb-2 text-dark">{{ $voucher->name ?? 'Diskon Spesial' }}</h4>
                        
                        <div class="voucher-code-box text-center py-3 my-3 rounded-3 border-dashed position-relative">
                            <span class="fs-4 fw-bold text-pink" style="letter-spacing: 2px;">{{ $voucher->code }}</span>
                            <div class="scissor-icon"><i class="fas fa-cut text-muted"></i></div>
                        </div>

                        <ul class="list-unstyled mb-3 flex-grow-1 text-muted small">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Potongan <strong>{{ $voucher->discount_type === 'percentage' ? $voucher->discount_amount . '%' : 'Rp' . number_format($voucher->discount_amount, 0, ',', '.') }}</strong></li>
                            @if($voucher->min_spend > 0)
                            <li class="mb-2"><i class="fas fa-shopping-cart text-info me-2"></i> Min. Belanja: Rp{{ number_format($voucher->min_spend, 0, ',', '.') }}</li>
                            @endif
                            <li><i class="fas fa-clock text-danger me-2"></i> {{ $isUsed ? 'Voucher telah Anda gunakan' : 'Berlaku: Selama Promo Berlangsung' }}</li>
                        </ul>

                        @if($isUsed)
                        <button class="btn btn-secondary w-100 rounded-pill py-2 fw-bold shadow-sm mb-3" disabled>
                            Sudah Dipakai
                        </button>
                        @else
                        <button class="btn btn-pink w-100 rounded-pill py-2 fw-bold shadow-sm btn-klaim mb-3" data-code="{{ $voucher->code }}">
                            Gunakan Sekarang
                        </button>
                        @endif
                        
                        {{-- Syarat dan Ketentuan Accordion --}}
                        <div class="accordion accordion-flush" id="tncAccordion{{ $voucher->id }}">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="headingTnc{{ $voucher->id }}">
                                    <button class="accordion-button collapsed p-0 bg-transparent text-muted small fw-bold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTnc{{ $voucher->id }}" aria-expanded="false" aria-controls="collapseTnc{{ $voucher->id }}" style="font-size: 0.8rem;">
                                        Lihat Syarat & Ketentuan
                                    </button>
                                </h2>
                                <div id="collapseTnc{{ $voucher->id }}" class="accordion-collapse collapse" aria-labelledby="headingTnc{{ $voucher->id }}" data-bs-parent="#tncAccordion{{ $voucher->id }}">
                                    <div class="accordion-body p-2 mt-2 bg-light rounded text-muted" style="font-size: 0.75rem;">
                                        <ul class="mb-0 ps-3">
                                            <li>Promo ini hanya berlaku untuk pesanan melalui website Riana Collection.</li>
                                            <li><strong class="text-danger">Batas penggunaan: 1 (satu) kali per Akun.</strong></li>
                                            <li>Minimal transaksi sebesar Rp{{ number_format($voucher->min_spend, 0, ',', '.') }} wajib terpenuhi.</li>
                                            <li>Voucher tidak dapat ditukarkan dengan uang tunai.</li>
                                            <li>Riana Collection berhak mengubah syarat & ketentuan sewaktu-waktu.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Hiasan Lingkaran Samping (Efek Sobekan) --}}
                    <div class="voucher-hole voucher-hole-left"></div>
                    <div class="voucher-hole voucher-hole-right"></div>
                    
                    {{-- Background Gradient --}}
                    <div class="voucher-bg-gradient"></div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 animate__animated animate__fadeIn">
                <i class="fas fa-ticket-alt text-muted fa-4x mb-4 opacity-25"></i>
                <h3 class="fw-bold text-dark">Oops! Belum Ada Promo</h3>
                <p class="text-muted">Saat ini belum ada voucher diskon yang tersedia. Terus pantau halaman ini untuk mendapatkan kejutan promo menarik dari Riana Collection!</p>
                <a href="{{ route('products') }}" class="btn btn-outline-pink rounded-pill px-4 py-2 mt-3 fw-bold">Kembali Belanja</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .cart-bg { background-color: #fff9fb; min-height: 100vh; }
    .text-pink { color: #ff6699 !important; }
    .btn-pink { background-color: #ff6699; color: white; border: none; transition: 0.3s; }
    .btn-pink:hover { background-color: #e65586; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230, 85, 134, 0.4) !important; }
    .btn-outline-pink { border: 2px solid #ff6699; color: #ff6699; transition: 0.3s; }
    .btn-outline-pink:hover { background-color: #ff6699; color: white; }
    
    /* VOUCHER CARD STYLING */
    .voucher-card {
        border-radius: 15px;
        background-color: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .voucher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(255, 102, 153, 0.15) !important;
    }
    
    /* Background Pattern Halus */
    .voucher-bg-gradient {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: radial-gradient(circle at top right, rgba(255, 102, 153, 0.05), transparent);
        z-index: 0;
        pointer-events: none;
    }

    /* Kotak Kode Dashed */
    .border-dashed {
        border: 2px dashed #ff99bb;
        background-color: rgba(255, 102, 153, 0.03);
    }
    .scissor-icon {
        position: absolute;
        top: -12px;
        left: -10px;
        font-size: 1rem;
        background: white;
        padding: 0 5px;
    }

    /* Efek Sobekan Samping */
    .voucher-hole {
        position: absolute;
        width: 30px;
        height: 30px;
        background-color: #fff9fb; /* Sesuaikan dengan background container */
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
    }
    .voucher-hole-left { left: -15px; }
    .voucher-hole-right { right: -15px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fungsi Copy ke Clipboard
        const copyBtns = document.querySelectorAll('.copy-btn');
        copyBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    const icon = this.querySelector('i');
                    icon.classList.remove('fa-copy');
                    icon.classList.add('fa-check', 'text-success');
                    setTimeout(() => {
                        icon.classList.remove('fa-check', 'text-success');
                        icon.classList.add('fa-copy');
                    }, 2000);
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Kode ' + code + ' disalin!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                });
            });
        });

        // Fungsi Tombol Gunakan Sekarang (Menyalin lalu redirect)
        const claimBtns = document.querySelectorAll('.btn-klaim');
        claimBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire({
                        title: 'Berhasil Diklaim!',
                        text: 'Kode voucher ' + code + ' telah disalin. Anda akan diarahkan ke halaman produk untuk berbelanja.',
                        icon: 'success',
                        confirmButtonColor: '#ff6699',
                        confirmButtonText: 'Lanjut Belanja'
                    }).then(() => {
                        window.location.href = "{{ route('products') }}";
                    });
                });
            });
        });
    });
</script>

@endsection