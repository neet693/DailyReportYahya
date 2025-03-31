@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('toaster')
            @foreach ($usersWithTasks as $data)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-2 rounded">
                        <div class="d-flex align-items-center p-3">
                            <img src="{{ $data->profile_image ? asset('profile_images/' . $data->profile_image) : asset('asset/default_profile.jpg') }}"
                                class="rounded-circle  border-2 border-primary" style="width:70px; height:70px;"
                                alt="Foto Profil">
                            <div class="ms-3">
                                <h5 class="card-title mb-0">{{ $data->name }}</h5>
                                <p class="card-subtitle text-muted small">
                                    {{ $data->jobdesk ? $data->jobdesk->title : 'Belum ada Job Desc' }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="text-muted">Tasks for Today</h6>
                            <ul class="list-group list-group-flush">
                                @foreach ($data->tasks as $task)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $task->title }}</strong> <br>
                                            <small class="text-muted">{{ $task->place }} |
                                                {{ $task->task_start_time->format('H:i') }} -
                                                {{ $task->task_end_time->format('H:i') }}</small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @if ($task->progres == 1)
                                                <span class="badge bg-success me-2">
                                                    <i class="bi bi-check-circle"></i> Selesai
                                                </span>
                                            @elseif ($task->progres == 0)
                                                <span class="badge bg-danger me-2">
                                                    <i class="bi bi-x-circle"></i> Belum Selesai
                                                </span>
                                            @endif

                                            @if (auth()->user()->id === $data->id)
                                                @if ($task->progres === 0)
                                                    <a href="{{ route('tasks.markAsComplete', $task) }}" title="Selesai"
                                                        class="btn btn-sm btn-success me-1" data-bs-toggle="modal"
                                                        data-bs-target="#completeModal{{ $task->id }}">
                                                        <i class="bi bi-check-lg"></i>
                                                    </a>
                                                    @include('components.task_modal_complete', [
                                                        'task' => $task,
                                                    ])

                                                    <a href="{{ route('tasks.markAsPending', $task) }}" title="Pending"
                                                        class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#pendingModal{{ $task->id }}">
                                                        <i class="bi bi-hourglass-split text-white"></i>
                                                    </a>
                                                    @include('components.task_modal_pending', [
                                                        'task' => $task,
                                                    ])
                                                @endif
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-3">
                                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala')
                                    <a href="{{ route('assignments.create') }}"
                                        class="btn btn-primary w-100 mb-2">Tugaskan</a>
                                    <a href="{{ route('tasks.create') }}" class="btn btn-outline-secondary w-100">Tambah
                                        Task</a>
                                @elseif (auth()->user()->id === $data->id)
                                    <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary w-100">Buat Task
                                        Anda</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

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
                                            class="rounded-circle border border-2" width="40" height="40">
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
                                                    <button type="submit" title="Selesai" class="btn btn-sm btn-success">
                                                        ✅
                                                    </button>
                                                </form>
                                                <button type="button" title="Pending"
                                                    class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                                    data-bs-target="#penugasanPendingModal{{ $assignment->id }}">
                                                    ⏳
                                                </button>
                                                @include('components.modal_pending', [
                                                    'assignment' => $assignment,
                                                ])
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
@endsection
