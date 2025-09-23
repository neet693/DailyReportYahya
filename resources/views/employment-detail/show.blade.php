@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Informasi Data Diri (nama & email selalu terlihat) -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h4 class="fw-bold">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <hr>

                {{-- Bagian sensitif --}}
                <div id="sensitive-section" class="{{ $verified ? '' : 'blurred position-relative' }}">
                    <h5 class="fw-bold">Informasi Data Diri</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Employee Number:</strong> {{ $user->employmentDetail->employee_number ?? 'N/A' }}</p>
                            <p><strong>Tanggal Lahir:</strong>
                                <span class="fw-bold">{{ $user->birth_date?->format('d F Y') ?? 'Belum Diisi' }}</span>
                            </p>
                            <p><strong>Alamat:</strong> {{ $user->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Gender:</strong> {{ $user->gender }}</p>
                            <p><strong>Status Perkawinan:</strong>
                                <span class="fw-bold">{{ $user->marital_status }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Informasi Kepegawaian -->
                    <div class="card shadow-sm border-0 mb-4 mt-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold">Informasi Kepegawaian</h5>
                                @if (Auth::user()->isAdmin() || Auth::user()->isHRD() || Auth::user()->isKepalaUnit())
                                    <a href="{{ route('employment-detail.create', ['employment_detail' => $user->employmentDetail->employee_number]) }}"
                                        class="btn btn-primary">
                                        Tambah / Edit Detail
                                    </a>
                                @endif
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Tahun Masuk:</strong> {{ $user->employmentDetail->tahun_masuk ?? 'N/A' }}</p>
                                    <p><strong>Tahun Sertifikasi:</strong>
                                        {{ $user->employmentDetail->tahun_sertifikasi ?? 'N/A' }}</p>
                                    @if (!($user->employmentDetail->is_active ?? true))
                                        <p><strong>Tahun Keluar:</strong>
                                            {{ $user->employmentDetail->tahun_keluar ?? 'N/A' }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Unit:</strong> {{ $user->employmentDetail->unit->name ?? 'N/A' }}</p>
                                    <p><strong>Status Kepegawaian:</strong>
                                        {{ $user->employmentDetail->status_kepegawaian ?? 'N/A' }}</p>
                                    <p><strong>Status Aktif:</strong>
                                        @if ($user->employmentDetail->is_active ?? true)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Surat Peringatan -->
                    @if (auth()->user()->canViewSP($user))
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold">Surat Peringatan</h5>
                                    @if ($canManageSP)
                                        <button class="btn btn-primary mb-2" data-bs-toggle="modal"
                                            data-bs-target="#modalTambahSP">
                                            + Tambah Surat Peringatan
                                        </button>
                                        @include('components.sp-modal')
                                    @endif
                                </div>
                                <hr>
                                <div class="mt-3">
                                    @if ($user->statusPeringatanAktif->isEmpty())
                                        <p><strong>Status SP:</strong> Tidak ada</p>
                                    @else
                                        <ul>
                                            @foreach ($user->statusPeringatan as $sp)
                                                <li class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#spDetailModal{{ $sp->id }}">
                                                            {{ $sp->judul }} - {{ $sp->sisa_durasi }}
                                                            @if ($sp->is_active)
                                                                <span class="badge bg-success">Aktif</span>
                                                            @else
                                                                <span class="badge bg-secondary">Non Aktif</span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                    @if ($canManageSP)
                                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditSP{{ $sp->id }}">
                                                            ✏ Edit
                                                        </button>
                                                    @endif
                                                </li>
                                                @include('components.sp-detail-modal', [
                                                    'sp' => $sp,
                                                    'user' => $user,
                                                ])
                                                @include('components.sp-edit-modal', [
                                                    'sp' => $sp,
                                                    'user' => $user,
                                                ])
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Riwayat Pendidikan dan Diklat -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold">Riwayat Pendidikan dan Diklat</h5>
                            <ul class="nav nav-tabs" id="educationTrainingTab" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="education-tab" data-bs-toggle="tab"
                                        data-bs-target="#education" type="button" role="tab">Pendidikan</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="training-tab" data-bs-toggle="tab"
                                        data-bs-target="#training" type="button" role="tab">Diklat</button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="education" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p><strong>Riwayat Pendidikan</strong></p>
                                        @if (Auth::id() == $user->id || Auth::user()->role === 'admin' || Auth::user()->role === 'hrd')
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addEducationModal">
                                                Tambah Pendidikan
                                            </button>
                                        @endif
                                    </div>
                                    <ul class="mt-3">
                                        @foreach ($user->educationHistories as $edu)
                                            <li>{{ $edu->degree }} - {{ $edu->institution }} (Lulus:
                                                {{ $edu->year_of_graduation ?? 'N/A' }})</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="training" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p><strong>Riwayat Diklat</strong></p>
                                        @if (Auth::id() == $user->id)
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addTrainingModal">
                                                Tambah Diklat
                                            </button>
                                        @endif
                                    </div>
                                    <ul class="mt-3">
                                        @foreach ($user->trainings as $training)
                                            <li>{{ $training->training_name }} - {{ $training->organizer }}
                                                ({{ $training->training_date ?? 'N/A' }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('components.education-modal')
                    @include('components.training-modal')
                </div>

                {{-- Overlay untuk verifikasi --}}
                @unless ($verified)
                    <div class="verify-overlay d-flex flex-column justify-content-center align-items-center">
                        <p class="mb-3 fw-bold text-white">🔒 Masukkan kode verifikasi untuk melihat detail kepegawaian</p>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#verifyModal">
                            Verifikasi Sekarang
                        </button>
                    </div>
                @endunless
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi -->
    <div class="modal fade" id="verifyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST"
                action="{{ route('employment-detail.verify', $user->employmentDetail->employee_number) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Verifikasi Akses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Masukkan kode untuk membuka detail kepegawaian.</p>
                        <input type="password" name="password" class="form-control" required placeholder="Password Anda">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Verifikasi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .blurred {
            filter: blur(5px);
            pointer-events: none;
            user-select: none;
        }

        .verify-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            text-align: center;
            border-radius: 0.5rem;
        }
    </style>
@endsection
