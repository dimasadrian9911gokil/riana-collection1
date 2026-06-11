@extends('layouts.admin')

@section('title', 'Manajemen Produk - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Produk & Stok</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="background-color: var(--primary-color); border:none;">
        <i class="fas fa-plus me-2"></i>Tambah Produk Baru
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="admin-card card mb-4">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-boxes text-info me-2"></i>Daftar Seluruh Produk</h6>
        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari nama produk..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-sm btn-dark">Cari</button>
        </form>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Produk</th>
                        <th>Harga Jual</th>
                        <th>Stok Saat Ini</th>
                        <th>Update Stok Cepat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-muted">#{{ $product->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/'.$product->image) }}" class="rounded me-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">{{ $product->name }}</h6>
                                    <span class="badge bg-secondary">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold text-danger">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stock > 10)
                                <span class="badge bg-success px-3 py-2 rounded-pill">{{ $product->stock }} Unit</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">{{ $product->stock }} Unit (Tipis)</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill">Habis!</span>
                            @endif
                        </td>
                        <td style="width: 250px;">
                            <form action="{{ route('admin.products.stock', $product->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="stock" class="form-control form-control-sm me-2 text-center" value="{{ $product->stock }}" min="0" style="width: 80px;">
                                <button type="submit" class="btn btn-sm btn-outline-success">Update</button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-light border"><i class="fas fa-edit text-primary"></i></a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border"><i class="fas fa-trash text-danger"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada produk yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
