@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<style>
    .order-card {
        border-radius: 15px;
        transition: 0.3s;
        border-left: 5px solid #ff6699;
    }
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    /* Warna Status */
    .badge-pending { background-color: #fff3cd; color: #856404; }
    .badge-paid { background-color: #cce5ff; color: #004085; }
    .badge-success { background-color: #d4edda; color: #155724; }
    .text-pink { color: #ff6699; }
</style>

<div class="container py-5">
    <h3 class="fw-bold mb-4"><i class="fas fa-history text-pink me-2"></i>Riwayat Pesanan Saya</h3>
    
    @forelse($orders as $order)
        <div class="card order-card shadow-sm mb-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-1">Invoice: {{ $order->invoice }}</h5>
                        <p class="text-muted mb-0 small">
                            <i class="far fa-calendar-alt me-1"></i> Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-0 fw-bold small text-muted">Total Pembayaran</p>
                        <p class="text-pink fw-bold fs-5">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-3 text-md-end">
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'badge-pending',
                                'paid' => 'badge-paid',
                                default => 'badge-success'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill text-uppercase">
                            {{ $order->status }}
                        </span>
                        <div class="mt-2">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="display-4 text-muted mb-3"><i class="fas fa-shopping-bag"></i></div>
            <h5 class="text-muted">Belum ada riwayat pesanan.</h5>
            <div class="mt-3">
                <a href="{{ route('products') }}" class="btn btn-pink rounded-pill px-4">Mulai Belanja</a>
            </div>
        </div>
    @endforelse
</div>
@endsection