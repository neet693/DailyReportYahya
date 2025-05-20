@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Detail Kepegawaian</h5>
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
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                    {{-- <a href="{{ route('employment-detail.show') }}" class="btn btn-secondary">Batal</a> --}}
                </form>
            </div>
        </div>
    </div>
@endsection
