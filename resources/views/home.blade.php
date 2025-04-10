@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="fw-bold text-center mb-4">
            Unit:
            @if (auth()->user()->role === 'admin')
                <span class="text-primary">Semua Unit</span>
            @else
                {{ auth()->user()->employmentDetail->unit->name ?? 'Tidak Ada Unit' }}
            @endif
        </h3>

        <div class="row justify-content-center">
            @include('toaster')

            @foreach ($usersWithTasks as $data)
                <div class="col-md-4 mb-3">
                    <div class="card-custom">
                        {{-- Header: Foto & Nama --}}
                        <div class="d-flex align-items-center p-4">
                            <img src="{{ $data->profile_image ? asset('profile_images/' . $data->profile_image) : asset('asset/default_profile.jpg') }}"
                                class="rounded-circle profile-img" alt="Foto Profil">
                            <div class="ms-3">
                                <h5 class="mb-0">{{ $data->name }}</h5>
                                <p class="text-muted small mb-0">
                                    {{ $data->jobdesk->title ?? 'Belum ada Job Desc' }}
                                </p>
                            </div>
                        </div>

                        {{-- Body: Tugas --}}
                        <div class="card-body">
                            <h6 class="title-section mb-3">
                                <i class="bi bi-clipboard-check me-2"></i> Tasks for Today
                            </h6>

                            <ul class="list-group list-group-flush">
                                {{-- 2 Tugas Pertama --}}
                                @forelse ($data->tasks->take(2) as $task)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong>{{ $task->title }}</strong>
                                            <span
                                                class="badge rounded-pill {{ $task->progres ? 'bg-success' : 'bg-danger' }}">
                                                {{ $task->progres ? 'Selesai' : 'Belum Selesai' }}
                                            </span>
                                        </div>
                                        <div class="text-muted small mb-2 fs-6">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $task->place }} |
                                            <i class="bi bi-clock me-1"></i>{{ $task->task_start_time->format('H:i') }} -
                                            {{ $task->task_end_time->format('H:i') }}
                                        </div>
                                        @if (auth()->user()->id === $data->id && $task->progres === 0)
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-success me-2" data-bs-toggle="modal"
                                                    data-bs-target="#completeModal{{ $task->id }}">
                                                    <i class="bi bi-check-lg"></i> Selesai
                                                </button>
                                                @include('components.task_modal_complete', [
                                                    'task' => $task,
                                                ])
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#pendingModal{{ $task->id }}">
                                                    <i class="bi bi-hourglass-split text-white"></i> Pending
                                                </button>
                                                @include('components.task_modal_pending', [
                                                    'task' => $task,
                                                ])
                                            </div>
                                        @endif
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">Tidak ada tugas hari ini.</li>
                                @endforelse
                            </ul>
                            @if ($data->tasks->count() > 2)
                                <button class="btn btn-light w-100 text-start position-relative toggle-tasks mt-2">
                                    <i class="bi bi-chevron-down me-1 toggle-icon"></i>
                                    Lihat tugas lainnya
                                    <span
                                        class="position-absolute top-50 end-0 translate-middle badge rounded-pill bg-danger me-3">
                                        {{ $data->tasks->count() - 2 }}
                                        <span class="visually-hidden">tugas lainnya</span>
                                    </span>
                                </button>

                                <ul class="more-tasks list-unstyled mt-2 d-none small">
                                    @foreach ($data->tasks->slice(2) as $task)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <strong>{{ $task->title }}</strong>
                                                <span
                                                    class="badge rounded-pill {{ $task->progres ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $task->progres ? 'Selesai' : 'Belum Selesai' }}
                                                </span>
                                            </div>
                                            <div class="text-muted small mb-2 fs-6">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $task->place }} |
                                                <i class="bi bi-clock me-1"></i>{{ $task->task_start_time->format('H:i') }}
                                                -
                                                {{ $task->task_end_time->format('H:i') }}
                                            </div>
                                            @if (auth()->user()->id === $data->id && $task->progres === 0)
                                                <div class="d-flex">
                                                    <button class="btn btn-sm btn-success me-2" data-bs-toggle="modal"
                                                        data-bs-target="#completeModal{{ $task->id }}">
                                                        <i class="bi bi-check-lg"></i> Selesai
                                                    </button>
                                                    @include('components.task_modal_complete', [
                                                        'task' => $task,
                                                    ])
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#pendingModal{{ $task->id }}">
                                                        <i class="bi bi-hourglass-split text-white"></i> Pending
                                                    </button>
                                                    @include('components.task_modal_pending', [
                                                        'task' => $task,
                                                    ])
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bagian Penugasan -->
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="card shadow-sm border-0 rounded">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📌 Penugasan</h5>
                    </div>
                    <div class="card-body table-responsive p-2">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>Yang Bertugas</th>
                                    <th>Tugas</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Ditugaskan Oleh</th>
                                    <th>Progres</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($assignment->user->profile_image ? 'profile_images/' . $assignment->user->profile_image : 'asset/logo-itdept.png') }}"
                                                class="rounded-circle  border-2" width="40" height="40">
                                            {{ $assignment->user->name }}
                                        </td>
                                        <td><strong>{{ $assignment->title }}</strong></td>
                                        <td>{{ $assignment->assignment_date->format('d M Y') }}</td>
                                        <td>{{ $assignment->start_assignment_time->format('H:i') }} -
                                            {{ $assignment->end_assignment_time->format('H:i') }}</td>
                                        <td>{{ $assignment->assigner->name }}</td>
                                        <td>
                                            @if ($assignment->progres === 'Selesai')
                                                <span class="badge bg-success">✔ Selesai</span>
                                            @elseif ($assignment->progres === 'Pending')
                                                <span class="badge bg-warning text-dark">⚠️ Pending</span>
                                            @else
                                                <span class="badge bg-danger">❌ Belum Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->id === $assignment->user_id)
                                                @if ($assignment->progres !== 'Selesai')
                                                    <form method="POST"
                                                        action="{{ route('assignments.markAsComplete', $assignment) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" title="Selesai"
                                                            class="btn btn-sm btn-success">
                                                            ✅
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
