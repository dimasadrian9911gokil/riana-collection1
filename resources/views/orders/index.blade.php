@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')

<div class="cart-bg py-5">
    <div class="container">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold"><i class="fas fa-box text-pink me-2"></i> Pesanan Saya</h2>
                <p class="text-muted mb-0">Pantau status dan riwayat pesanan Anda</p>
            </div>
            <a href="{{ route('products') }}" class="btn btn-pink rounded-pill px-4 shadow-sm">
                <i class="fas fa-shopping-bag me-2"></i> Belanja Lagi
            </a>
        </div>

        {{-- Statistik Pesanan --}}
        <div class="row mb-4">
            @php
                // Array statistik diperbarui agar mencakup status 'paid'
                $stats = [
                    ['menunggu_pembayaran', 'Menunggu Bayar', 'text-warning', 'fa-clock',    $counts['menunggu_pembayaran']],
                    ['sudah_dibayar',       'Sudah Dibayar',  'text-primary', 'fa-wallet',   $counts['sudah_dibayar']],
                    ['dikemas',             'Diproses',       'text-info',    'fa-spinner',  $counts['dikemas']],
                    ['dikirim',             'Dikirim',        'text-primary', 'fa-truck',    $counts['dikirim']],
                    ['selesai',             'Selesai',        'text-success', 'fa-check-double', $counts['selesai']]
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="col-md-2-4 mb-3">
                <div class="card card-3d border-0 p-3 text-center">
                    <div class="fs-4 mb-1 {{ $stat[2] }}"><i class="fas {{ $stat[3] }}"></i></div>
                    <h2 class="fw-bold mb-0">{{ $stat[4] }}</h2>
                    <small class="fw-bold text-muted">{{ $stat[1] }}</small>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pencarian --}}
        <div class="card card-3d border-0 mb-4 p-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8"><h5 class="fw-bold mb-0"><i class="fas fa-history text-secondary me-2"></i> Riwayat Pesanan</h5></div>
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control rounded-pill border-pink" placeholder="Cari berdasarkan nomor invoice...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Pesanan --}}
        <div id="orderList">
            @forelse($orders as $order)
            <div class="card card-3d border-0 mb-3 p-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <small class="text-muted text-uppercase">Invoice</small>
                            <div class="fw-bold text-dark">{{ $order->invoice }}</div>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted text-uppercase">Tanggal</small>
                            <div class="fw-medium">{{ $order->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted text-uppercase">Status</small>
                            <div>
                                @php
                                    $badges = [
                                        'menunggu_pembayaran' => 'bg-warning text-dark', 
                                        'sudah_dibayar'       => 'bg-primary text-white', 
                                        'dikemas'             => 'bg-info text-white', 
                                        'dikirim'             => 'bg-primary text-white', 
                                        'selesai'             => 'bg-success text-white',
                                        'dibatalkan'          => 'bg-danger text-white'
                                    ];
                                @endphp
                                <span class="badge {{ $badges[$order->status] ?? 'bg-secondary' }} rounded-pill px-3">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                                @if($order->tracking_number)
                                    <div class="mt-2 small fw-bold" style="color: var(--primary-pink);"><i class="fas fa-truck me-1"></i> {{ $order->tracking_number }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted text-uppercase">Total</small>
                            <div class="fw-bold text-pink">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-pink rounded-pill px-4">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card card-3d border-0 text-center py-5 mt-4">
                <div class="display-1 text-muted">📦</div>
                <h4 class="fw-bold mt-3">Belum Ada Pesanan</h4>
                <p class="text-muted mb-4">Sepertinya Anda belum melakukan transaksi apapun.</p>
                <a href="{{ route('products') }}" class="btn btn-pink btn-lg rounded-pill px-5 shadow-sm">Mulai Belanja Sekarang</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .cart-bg { background-color: #fff9fb; min-height: 100vh; }
    .card-3d { background: #ffffff; box-shadow: 0 10px 20px rgba(255, 102, 153, 0.08); border-radius: 20px; border-left: 6px solid #ff6699; transition: 0.3s; }
    .card-3d:hover { box-shadow: 0 15px 30px rgba(255, 102, 153, 0.15); }
    .text-pink { color: #ff6699; }
    .btn-pink { background-color: #ff6699; color: white; }
    .btn-pink:hover { background-color: #e65586; color: white; }
    .btn-outline-pink { border: 1px solid #ff6699; color: #ff6699; }
    .btn-outline-pink:hover { background-color: #ff6699; color: white; }
    .border-pink { border: 1px solid #ff6699; }
    /* Utility agar 5 kolom statistik pas di layar */
    .col-md-2-4 { flex: 0 0 20%; max-width: 20%; }
</style>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let cards = document.querySelectorAll('#orderList .card');
        cards.forEach(card => {
            card.style.display = card.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });
</script>
@endsection