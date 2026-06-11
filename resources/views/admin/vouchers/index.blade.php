@extends('layouts.admin')

@section('title', 'Manajemen Voucher - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Voucher & Diskon</h3>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger border-0 shadow-sm">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <!-- TAMBAH VOUCHER BARU -->
    <div class="col-md-4">
        <div class="card admin-card mb-4 border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-plus-circle text-success me-2"></i>Buat Voucher Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.vouchers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Kode Voucher</label>
                        <input type="text" name="code" class="form-control" placeholder="CTH: DISKON10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Promo</label>
                        <input type="text" name="name" class="form-control" placeholder="Promo Spesial Kemerdekaan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Tipe Diskon</label>
                        <select name="discount_type" class="form-select" required>
                            <option value="fixed">Nominal Rupiah (Rp)</option>
                            <option value="percentage">Persentase (%)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nilai Diskon</label>
                        <input type="number" name="discount_amount" class="form-control" placeholder="Contoh: 10000 atau 15" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Min. Belanja (Opsional)</label>
                        <input type="number" name="min_spend" class="form-control" placeholder="Contoh: 50000">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Langsung Aktifkan</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="background-color: var(--primary-color); border:none;">Simpan Voucher</button>
                </form>
            </div>
        </div>
    </div>

    <!-- DAFTAR VOUCHER -->
    <div class="col-md-8">
        <div class="card admin-card mb-4 border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-ticket-alt text-primary me-2"></i>Daftar Kode Voucher Aktif</h6>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Promo</th>
                                <th>Nilai Diskon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $voucher)
                            <tr>
                                <td><span class="badge bg-dark fw-bold px-3 py-2" style="letter-spacing: 2px;">{{ $voucher->code }}</span></td>
                                <td>
                                    <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">{{ $voucher->name }}</h6>
                                    <small class="text-muted">Min. Belanja: Rp{{ number_format($voucher->min_spend ?? 0, 0, ',', '.') }}</small>
                                </td>
                                <td class="fw-bold text-danger">
                                    @if($voucher->discount_type == 'fixed')
                                        Rp{{ number_format($voucher->discount_amount, 0, ',', '.') }}
                                    @else
                                        {{ $voucher->discount_amount }}%
                                    @endif
                                </td>
                                <td>
                                    @if($voucher->is_active)
                                        <span class="badge bg-success px-3 py-2 rounded-pill">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $voucher->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $voucher->is_active ? 'Nonaktifkan Voucher' : 'Aktifkan Voucher' }}">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-light border" title="Edit Voucher" onclick="openEditModal({{ $voucher->id }}, '{{ $voucher->code }}', '{{ $voucher->name }}', '{{ $voucher->discount_type }}', {{ $voucher->discount_amount }}, {{ $voucher->min_spend ?? 0 }}, {{ $voucher->is_active ? 'true' : 'false' }})">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                        <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus voucher ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Voucher">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada kode voucher yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Voucher -->
<div class="modal fade" id="editVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit text-primary me-2"></i>Edit Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVoucherForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Kode Voucher</label>
                        <input type="text" name="code" id="edit_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Promo</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Tipe Diskon</label>
                        <select name="discount_type" id="edit_discount_type" class="form-select" required>
                            <option value="fixed">Nominal Rupiah (Rp)</option>
                            <option value="percentage">Persentase (%)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nilai Diskon</label>
                        <input type="number" name="discount_amount" id="edit_discount_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Min. Belanja (Opsional)</label>
                        <input type="number" name="min_spend" id="edit_min_spend" class="form-control">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="edit_is_active">
                        <label class="form-check-label" for="edit_is_active">Voucher Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, code, name, type, amount, minSpend, isActive) {
    const form = document.getElementById('editVoucherForm');
    form.action = `/admin/vouchers/${id}`;
    
    document.getElementById('edit_code').value = code;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_discount_type').value = type;
    document.getElementById('edit_discount_amount').value = amount;
    document.getElementById('edit_min_spend').value = minSpend > 0 ? minSpend : '';
    document.getElementById('edit_is_active').checked = isActive;
    
    new bootstrap.Modal(document.getElementById('editVoucherModal')).show();
}
</script>

@endsection
