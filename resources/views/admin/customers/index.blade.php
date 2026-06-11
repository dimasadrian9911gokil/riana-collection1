@extends('layouts.admin')

@section('title', 'Data Pelanggan - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Data Pelanggan</h3>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="admin-card card mb-4">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-users text-primary me-2"></i>Daftar Pelanggan Terdaftar</h6>
        <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari nama/email..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-sm btn-dark">Cari</button>
        </form>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Pelanggan</th>
                        <th>Tanggal Daftar</th>
                        <th>Total Pesanan</th>
                        <th>Status Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($customer->avatar)
                                    <img src="{{ asset('storage/'.$customer->avatar) }}" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">{{ $customer->name }}</h6>
                                    <small class="text-muted d-block">{{ $customer->email }}</small>
                                    @if($customer->phone)
                                        <small class="text-muted"><i class="fas fa-phone-alt me-1" style="font-size: 0.75rem;"></i>{{ $customer->phone }}</small>
                                    @else
                                        <small class="text-muted fst-italic"><i class="fas fa-phone-alt me-1" style="font-size: 0.75rem;"></i>Belum ada no HP</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $customer->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex flex-column align-items-start gap-1">
                                <span class="badge bg-secondary rounded-pill px-2 py-1"><i class="fas fa-shopping-bag me-1"></i> {{ $customer->orders_count ?? 0 }} Pesanan</span>
                                @if(($customer->total_spent ?? 0) > 0)
                                    <small class="text-success fw-bold"><i class="fas fa-money-bill-wave me-1"></i> Rp{{ number_format($customer->total_spent, 0, ',', '.') }}</small>
                                @else
                                    <small class="text-muted"><i class="fas fa-money-bill-wave me-1"></i> Rp0</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($customer->is_active)
                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fas fa-ban me-1"></i> Diblokir</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail & Alamat">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <form action="{{ route('admin.customers.toggle', $customer->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $customer->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $customer->is_active ? 'Nonaktifkan/Blokir Akun' : 'Aktifkan Akun' }}">
                                        @if($customer->is_active)
                                            <i class="fas fa-user-lock"></i> Suspend
                                        @else
                                            <i class="fas fa-user-check"></i> Aktifkan
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini permanen? Semua data pesanan yang tertaut dengan akun ini juga mungkin akan terdampak.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Permanen">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada pelanggan terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
