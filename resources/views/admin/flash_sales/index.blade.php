@extends('layouts.admin')

@section('title', 'Kampanye Flash Sale - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Flash Sale</h3>
    <button class="btn btn-danger border-0" data-bs-toggle="modal" data-bs-target="#addFlashSaleModal">
        <i class="fas fa-plus me-2"></i>Buat Flash Sale Baru
    </button>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-bolt text-warning me-2"></i>Daftar Kampanye Flash Sale</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Kampanye</th>
                        <th>Jadwal Pelaksanaan</th>
                        <th>Status</th>
                        <th>Jumlah Produk</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flashSales as $flashSale)
                    <tr>
                        <td><h6 class="mb-0 fw-bold text-dark">{{ $flashSale->name }}</h6></td>
                        <td>
                            <div class="text-muted small"><i class="far fa-clock me-1"></i>Mulai: {{ $flashSale->start_time->format('d M Y, H:i') }}</div>
                            <div class="text-danger small fw-bold"><i class="far fa-clock me-1"></i>Selesai: {{ $flashSale->end_time->format('d M Y, H:i') }}</div>
                        </td>
                        <td>
                            @if(!$flashSale->is_active)
                                <span class="badge bg-secondary">Nonaktif</span>
                            @elseif(now() < $flashSale->start_time)
                                <span class="badge bg-info">Akan Datang</span>
                            @elseif(now() > $flashSale->end_time)
                                <span class="badge bg-dark">Berakhir</span>
                            @else
                                <span class="badge bg-success">Sedang Berlangsung</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary rounded-pill px-3">{{ $flashSale->items_count }} Produk</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.flash_sales.show', $flashSale) }}" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-box-open me-1"></i>Kelola Produk</a>
                            <button class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#editFlashSaleModal{{ $flashSale->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.flash_sales.destroy', $flashSale) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kampanye ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>


        @if($flashSales->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-bolt text-muted fa-3x mb-3 opacity-25"></i>
            <h5 class="text-muted fw-bold">Belum Ada Flash Sale</h5>
            <p class="text-muted mb-0">Buat jadwal kampanye diskon pertama Anda.</p>
        </div>
        @endif
    </div>
</div>

<!-- Edit Modals (Di luar card untuk menghindari masalah z-index & backdrop) -->
@foreach($flashSales as $flashSale)
<div class="modal fade" id="editFlashSaleModal{{ $flashSale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.flash_sales.update', $flashSale) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Flash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kampanye</label>
                    <input type="text" name="name" class="form-control" value="{{ $flashSale->name }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" class="form-control" value="{{ $flashSale->start_time->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Waktu Berakhir</label>
                        <input type="datetime-local" name="end_time" class="form-control" value="{{ $flashSale->end_time->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveEdit{{ $flashSale->id }}" {{ $flashSale->is_active ? 'checked' : '' }} value="1">
                    <label class="form-check-label fw-bold" for="isActiveEdit{{ $flashSale->id }}">Aktifkan Kampanye Ini</label>
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

<!-- Add Modal -->
<div class="modal fade" id="addFlashSaleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.flash_sales.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Flash Sale Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kampanye</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Promo 6.6 Super Sale" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Waktu Berakhir</label>
                        <input type="datetime-local" name="end_time" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveAdd" checked value="1">
                    <label class="form-check-label fw-bold" for="isActiveAdd">Aktifkan Kampanye Ini</label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Buat Kampanye</button>
            </div>
        </form>
    </div>
</div>
@endsection
