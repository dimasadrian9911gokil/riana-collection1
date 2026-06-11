@extends('layouts.admin')

@section('title', 'Detail Pelanggan - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Detail Pelanggan</h3>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Profil Pelanggan -->
    <div class="col-md-4">
        <div class="admin-card card mb-4">
            <div class="card-body text-center p-4">
                @if($customer->avatar)
                    <img src="{{ asset('storage/'.$customer->avatar) }}" class="rounded-circle mb-3 border" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h5 class="fw-bold text-dark mb-1">{{ $customer->name }}</h5>
                <p class="text-muted mb-3">{{ $customer->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($customer->is_active)
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Akun Aktif</span>
                    @else
                        <span class="badge bg-danger"><i class="fas fa-ban me-1"></i> Diblokir</span>
                    @endif
                    <span class="badge bg-primary">{{ $customer->orders->count() ?? 0 }} Pesanan</span>
                </div>
                
                <div class="text-start mt-4">
                    <p class="mb-2 text-muted small fw-bold">Tanggal Mendaftar</p>
                    <p class="mb-0 fw-medium">{{ $customer->created_at->format('d F Y - H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Alamat & Pesanan -->
    <div class="col-md-8">
        <!-- Daftar Alamat -->
        <div class="admin-card card mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-map-marker-alt text-danger me-2"></i>Daftar Alamat Tersimpan</h6>
            </div>
            <div class="card-body p-4">
                @forelse($customer->addresses as $address)
                <div class="card border mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-secondary mb-2"><i class="fas fa-tag me-1"></i>{{ $address->label }}</span>
                                @if($address->is_primary)
                                    <span class="badge bg-danger mb-2 ms-1"><i class="fas fa-star me-1"></i>Utama</span>
                                @endif
                                <h6 class="fw-bold mb-1">{{ $address->receiver_name }} <span class="text-muted fw-normal">({{ $address->receiver_phone }})</span></h6>
                            </div>
                        </div>
                        <p class="mb-1 text-muted">{{ $address->full_address }}</p>
                        <p class="mb-0 text-muted">{{ $address->district }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-map-signs fs-2 mb-3 opacity-25"></i>
                    <p class="mb-0">Pelanggan ini belum menyimpan alamat pengiriman apa pun.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Pesanan Terakhir -->
        <div class="admin-card card">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-box-open text-warning me-2"></i>5 Pesanan Terakhir</h6>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Total Belanja</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->orders as $order)
                            <tr>
                                <td class="fw-bold text-primary">{{ $order->invoice }}</td>
                                <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'menunggu_pembayaran' => 'bg-warning text-dark',
                                            'sudah_dibayar' => 'bg-info text-dark',
                                            'dikemas' => 'bg-primary',
                                            'dikirim' => 'bg-secondary',
                                            'selesai' => 'bg-success',
                                            'dibatalkan' => 'bg-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$order->status] ?? 'bg-dark' }}">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.index', ['search' => $order->invoice]) }}" class="btn btn-sm btn-outline-secondary" title="Cari di Daftar Pesanan">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat pesanan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
