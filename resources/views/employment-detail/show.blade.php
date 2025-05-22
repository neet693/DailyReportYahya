@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Informasi Data Diri -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h4 class="fw-bold">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <hr>
                <h5 class="fw-bold">Informasi Data Diri</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Employee Number:</strong>{{ $user->employmentDetail->employee_number ?? 'N/A' }}</p>
                        <p><strong>Tanggal Lahir:</strong> <span
                                class="fw-bold">{{ $user->birth_date->format('d F Y') ?? 'Belum Diisi' }}</span></p>
                        <p><strong>Alamat:</strong> {{ $user->address }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Gender:</strong> {{ $user->gender }}</p>
                        <p><strong>Status Perkawinan:</strong> <span class="fw-bold">{{ $user->marital_status }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kepegawaian -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold">Informasi Kepegawaian</h5>
                    @if (Auth::id() == $user->id || Auth::user()->role === 'admin' || Auth::user()->role === 'hrd')
                        {{-- <a href="{{ route('employment-detail.create') }}" class="btn btn-primary">Tambah / Edit Detail</a> --}}
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
                    </div>
                    <div class="col-md-6">
                        <p><strong>Unit:</strong> {{ $user->employmentDetail->unit->name ?? 'N/A' }}</p>
                        <p><strong>Status
                                Kepegawaian:</strong>{{ $user->employmentDetail->status_kepegawaian ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surat Peringatan -->
        @if (auth()->user()->canViewSP($user))
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold">Surat Peringatan</h5>
                            @if ($canManageSP)
                                <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambahSP">
                                    + Tambah Surat Peringatan
                                </button>
                                @include('components.sp-modal')
                            @endif
                        </div>
                    </div>
                    <hr>

                    <div class="mt-3">
                        @if ($user->statusPeringatanAktif->isEmpty())
                            <p><strong>Status SP:</strong> Tidak ada</p>
                        @else
                            <ul>
                                @foreach ($user->statusPeringatan as $sp)
                                    <li>
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#spDetailModal{{ $sp->id }}">
                                            {{ $sp->judul }} - {{ $sp->durasi }}
                                        </a>
                                    </li>

                                    <!-- Modal SP Detail -->
                                    <div class="modal fade" id="spDetailModal{{ $sp->id }}" tabindex="-1"
                                        aria-labelledby="spDetailModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="spDetailModalLabel">Detail Surat Peringatan
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>{{ $sp->judul }}</h5>
                                                    <p><strong>Tanggal:</strong>
                                                        {{ $sp->mulai_berlaku->format('d M Y') }} -
                                                        {{ $sp->berakhir_berlaku->format('d M Y') }}
                                                    </p>
                                                    <p><strong>Durasi:</strong> {{ $sp->durasi }}</p>
                                                    <p><strong>Alasan SP:</strong></p>
                                                    <div>{!! $sp->alasan !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                        <button class="nav-link active" id="education-tab" data-bs-toggle="tab" data-bs-target="#education"
                            type="button" role="tab">Pendidikan</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="training-tab" data-bs-toggle="tab" data-bs-target="#training"
                            type="button" role="tab">Diklat</button>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- Tab Pendidikan -->
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

                    <!-- Tab Diklat -->
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

        <!-- Include Modal -->
        @include('components.education-modal')
        @include('components.training-modal')
    @endsection
