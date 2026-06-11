@extends('layouts.app')

@section('title','Pengaturan Akun')

@section('content')

<style>
    body { background-color: #fcf7f9 !important; }
    .settings-sidebar .nav-link {
        color: #495057;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 8px;
        font-weight: 500;
        transition: 0.3s;
    }
    .settings-sidebar .nav-link:hover {
        background-color: #ffe6f0;
        color: #ff6699;
    }
    .settings-sidebar .nav-link.active {
        background-color: #ff6699;
        color: white;
        box-shadow: 0 4px 10px rgba(255,102,153,0.3);
    }
    
    .avatar-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .avatar-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-upload-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.5);
        color: white;
        padding: 8px 0;
        text-align: center;
        cursor: pointer;
        opacity: 0;
        transition: 0.3s;
    }
    .avatar-wrapper:hover .avatar-upload-overlay {
        opacity: 1;
    }
    .form-control-premium {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
    }
    .form-control-premium:focus {
        border-color: #ff6699;
        box-shadow: 0 0 0 0.25rem rgba(255,102,153,0.25);
    }
    .btn-pink { background-color: #ff6699; color: white; border-radius: 50px; padding: 10px 25px;}
    .btn-pink:hover { background-color: #e65586; color: white; }

    /* Address Tab Styling */
    .card-address-premium {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }
    .card-address-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: #ffb3d1; }
</style>

<div class="container py-5">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 mb-4">
            <h4 class="fw-bold mb-4">Pengaturan</h4>
            <div class="nav flex-column nav-pills settings-sidebar" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link text-start active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="true">
                    <i class="fas fa-user-circle me-2"></i> Profil Saya
                </button>
                <button class="nav-link text-start" id="v-pills-address-tab" data-bs-toggle="pill" data-bs-target="#v-pills-address" type="button" role="tab" aria-controls="v-pills-address" aria-selected="false">
                    <i class="fas fa-map-marker-alt me-2"></i> Daftar Alamat
                </button>
                <button class="nav-link text-start" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab" aria-controls="v-pills-security" aria-selected="false">
                    <i class="fas fa-lock me-2"></i> Keamanan
                </button>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- TAB PROFIL -->
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-4 border-bottom pb-3">Profil Saya</h4>
                            
                            <!-- NOTIFIKASI SUCCESS AJAX -->
                            <div id="profile-alert" class="alert alert-success d-none mb-4">
                                <i class="fas fa-check-circle me-2"></i> <span id="profile-msg"></span>
                            </div>

                            <div class="row">
                                <!-- AVATAR UPLOAD -->
                                <div class="col-md-4 text-center mb-4 mb-md-0">
                                    <div class="avatar-wrapper mb-3">
                                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=E91E63&color=fff' }}" 
                                             class="avatar-preview" id="avatarPreview" alt="Avatar">
                                        <label for="avatarUpload" class="avatar-upload-overlay">
                                            <i class="fas fa-camera"></i><br><small>Ubah Foto</small>
                                        </label>
                                        <input type="file" id="avatarUpload" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small">Ukuran maks: 2MB.<br>Format: JPG, PNG, GIF.</p>
                                    <div class="spinner-border text-danger d-none" id="avatarSpinner" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                                <!-- FORM BIODATA -->
                                <div class="col-md-8">
                                    <form id="profileForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small">Nama Lengkap</label>
                                            <input type="text" id="name" class="form-control form-control-premium" value="{{ Auth::user()->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small">Email</label>
                                            <input type="email" id="email" class="form-control form-control-premium" value="{{ Auth::user()->email }}" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-muted small">No. Handphone</label>
                                            <input type="text" id="phone" class="form-control form-control-premium" value="{{ Auth::user()->phone }}" placeholder="Contoh: 08123456789">
                                        </div>
                                        <button type="submit" class="btn btn-pink px-4" id="btnSaveProfile">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB ALAMAT (Integrasi Langsung) -->
                <div class="tab-pane fade" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab" tabindex="0">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                <h4 class="fw-bold mb-0">Daftar Alamat</h4>
                                <a href="{{ route('address.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    Kelola Alamat Lengkap
                                </a>
                            </div>

                            @php
                                $addresses = Auth::user()->addresses()->latest()->get();
                            @endphp

                            @forelse($addresses as $address)
                                <div class="card card-address-premium mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                @if($address->is_default)
                                                    <span class="badge bg-danger mb-2 px-2 py-1"><i class="fas fa-star me-1"></i> Utama</span>
                                                @else
                                                    <span class="badge bg-light text-dark mb-2 border"><i class="fas fa-tag me-1"></i> {{ $address->label ?? 'Rumah' }}</span>
                                                @endif
                                                <h6 class="fw-bold mb-1">{{ $address->recipient_name }} <span class="text-muted fw-normal">({{ $address->phone }})</span></h6>
                                                <p class="mb-0 text-muted small">
                                                    {{ $address->address }}<br>
                                                    {{ $address->district }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <a href="{{ route('address.edit', $address->id) }}" class="btn btn-sm btn-light text-primary rounded-circle" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('address.destroy', $address->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle" title="Hapus" onclick="return confirm('Hapus alamat ini?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 bg-light rounded-3">
                                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6>Belum ada alamat tersimpan</h6>
                                    <button class="btn btn-sm btn-danger mt-2 px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#alamatModal">Tambah Alamat Baru</button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- TAB KEAMANAN -->
                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab" tabindex="0">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-4 border-bottom pb-3">Keamanan Akun</h4>
                            
                            <form method="post" action="{{ route('password.update') }}">
                                @csrf
                                @method('put')
                                
                                @if (session('status') === 'password-updated')
                                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                                        <i class="fas fa-check-circle me-2"></i> Kata sandi berhasil diperbarui!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted small">Kata Sandi Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control form-control-premium @error('current_password', 'updatePassword') is-invalid @enderror" required>
                                    @error('current_password', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted small">Kata Sandi Baru</label>
                                    <input type="password" name="password" class="form-control form-control-premium @error('password', 'updatePassword') is-invalid @enderror" required>
                                    @error('password', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-premium @error('password_confirmation', 'updatePassword') is-invalid @enderror" required>
                                    @error('password_confirmation', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-pink px-4"><i class="fas fa-key me-2"></i> Perbarui Kata Sandi</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('status') === 'password-updated' || $errors->updatePassword->any())
        var securityTab = new bootstrap.Tab(document.querySelector('#v-pills-security-tab'));
        securityTab.show();
    @endif
    
    // --- 1. UPLOAD AVATAR AJAX ---
    const avatarInput = document.getElementById('avatarUpload');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarSpinner = document.getElementById('avatarSpinner');
    const profileAlert = document.getElementById('profile-alert');
    const profileMsg = document.getElementById('profile-msg');
    
    // Update profil di navbar header jika ada
    const headerAvatarElements = document.querySelectorAll('.profile-img');

    avatarInput.addEventListener('change', function() {
        if(this.files && this.files[0]) {
            let formData = new FormData();
            formData.append('avatar', this.files[0]);
            
            // Show Spinner
            avatarSpinner.classList.remove('d-none');
            
            fetch("{{ route('profile.upload.avatar') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                avatarSpinner.classList.add('d-none');
                if(data.success) {
                    // Update preview instantly
                    avatarPreview.src = data.avatar_url;
                    
                    // Update all avatars in navbar
                    headerAvatarElements.forEach(el => {
                        el.src = data.avatar_url;
                    });
                    
                    showAlert(data.message);
                } else {
                    alert("Gagal upload: " + (data.message || "Terjadi kesalahan."));
                }
            })
            .catch(error => {
                avatarSpinner.classList.add('d-none');
                console.error("Error:", error);
                alert("Terjadi kesalahan jaringan.");
            });
        }
    });

    // --- 2. UPDATE PROFIL AJAX ---
    const profileForm = document.getElementById('profileForm');
    const btnSave = document.getElementById('btnSaveProfile');

    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let originalText = btnSave.innerHTML;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menyimpan...';
        btnSave.disabled = true;

        let formData = new FormData();
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);

        fetch("{{ route('profile.update.ajax') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btnSave.innerHTML = originalText;
            btnSave.disabled = false;
            
            if(data.success) {
                showAlert(data.message);
            } else {
                alert("Gagal menyimpan profil.");
            }
        })
        .catch(error => {
            btnSave.innerHTML = originalText;
            btnSave.disabled = false;
            console.error("Error:", error);
            alert("Email sudah digunakan atau terjadi kesalahan input.");
        });
    });

    // Utility function to show auto-hiding alert
    function showAlert(msg) {
        profileMsg.textContent = msg;
        profileAlert.classList.remove('d-none');
        
        setTimeout(() => {
            profileAlert.classList.add('d-none');
        }, 4000);
    }
});
</script>

@include('address.partials.modal-tambah') 
@endsection