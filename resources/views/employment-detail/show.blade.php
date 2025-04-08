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
                    <a href="{{ route('employment-detail.create') }}" class="btn btn-primary">Tambah / Edit Detail</a>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tahun Masuk:</strong> {{ $user->employmentDetail->tahun_masuk ?? 'N/A' }}</p>
                        <p><strong>Tahun Sertifikasi:</strong> {{ $user->employmentDetail->tahun_sertifikasi ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Unit:</strong> {{ $user->employmentDetail->unit->name ?? 'N/A' }}</p>
                        <p><strong>Status Kepegawaian:</strong>{{ $user->employmentDetail->status_kepegawaian ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

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
                        <p><strong>Riwayat Pendidikan</strong>
                            <a href="#" class="text-primary fw-bold" data-bs-toggle="modal"
                                data-bs-target="#addEducationModal">+</a>
                        </p>
                        <ul>
                            @foreach ($user->educationHistories as $edu)
                                <li>{{ $edu->degree }} - {{ $edu->institution }} (Lulus:
                                    {{ $edu->year_of_graduation ?? 'N/A' }})</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Tab Diklat -->
                    <div class="tab-pane fade" id="training" role="tabpanel">
                        <p><strong>Riwayat Diklat</strong>
                            <a href="#" class="text-primary fw-bold" data-bs-toggle="modal"
                                data-bs-target="#addTrainingModal">+</a>
                        </p>
                        <ul>
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
