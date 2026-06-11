@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                
                <div class="text-center text-white p-5" style="background:linear-gradient(135deg,#ff5c8d,#ff8fb1);">
                    <div class="bg-white rounded-circle mx-auto d-flex align-items-center justify-content-center shadow" style="width:100px;height:100px;">
                        <span style="font-size:50px;color:#28a745;">✓</span>
                    </div>
                    <h1 class="fw-bold mt-4 mb-2">Pesanan Berhasil!</h1>
                    <p class="mb-0 fs-5">Terima kasih telah berbelanja di <strong>Riana Collection</strong></p>
                </div>

                <div class="card-body p-5">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Nomor Pesanan</small>
                            <h6 class="fw-bold text-danger mt-2">{{ $order->invoice }}</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Status Pembayaran</small>
                            <div class="mt-2">
                                <span class="badge {{ $order->status == 'menunggu_pembayaran' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted">Metode Pembayaran</small>
                            <h6 class="fw-bold mt-2">{{ $order->payment_method }}</h6>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <span>{{ $item->product_name }}</span><br>
                            <small class="text-muted">Varian: {{ $item->variant ?? 'Standard' }} | Qty: {{ $item->qty }}</small>
                        </div>
                        <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Ongkir ({{ $order->courier }})</span>
                        <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Diskon</span>
                        <span>-Rp{{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold">Total Pembayaran</h4>
                        <h3 class="fw-bold text-danger">Rp{{ number_format($order->total, 0, ',', '.') }}</h3>
                    </div>

                    <div class="alert alert-light border mt-4">
                        📦 Pesanan sedang diproses. Silakan lakukan pembayaran. 
                        Status pesanan dapat dipantau melalui menu <a href="{{ route('orders.index') }}" class="text-decoration-none fw-bold" style="color: #E91E63;">Riwayat Pesanan</a>.
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-danger btn-lg w-100 rounded-pill">📋 Lihat Detail Pesanan</a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('home') }}" class="btn btn-outline-danger btn-lg w-100 rounded-pill">🛍️ Lanjut Belanja</a>
                        </div>
                    </div>
                </div>

                <div class="text-center text-white p-3" style="background:#ff5c8d;">
                    💖 Terima kasih telah mempercayai Riana Collection
                </div>
            </div>
        </div>
    </div>
</div>
@endsection