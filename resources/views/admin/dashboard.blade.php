@extends('layouts.admin')

@section('title', 'Admin Dashboard - Riana Collection')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Dashboard 24 Jam Terakhir</h3>
    <div class="text-muted"><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::now()->format('l, d F Y H:i') }}</div>
</div>

<style>
    .metric-card { border: none; border-radius: 15px; color: white; transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden; position: relative; }
    .metric-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15); }
    .metric-card .icon-wrapper { position: absolute; right: -10px; bottom: -20px; opacity: 0.15; font-size: 7rem; transform: rotate(-15deg); transition: transform 0.3s ease; }
    .metric-card:hover .icon-wrapper { transform: rotate(0deg) scale(1.1); }
    .bg-grad-1 { background: linear-gradient(135deg, #d81b60 0%, #ff4081 100%); }
    .bg-grad-2 { background: linear-gradient(135deg, #f57c00 0%, #ffb74d 100%); }
    .bg-grad-3 { background: linear-gradient(135deg, #43a047 0%, #81c784 100%); }
    .bg-grad-4 { background: linear-gradient(135deg, #1e88e5 0%, #64b5f6 100%); }
    
    .quick-action-btn { transition: all 0.3s ease; border-radius: 12px; }
    .quick-action-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
</style>

<!-- TOP METRICS (HARI INI) -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card bg-grad-1 h-100 p-4 shadow-sm">
            <div class="icon-wrapper"><i class="fas fa-wallet"></i></div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-1 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;">Pendapatan 24 Jam</p>
                <h3 class="fw-bold mb-0 text-white">Rp{{ number_format($todaySales, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card bg-grad-2 h-100 p-4 shadow-sm">
            <div class="icon-wrapper"><i class="fas fa-cart-arrow-down"></i></div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-1 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;">Pesanan Baru (24 Jam)</p>
                <h3 class="fw-bold mb-0 text-white">{{ $pendingOrdersToday }} <span style="font-size: 1rem; opacity:0.8;">Order</span></h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card bg-grad-3 h-100 p-4 shadow-sm">
            <div class="icon-wrapper"><i class="fas fa-check-circle"></i></div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-1 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;">Pesanan Lunas (24 Jam)</p>
                <h3 class="fw-bold mb-0 text-white">{{ $paidOrdersToday }} <span style="font-size: 1rem; opacity:0.8;">Order</span></h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card bg-grad-4 h-100 p-4 shadow-sm">
            <div class="icon-wrapper"><i class="fas fa-user-plus"></i></div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-1 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;">Pendaftar Baru (24 Jam)</p>
                <h3 class="fw-bold mb-0 text-white">{{ $newUsersToday }} <span style="font-size: 1rem; opacity:0.8;">User</span></h3>
            </div>
        </div>
    </div>
</div>

<!-- STATUS PESANAN (Keseluruhan) -->
<h6 class="fw-bold text-dark mt-2 mb-3"><i class="fas fa-tasks text-primary me-2"></i>Rincian Status Pesanan</h6>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="admin-card card border-0 shadow-sm text-center p-3 h-100 text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);">
            <h4 class="fw-bold mb-1">{{ $orderStats['menunggu_pembayaran'] }}</h4>
            <small class="fw-bold opacity-75" style="font-size: 0.7rem;">BELUM BAYAR</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="admin-card card border-0 shadow-sm text-center p-3 h-100 text-dark" style="background: linear-gradient(135deg, #0dcaf0 0%, #31d2f2 100%);">
            <h4 class="fw-bold mb-1">{{ $orderStats['menunggu_verifikasi'] }}</h4>
            <small class="fw-bold opacity-75" style="font-size: 0.7rem;">VERIFIKASI BAYAR</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="admin-card card border-0 shadow-sm text-center p-3 h-100 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #3b82f6 100%);">
            <h4 class="fw-bold mb-1">{{ $orderStats['diproses'] }}</h4>
            <small class="fw-bold text-white-50" style="font-size: 0.7rem;">DIPROSES/KEMAS</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="admin-card card border-0 shadow-sm text-center p-3 h-100 text-white" style="background: linear-gradient(135deg, #6c757d 0%, #8b9298 100%);">
            <h4 class="fw-bold mb-1">{{ $orderStats['dikirim'] }}</h4>
            <small class="fw-bold text-white-50" style="font-size: 0.7rem;">DIKIRIM (RESI)</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="admin-card card border-0 shadow-sm text-center p-3 h-100 text-white" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
            <h4 class="fw-bold mb-1">{{ $orderStats['selesai'] }}</h4>
            <small class="fw-bold text-white-50" style="font-size: 0.7rem;">SELESAI/LUNAS</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="admin-card card border-0 shadow-sm text-center p-3 h-100 text-white" style="background: linear-gradient(135deg, #dc3545 0%, #f87171 100%);">
            <h4 class="fw-bold mb-1">{{ $orderStats['dibatalkan'] }}</h4>
            <small class="fw-bold text-white-50" style="font-size: 0.7rem;">DITOLAK/BATAL</small>
        </div>
    </div>
</div>

<!-- QUICK ACTIONS & STATS -->
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="row g-3">
            <div class="col-sm-4">
                <a href="{{ route('admin.products.create') }}" class="btn btn-white w-100 p-3 quick-action-btn text-start border-0 shadow d-flex align-items-center bg-white text-decoration-none">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3"><i class="fas fa-plus"></i></div>
                    <div><h6 class="mb-0 fw-bold text-dark">Tambah Produk</h6><small class="text-muted">Katalog Baru</small></div>
                </a>
            </div>
            <div class="col-sm-4">
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-white w-100 p-3 quick-action-btn text-start border-0 shadow d-flex align-items-center bg-white text-decoration-none">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3"><i class="fas fa-ticket-alt"></i></div>
                    <div><h6 class="mb-0 fw-bold text-dark">Buat Voucher</h6><small class="text-muted">Diskon Toko</small></div>
                </a>
            </div>
            <div class="col-sm-4">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-white w-100 p-3 quick-action-btn text-start border-0 shadow d-flex align-items-center bg-white text-decoration-none">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3"><i class="fas fa-file-excel"></i></div>
                    <div><h6 class="mb-0 fw-bold text-dark">Cetak Laporan</h6><small class="text-muted">Unduh Excel/PDF</small></div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="row g-3">
            <div class="col-6">
                <div class="admin-card card h-100 p-3 border-0 shadow text-center">
                    <h3 class="fw-bold text-primary mb-0">{{ $totalProductsCount }}</h3>
                    <small class="text-muted fw-bold">TOTAL PRODUK</small>
                </div>
            </div>
            <div class="col-6">
                <div class="admin-card card h-100 p-3 border-0 shadow text-center">
                    <h3 class="fw-bold text-info mb-0">{{ $totalCustomers }}</h3>
                    <small class="text-muted fw-bold">TOTAL USER</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- CHART PENJUALAN -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm h-100 p-4">
            <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-line text-primary me-2"></i> Grafik Pendapatan 24 Jam Terakhir (Per 4 Jam)</h6>
            <div style="position: relative; height:300px; width:100%">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- BEST SELLING PRODUCTS -->
    <div class="col-12 col-lg-5">
        <div class="admin-card card mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-medal text-warning me-2"></i>Produk Terlaris</h6>
            </div>
            <div class="card-body p-4">
                @if($bestSellingProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach($bestSellingProducts as $index => $item)
                                <tr class="border-bottom">
                                    <td class="ps-0 py-2" style="width: 30px;">
                                        <h5 class="fw-bold text-muted mb-0">#{{ $index + 1 }}</h5>
                                    </td>
                                    <td class="py-2">
                                        <div class="d-flex align-items-center">
                                            @if($item->product)
                                            <img src="{{ asset('storage/'.$item->product->image) }}" class="rounded me-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ Str::limit($item->product->name, 25) }}</h6>
                                                <small class="text-danger fw-bold">Rp{{ number_format($item->product->price, 0, ',', '.') }}</small>
                                            </div>
                                            @else
                                            <span class="text-muted">Produk Dihapus</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="pe-0 py-2 text-end">
                                        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $item->total_sold }} Terjual</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open text-muted fa-3x mb-3 opacity-25"></i>
                        <p class="text-muted mb-0">Belum ada data penjualan yang valid.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- BEST SELLING BRANDS -->
        <div class="admin-card card">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-crown text-danger me-2"></i>Brand Terlaris</h6>
            </div>
            <div class="card-body p-4">
                @if(isset($bestSellingBrands) && $bestSellingBrands->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach($bestSellingBrands as $index => $brand)
                                <tr class="border-bottom">
                                    <td class="ps-0 py-2" style="width: 30px;">
                                        <h5 class="fw-bold text-muted mb-0">#{{ $index + 1 }}</h5>
                                    </td>
                                    <td class="py-2">
                                        <div class="d-flex align-items-center">
                                            @if($brand->logo)
                                            <img src="{{ asset('storage/'.$brand->logo) }}" class="rounded-circle me-3 shadow-sm border" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                            <div class="rounded-circle me-3 shadow-sm border bg-light d-flex justify-content-center align-items-center text-primary fw-bold" style="width: 40px; height: 40px;">
                                                {{ substr($brand->name, 0, 1) }}
                                            </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">{{ $brand->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pe-0 py-2 text-end">
                                        <span class="badge bg-danger rounded-pill px-3 py-2">{{ $brand->total_sold }} Terjual</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-copyright text-muted fa-2x mb-2 opacity-25"></i>
                        <p class="text-muted mb-0 small">Data penjualan brand belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- RECENT ORDERS -->
    <div class="col-12 col-lg-7">
        <div class="admin-card card h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-clock text-info me-2"></i>5 Pesanan Terbaru (Live)</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-4">
                @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice & Waktu</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $order->invoice }}</h6>
                                    <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                </td>
                                <td>{{ $order->user->name ?? 'User Terhapus' }}</td>
                                <td>
                                    @if($order->status == 'pending') <span class="badge bg-warning text-dark px-2 py-1">Pending</span>
                                    @elseif($order->status == 'paid') <span class="badge bg-info px-2 py-1">Dibayar</span>
                                    @elseif($order->status == 'processing') <span class="badge bg-primary px-2 py-1">Diproses</span>
                                    @elseif($order->status == 'shipped') <span class="badge bg-secondary px-2 py-1">Dikirim</span>
                                    @else <span class="badge bg-success px-2 py-1">Selesai</span> @endif
                                </td>
                                <td class="text-end fw-bold text-danger">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">Belum ada pesanan masuk.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- LOW STOCK ALERTS -->
    <div class="col-12 col-lg-5">
        <div class="admin-card card h-100 border-danger border-2">
            <div class="card-header bg-danger bg-opacity-10 border-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Peringatan Stok Menipis (<= 10)</h6>
            </div>
            <div class="card-body p-4">
                @if($lowStockProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            <tr class="border-bottom">
                                <td class="ps-0 py-2">
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ Str::limit($product->name, 30) }}</h6>
                                </td>
                                <td class="pe-0 py-2 text-end">
                                    <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill px-3 py-2">Sisa {{ $product->stock }}</span>
                                </td>
                                <td class="pe-0 py-2 text-end" style="width:50px;">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-plus"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-success">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <p class="mb-0">Semua stok produk aman!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Data dari Controller
    const labels = {!! json_encode($chartData['labels']) !!};
    const dataPoints = {!! json_encode($chartData['data']) !!};

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(216, 27, 96, 0.8)'); // Pinkish
    gradient.addColorStop(1, 'rgba(255, 128, 171, 0.5)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: dataPoints,
                backgroundColor: gradient,
                borderColor: '#d81b60',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed.y;
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f0f0', drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            if(value >= 1000000) return 'Rp' + (value/1000000).toFixed(1) + 'M';
                            if(value >= 1000) return 'Rp' + (value/1000) + 'k';
                            return 'Rp' + value;
                        }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });
});
</script>
@endpush
