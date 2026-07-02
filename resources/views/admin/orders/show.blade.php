@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-2"><i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pesanan</a>
        <h3 class="fw-bold text-dark mb-0">Detail Pesanan: {{ $order->invoice }}</h3>
    </div>
    <div>
        @if($order->status == 'menunggu_pembayaran') <span class="badge bg-warning text-dark px-3 py-2 fs-6">Menunggu Pembayaran</span>
        @elseif($order->status == 'menunggu_verifikasi') <span class="badge bg-primary px-3 py-2 fs-6">Menunggu Verifikasi</span>
        @elseif($order->status == 'sudah_dibayar') <span class="badge bg-info px-3 py-2 fs-6">Sudah Dibayar</span>
        @elseif($order->status == 'dikemas') <span class="badge bg-primary px-3 py-2 fs-6">Dikemas</span>
        @elseif($order->status == 'dikirim') <span class="badge bg-secondary px-3 py-2 fs-6">Dikirim</span>
        @elseif($order->status == 'dibatalkan') <span class="badge bg-danger px-3 py-2 fs-6">Dibatalkan</span>
        @else <span class="badge bg-success px-3 py-2 fs-6">Selesai</span> @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Produk yang Dipesan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-box-open text-primary me-2"></i>Produk Dipesan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga Satuan</th>
                                <th>Qty</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                        <div class="ms-3">
                                            <h6 class="mb-1 text-dark fw-bold">{{ $item->product_name }}</h6>
                                            <span class="badge bg-light text-muted border">Varian: {{ $item->variant ?? 'Standard' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->qty }}</td>
                                <td class="text-end pe-4 fw-bold text-dark">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rincian Biaya -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-file-invoice-dollar text-success me-2"></i>Rincian Pembayaran</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal Produk</span>
                    <span class="fw-medium text-dark">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ongkos Kirim ({{ $order->courier }})</span>
                    <span class="fw-medium text-dark">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Biaya Admin</span>
                    <span class="fw-medium text-dark">Rp{{ number_format($order->admin_fee, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-success fw-bold">Diskon Voucher</span>
                    <span class="fw-bold text-success">-Rp{{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h5 class="fw-bold text-dark mb-0">Total Tagihan</h5>
                    <h4 class="fw-bold text-danger mb-0">Rp{{ number_format($order->total, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Bukti Pembayaran -->
        <!-- Bukti Pembayaran -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-receipt text-success me-2"></i>Bukti Pembayaran</h6>
            </div>
            <div class="card-body p-4 text-center">
                @if($order->payment_method === 'COD')
                    <div class="text-info py-3">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-handshake fa-3x text-info"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Bayar di Tempat (COD)</h5>
                        <p class="mb-0 text-muted small">Pelanggan akan membayar pesanan ini secara langsung di toko saat melakukan pengambilan.</p>
                        <p class="text-muted small mt-2">Silakan periksa ketersediaan barang dan ubah status pesanan ini menjadi <strong>Dikemas</strong> agar pelanggan tahu pesanannya sedang disiapkan.</p>
                    </div>
                    @if($order->status == 'menunggu_verifikasi')
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="mt-3">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="dikemas">
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-2"><i class="fas fa-box me-1"></i> Konfirmasi & Mulai Kemas</button>
                        </form>
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="mt-2">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="dibatalkan">
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold"><i class="fas fa-times me-1"></i> Batalkan Pesanan</button>
                        </form>
                    @endif
                @elseif($order->payment_proof)
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" class="img-fluid rounded border mb-3" style="max-height: 250px;" alt="Bukti Transfer">
                    </a>
                    @if($order->status == 'menunggu_verifikasi')
                    <div class="d-flex gap-2 justify-content-center">
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex-fill">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="sudah_dibayar">
                            <button type="submit" class="btn btn-success btn-sm w-100 fw-bold"><i class="fas fa-check me-1"></i> Terima</button>
                        </form>
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex-fill">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="dibatalkan">
                            <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold"><i class="fas fa-times me-1"></i> Tolak</button>
                        </form>
                    </div>
                    @else
                    <span class="badge bg-success w-100 py-2"><i class="fas fa-check-circle me-1"></i> Pembayaran Terverifikasi</span>
                    @endif
                @else
                    <div class="text-muted py-3">
                        <i class="fas fa-image fa-3x mb-2 opacity-25"></i>
                        <p class="mb-0 small fw-bold">Belum ada bukti pembayaran.</p>
                        <small>Menunggu pelanggan mengunggah struk.</small>
                    </div>
                @endif
            </div>
        </div>
        <!-- Informasi Pelanggan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-user text-info me-2"></i>Informasi Pelanggan</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold text-dark">{{ $order->user->name ?? 'User Terhapus' }}</h6>
                        <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Nomor Telepon</small>
                    <span class="fw-medium text-dark">{{ $order->user->phone ?? 'Tidak ada data' }}</span>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Metode Pembayaran</small>
                    <span class="badge bg-light text-dark border">{{ $order->payment_method }}</span>
                </div>
            </div>
        </div>

        <!-- Informasi Pengiriman -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-truck text-secondary me-2"></i>Informasi Pengiriman</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3 border-bottom pb-3">
                    <small class="text-muted d-block mb-1">Penerima & Alamat Ketikan Pelanggan</small>
                    <span class="fw-bold text-dark d-block">{{ $order->recipient_name ?? '-' }} ({{ $order->recipient_phone ?? '-' }})</span>
                    <span class="text-dark d-block mt-1">{{ $order->shipping_address ?? 'Alamat tidak ditemukan' }}</span>
                    <span class="text-dark d-block">{{ $order->city ?? '' }}, {{ $order->province ?? '' }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Area Pengiriman / Kurir (Dipilih Saat Checkout)</small>
                    <span class="fw-bold text-primary fs-6">{{ $order->courier }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Nomor Resi / Info Kurir</small>
                    @if($order->tracking_number)
                        <div class="input-group input-group-sm mt-1">
                            <input type="text" class="form-control bg-light" value="{{ $order->tracking_number }}" readonly>
                        </div>
                    @else
                        <span class="text-muted fst-italic">Belum ada resi</span>
                    @endif
                </div>
                <hr>
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Update Status Pesanan</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="menunggu_pembayaran" {{ $order->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="menunggu_verifikasi" {{ $order->status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="sudah_dibayar" {{ $order->status == 'sudah_dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                            <option value="dikemas" {{ $order->status == 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                            <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Update Resi / Info Kurir (Opsional)</label>
                        <input type="text" name="tracking_number" class="form-control form-control-sm" value="{{ $order->tracking_number }}" placeholder="Contoh: JNT123456 atau Diantar Budi">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
