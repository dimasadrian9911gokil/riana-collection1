@extends('layouts.app')

@section('title', 'Detail Pesanan #' . ($order->invoice ?? '...'))

@section('content')

<div class="cart-bg py-5">
    <div class="container">
        {{-- Header & Status --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold"><i class="fas fa-box-open text-pink me-2"></i>Detail Pesanan</h2>
                <p class="text-muted mb-0 fs-5">Invoice: <span class="fw-bold text-dark">{{ $order->invoice }}</span></p>
            </div>
            <span id="order-status-badge" class="badge {{ $order->status == 'selesai' ? 'bg-success' : ($order->status == 'sudah_dibayar' ? 'bg-primary' : 'bg-warning') }} text-white fs-6 px-4 py-2 rounded-pill shadow-sm text-uppercase">
                {{ ucwords(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>

        <div class="row">
            {{-- Bagian Kiri: Status & Produk --}}
            <div class="col-lg-8">
                <div class="card card-3d border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-4">Status Pesanan</h5>
                    <div class="d-flex justify-content-between text-center">
                        @php
                            $steps = [
                                ['label' => 'Dibuat',   'icon' => 'fa-file-alt'],
                                ['label' => $order->payment_method === 'COD' ? 'Dikonfirmasi' : 'Dibayar', 'icon' => $order->payment_method === 'COD' ? 'fa-user-check' : 'fa-wallet'],
                                ['label' => 'Diproses', 'icon' => 'fa-cog'],
                                ['label' => 'Dikirim',  'icon' => 'fa-truck'],
                                ['label' => 'Selesai',  'icon' => 'fa-check-double']
                            ];
                            $currentIndex = 0;
                            if (in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi'])) {
                                $currentIndex = 0;
                            } elseif ($order->status == 'sudah_dibayar') {
                                $currentIndex = 1;
                            } elseif ($order->status == 'dikemas') {
                                $currentIndex = 2;
                            } elseif ($order->status == 'dikirim') {
                                $currentIndex = 3;
                            } elseif ($order->status == 'selesai') {
                                $currentIndex = 4;
                            } else {
                                $currentIndex = -1;
                            }
                        @endphp
                        @foreach($steps as $index => $data)
                        <div class="flex-fill position-relative order-step" data-step-index="{{ $index }}">
                            <div class="rounded-circle mx-auto mb-2 step-icon {{ $index <= $currentIndex ? 'bg-pink text-white' : 'bg-light text-secondary' }}" style="width: 45px; height: 45px; line-height: 45px; transition: 0.3s;">
                                <i class="fas {{ $data['icon'] }}"></i>
                            </div>
                            <small class="fw-bold step-label {{ $index <= $currentIndex ? 'text-pink' : 'text-muted' }}">{{ $data['label'] }}</small>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($order->tracking_number)
                    <div id="tracking-info-container" class="mt-4 pt-4 border-top text-center animate__animated animate__fadeIn">
                        <p class="text-muted mb-1 text-uppercase small fw-bold">Nomor Resi / Info Kurir ({{ strtoupper(str_replace('_', ' ', $order->courier ?? 'Kurir')) }})</p>
                        <h4 class="fw-bold mb-0 tracking-number-text" style="color: var(--primary-pink); letter-spacing: 2px;">
                            <i class="fas fa-shipping-fast me-2"></i><span id="tracking-number-value">{{ $order->tracking_number }}</span>
                        </h4>
                    </div>
                    @endif
                </div>

                <div class="card card-3d border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-4">Produk Dipesan</h5>
                    @foreach($order->items as $item)
                        <div class="row align-items-center mb-3">
                            <div class="col-md-2">
                                @if($item->product)
                                    <a href="{{ route('products.show', $item->product->id) }}">
                                        <img src="{{ asset('storage/' . ($item->product->image ?? 'default.png')) }}" class="img-fluid rounded-3 shadow-sm" alt="{{ $item->product_name }}">
                                    </a>
                                @else
                                    <img src="{{ asset('storage/' . ($item->product->image ?? 'default.png')) }}" class="img-fluid rounded-3 shadow-sm" alt="{{ $item->product_name }}">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-0">
                                    @if($item->product)
                                        <a href="{{ route('products.show', $item->product->id) }}" class="text-dark text-decoration-none hover-pink">{{ $item->product_name }}</a>
                                    @else
                                        {{ $item->product_name }}
                                    @endif
                                </h6>
                                <small class="text-muted border rounded px-1 d-inline-block mt-1 mb-1" style="font-size: 0.75rem;">Varian: {{ $item->variant ?? 'Standard' }}</small><br>
                                <small class="text-muted">Qty: {{ $item->qty }}</small>
                            </div>
                            <div class="col-md-4 text-end fw-bold text-pink">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bagian Kanan: Pembayaran Manual --}}
            <div class="col-lg-4">
                <div class="card card-3d border-0 p-4 sticky-top mb-4" style="top: 100px;">
                    <h5 class="fw-bold mb-3">Pembayaran</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-0">Metode: <strong>{{ $order->payment_method }}</strong></p>
                        @if($order->status == 'menunggu_pembayaran' && $order->payment_method !== 'COD')
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#changePaymentModal">Ubah</button>
                        @endif
                    </div>
                    
                    @if($order->status == 'menunggu_pembayaran' && $order->payment_method !== 'COD')
                        <!-- Countdown Timer -->
                        <div class="alert alert-warning border-0 rounded-4 text-center mb-3">
                            <small class="d-block mb-1 text-uppercase fw-bold text-dark">Sisa Waktu Pembayaran</small>
                            <h3 class="fw-bold mb-0 text-danger" id="countdown-timer">--:--</h3>
                            <small id="countdown-expired-msg" class="text-danger d-none fw-bold">Waktu Habis!</small>
                        </div>
                        <div class="alert alert-info border-0 rounded-4 text-center">
                            @if($order->payment_method === 'QRIS')
                                <small class="d-block mb-2">Silakan scan kode QRIS berikut:</small>
                                <!-- Placeholder QRIS -->
                                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS" class="img-fluid rounded bg-white p-2 mb-2 shadow-sm" style="max-height: 200px;">
                                <small class="d-block fw-bold">a.n. Riana Collection</small>
                            @elseif($order->payment_method === 'DANA')
                                <small class="d-block mb-2">Silakan transfer ke DANA:</small>
                                <strong class="d-block mb-1 fs-4" style="color: #118EEA;">0812-3456-7890</strong>
                                <small class="fw-bold">a.n. Riana Collection</small>
                            @else
                                <small class="d-block mb-2">Silakan transfer ke Bank {{ $order->payment_method }}:</small>
                                <strong class="d-block mb-1 fs-5">{{ $order->payment_method === 'BCA' ? '1234 5678 90' : ($order->payment_method === 'Mandiri' ? '0987 6543 21' : '1122 3344 55') }}</strong>
                                <small class="fw-bold">a.n. Riana Collection</small>
                            @endif
                        </div>
                        <form action="{{ route('orders.confirmPayment', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label fw-bold text-muted small">Upload Bukti Transfer</label>
                                <input class="form-control" type="file" id="payment_proof" name="payment_proof" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-pink w-100 rounded-pill py-2 fw-bold shadow-sm">
                                <i class="fas fa-upload me-2"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @else
                        @if($order->payment_method === 'COD')
                            <div class="alert alert-info border-0 rounded-4 text-center mb-3">
                                <i class="fas fa-store fa-2x mb-2 text-info"></i>
                                <h6 class="fw-bold mb-1">Bayar di Tempat (COD)</h6>
                                @if($order->status == 'menunggu_verifikasi')
                                    <p class="small mb-0">Pesanan Anda sedang menunggu konfirmasi admin. Kami akan segera menyiapkan barang Anda!</p>
                                @elseif($order->status == 'dikemas')
                                    <p class="small mb-0">Barang Anda sedang disiapkan. Silakan ambil dan bayar di toko.</p>
                                @else
                                    <p class="small mb-0">Silakan ambil barang di toko dan lakukan pembayaran secara langsung.</p>
                                @endif
                            </div>
                        @endif
                        <div id="payment-status-alert" class="alert alert-{{ in_array($order->status, ['sudah_dibayar', 'selesai']) ? 'success' : 'warning' }} border-0 rounded-4 text-center fw-bold">
                            STATUS: {{ $order->payment_method === 'COD' && $order->status === 'menunggu_verifikasi' ? 'MENUNGGU KONFIRMASI ADMIN' : ucwords(str_replace('_', ' ', $order->status)) }}
                        </div>
                    @endif

                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Ringkasan</h5>
                    <div class="d-flex justify-content-between mb-2 text-muted"><span>Subtotal</span><span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    <div class="d-flex justify-content-between mb-2 text-muted"><span>Ongkir</span><span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                    <div class="d-flex justify-content-between mb-2 text-success"><span>Diskon</span><span>-Rp{{ number_format($order->discount, 0, ',', '.') }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5 class="fw-bold">Total</h5>
                        <h4 class="text-pink fw-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</h4>
                    </div>

                    @if(in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi']))
                        <hr class="my-4">
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold py-2">
                                <i class="fas fa-times-circle me-2"></i> Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Metode Pembayaran -->
<div class="modal fade" id="changePaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Ubah Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('orders.changePaymentMethod', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Pilih Metode Baru:</label>
                        <select class="form-select form-select-lg" name="payment_method" required>
                            <option value="">-- Pilih --</option>
                            <option value="BCA">Transfer BCA</option>
                            <option value="BNI">Transfer BNI</option>
                            <option value="Mandiri">Transfer Mandiri</option>
                            <option value="DANA">E-Wallet DANA</option>
                            <option value="QRIS">Scan QRIS</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-pink w-100 rounded-pill fw-bold py-2">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .cart-bg { background-color: #fff9fb; min-height: 100vh; }
    .card-3d { background: #ffffff; box-shadow: 0 10px 20px rgba(255, 102, 153, 0.08); border-radius: 20px; border-left: 6px solid #ff6699; }
    .text-pink { color: #ff6699; }
    .hover-pink { transition: color 0.3s; }
    .hover-pink:hover { color: #ff6699 !important; text-decoration: underline !important; }
    .bg-pink { background-color: #ff6699; }
    .btn-pink { background-color: #ff6699; color: white; }
    .btn-pink:hover { background-color: #e65586; color: white; }
</style>

<!-- Auto Polling Script untuk Realtime Status -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentStatus = "{{ $order->status }}";
        const orderId = "{{ $order->id }}";

        // Countdown Timer Logic
        @if($order->status == 'menunggu_pembayaran' && $order->payment_method !== 'COD')
        const expireTime = new Date("{{ $order->created_at->copy()->addMinutes(30)->toIso8601String() }}").getTime();
        const timerElement = document.getElementById('countdown-timer');
        const expiredMsg = document.getElementById('countdown-expired-msg');
        
        const countdownInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = expireTime - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                timerElement.style.display = 'none';
                expiredMsg.classList.remove('d-none');
                // Reload halaman untuk memicu checkExpiry dari server
                setTimeout(() => location.reload(), 2000);
            } else {
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerElement.innerHTML = (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds);
            }
        }, 1000);
        @endif
        
        // Polling setiap 5 detik
        setInterval(function() {
            fetch(`/api/orders/${orderId}/status`)
                .then(response => response.json())
                .then(data => {
                    if (data.status && data.status !== currentStatus) {
                        currentStatus = data.status;
                        
                        // Update Badge Atas
                        const badge = document.getElementById('order-status-badge');
                        if(badge) {
                            badge.innerText = data.status_label.toUpperCase();
                            badge.className = `badge ${data.status === 'selesai' ? 'bg-success' : (data.status === 'sudah_dibayar' ? 'bg-primary' : 'bg-warning')} text-white fs-6 px-4 py-2 rounded-pill shadow-sm text-uppercase`;
                        }

                        // Update Timeline Progress
                        let stepIndex = 0;
                        if (['menunggu_pembayaran', 'menunggu_verifikasi'].includes(data.status)) {
                            stepIndex = 0;
                        } else if (data.status === 'sudah_dibayar') {
                            stepIndex = 1;
                        } else if (data.status === 'dikemas') {
                            stepIndex = 2;
                        } else if (data.status === 'dikirim') {
                            stepIndex = 3;
                        } else if (data.status === 'selesai') {
                            stepIndex = 4;
                        } else if (data.status === 'dibatalkan') {
                            stepIndex = -1;
                        }
                        
                        document.querySelectorAll('.order-step').forEach(function(el) {
                            const index = parseInt(el.getAttribute('data-step-index'));
                            const icon = el.querySelector('.step-icon');
                            const label = el.querySelector('.step-label');
                            
                            if (index <= stepIndex) {
                                icon.className = 'rounded-circle mx-auto mb-2 step-icon bg-pink text-white';
                                label.className = 'fw-bold step-label text-pink';
                            } else {
                                icon.className = 'rounded-circle mx-auto mb-2 step-icon bg-light text-secondary';
                                label.className = 'fw-bold step-label text-muted';
                            }
                        });

                        // Update Payment Alert
                        const alertBox = document.getElementById('payment-status-alert');
                        if(alertBox) {
                            alertBox.innerText = `STATUS: ${data.status_label.toUpperCase()}`;
                            alertBox.className = `alert alert-${data.status === 'sudah_dibayar' ? 'success' : 'warning'} border-0 rounded-4 text-center fw-bold`;
                        } else if(data.status !== 'menunggu_pembayaran') {
                            // Reload halaman agar tombol bayar hilang jika status tiba2 berubah
                            location.reload();
                        }

                        // Update Tracking Number Realtime
                        if(data.tracking_number) {
                            let trackingContainer = document.getElementById('tracking-info-container');
                            if(!trackingContainer) {
                                // Buat container baru jika sebelumnya kosong
                                const containerHTML = `
                                <div id="tracking-info-container" class="mt-4 pt-4 border-top text-center animate__animated animate__fadeIn">
                                    <p class="text-muted mb-1 text-uppercase small fw-bold">Nomor Resi / Info Kurir (<span id="tracking-courier-value">${data.courier.replace('_', ' ')}</span>)</p>
                                    <h4 class="fw-bold mb-0 tracking-number-text" style="color: var(--primary-pink); letter-spacing: 2px;">
                                        <i class="fas fa-shipping-fast me-2"></i><span id="tracking-number-value">${data.tracking_number}</span>
                                    </h4>
                                </div>`;
                                // Insert at the bottom of left column
                                document.querySelector('.col-lg-8 .card-3d:first-child').insertAdjacentHTML('beforeend', containerHTML);
                            } else {
                                document.getElementById('tracking-number-value').innerText = data.tracking_number;
                                const courierValue = document.getElementById('tracking-courier-value');
                                if(courierValue) courierValue.innerText = data.courier;
                            }
                        }
                    }
                })
                .catch(err => console.error("Error polling order status:", err));
        }, 5000);
    });
</script>
@endsection