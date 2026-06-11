<!-- Modal Tambah Alamat -->
<div class="modal fade" id="alamatModal" tabindex="-1" aria-labelledby="alamatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3" style="background: linear-gradient(135deg, #FF6699, #E91E63);">
                <h5 class="modal-title text-white fw-bold" id="alamatModalLabel"><i class="fas fa-map-marker-alt me-2"></i>Tambah Alamat Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('address.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark">Label Alamat</label>
                            <input type="text" name="label" class="form-control rounded-3" placeholder="Contoh: Rumah, Kantor, Kos" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Nama Penerima</label>
                            <input type="text" name="recipient_name" class="form-control rounded-3" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control rounded-3" placeholder="08xxxxxxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Provinsi</label>
                            <select name="province" id="modalProvinceSelect" class="form-select rounded-3" required>
                                <option value="">Memuat...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Kota / Kabupaten</label>
                            <select name="city" id="modalCitySelect" class="form-select rounded-3" required>
                                <option value="">Pilih Provinsi Dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Kecamatan</label>
                            <select name="district" id="modalDistrictSelect" class="form-select rounded-3" required>
                                <option value="">Pilih Kota/Kab Dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Kode Pos</label>
                            <input type="text" name="postal_code" id="modalPostalCodeInput" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark">Alamat Lengkap</label>
                            <textarea name="address" class="form-control rounded-3" rows="3" placeholder="Nama Jalan, Gedung, No. Rumah, dll." required></textarea>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_default" id="is_default">
                                <label class="form-check-label text-dark" for="is_default">
                                    Jadikan sebagai alamat utama
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-pink rounded-pill px-4 text-white border-0" style="background: linear-gradient(45deg, #FF6699, #E91E63);">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalApiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        const mProvSelect = document.getElementById('modalProvinceSelect');
        const mCitySelect = document.getElementById('modalCitySelect');
        const mDistSelect = document.getElementById('modalDistrictSelect');
        const mPostalInput = document.getElementById('modalPostalCodeInput');

        if(mProvSelect) {
            // Fetch Provinces
            fetch(`${modalApiBase}/provinces.json`)
                .then(res => res.json())
                .then(data => {
                    mProvSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    data.forEach(prov => {
                        mProvSelect.innerHTML += `<option value="${prov.name}" data-id="${prov.id}">${prov.name}</option>`;
                    });
                });

            mProvSelect.addEventListener('change', function() {
                const id = this.options[this.selectedIndex]?.getAttribute('data-id');
                mCitySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                mDistSelect.innerHTML = '<option value="">Pilih Kota/Kab Dahulu</option>';
                if(!id) return;

                mCitySelect.innerHTML = '<option value="">Memuat...</option>';
                fetch(`${modalApiBase}/regencies/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        mCitySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                        data.forEach(city => {
                            mCitySelect.innerHTML += `<option value="${city.name}" data-id="${city.id}">${city.name}</option>`;
                        });
                    });
            });

            mCitySelect.addEventListener('change', function() {
                const id = this.options[this.selectedIndex]?.getAttribute('data-id');
                mDistSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                if(!id) return;

                mDistSelect.innerHTML = '<option value="">Memuat...</option>';
                fetch(`${modalApiBase}/districts/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        mDistSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        data.forEach(dist => {
                            mDistSelect.innerHTML += `<option value="${dist.name}" data-id="${dist.id}">${dist.name}</option>`;
                        });
                    });
            });

            mDistSelect.addEventListener('change', function() {
                const id = this.options[this.selectedIndex]?.getAttribute('data-id');
                // Auto-fill postal code simulation only if empty
                if(id && !mPostalInput.value) {
                    mPostalInput.value = "2" + id.substring(0, 4);
                }
            });
        }
    });
</script>
