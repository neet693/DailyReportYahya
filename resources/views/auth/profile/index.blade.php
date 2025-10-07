@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h5 class="fw-bold mb-2">Edit Profil</h5>
                            <p class="text-muted small mb-0">Perbarui informasi pribadi kamu</p>
                        </div>

                        <form method="POST" action="{{ route('profile.update', ['profile' => $user->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- FOTO PROFIL --}}
                            <div class="d-flex justify-content-center mb-4">
                                <div class="profile-photo-wrapper position-relative" style="width:130px; height:130px;">
                                    <img id="profileImagePreview"
                                        src="{{ asset('profile_images/' . (auth()->user()->profile_image ?? 'default.png')) }}"
                                        alt="Foto Profil" class="rounded-circle shadow-sm border w-100 h-100"
                                        style="object-fit:cover;">

                                    {{-- Overlay --}}
                                    <div
                                        class="photo-overlay d-flex flex-column justify-content-center align-items-center text-white">
                                        <i class="bi bi-camera fs-4 mb-1"></i>
                                        <small>Ganti Foto</small>
                                    </div>

                                    <input type="file" id="profile_image" name="profile_image" class="d-none"
                                        accept="image/*" onchange="previewImage(event)">
                                </div>
                            </div>

                            {{-- FORM --}}
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control form-control-sm"
                                        value="{{ old('name', auth()->user()->name) }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" name="email" class="form-control form-control-sm"
                                        value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label fw-semibold small">Jenis Kelamin</label>
                                    <select name="gender" class="form-select form-select-sm">
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki"
                                            {{ old('gender', auth()->user()->gender) == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="Perempuan"
                                            {{ old('gender', auth()->user()->gender) == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-semibold small">Status</label>
                                    <select name="marital_status" class="form-select form-select-sm">
                                        <option value="">Pilih</option>
                                        <option value="Belum Kawin"
                                            {{ old('marital_status', auth()->user()->marital_status) == 'Belum Kawin' ? 'selected' : '' }}>
                                            Belum Kawin</option>
                                        <option value="Kawin"
                                            {{ old('marital_status', auth()->user()->marital_status) == 'Kawin' ? 'selected' : '' }}>
                                            Kawin</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Tanggal Lahir</label>
                                        <input type="date" name="birth_date"
                                            value="{{ old('birth_date', auth()->user()->birth_date ? auth()->user()->birth_date->format('Y-m-d') : '') }}"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Alamat</label>
                                <textarea name="address" rows="2" class="form-control form-control-sm">{{ old('address', auth()->user()->address) }}</textarea>
                            </div>

                            {{-- PASSWORD --}}
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Kata Sandi Baru</label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Opsional">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Konfirmasi Sandi</label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Ulangi sandi baru">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('password_confirmation', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-pill fw-semibold">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <style>
        .profile-photo-wrapper:hover .photo-overlay {
            opacity: 1;
            pointer-events: auto;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.55);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
    </style>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = () => {
                document.getElementById('profileImagePreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Klik overlay = buka file picker
        document.querySelector('.photo-overlay').addEventListener('click', () => {
            document.getElementById('profile_image').click();
        });
    </script>
@endsection
