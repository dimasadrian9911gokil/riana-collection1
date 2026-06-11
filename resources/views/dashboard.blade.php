@extends('layouts.app')

@section('title', 'Dashboard Member')

@section('content')

@php
    $alamatUtama = Auth::user()->addresses()->where('is_default', 1)->first() 
                ?? Auth::user()->addresses()->latest()->first();

    $hour = date('H');
    if ($hour < 12) $greeting = 'Selamat Pagi';
    elseif ($hour < 15) $greeting = 'Selamat Siang';
    elseif ($hour < 18) $greeting = 'Selamat Sore';
    else $greeting = 'Selamat Malam';

    $orderCount = Auth::user()->orders()->count();
    $cartCount = Auth::user()->carts()->count();
    $wishlistCount = Auth::user()->wishlists()->count();
    
    // Simulasi Poin berdasarkan jumlah pesanan
    $poin = $orderCount * 50; 
    $progress = min(($poin / 500) * 100, 100);
@endphp

<style>
    /* ... existing styles ... */
    .dashboard-bg {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%);
        min-height: 100vh;
    }
    .stat-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 15px;
        overflow: hidden;
        border: none !important;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Custom Gradients for Quick Menu */
    .menu-belanja { background: linear-gradient(135deg, #FF6699 0%, #E91E63 100%); color: white; }
    .menu-pesanan { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); color: white; }
    .menu-wishlist { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; }
    .menu-profil { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: white; }
    
    .menu-btn {
        transition: 0.3s;
        border-radius: 15px;
        border: none;
    }
    .menu-btn:hover {
        transform: scale(1.05);
        color: white;
    }

    /* Custom Gradients for Stats */
    .stat-order { background: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%); }
    .stat-cart { background: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%); }
    .stat-wish { background: linear-gradient(120deg, #ff9a9e 0%, #fecfef 100%); }
    .stat-poin { background: linear-gradient(120deg, #f6d365 0%, #fda085 100%); }
</style>

<div class="dashboard-bg py-5">
    <div class="container">
        <!-- HEADER PROFILE -->
        <div class="card border-0 shadow-sm mb-4 position-relative" style="border-radius: 20px; overflow: hidden; border: 1px solid #fce4ec !important;">
            <!-- Dekorasi latar belakang (lingkaran samar) -->
            <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); opacity: 0.1; top: -150px; left: -50px; z-index: 0;"></div>
            <div class="position-absolute rounded-circle" style="width: 200px; height: 200px; background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); opacity: 0.1; bottom: -100px; right: 20%; z-index: 0;"></div>
            
            <div class="card-body p-0 position-relative z-index-1">
                <div class="row g-0">
                    <div class="col-md-7 p-4 p-md-5 d-flex align-items-center bg-white">
                        <div class="me-4 position-relative flex-shrink-0">
                            <div class="rounded-circle p-1" style="background: linear-gradient(135deg, #FF6699 0%, #ffb6c1 100%); display: inline-block;">
                                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=E91E63&color=fff&size=150' }}" 
                                     class="rounded-circle profile-img" style="border: 4px solid #fff; object-fit: cover; width: 110px; height: 110px;" alt="Avatar">
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-light rounded-circle shadow-sm" title="Online" style="margin-bottom: 5px; margin-right: 5px;"></span>
                        </div>
                        <div>
                            <h5 class="text-muted mb-1" style="font-weight: 500;">{{ $greeting }},</h5>
                            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">{{ Auth::user()->name }}</h2>
                            <p class="text-muted mb-3"><i class="fas fa-envelope me-2 text-pink"></i>{{ Auth::user()->email }}</p>
                            <span class="badge px-4 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #FF6699, #E91E63); font-size: 0.85rem;"><i class="fas fa-crown me-1"></i> Beauty Member</span>
                        </div>
                    </div>
                    <div class="col-md-5 p-4 p-md-5 d-flex flex-column justify-content-center border-start-md" style="background: #fff9fb; border-color: #fce4ec !important;">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-star text-warning me-2"></i>Progress Member</h6>
                                <small class="text-muted">Kumpulkan poin untuk naik level!</small>
                            </div>
                            <h4 class="fw-bold mb-0" style="color: #E91E63;">{{ $poin }} <span class="fs-6 text-muted fw-normal">/ 500</span></h4>
                        </div>
                        <div class="progress my-3 rounded-pill shadow-sm" style="height: 14px; background-color: #fce4ec;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="background: linear-gradient(45deg, #FF6699, #E91E63); width: {{ $progress }}%;"></div>
                        </div>
                        <p class="text-muted text-center mb-0 small fw-bold">
                            @if(500 - $poin > 0)
                                <i class="fas fa-arrow-up text-pink me-1"></i> {{ 500 - $poin }} poin lagi menuju <span class="text-warning">Gold Member!</span>
                            @else
                                <i class="fas fa-gem text-info me-1"></i> Selamat! Kamu adalah <span class="text-warning">Gold Member!</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUICK MENU -->
        <div class="row mb-4 g-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('home') }}" class="btn menu-belanja w-100 py-3 fw-bold menu-btn shadow-sm">
                    <i class="fas fa-shopping-bag mb-2 d-block fa-2x"></i> Belanja
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('orders.index') }}" class="btn menu-pesanan w-100 py-3 fw-bold menu-btn shadow-sm">
                    <i class="fas fa-box mb-2 d-block fa-2x"></i> Pesanan
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('wishlist.index') }}" class="btn menu-wishlist w-100 py-3 fw-bold menu-btn shadow-sm text-dark">
                    <i class="fas fa-heart mb-2 d-block fa-2x text-danger"></i> Wishlist
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('profile.edit') }}" class="btn menu-profil w-100 py-3 fw-bold menu-btn shadow-sm text-dark">
                    <i class="fas fa-user-cog mb-2 d-block fa-2x text-primary"></i> Profil
                </a>
            </div>
        </div>

        <!-- STATISTIK -->
        <div class="row mb-4 g-3">
            <div class="col-6 col-md-3">
                <div class="card stat-card stat-order shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                        <div class="text-white mb-2"><i class="fas fa-box-open fa-2x opacity-75"></i></div>
                        <h6 class="text-white fw-bold mb-1 opacity-75">Total Pesanan</h6>
                        <h2 class="fw-bold text-white mb-0">{{ $orderCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card stat-cart shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                        <div class="text-white mb-2"><i class="fas fa-shopping-cart fa-2x opacity-75"></i></div>
                        <h6 class="text-white fw-bold mb-1 opacity-75">Di Keranjang</h6>
                        <h2 class="fw-bold text-white mb-0">{{ $cartCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card stat-wish shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                        <div class="text-danger mb-2"><i class="fas fa-heart fa-2x opacity-75"></i></div>
                        <h6 class="text-dark fw-bold mb-1 opacity-75">Wishlist</h6>
                        <h2 class="fw-bold text-dark mb-0">{{ $wishlistCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card stat-poin shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                        <div class="text-white mb-2"><i class="fas fa-star fa-2x opacity-75"></i></div>
                        <h6 class="text-white fw-bold mb-1 opacity-75">Poin Reward</h6>
                        <h2 class="fw-bold text-white mb-0">{{ $poin }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- LEFT COLUMN -->
            <div class="col-md-7 col-lg-8">
                <!-- PESANAN TERAKHIR -->
                <div class="card border-0 shadow-sm mb-4 stat-card bg-white">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-clock text-danger me-2"></i> Pesanan Terakhir</h5>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm rounded-pill px-3 fw-bold" style="border: 2px solid #ff758c; color: #ff758c; transition: 0.3s;" onmouseover="this.style.backgroundColor='#ff758c'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ff758c';">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @php
                            $recentOrders = Auth::user()->orders()->latest()->take(3)->get();
                        @endphp
                        
                        @if($recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover align-middle mb-0">
                                    <thead class="border-bottom border-pink" style="border-color: #ffb6c1 !important;">
                                        <tr class="text-muted small">
                                            <th>Order ID</th>
                                            <th>Tanggal</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                        <tr style="transition: 0.2s;" onmouseover="this.style.backgroundColor='#fff0f5';" onmouseout="this.style.backgroundColor='transparent';">
                                            <td class="fw-bold">#{{ $order->order_number ?? $order->id }}</td>
                                            <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                            <td class="fw-bold text-danger">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td>
                                                @if($order->status == 'pending')
                                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Belum Dibayar</span>
                                                @elseif($order->status == 'processing')
                                                    <span class="badge bg-info text-dark px-2 py-1 rounded-pill">Diproses</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge px-2 py-1 rounded-pill text-white" style="background: linear-gradient(135deg, #a18cd1, #fbc2eb);">Dikirim</span>
                                                @elseif($order->status == 'completed')
                                                    <span class="badge px-2 py-1 rounded-pill text-white" style="background: linear-gradient(135deg, #84fab0, #8fd3f4);">Selesai</span>
                                                @else
                                                    <span class="badge bg-secondary px-2 py-1 rounded-pill">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-light fw-bold rounded-pill px-3" style="color: #ff758c;">Detail</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 bg-light rounded-4">
                                <img src="https://cdn-icons-png.flaticon.com/512/1008/1008010.png" width="60" class="mb-3 opacity-50" alt="Empty">
                                <h6 class="fw-bold text-dark">Belum ada pesanan nih</h6>
                                <p class="text-muted small mb-3">Pesan produk impianmu sekarang juga!</p>
                                <a href="{{ route('products') }}" class="btn btn-sm menu-belanja rounded-pill px-4">Mulai Belanja</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ALAMAT UTAMA -->
                <div class="card border-0 shadow-sm stat-card bg-white">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marker-alt text-danger me-2"></i> Alamat Utama</h5>
                        <a href="{{ route('address.index') }}" class="btn btn-sm rounded-pill px-3 fw-bold" style="border: 2px solid #ff758c; color: #ff758c; transition: 0.3s;" onmouseover="this.style.backgroundColor='#ff758c'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ff758c';">Kelola Alamat</a>
                    </div>
                    <div class="card-body">
                        @if($alamatUtama)
                            <div class="d-flex p-4 border rounded-4 bg-light position-relative overflow-hidden" style="border-color: #fce4ec !important;">
                                <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.05; transform: rotate(15deg);">
                                    <i class="fas fa-map-marked-alt" style="font-size: 8rem;"></i>
                                </div>
                                <div class="me-3 mt-1 position-relative z-index-1">
                                    <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white;">
                                        <i class="fas fa-home fa-lg"></i>
                                    </div>
                                </div>
                                <div class="position-relative z-index-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 me-2 text-dark">{{ $alamatUtama->recipient_name }}</h6>
                                        <span class="badge rounded-pill" style="font-size: 0.65rem; background: linear-gradient(135deg, #FF6699, #E91E63);">{{ $alamatUtama->label ?? 'Utama' }}</span>
                                    </div>
                                    <p class="mb-1 text-muted small"><i class="fas fa-phone-alt me-1 text-pink"></i> {{ $alamatUtama->phone }}</p>
                                    <p class="text-muted small mb-0">
                                        {{ $alamatUtama->address }}<br>
                                        {{ $alamatUtama->district }}, {{ $alamatUtama->city }}, {{ $alamatUtama->province }} {{ $alamatUtama->postal_code }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4 border rounded-4" style="border-style: dashed !important; border-color: #ffb6c1 !important;">
                                <i class="fas fa-map-marked-alt fa-2x mb-2" style="color: #ffb6c1;"></i>
                                <h6 class="fw-bold text-dark">Belum Ada Alamat</h6>
                                <p class="text-muted small">Tambahkan alamat agar kamu bisa mulai berbelanja.</p>
                                <a href="{{ route('address.index') }}" class="btn btn-sm menu-belanja rounded-pill px-4">Tambah Alamat</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-5 col-lg-4">
                <!-- VOUCHER -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: linear-gradient(135deg, #E91E63, #FF6699); color: white;">
                    <div class="card-body position-relative overflow-hidden p-4">
                        <i class="fas fa-ticket-alt position-absolute" style="font-size: 8rem; right: -20px; bottom: -20px; opacity: 0.1; transform: rotate(-15deg);"></i>
                        <h5 class="fw-bold mb-3"><i class="fas fa-gift me-2"></i> Voucher Saya</h5>
                        @php
                            $availableVouchers = \App\Models\Voucher::where('is_active', true)->orderBy('min_spend', 'asc')->take(3)->get();
                        @endphp
                        
                        @forelse($availableVouchers as $v)
                        <div class="bg-white text-dark p-3 rounded-3 shadow-sm mb-2 position-relative" style="border-left: 4px dashed #E91E63;">
                            <h6 class="fw-bold text-danger mb-1">{{ $v->name }}</h6>
                            <p class="small text-muted mb-2">Min. belanja Rp {{ number_format($v->min_spend, 0, ',', '.') }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted" style="font-size: 0.7rem;">Kode: <strong>{{ $v->code }}</strong></small>
                                <button class="btn btn-sm btn-outline-danger py-0 px-2 rounded-1" style="font-size: 0.7rem;" onclick="navigator.clipboard.writeText('{{ $v->code }}'); alert('Kode voucher disalin!');">Salin Kode</button>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white text-muted p-3 rounded-3 shadow-sm text-center">
                            <small>Belum ada voucher saat ini.</small>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
