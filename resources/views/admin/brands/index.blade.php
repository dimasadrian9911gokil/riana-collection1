@extends('layouts.admin')

@section('title', 'Manajemen Brand - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Brand</h3>
    <button class="btn btn-primary" style="background-color: var(--primary-color); border:none;" data-bs-toggle="modal" data-bs-target="#addBrandModal">
        <i class="fas fa-plus me-2"></i>Tambah Brand Baru
    </button>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-copyright text-danger me-2"></i>Daftar Seluruh Brand</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Nama Brand</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Total Produk</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brands as $brand)
                    <tr>
                        <td><span class="text-muted fw-bold">#{{ $brand->id }}</span></td>
                        <td><h6 class="mb-0 fw-bold text-dark">{{ $brand->name }}</h6></td>
                        <td><span class="text-muted">{{ $brand->slug }}</span></td>
                        <td>{{ Str::limit($brand->description, 50) ?? '-' }}</td>
                        <td><span class="badge bg-danger rounded-pill px-3">{{ $brand->products_count }} Produk</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $brand->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus brand ini?');">
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
        @if($brands->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-copyright text-muted fa-3x mb-3 opacity-25"></i>
            <h5 class="text-muted fw-bold">Belum Ada Brand</h5>
            <p class="text-muted mb-0">Tambahkan brand pertama Anda menggunakan tombol di atas.</p>
        </div>
        @endif
    </div>
</div>

<!-- Render Edit Modals Outside Table -->
@foreach($brands as $brand)
<!-- Edit Modal -->
<div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Brand</label>
                    <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $brand->description }}</textarea>
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
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.brands.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Brand Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Brand</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Skintific" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi opsional..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color); border:none;">Tambah Brand</button>
            </div>
        </form>
    </div>
</div>
@endsection
