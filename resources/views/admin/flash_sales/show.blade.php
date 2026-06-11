@extends('layouts.admin')

@section('title', 'Kelola Produk Flash Sale - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.flash_sales.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
        <h3 class="fw-bold text-dark mb-0">Kelola Produk: {{ $flashSale->name }}</h3>
    </div>
    <button class="btn btn-danger border-0" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus me-2"></i>Tambah Produk ke Flash Sale
    </button>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="admin-card card border-0 bg-danger bg-opacity-10">
            <div class="card-body">
                <div class="text-danger small fw-bold text-uppercase mb-1">Status Kampanye</div>
                <h5 class="fw-bold text-dark mb-0">
                    @if(!$flashSale->is_active) Nonaktif
                    @elseif(now() < $flashSale->start_time) Akan Datang
                    @elseif(now() > $flashSale->end_time) Berakhir
                    @else Sedang Berlangsung @endif
                </h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card card border-0">
            <div class="card-body">
                <div class="text-muted small fw-bold text-uppercase mb-1">Mulai</div>
                <h6 class="fw-bold text-dark mb-0">{{ $flashSale->start_time->format('d M Y, H:i') }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card card border-0">
            <div class="card-body">
                <div class="text-muted small fw-bold text-uppercase mb-1">Selesai</div>
                <h6 class="fw-bold text-danger mb-0">{{ $flashSale->end_time->format('d M Y, H:i') }}</h6>
            </div>
        </div>
    </div>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-boxes text-danger me-2"></i>Daftar Produk yang Diikutsertakan</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Harga Asli</th>
                        <th>Harga Flash Sale</th>
                        <th>Stok Promo</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flashSale->items as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/'.$item->product->image) }}" class="rounded me-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                <h6 class="mb-0 fw-bold text-dark">{{ $item->product->name }}</h6>
                            </div>
                        </td>
                        <td><span class="text-muted text-decoration-line-through">Rp{{ number_format($item->product->price, 0, ',', '.') }}</span></td>
                        <td><h6 class="fw-bold text-danger mb-0">Rp{{ number_format($item->discount_price, 0, ',', '.') }}</h6></td>
                        <td><span class="badge bg-dark px-3 py-2">{{ $item->stock_allocated }} Pcs</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.flash_sales.removeItem', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini dari kampanye?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        </div>
        @if($flashSale->items->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-box-open text-muted fa-3x mb-3 opacity-25"></i>
            <h5 class="text-muted fw-bold">Belum Ada Produk</h5>
            <p class="text-muted mb-0">Tambahkan produk pertama untuk kampanye ini.</p>
        </div>
        @endif
    </div>
</div>

<!-- Render Edit Modals Outside Table -->
@foreach($flashSale->items as $item)
<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.flash_sales.updateItem', $item) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Promo Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Produk</label>
                    <input type="text" class="form-control bg-light" value="{{ $item->product->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Harga Flash Sale (Rp)</label>
                    <input type="number" name="discount_price" class="form-control" value="{{ $item->discount_price }}" required min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Batas Stok Promo</label>
                    <input type="number" name="stock_allocated" class="form-control" value="{{ $item->stock_allocated }}" required min="1">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.flash_sales.addItem', $flashSale) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Produk Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Produk</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Harga: Rp{{ number_format($product->price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Harga Flash Sale (Rp)</label>
                    <input type="number" name="discount_price" class="form-control" placeholder="Contoh: 50000" required min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Batas Stok Promo</label>
                    <input type="number" name="stock_allocated" class="form-control" placeholder="Contoh: 10" required min="1">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tambah ke Kampanye</button>
            </div>
        </form>
    </div>
</div>
@endsection
