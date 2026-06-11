@extends('layouts.app')

@section('title', 'Alamat Pengiriman')

@section('content')

<style>
    body { background-color: #fcf7f9 !important; }
    .card-address-premium {
        background: #ffffff;
        border-radius: 20px;
        border: 2px solid transparent;
        background-image: linear-gradient(white, white), linear-gradient(135deg, #ff6699, #ffb3d1);
        background-origin: border-box;
        background-clip: padding-box, border-box;
        box-shadow: 0 10px 25px rgba(255, 102, 153, 0.1);
        transition: all 0.3s ease;
    }
    .card-address-premium:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(255, 102, 153, 0.2); }
    .text-pink { color: #ff6699; }
    .btn-pink { background-color: #ff6699; color: white; border-radius: 50px; }
    .btn-pink:hover { background-color: #e65586; color: white; }
    .btn-outline-pink { border: 1px solid #ff6699; color: #ff6699; border-radius: 50px; }
    .btn-outline-pink:hover { background-color: #ff6699; color: white; }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="fas fa-map-marker-alt text-pink me-2"></i>Alamat Pengiriman</h2>
            <p class="text-muted mb-0">Kelola alamat pengiriman untuk checkout yang lebih cepat</p>
        </div>
        <button class="btn btn-pink px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#alamatModal">
            <i class="fas fa-plus me-1"></i> Tambah Alamat
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($addresses as $address)
        <div class="card card-address-premium mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        @if($address->is_default)
                            <span class="badge mb-2 text-white" style="background-color: #ff6699;">
                                <i class="fas fa-star me-1"></i> Alamat Utama
                            </span>
                        @else
                            <span class="badge bg-light text-dark mb-2 border">
                                <i class="fas fa-tag me-1"></i> {{ $address->label ?? 'Alamat' }}
                            </span>
                        @endif
                        <h5 class="fw-bold mb-1">{{ $address->recipient_name }}</h5>
                        <p class="mb-2 text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $address->phone }}</p>
                        <p class="mb-0 text-secondary" style="font-size: 0.95rem;">
                            {{ $address->address }}<br>
                            {{ $address->district }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                        </p>
                    </div>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('address.edit', $address->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                        <form action="{{ route('address.destroy', $address->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 w-100" onclick="return confirm('Hapus alamat ini?')">Hapus</button>
                        </form>
                    </div>
                </div>

                @if(!$address->is_default)
                    <div class="mt-3 pt-3 border-top">
                        <form action="{{ route('address.setPrimary', $address->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-pink w-100">Jadikan Alamat Utama</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="display-4 mb-3 text-muted">📍</div>
            <h5 class="text-muted">Belum ada alamat tersimpan</h5>
        </div>
    @endforelse
</div>

@include('address.partials.modal-tambah') 
@endsection