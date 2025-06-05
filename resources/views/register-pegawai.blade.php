{{-- resources/views/hrd/register_employee.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Pegawai Baru</h2>

        <form id="registerForm" action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Stepper Navigation --}}
            <ul class="nav nav-tabs mb-4" id="stepperTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="step1-tab" data-bs-toggle="tab" data-bs-target="#step1"
                        type="button" role="tab" aria-controls="step1" aria-selected="true">
                        1. Data User
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="step2-tab" data-bs-toggle="tab" data-bs-target="#step2" type="button"
                        role="tab" aria-controls="step2" aria-selected="false" disabled>
                        2. Detail Kepegawaian
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="stepperContent">
                {{-- Step 1: Data User --}}
                <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                            class="form-control @error('birth_date') is-invalid @enderror" required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="marital_status" class="form-label">Status Perkawinan</label>
                        <select name="marital_status" id="marital_status"
                            class="form-select @error('marital_status') is-invalid @enderror" required>
                            <option value="">-- Pilih Status Perkawinan --</option>
                            <option value="Belum Kawin" {{ old('marital_status') == 'Belum Kawin' ? 'selected' : '' }}>
                                Belum Kawin
                            </option>
                            <option value="Kawin" {{ old('marital_status') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                        </select>
                        @error('marital_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="button" class="btn btn-primary" id="nextToStep2">Next</button>
                </div>

                {{-- Step 2: Detail Kepegawaian --}}
                <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                    <div class="mb-3">
                        <label for="unit_kerja_id" class="form-label">Unit Kerja</label>
                        <select name="unit_kerja_id" id="unit_kerja_id"
                            class="form-select @error('unit_kerja_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_kerja_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="employee_number" class="form-label">Nomor Pegawai</label>
                        <input type="text" name="employee_number" id="employee_number"
                            class="form-control @error('employee_number') is-invalid @enderror"
                            value="{{ old('employee_number') }}" required>
                        @error('employee_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nomor pegawai bisa otomatis atau manual</div>
                    </div>

                    <div class="mb-3">
                        <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" id="tahun_masuk"
                            class="form-control @error('tahun_masuk') is-invalid @enderror"
                            value="{{ old('tahun_masuk', date('Y')) }}" required min="1900"
                            max="{{ date('Y') }}">
                        @error('tahun_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tahun_sertifikasi" class="form-label">Tahun Sertifikasi</label>
                        <input type="number" name="tahun_sertifikasi" id="tahun_sertifikasi"
                            class="form-control @error('tahun_sertifikasi') is-invalid @enderror"
                            value="{{ old('tahun_sertifikasi', date('Y')) }}" required min="1900"
                            max="{{ date('Y') }}">
                        @error('tahun_sertifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status_kepegawaian" class="form-label">Status Kepegawaian</label>
                        <select name="status_kepegawaian" id="status_kepegawaian"
                            class="form-select @error('status_kepegawaian') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Kontrak" {{ old('status_kepegawaian') == 'Kontrak' ? 'selected' : '' }}>Kontrak
                            </option>
                            <option value="Tetap" {{ old('status_kepegawaian') == 'Tetap' ? 'selected' : '' }}>Tetap
                            </option>
                            <option value="Magang" {{ old('status_kepegawaian') == 'Magang' ? 'selected' : '' }}>Magang
                            </option>
                        </select>
                        @error('status_kepegawaian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="button" class="btn btn-secondary" id="backToStep1">Back</button>
                    <button type="submit" class="btn btn-success">Simpan Pegawai</button>
                </div>
            </div>
        </form>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const step1Tab = document.getElementById('step1-tab');
        const step2Tab = document.getElementById('step2-tab');

        const nextBtn = document.getElementById('nextToStep2');
        const backBtn = document.getElementById('backToStep1');

        nextBtn.addEventListener('click', function() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const passwordConfirmation = document.getElementById('password_confirmation').value.trim();
            const gender = document.getElementById('gender').value;
            const birthDate = document.getElementById('birth_date').value;
            const maritalStatus = document.getElementById('marital_status').value;

            if (!name || !email || !password || !passwordConfirmation || !gender || !birthDate || !
                maritalStatus) {
                alert('Mohon isi semua field di Data User sebelum lanjut.');
                return;
            }

            if (password !== passwordConfirmation) {
                alert('Password dan Konfirmasi Password tidak sama.');
                return;
            }

            new bootstrap.Tab(step2Tab).show();
        });

        backBtn.addEventListener('click', function() {
            new bootstrap.Tab(step1Tab).show();
        });
    });
</script>
