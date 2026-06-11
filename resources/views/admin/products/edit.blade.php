@extends('layouts.admin')

@section('title', 'Edit Produk - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Edit Produk: {{ $product->name }}</h3>
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

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required placeholder="Contoh: Skintific 5X Ceramide Barrier Repair Moisture Gel">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Kategori *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Brand <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-select" required>
                                <option value="">Pilih Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Brand tidak ada? <a href="{{ route('admin.brands.index') }}" target="_blank">Tambah Brand Baru</a></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Harga Normal (Rp) *</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Stok Awal *</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Tuliskan deskripsi lengkap produk Anda..." required>{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- VARIASI PRODUK -->
            <div class="admin-card card mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-tags text-warning me-2"></i>Variasi Produk</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addVariantBtn">
                        <i class="fas fa-plus me-1"></i>Tambah Variasi
                    </button>
                </div>
                <div class="card-body p-4" id="variantsContainer">
                    <div class="alert alert-info py-2 small border-0 bg-light text-muted mb-3">
                        Hapus variasi dengan menekan tombol silang merah. Kosongkan Harga Variasi (atau isi 0) jika harganya mengikuti harga dasar produk asli.
                    </div>
                    @if($product->variants && $product->variants->count() > 0)
                        @foreach($product->variants as $index => $variant)
                        <div class="row g-2 align-items-end mb-3 pb-3 border-bottom variant-row">
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Nama Variasi</label>
                                <input type="text" name="variants[{{ $index }}][name]" class="form-control form-control-sm" value="{{ $variant->name }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Foto Variasi</label>
                                @if($variant->image_path)
                                    <div class="mb-1"><img src="{{ asset('storage/'.$variant->image_path) }}" width="40" height="40" style="object-fit:cover; border-radius:4px;"></div>
                                @endif
                                <input type="file" name="variants[{{ $index }}][image]" class="form-control form-control-sm" accept="image/*">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small fw-bold">Harga Variasi (Rp)</label>
                                <input type="number" name="variants[{{ $index }}][price_modifier]" class="form-control form-control-sm" value="{{ rtrim(rtrim(number_format($variant->price_modifier, 2, '.', ''), '0'), '.') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small fw-bold">Stok</label>
                                <input type="number" name="variants[{{ $index }}][stock]" class="form-control form-control-sm" value="{{ $variant->stock }}">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-variant-btn" title="Hapus Variasi" data-id="{{ $variant->id }}">
                                    <i class="fas fa-times"></i> Hapus
                                </button>
                                <input type="hidden" name="variants[{{ $index }}][delete]" class="delete-flag" value="0">
                            </div>
                        </div>
                        @endforeach
                    @endif
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
                            <option value="Normal" {{ old('skin_type', $product->skin_type) == 'Normal' ? 'selected' : '' }}>Kulit Normal</option>
                            <option value="Kering" {{ old('skin_type', $product->skin_type) == 'Kering' ? 'selected' : '' }}>Kulit Kering</option>
                            <option value="Berminyak" {{ old('skin_type', $product->skin_type) == 'Berminyak' ? 'selected' : '' }}>Kulit Berminyak</option>
                            <option value="Sensitif" {{ old('skin_type', $product->skin_type) == 'Sensitif' ? 'selected' : '' }}>Kulit Sensitif</option>
                            <option value="Kombinasi" {{ old('skin_type', $product->skin_type) == 'Kombinasi' ? 'selected' : '' }}>Kulit Kombinasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Cara Penggunaan</label>
                        <textarea name="how_to_use" class="form-control" rows="3">{{ old('how_to_use', $product->how_to_use) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Kandungan Utama (Ingredients)</label>
                        <textarea name="ingredients" class="form-control" rows="3">{{ old('ingredients', $product->ingredients) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (Upload Gambar) -->
        <div class="col-lg-4">
            <!-- FOTO UTAMA -->
            <div class="admin-card card mb-4 border-primary">
                <div class="card-header bg-primary bg-opacity-10 border-0 pt-4 pb-3 px-4">
                    <h6 class="fw-bold text-primary mb-0"><i class="fas fa-image me-2"></i>Foto Utama (Sampul) *</h6>
                </div>
                <div class="card-body p-4 text-center">
                    @if($product->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                    </div>
                    @endif
                    <div class="mb-3">
                        <i class="fas fa-camera fa-2x text-muted opacity-25 mb-2"></i>
                        <input class="form-control" type="file" name="image" id="mainImage" accept="image/*">
                    </div>
                    <small class="text-muted d-block text-start">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP. Maks 2MB.</small>
                </div>
            </div>

            <!-- FOTO GALERI -->
            <div class="admin-card card mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-images text-warning me-2"></i>Galeri Foto (Banyak Foto)</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-clone fa-3x text-muted opacity-25 mb-3"></i>
                        <input class="form-control" type="file" name="gallery[]" id="galleryImages" accept="image/*" multiple>
                    </div>
                    <small class="text-muted d-block text-start">Anda bisa memilih banyak foto sekaligus (tahan tombol CTRL / Shift saat memilih file). Format: JPG, PNG, WEBP. Maks 2MB/file.</small>
                </div>
            </div>

            <!-- TOMBOL SUBMIT -->
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5 shadow" style="background-color: var(--primary-color); border:none; border-radius: 10px;">
                <i class="fas fa-save me-2"></i>SIMPAN PERUBAHAN
            </button>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let variantIndex = {{ $product->variants ? $product->variants->count() : 0 }};
        const addBtn = document.getElementById('addVariantBtn');
        const container = document.getElementById('variantsContainer');

        // Handle existing remove buttons
        document.querySelectorAll('.remove-variant-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('.variant-row');
                // Set delete flag to 1
                row.querySelector('.delete-flag').value = '1';
                // Hide the row
                row.style.display = 'none';
            });
        });

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
                    <input type="number" name="variants[${variantIndex}][price_modifier]" class="form-control form-control-sm" placeholder="Misal: 10000" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">Stok</label>
                    <input type="number" name="variants[${variantIndex}][stock]" class="form-control form-control-sm" placeholder="Misal: 50">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-new-variant-btn" title="Hapus Variasi">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
            `;
            container.appendChild(row);
            variantIndex++;

            // Bind remove event for new rows
            row.querySelector('.remove-new-variant-btn').addEventListener('click', function() {
                row.remove();
            });
        });
    });
</script>
@endsection
