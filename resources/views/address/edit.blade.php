@extends('layouts.app')

@section('title', 'Edit Alamat')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-3d border-0 p-4">
                <h4 class="fw-bold mb-4"><i class="fas fa-edit text-pink me-2"></i>Edit Alamat</h4>
                
                {{-- Blok Menampilkan Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('address.update', $address->id) }}" method="POST">
                    @csrf @method('PATCH')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold small text-muted">Nama Penerima</label>
                            <input type="text" name="recipient_name" class="form-control rounded-pill" value="{{ old('recipient_name', $address->recipient_name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold small text-muted">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control rounded-pill" value="{{ old('phone', $address->phone) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold small text-muted">Alamat Lengkap</label>
                        <textarea name="address" class="form-control rounded-4" rows="3" required>{{ old('address', $address->address) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold small text-muted">Provinsi</label>
                            <select name="province" id="provinceSelect" class="form-select rounded-pill" required>
                                <option value="">Memuat...</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold small text-muted">Kota/Kabupaten</label>
                            <select name="city" id="citySelect" class="form-select rounded-pill" required>
                                <option value="">Pilih Provinsi Dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold small text-muted">Kode Pos</label>
                            <input type="text" name="postal_code" id="postalCodeInput" class="form-control rounded-pill" value="{{ old('postal_code', $address->postal_code) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold small text-muted">Kecamatan</label>
                            <select name="district" id="districtSelect" class="form-select rounded-pill" required>
                                <option value="">Pilih Kota/Kab Dahulu</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="is_default" value="0">
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default" {{ $address->is_default ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_default">Jadikan sebagai Alamat Utama</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn text-white rounded-pill px-4 shadow-sm" style="background-color: #ff6699;">Simpan Perubahan</button>
                    <a href="{{ route('address.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card-3d {
        background: #ffffff;
        box-shadow: 0 10px 20px rgba(255, 102, 153, 0.08);
        border-radius: 20px;
        border-left: 6px solid #ff6699;
    }
    .text-pink { color: #ff6699; }
    .form-control:focus, .form-select:focus { border-color: #ff6699; box-shadow: 0 0 0 0.25rem rgba(255, 102, 153, 0.25); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        const provSelect = document.getElementById('provinceSelect');
        const citySelect = document.getElementById('citySelect');
        const distSelect = document.getElementById('districtSelect');
        const postalInput = document.getElementById('postalCodeInput');

        const oldProv = "{{ old('province', $address->province) }}";
        const oldCity = "{{ old('city', $address->city) }}";
        const oldDist = "{{ old('district', $address->district) }}";

        // Fetch Provinces
        fetch(`${apiBase}/provinces.json`)
            .then(res => res.json())
            .then(data => {
                provSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                data.forEach(prov => {
                    const selected = prov.name === oldProv ? 'selected' : '';
                    provSelect.innerHTML += `<option value="${prov.name}" data-id="${prov.id}" ${selected}>${prov.name}</option>`;
                });
                if(oldProv) provSelect.dispatchEvent(new Event('change'));
            });

        provSelect.addEventListener('change', function() {
            const id = this.options[this.selectedIndex]?.getAttribute('data-id');
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            distSelect.innerHTML = '<option value="">Pilih Kota/Kab Dahulu</option>';
            if(!id) return;

            citySelect.innerHTML = '<option value="">Memuat...</option>';
            fetch(`${apiBase}/regencies/${id}.json`)
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    data.forEach(city => {
                        const selected = city.name === oldCity ? 'selected' : '';
                        citySelect.innerHTML += `<option value="${city.name}" data-id="${city.id}" ${selected}>${city.name}</option>`;
                    });
                    if(oldCity && provSelect.value === oldProv) citySelect.dispatchEvent(new Event('change'));
                });
        });

        citySelect.addEventListener('change', function() {
            const id = this.options[this.selectedIndex]?.getAttribute('data-id');
            distSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            if(!id) return;

            distSelect.innerHTML = '<option value="">Memuat...</option>';
            fetch(`${apiBase}/districts/${id}.json`)
                .then(res => res.json())
                .then(data => {
                    distSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    data.forEach(dist => {
                        const selected = dist.name === oldDist ? 'selected' : '';
                        distSelect.innerHTML += `<option value="${dist.name}" data-id="${dist.id}" ${selected}>${dist.name}</option>`;
                    });
                });
        });

        distSelect.addEventListener('change', function() {
            const id = this.options[this.selectedIndex]?.getAttribute('data-id');
            if(id && !postalInput.value) {
                // Auto-fill postal code simulation
                postalInput.value = "2" + id.substring(0, 4);
            }
        });
    });
</script>
@endsection