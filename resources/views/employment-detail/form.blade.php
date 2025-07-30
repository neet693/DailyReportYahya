@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Detail Kepegawaian</h5>
                <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('employment-detail.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="mb-3">
                        <label class="form-label">Nomor Pegawai</label>
                        <input type="text" name="employee_number" class="form-control"
                            value="{{ $user->employmentDetail->employee_number ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit Kerja</label>
                        <select name="unit_kerja_id" class="form-control" required>
                            <option value="">Pilih Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ isset($user->employmentDetail) && $user->employmentDetail->unit_kerja_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" class="form-control"
                            value="{{ $user->employmentDetail->tahun_masuk ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun Sertifikasi</label>
                        <input type="number" name="tahun_sertifikasi" class="form-control"
                            value="{{ $user->employmentDetail->tahun_sertifikasi ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kepegawaian</label>
                        <select name="status_kepegawaian" class="form-control" required>
                            <option value="Tetap"
                                {{ isset($user->employmentDetail) && $user->employmentDetail->status_kepegawaian == 'Tetap' ? 'selected' : '' }}>
                                Tetap</option>
                            <option value="Tidak Tetap"
                                {{ isset($user->employmentDetail) && $user->employmentDetail->status_kepegawaian == 'Tidak Tetap' ? 'selected' : '' }}>
                                Tidak Tetap</option>
                            <option value="Kontrak"
                                {{ isset($user->employmentDetail) && $user->employmentDetail->status_kepegawaian == 'Kontrak' ? 'selected' : '' }}>
                                Kontrak</option>
                        </select>
                    </div>

                    {{-- 🔥 Bagian baru: status aktif dan tahun keluar --}}
                    <div class="mb-3">
                        <label class="form-label">Status Aktif</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1"
                                {{ isset($user->employmentDetail) && $user->employmentDetail->is_active ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="0"
                                {{ isset($user->employmentDetail) && !$user->employmentDetail->is_active ? 'selected' : '' }}>
                                Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun Keluar</label>
                        <input type="number" name="tahun_keluar" class="form-control"
                            value="{{ $user->employmentDetail->tahun_keluar ?? '' }}">
                        <small class="text-muted">Kosongkan jika masih aktif / kontrak masih berjalan</small>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
