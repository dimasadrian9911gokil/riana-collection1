@extends('layouts.admin')

@section('title', 'Manajemen Kategori - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Kategori</h3>
    <button class="btn btn-primary" style="background-color: var(--primary-color); border:none;" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus me-2"></i>Tambah Kategori Baru
    </button>
</div>

<div class="admin-card card mb-4 border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-tags text-primary me-2"></i>Daftar Seluruh Kategori</h6>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Total Produk</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td><span class="text-muted fw-bold">#{{ $category->id }}</span></td>
                        <td><h6 class="mb-0 fw-bold text-dark">{{ $category->name }}</h6></td>
                        <td><span class="text-muted">{{ $category->slug }}</span></td>
                        <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                        <td><span class="badge bg-info rounded-pill px-3">{{ $category->products_count }} Produk</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        @if($categories->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-tags text-muted fa-3x mb-3 opacity-25"></i>
            <h5 class="text-muted fw-bold">Belum Ada Kategori</h5>
            <p class="text-muted mb-0">Tambahkan kategori pertama Anda menggunakan tombol di atas.</p>
        </div>
        @endif
    </div>
</div>

<!-- Edit Modals -->
@foreach($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
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
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Skincare" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi opsional..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color); border:none;">Tambah Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection
