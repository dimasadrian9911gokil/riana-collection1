@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Laporan Penjualan</h3>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card card border-0">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <form action="{{ route('admin.reports.index') }}" method="GET" class="d-flex align-items-center">
                    <label class="fw-bold me-3">Rentang Tanggal:</label>
                    <input type="date" name="start_date" class="form-control me-2" value="{{ $startDate }}" style="width: 150px;">
                    <span class="me-2">s/d</span>
                    <input type="date" name="end_date" class="form-control me-2" value="{{ $endDate }}" style="width: 150px;">
                    <button type="submit" class="btn btn-dark">Filter</button>
                </form>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.reports.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success fw-bold shadow-sm" style="background: linear-gradient(135deg, #43a047, #66bb6a); border:none;">
                        <i class="fas fa-file-excel me-2"></i>Laporan Excel
                    </a>
                    <a href="{{ route('admin.reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger fw-bold shadow-sm" style="background: linear-gradient(135deg, #e53935, #ef5350); border:none;" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Laporan PDF
                    </a>
                    <div style="border-left: 2px dashed #ddd; margin: 0 10px;"></div>
                    <a href="{{ route('admin.backup.full') }}" class="btn text-white fw-bold shadow-sm" style="background: linear-gradient(135deg, #8e24aa, #ba68c8); border:none;" onclick="return confirm('Proses ini akan mengunduh seluruh data toko. Lanjutkan?')">
                        <i class="fas fa-database me-2"></i>Full Backup Excel
                    </a>
                    <a href="{{ route('admin.backup.full_pdf') }}" class="btn text-white fw-bold shadow-sm" style="background: linear-gradient(135deg, #e91e63, #f06292); border:none;" onclick="return confirm('Proses ini akan mengunduh seluruh data toko dalam bentuk PDF. Lanjutkan?')" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Full Backup PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .metric-card {
        border: none;
        border-radius: 15px;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(216, 27, 96, 0.2);
    }
    .metric-card .icon-wrapper {
        position: absolute;
        right: -10px;
        bottom: -20px;
        opacity: 0.15;
        font-size: 7rem;
        transform: rotate(-15deg);
        transition: transform 0.3s ease;
    }
    .metric-card:hover .icon-wrapper {
        transform: rotate(0deg) scale(1.1);
    }
    .bg-gradient-pink {
        background: linear-gradient(135deg, #d81b60 0%, #ff4081 100%);
    }
    .bg-gradient-purple {
        background: linear-gradient(135deg, #8e24aa 0%, #ba68c8 100%);
    }
    .bg-gradient-blue {
        background: linear-gradient(135deg, #1e88e5 0%, #64b5f6 100%);
    }
    .bg-gradient-orange {
        background: linear-gradient(135deg, #f57c00 0%, #ffb74d 100%);
    }
</style>

<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card metric-card bg-gradient-pink h-100 p-4 shadow">
            <div class="icon-wrapper">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-2 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;"><i class="fas fa-money-bill-wave me-2"></i>Total Pendapatan</p>
                <h3 class="fw-bold mb-0 text-white text-truncate" title="Rp{{ number_format($totalRevenue, 0, ',', '.') }}">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card metric-card bg-gradient-purple h-100 p-4 shadow">
            <div class="icon-wrapper">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-2 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;"><i class="fas fa-check-circle me-2"></i>Total Transaksi</p>
                <h3 class="fw-bold mb-0 text-white">{{ $orders->count() }} <span style="font-size: 1rem; font-weight: normal; opacity: 0.8;">Pesanan</span></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card metric-card bg-gradient-blue h-100 p-4 shadow">
            <div class="icon-wrapper">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-2 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;"><i class="fas fa-boxes me-2"></i>Produk Terjual</p>
                <h3 class="fw-bold mb-0 text-white">{{ $totalProductsSold }} <span style="font-size: 1rem; font-weight: normal; opacity: 0.8;">Pcs</span></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card metric-card bg-gradient-orange h-100 p-4 shadow">
            <div class="icon-wrapper">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="position-relative" style="z-index: 1;">
                <p class="mb-2 text-uppercase fw-bold text-white-50" style="font-size:0.75rem; letter-spacing: 1px;"><i class="fas fa-calculator me-2"></i>Rata-Rata Pesanan</p>
                <h3 class="fw-bold mb-0 text-white text-truncate" title="Rp{{ number_format($averageOrderValue, 0, ',', '.') }}">Rp{{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-area text-primary me-2"></i> Laporan Harian: Grafik Pendapatan per Hari</h6>
            <div style="position: relative; height:300px; width:100%">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-pie text-success me-2"></i> Pendapatan Berdasarkan Kategori</h6>
            <div style="position: relative; height:250px; width:100%">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-credit-card text-warning me-2"></i> Metode Pembayaran Populer</h6>
            <div style="position: relative; height:250px; width:100%">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-list text-info me-2"></i>Rincian Penjualan ({{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }})</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="fw-bold">{{ $order->invoice }}</td>
                        <td>{{ $order->user->name ?? 'User Dihapus' }}</td>
                        <td>
                            @if($order->status == 'paid')
                                <span class="badge bg-info px-2 py-1 rounded-pill">Dibayar</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-primary px-2 py-1 rounded-pill">Diproses</span>
                            @elseif($order->status == 'shipped')
                                <span class="badge bg-secondary px-2 py-1 rounded-pill">Dikirim</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success px-2 py-1 rounded-pill">Selesai</span>
                            @endif
                        </td>
                        <td class="fw-bold text-danger">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada penjualan valid di bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-file-invoice text-muted fa-3x mb-3 opacity-25"></i>
            <p class="text-muted mb-0">Tidak ada transaksi penjualan pada rentang tanggal tersebut.</p>
        </div>
        @endif
    </div>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-box-open text-warning me-2"></i>Rincian Produk Terjual ({{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }})</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Pendapatan Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($soldProducts as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($item->product)
                                <img src="{{ asset('storage/'.$item->product->image) }}" class="rounded me-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Rp{{ number_format($item->product->price, 0, ',', '.') }}</small>
                                </div>
                                @else
                                <span class="text-muted">Produk Dihapus</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->product ? ($item->product->category->name ?? '-') : '-' }}</td>
                        <td><span class="badge bg-primary rounded-pill px-3 py-2">{{ $item->total_qty }} Pcs</span></td>
                        <td><span class="fw-bold text-success">Rp{{ number_format($item->total_revenue, 0, ',', '.') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(isset($soldProducts) && $soldProducts->isEmpty())
        <div class="text-center py-4">
            <i class="fas fa-box text-muted fa-2x mb-2 opacity-25"></i>
            <p class="text-muted mb-0 small">Belum ada produk yang terjual.</p>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('dailySalesChart');
        if(canvas) {
            const ctx = canvas.getContext('2d');
            const chartData = @json($dailyChartData);
            
            // Buat efek gradasi warna vertikal untuk Bar (Batang grafik)
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(216, 27, 96, 0.9)'); // Pink tua di atas
            gradient.addColorStop(1, 'rgba(255, 154, 158, 0.3)'); // Soft pink di bawah

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: chartData.data,
                        backgroundColor: gradient,
                        borderColor: '#d81b60',
                        borderWidth: { top: 2, right: 0, bottom: 0, left: 0 },
                        borderRadius: 6, // Ujung batang melengkung
                        barPercentage: 0.6,
                        hoverBackgroundColor: '#d81b60'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#333',
                            bodyColor: '#d81b60',
                            bodyFont: { weight: 'bold', size: 14 },
                            borderColor: '#ffe0e9',
                            borderWidth: 2,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                title: function(context) {
                                    return 'Tanggal: ' + context[0].label;
                                },
                                label: function(context) {
                                    let value = context.raw || 0;
                                    return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#f1f3f5' },
                            ticks: {
                                font: { size: 11, family: "'Inter', sans-serif" },
                                callback: function(value) {
                                    if(value === 0) return 'Rp 0';
                                    if(value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + ' Jt';
                                    }
                                    return 'Rp ' + (value / 1000) + ' Rb';
                                }
                            }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 11, family: "'Inter', sans-serif" } }
                        }
                    },
                    animation: {
                        y: { duration: 2000, easing: 'easeOutElastic' }
                    }
                }
            });
        }

        // --- CHART KATEGORI (PIE) ---
        const catCanvas = document.getElementById('categoryChart');
        if(catCanvas) {
            const catData = @json($categoryChartData);
            new Chart(catCanvas.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: catData.labels,
                    datasets: [{
                        data: catData.data,
                        backgroundColor: ['#ff6699', '#4facfe', '#f6d365', '#43e97b', '#c471ed', '#ff758c'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { font: { family: "'Inter', sans-serif" } } },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#333',
                            bodyColor: '#d81b60',
                            bodyFont: { weight: 'bold', size: 13 },
                            borderColor: '#ffe0e9',
                            borderWidth: 2,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw || 0;
                                    return ' Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        // --- CHART METODE PEMBAYARAN (DOUGHNUT) ---
        const pmCanvas = document.getElementById('paymentMethodChart');
        if(pmCanvas) {
            const pmData = @json($paymentMethodChartData);
            new Chart(pmCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: pmData.labels,
                    datasets: [{
                        data: pmData.data,
                        backgroundColor: ['#00b0ff', '#ff9100', '#00e676', '#ff1744', '#7c4dff'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'right', labels: { font: { family: "'Inter', sans-serif" } } },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#333',
                            bodyColor: '#00b0ff',
                            bodyFont: { weight: 'bold', size: 13 },
                            borderColor: '#e1f5fe',
                            borderWidth: 2,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw || 0;
                                    return ' ' + value + ' Transaksi';
                                }
                            }
                        }
                    }
                }
            });
        }

    });
</script>
@endsection
