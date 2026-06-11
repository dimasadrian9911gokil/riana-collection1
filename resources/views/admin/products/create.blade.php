@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Tambah Produk Baru</h3>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger shadow-sm border-0">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- KOLOM KIRI (Data Utama) -->
        <div class="col-lg-8">
            <div class="admin-card card mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-box text-primary me-2"></i>Informasi Dasar</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Produk *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Skintific 5X Ceramide Barrier Repair Moisture Gel">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Kategori *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Brand <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-select" required>
                                <option value="">Pilih Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Brand tidak ada? <a href="{{ route('admin.brands.index') }}" target="_blank">Tambah Brand Baru</a></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Harga Normal (Rp) *</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Stok Awal *</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Tuliskan deskripsi lengkap produk Anda..." required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- VARIASI PRODUK -->
            <div class="admin-card card mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-tags text-warning me-2"></i>Variasi Produk (Opsional)</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addVariantBtn">
                        <i class="fas fa-plus me-1"></i>Tambah Variasi
                    </button>
                </div>
                <div class="card-body p-4" id="variantsContainer">
                    <div class="alert alert-info py-2 small border-0 bg-light text-muted">
                        Tambahkan variasi jika produk memiliki ukuran, warna, atau paket berbeda (misal: 50ml, 100ml). Kosongkan jika produk standar.
                    </div>
                    <!-- Variant rows will be appended here -->
                </div>
            </div>

            <!-- DETAIL TAMBAHAN -->
            <div class="admin-card card mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-info-circle text-info me-2"></i>Detail Spesifik (Opsional)</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Cocok Untuk Tipe Kulit</label>
                        <select name="skin_type" class="form-select">
                            <option value="">Semua Jenis Kulit / Tidak Spesifik</option>
                            <option value="Normal" {{ old('skin_type') == 'Normal' ? 'selected' : '' }}>Kulit Normal</option>
                            <option value="Kering" {{ old('skin_type') == 'Kering' ? 'selected' : '' }}>Kulit Kering</option>
                            <option value="Berminyak" {{ old('skin_type') == 'Berminyak' ? 'selected' : '' }}>Kulit Berminyak</option>
                            <option value="Sensitif" {{ old('skin_type') == 'Sensitif' ? 'selected' : '' }}>Kulit Sensitif</option>
                            <option value="Kombinasi" {{ old('skin_type') == 'Kombinasi' ? 'selected' : '' }}>Kulit Kombinasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Cara Penggunaan</label>
                        <textarea name="how_to_use" class="form-control" rows="3">{{ old('how_to_use') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Kandungan Utama (Ingredients)</label>
                        <textarea name="ingredients" class="form-control" rows="3">{{ old('ingredients') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (Gambar & Submit) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary bg-opacity-10 border-0 pt-4 pb-2 px-4">
                    <h6 class="fw-bold text-primary mb-0"><i class="fas fa-image me-2"></i>Foto Utama (Sampul) *</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-3 text-muted">
                        <i class="fas fa-camera fa-3x mb-3 opacity-25"></i>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <p class="small text-muted mb-0 text-start">Foto utama yang akan tampil di halaman depan katalog produk. Format: JPG, PNG, WEBP. Maks 2MB.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning bg-opacity-10 border-0 pt-4 pb-2 px-4">
                    <h6 class="fw-bold text-warning mb-0"><i class="fas fa-images me-2"></i>Galeri Foto (Banyak Foto)</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-3 text-muted">
                        <i class="fas fa-clone fa-3x mb-3 opacity-25"></i>
                        <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    </div>
                    <p class="small text-muted mb-0 text-start">Anda bisa memilih banyak foto sekaligus (tahan tombol CTRL / Shift saat memilih file). Format: JPG, PNG, WEBP. Maks 2MB/file.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm mb-2">
                        <i class="fas fa-save me-2"></i>Simpan Produk Baru
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100 py-2 fw-bold rounded-pill">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let variantIndex = 0;
        const addBtn = document.getElementById('addVariantBtn');
        const container = document.getElementById('variantsContainer');

        addBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'row g-2 align-items-end mb-3 pb-3 border-bottom variant-row';
            row.innerHTML = `
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Nama Variasi</label>
                    <input type="text" name="variants[${variantIndex}][name]" class="form-control form-control-sm" placeholder="Misal: 50ml, Merah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">Foto Variasi</label>
                    <input type="file" name="variants[${variantIndex}][image]" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">Harga Variasi (Rp)</label>
                    <input type="number" name="variants[${variantIndex}][price_modifier]" class="form-control form-control-sm" placeholder="Kosongkan jika sama dengan produk dasar" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">Stok</label>
                    <input type="number" name="variants[${variantIndex}][stock]" class="form-control form-control-sm" placeholder="Misal: 50">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant-btn" title="Hapus Variasi">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
            `;
            container.appendChild(row);
            variantIndex++;

            // Bind remove event
            row.querySelector('.remove-variant-btn').addEventListener('click', function() {
                row.remove();
            });
        });
    });
</script>
@endsection
