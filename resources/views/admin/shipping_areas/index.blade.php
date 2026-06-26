@extends('layouts.admin')

@section('title', 'Manajemen Ongkir Desa - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Ongkir Desa</h3>
    <button class="btn btn-primary" style="background-color: var(--primary-color); border:none;" data-bs-toggle="modal" data-bs-target="#addAreaModal">
        <i class="fas fa-plus me-2"></i>Tambah Area Baru
    </button>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-truck-fast text-primary me-2"></i>Daftar Area Pengiriman</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Nama Area (Desa/Kelurahan)</th>
                        <th>Ongkos Kirim (Rp)</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($areas as $area)
                    <tr>
                        <td><span class="text-muted fw-bold">#{{ $area->id }}</span></td>
                        <td><h6 class="mb-0 fw-bold text-dark">{{ $area->name }}</h6></td>
                        <td><span class="text-success fw-bold">Rp {{ number_format($area->cost, 0, ',', '.') }}</span></td>
                        <td>
                            <form action="{{ route('admin.shipping_areas.toggle', $area) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $area->is_active ? 'btn-success' : 'btn-secondary' }} rounded-pill px-3">
                                    {{ $area->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editAreaModal{{ $area->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.shipping_areas.destroy', $area) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus area ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($areas->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-truck-fast text-muted fa-3x mb-3 opacity-25"></i>
            <h5 class="text-muted fw-bold">Belum Ada Area Pengiriman</h5>
            <p class="text-muted mb-0">Tambahkan area pertama Anda menggunakan tombol di atas.</p>
        </div>
        @endif
    </div>
</div>

<!-- Edit Modals -->
@foreach($areas as $area)
<div class="modal fade" id="editAreaModal{{ $area->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.shipping_areas.update', $area) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Area Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Area (Misal: Lokal_AirPutih)</label>
                    <input type="text" name="name" class="form-control" value="{{ $area->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Ongkos Kirim (Rp)</label>
                    <input type="number" name="cost" class="form-control" value="{{ $area->cost }}" required min="0">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color); border:none;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Add Modal -->
<div class="modal fade" id="addAreaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.shipping_areas.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Area Pengiriman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Area (Misal: Lokal_AirPutih)</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Lokal_Senggoro" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Ongkos Kirim (Rp)</label>
                    <input type="number" name="cost" class="form-control" placeholder="Contoh: 5000" required min="0">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color); border:none;">Tambah Area</button>
            </div>
        </form>
    </div>
</div>
@endsection
