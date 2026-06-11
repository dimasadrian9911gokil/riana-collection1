@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Pesanan</h3>
    <div class="text-muted"><i class="fas fa-filter me-2"></i>Status: {{ $status ? ucfirst($status) : 'Semua Pesanan' }}</div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.orders.index') }}" class="btn {{ !$status ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
            <a href="{{ route('admin.orders.index', ['status' => 'menunggu_pembayaran']) }}" class="btn {{ $status == 'menunggu_pembayaran' ? 'btn-warning' : 'btn-outline-warning text-dark' }}">Menunggu Pembayaran</a>
            <a href="{{ route('admin.orders.index', ['status' => 'menunggu_verifikasi']) }}" class="btn {{ $status == 'menunggu_verifikasi' ? 'btn-info' : 'btn-outline-info text-dark' }}">Menunggu Verifikasi</a>
            <a href="{{ route('admin.orders.index', ['status' => 'sudah_dibayar']) }}" class="btn {{ $status == 'sudah_dibayar' ? 'btn-success' : 'btn-outline-success text-dark' }}">Sudah Dibayar</a>
            <a href="{{ route('admin.orders.index', ['status' => 'dikemas']) }}" class="btn {{ $status == 'dikemas' ? 'btn-primary' : 'btn-outline-primary' }}">Dikemas</a>
            <a href="{{ route('admin.orders.index', ['status' => 'dikirim']) }}" class="btn {{ $status == 'dikirim' ? 'btn-secondary' : 'btn-outline-secondary text-dark' }}">Dikirim</a>
            <a href="{{ route('admin.orders.index', ['status' => 'selesai']) }}" class="btn {{ $status == 'selesai' ? 'btn-success' : 'btn-outline-success' }}">Selesai</a>
            <a href="{{ route('admin.orders.index', ['status' => 'dibatalkan']) }}" class="btn {{ $status == 'dibatalkan' ? 'btn-danger' : 'btn-outline-danger' }}">Dibatalkan</a>
        </div>
    </div>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-shopping-cart text-info me-2"></i>Daftar Pesanan Masuk</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice / Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Resi (Tracking)</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <h6 class="mb-0 fw-bold text-dark">{{ $order->invoice }}</h6>
                            <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                        </td>
                        <td>
                            <h6 class="mb-0 fw-bold text-dark">{{ $order->user->name ?? 'User Terhapus' }}</h6>
                            <small class="text-muted">{{ $order->user->phone ?? '-' }}</small>
                        </td>
                        <td><span class="fw-bold text-danger">Rp{{ number_format($order->total, 0, ',', '.') }}</span></td>
                        <td>
                            @if($order->status == 'menunggu_pembayaran') <span class="badge bg-warning text-dark px-3 py-2">Menunggu Pembayaran</span>
                            @elseif($order->status == 'menunggu_verifikasi') <span class="badge bg-info px-3 py-2">Menunggu Verifikasi</span>
                            @elseif($order->status == 'sudah_dibayar') <span class="badge bg-success px-3 py-2">Sudah Dibayar</span>
                            @elseif($order->status == 'dikemas') <span class="badge bg-primary px-3 py-2">Dikemas</span>
                            @elseif($order->status == 'dikirim') <span class="badge bg-secondary px-3 py-2">Dikirim</span>
                            @elseif($order->status == 'dibatalkan') <span class="badge bg-danger px-3 py-2">Dibatalkan</span>
                            @else <span class="badge bg-success px-3 py-2">Selesai</span> @endif
                        </td>
                        <td>
                            @if($order->tracking_number)
                                <span class="badge border border-secondary text-secondary bg-light"><i class="fas fa-truck me-1"></i>{{ $order->tracking_number }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary" onclick="openUpdateModal({{ $order->id }}, '{{ $order->invoice }}', '{{ $order->status }}', '{{ $order->tracking_number ?? '' }}', '{{ $order->courier ?? '' }}')" title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pesanan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart text-muted fa-3x mb-3 opacity-25"></i>
            <h5 class="text-muted fw-bold">Belum Ada Pesanan</h5>
            <p class="text-muted mb-0">Daftar pesanan dengan status ini masih kosong.</p>
        </div>
        @endif
        
        <div class="d-flex justify-content-end mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Global Single Modal -->
<div class="modal fade" id="globalUpdateOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="globalUpdateOrderForm" method="POST" class="modal-content bg-white shadow-lg border-0">
            @csrf @method('PATCH')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="globalModalTitle">Update Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Ubah Status</label>
                    <select name="status" id="globalStatusSelect" class="form-select">
                        <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                        <option value="sudah_dibayar">Sudah Dibayar</option>
                        <option value="dikemas">Sedang Dikemas</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nomor Resi (Tracking Number)</label>
                    <div class="input-group">
                        <input type="text" name="tracking_number" id="globalTrackingInput" class="form-control" placeholder="Contoh: JNE123456789">
                        <button class="btn btn-outline-secondary" type="button" onclick="generateResi()">
                            <i class="fas fa-magic me-1"></i> Generate
                        </button>
                    </div>
                    <small class="text-muted">Kurir: <span id="courierNameText" class="fw-bold text-dark"></span>. Masukkan manual atau klik tombol di atas.</small>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color); border:none;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentCourier = '';

    function openUpdateModal(orderId, invoice, status, tracking, courier) {
        // Update Form Action URL
        const form = document.getElementById('globalUpdateOrderForm');
        form.action = `/admin/orders/${orderId}/status`;
        
        // Update Title
        document.getElementById('globalModalTitle').innerText = `Update Pesanan ${invoice}`;
        
        // Set Values
        document.getElementById('globalStatusSelect').value = status;
        document.getElementById('globalTrackingInput').value = tracking;
        currentCourier = courier ? courier.toUpperCase() : 'JNE'; // Default ke JNE jika kosong
        
        // Tampilkan kurir di keterangan
        document.getElementById('courierNameText').innerText = currentCourier;

        // Show Modal
        const modal = new bootstrap.Modal(document.getElementById('globalUpdateOrderModal'));
        modal.show();
    }

    function generateResi() {
        // Logika sederhana membuat resi sesuai kurir
        let prefix = currentCourier;
        if (prefix.includes('JNT') || prefix.includes('J&T')) prefix = 'JP';
        if (prefix.includes('SICEPAT')) prefix = '00';
        if (prefix.includes('ANTERAJA')) prefix = '100';

        // Buat 10 digit angka acak
        const randomDigits = Math.floor(1000000000 + Math.random() * 9000000000);
        const resi = prefix + randomDigits;

        // Masukkan ke input field
        document.getElementById('globalTrackingInput').value = resi;
        
        // Opsional: otomatis ubah status ke "dikirim" jika membuat resi
        document.getElementById('globalStatusSelect').value = 'dikirim';
    }
</script>
@endsection
