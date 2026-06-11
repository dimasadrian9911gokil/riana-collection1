@extends('layouts.app')

@section('title', 'Notifikasi & Lacak Pesanan')

@section('content')

<style>
    .notif-hero {
        background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
        padding: 60px 0 80px 0;
        text-align: center;
        color: white;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
        margin-bottom: -50px;
        position: relative;
        z-index: 0;
    }
    .notif-hero h1 { font-weight: 900; letter-spacing: 1px; text-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .notif-hero p { opacity: 0.9; font-size: 1.1rem; }
    
    .tracking-card-header {
        background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%) !important;
    }
    
    .tracking-icon-active {
        background-color: #ff758c !important;
        color: white !important;
        box-shadow: 0 5px 15px rgba(255, 117, 140, 0.4) !important;
        border: none !important;
    }
    .tracking-line {
        border-left: 2px dashed #ffb6c1 !important;
    }
    .unread-notif {
        background: linear-gradient(90deg, #fff9fb 0%, #fff 100%) !important;
        border-left: 4px solid #ff758c !important;
    }
    .notif-hover:hover {
        background-color: #fff0f5 !important;
        transform: scale(1.01);
        transition: 0.2s;
    }
</style>

<!-- HEADER BANNER -->
<div class="notif-hero shadow-sm">
    <div class="container">
        <h1><i class="fas fa-bell me-2"></i> Pusat Informasi</h1>
        <p>Lacak pesanan dan lihat pemberitahuan terbaru Anda di sini.</p>
    </div>
</div>

<div class="container py-5 position-relative" style="z-index: 1;">
    <div class="row g-4 mt-2">
        
        <!-- KOLOM KIRI: LACAK PESANAN REALTIME -->
        <div class="col-lg-5">
            <h4 class="fw-bold mb-4 text-dark"><i class="fas fa-map-marked-alt text-pink me-2"></i> Lacak Pesanan</h4>
            
            @if($orders->count() > 0)
            <form action="{{ route('notifications.index') }}" method="GET" class="mb-3">
                <select name="track_id" class="form-select shadow-sm" style="border: 2px solid #ffb6c1; border-radius: 12px; padding: 12px;" onchange="this.form.submit()">
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ $activeOrder && $activeOrder->id == $order->id ? 'selected' : '' }}>
                            {{ $order->invoice }} - {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                        </option>
                    @endforeach
                </select>
            </form>
            @endif

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                @if($activeOrder)
                    <div class="card-header border-0 text-white p-4 tracking-card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 opacity-75 small">No. Invoice</p>
                                <h5 class="fw-bold mb-0">{{ $activeOrder->invoice }}</h5>
                            </div>
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm text-uppercase">{{ $activeOrder->status }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 position-relative">
                        <h6 class="fw-bold mb-4">Status Pengiriman Realtime</h6>
                        
                        @php
                            $status = $activeOrder->status;
                            $isCanceled = $status === 'dibatalkan';
                            $isPending = $status === 'menunggu_pembayaran';
                            $isPaid = in_array($status, ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai']);
                            $isShipped = in_array($status, ['dikirim', 'selesai']);
                            $isCompleted = $status === 'selesai';
                        @endphp
                        
                        <div class="tracking-timeline position-relative">
                            <!-- Garis vertikal -->
                            <div class="position-absolute h-100 tracking-line" style="left: 20px; top: 0; z-index: 1;"></div>
                            
                            @if($isCanceled)
                                <!-- Step Batal -->
                                <div class="d-flex mb-4 position-relative z-index-2">
                                    <div class="position-absolute rounded-circle bg-danger opacity-25 pulse-animation" style="width: 60px; height: 60px; left: -9px; top: -9px; z-index: 1;"></div>
                                    <div class="rounded-circle bg-danger text-white shadow d-flex justify-content-center align-items-center" style="width: 42px; height: 42px; z-index: 2;">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="ms-4 pt-1">
                                        <h6 class="fw-bold mb-1 text-danger">Pesanan Dibatalkan</h6>
                                        <p class="text-muted small mb-0">Pesanan ini telah dibatalkan oleh sistem.</p>
                                    </div>
                                </div>
                            @else
                                <!-- Step 4 (Tiba) -->
                                <div class="d-flex mb-4 position-relative z-index-2 {{ $isCompleted ? '' : 'opacity-50' }}">
                                    @if($isCompleted)
                                    <div class="position-absolute rounded-circle bg-pink opacity-25 pulse-animation" style="width: 60px; height: 60px; left: -9px; top: -9px; z-index: 1; background-color: #ffb6c1;"></div>
                                    @endif
                                    <div class="rounded-circle {{ $isCompleted ? 'tracking-icon-active' : 'bg-light border border-2 border-secondary' }} d-flex justify-content-center align-items-center" style="width: 42px; height: 42px; z-index: 2;">
                                        <i class="fas fa-home {{ $isCompleted ? '' : 'text-secondary' }}"></i>
                                    </div>
                                    <div class="ms-4 pt-1">
                                        <h6 class="fw-bold mb-1 {{ $isCompleted ? 'text-pink' : '' }}">Tiba di Tujuan</h6>
                                        <p class="text-muted small mb-0">Paket telah diterima.</p>
                                    </div>
                                </div>

                                <!-- Step 3 (Dalam Perjalanan) -->
                                <div class="d-flex mb-4 position-relative z-index-2 {{ $isShipped ? '' : ($isCompleted ? '' : 'opacity-50') }}">
                                    @if($isShipped)
                                    <div class="position-absolute rounded-circle bg-pink opacity-25 pulse-animation" style="width: 60px; height: 60px; left: -9px; top: -9px; z-index: 1; background-color: #ffb6c1;"></div>
                                    @endif
                                    <div class="rounded-circle {{ $isShipped || $isCompleted ? 'tracking-icon-active' : 'bg-light border border-2 border-secondary' }} d-flex justify-content-center align-items-center position-relative" style="width: 42px; height: 42px; z-index: 2;">
                                        <i class="fas fa-truck-fast {{ $isShipped || $isCompleted ? '' : 'text-secondary' }}"></i>
                                    </div>
                                    <div class="ms-4 pt-1">
                                        <h6 class="fw-bold {{ $isShipped ? 'text-pink' : '' }} mb-1">Sedang Dalam Perjalanan</h6>
                                        <p class="text-muted small mb-0">Paket sedang dikirim oleh kurir {{ $activeOrder->courier ?? 'Reguler' }}.</p>
                                    </div>
                                </div>

                                <!-- Step 2 (Dikemas) -->
                                <div class="d-flex mb-4 position-relative z-index-2 {{ $isPaid ? '' : ($isShipped || $isCompleted ? '' : 'opacity-50') }}">
                                    @if($isPaid)
                                    <div class="position-absolute rounded-circle bg-pink opacity-25 pulse-animation" style="width: 60px; height: 60px; left: -9px; top: -9px; z-index: 1; background-color: #ffb6c1;"></div>
                                    @endif
                                    <div class="rounded-circle {{ $isPaid || $isShipped || $isCompleted ? 'tracking-icon-active' : 'bg-light border border-2 border-secondary' }} d-flex justify-content-center align-items-center position-relative" style="width: 42px; height: 42px; z-index: 2;">
                                        <i class="fas fa-box {{ $isPaid || $isShipped || $isCompleted ? '' : 'text-secondary' }}"></i>
                                    </div>
                                    <div class="ms-4 pt-1">
                                        <h6 class="fw-bold mb-1 {{ $isPaid ? 'text-pink' : '' }}">Pesanan Dikemas</h6>
                                        <p class="text-muted small mb-0">Pembayaran berhasil, pesanan sedang disiapkan.</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 1 (Pending) -->
                            <div class="d-flex position-relative z-index-2">
                                @if($isPending)
                                <div class="position-absolute rounded-circle bg-pink opacity-25 pulse-animation" style="width: 60px; height: 60px; left: -9px; top: -9px; z-index: 1; background-color: #ffb6c1;"></div>
                                @endif
                                <div class="rounded-circle tracking-icon-active d-flex justify-content-center align-items-center position-relative" style="width: 42px; height: 42px; z-index: 2;">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div class="ms-4 pt-1">
                                    <h6 class="fw-bold mb-1 text-pink">Pesanan Dibuat</h6>
                                    <p class="text-muted small mb-0">Pesanan tercatat pada sistem.</p>
                                    <span class="text-muted small">{{ $activeOrder->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Detail Pesanan -->
                        <div class="mt-4 pt-3 border-top border-light">
                            <a href="{{ route('orders.show', $activeOrder->id) }}" class="btn btn-pink w-100 rounded-pill fw-bold py-2 shadow-sm d-flex justify-content-center align-items-center gap-2 text-white" style="background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%); border: none;">
                                <i class="fas fa-file-invoice"></i> Lihat Detail Pesanan Lengkap
                            </a>
                        </div>
                    </div>
                @else
                    <div class="card-body p-5 text-center">
                        <div class="mb-4 text-muted opacity-50">
                            <i class="fas fa-box-open" style="font-size: 5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Belum Ada Pesanan Aktif</h5>
                        <p class="text-muted">Ayo belanja sekarang dan pantau proses pengirimannya di sini!</p>
                        <a href="{{ route('products') }}" class="btn btn-outline-primary rounded-pill px-4 mt-2">Mulai Belanja</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: SEMUA NOTIFIKASI -->
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-bell text-pink me-2"></i> Semua Notifikasi</h4>
                <button onclick="markAllAsRead()" class="btn btn-sm rounded-pill px-3 fw-bold" style="border: 2px solid #ff7eb3; color: #ff7eb3; background: transparent; transition: 0.3s;" onmouseover="this.style.backgroundColor='#ff7eb3'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ff7eb3';">Tandai Semua Dibaca</button>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid #fce4ec !important;">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notif)
                    <a href="{{ $notif->data['url'] ?? (isset($notif->data['order_id']) ? route('orders.show', $notif->data['order_id']) : '#') }}" class="list-group-item list-group-item-action p-4 border-bottom notif-hover {{ rtrim($notif->read_at) ? '' : 'unread-notif' }}">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-pink bg-opacity-10 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; flex-shrink: 0; color: #ff7eb3;">
                                <i class="fas {{ $notif->data['icon'] ?? 'fa-bell' }} fs-5"></i>
                            </div>
                            <div class="ms-3 w-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $notif->data['title'] ?? 'Notifikasi' }}</h6>
                                    @if(!$notif->read_at)
                                        <span class="badge rounded-circle p-1" style="width: 10px; height: 10px; background-color: #ff758c;"></span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-2">{{ $notif->data['message'] ?? '' }}</p>
                                <span class="text-pink fw-bold" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i> {{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="p-5 text-center">
                        <i class="far fa-bell-slash fa-3x mb-3" style="color: #ffb6c1; opacity: 0.5;"></i>
                        <p class="text-muted">Anda belum memiliki notifikasi apapun saat ini.</p>
                    </div>
                    @endforelse
                </div>
                
                <div class="card-footer bg-white text-center p-3 border-0">
                    @if($notifications->hasMorePages())
                        <a href="{{ $notifications->nextPageUrl() }}" class="text-decoration-none fw-bold" style="color: #ff758c;">Muat Lebih Banyak Notifikasi <i class="fas fa-chevron-down ms-1"></i></a>
                    @else
                        <span class="text-muted small">Semua notifikasi telah dimuat</span>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function markAllAsRead() {
    fetch('/api/notifications/mark-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    }).then(res => {
        if(res.ok) window.location.reload();
    });
}
</script>

<style>
    .text-pink { color: #FF6699; }
    
    /* Animasi Pulse untuk Tracker Realtime */
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(0.8); opacity: 0.5; }
        50% { transform: scale(1.2); opacity: 0.2; }
        100% { transform: scale(0.8); opacity: 0.5; }
    }
    
    .list-group-item-action {
        transition: all 0.3s ease;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa !important;
        transform: translateX(5px);
    }
</style>

@endsection