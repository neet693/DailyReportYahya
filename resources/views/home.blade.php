@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <form action="{{ route('switchUnit') }}" method="POST" class="d-flex align-items-center">
                @csrf
                <label for="unit_id" class="fw-bold me-2 mb-0">Unit:</label>
                <select name="unit_id" onchange="this.form.submit()" class="form-select w-auto">
                    @if (auth()->user()->isAdmin())
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}"
                                {{ session('active_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    @else
                        @foreach (auth()->user()->units as $unit)
                            <option value="{{ $unit->id }}"
                                {{ session('active_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </form>

            <div class="d-flex flex-column align-items-end gap-2">
                <div class="d-flex flex-wrap gap-2 justify-content-end w-100">
                    @if (!auth()->user()->isAdmin())
                        @foreach (auth()->user()->units as $unit)
                            <a href="{{ route('unit.pegawai', $unit->id) }}" class="btn btn-primary">
                                List Pegawai - {{ $unit->name }}
                            </a>
                        @endforeach
                    @endif
                </div>

                <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center w-100" role="search">
                    <input type="text" name="search" class="form-control me-2" style="min-width: 300px;"
                        placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                </form>
            </div>
        </div>

        <div class="scroll-container">
            @include('toaster')

            @if ($usersWithTasks->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i> Belum ada data tugas untuk ditampilkan.
                </div>
            @else
                @foreach ($usersWithTasks as $data)
                    <div class="card-wrapper">
                        <div class="card-custom">
                            {{-- Header: Foto & Nama --}}
                            <div class="d-flex align-items-center p-4">
                                <img src="{{ $data->profile_image ? asset('profile_images/' . $data->profile_image) : asset('asset/default_profile.jpg') }}"
                                    class="rounded-circle profile-img" alt="Foto Profil">
                                <div class="ms-3">
                                    <h5 class="mb-0 text-dark">{{ $data->name }}</h5>
                                    <p class="text-dark small mb-0">
                                        {{ $data->jobdesk->title ?? 'Belum ada Job Desc' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Body: Tugas --}}
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="title-section mb-0">
                                        <i class="bi bi-clipboard-check me-2"></i> Tugas Hari Ini
                                    </h6>

                                    @if ($data->id == auth()->id())
                                        <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Tugas
                                        </a>
                                    @endif
                                </div>

                                <ul class="list-group list-group-flush">
                                    {{-- 1 Tugas Pertama --}}
                                    @forelse ($data->tasks->take(1) as $task)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center mb-1 text-dark">
                                                <strong>{{ $task->title }}</strong>
                                                <span
                                                    class="badge rounded-pill {{ $task->progres ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $task->progres ? 'Selesai' : 'Belum Selesai' }}
                                                </span>
                                            </div>
                                            <div class="text-dark small mb-2 fs-6">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $task->place }} |
                                                <i class="bi bi-clock me-1"></i>{{ $task->task_start_time->format('H:i') }}
                                                -
                                                {{ $task->task_end_time->format('H:i') }}
                                            </div>
                                            @if (auth()->user()->id == $data->id && $task->progres == 0)
                                                <div class="d-flex">
                                                    <button class="btn btn-sm btn-success me-2" data-bs-toggle="modal"
                                                        data-bs-target="#completeModal{{ $task->id }}">
                                                        <i class="bi bi-check-lg"></i> Selesai
                                                    </button>

                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#pendingModal{{ $task->id }}">
                                                        <i class="bi bi-hourglass-split text-white"></i> Pending
                                                    </button>
                                                </div>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="list-group-item text-dark">
                                            {{ $data->name }} Belum membuat Tugas hari ini.
                                        </li>
                                    @endforelse
                                </ul>

                                @if ($data->tasks->count() > 1)
                                    <button class="btn btn-light w-100 text-start position-relative toggle-tasks mt-2">
                                        <i class="bi bi-chevron-down me-1 toggle-icon"></i>
                                        Lihat tugas lainnya
                                        <span
                                            class="position-absolute top-50 end-0 translate-middle badge rounded-pill bg-danger me-3">
                                            {{ $data->tasks->count() - 1 }}
                                            <span class="visually-hidden">tugas lainnya</span>
                                        </span>
                                    </button>

                                    <ul class="more-tasks list-unstyled mt-2 d-none small text-dark">
                                        @foreach ($data->tasks->slice(1) as $task)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <strong>{{ $task->title }}</strong>
                                                    <span
                                                        class="badge rounded-pill {{ $task->progres ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $task->progres ? 'Selesai' : 'Belum Selesai' }}
                                                    </span>
                                                </div>
                                                <div class="text-dark small mb-2 fs-6">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $task->place }} |
                                                    <i
                                                        class="bi bi-clock me-1"></i>{{ $task->task_start_time->format('H:i') }}
                                                    -
                                                    {{ $task->task_end_time->format('H:i') }}
                                                </div>
                                                @if (auth()->user()->id == $data->id && $task->progres == 0)
                                                    <div class="d-flex">
                                                        <button class="btn btn-sm btn-success me-2" data-bs-toggle="modal"
                                                            data-bs-target="#completeModal{{ $task->id }}">
                                                            <i class="bi bi-check-lg"></i> Selesai
                                                        </button>
                                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                            data-bs-target="#pendingModal{{ $task->id }}">
                                                            <i class="bi bi-hourglass-split text-white"></i> Pending
                                                        </button>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Modal Task --}}
                    @foreach ($data->tasks as $task)
                        @include('components.task_modal_complete', ['task' => $task])
                        @include('components.task_modal_pending', ['task' => $task])
                    @endforeach
                @endforeach
            @endif
        </div>

        <div class="container mt-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📌 Penugasan</h5>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        @if ($assignments->isEmpty())
                            <div class="alert alert-warning m-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Belum ada penugasan.
                            </div>
                        @else
                            <table id="myTable" class="table table-hover align-middle mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Petugas</th>
                                        <th>Tugas</th>
                                        <th>Progres</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($assignments as $assignment)
                                        <tr>
                                            <td class="text-nowrap">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($assignment->user->profile_image ? 'profile_images/' . $assignment->user->profile_image : 'asset/logo-itdept.png') }}"
                                                        class="rounded-circle me-2" width="40" height="40"
                                                        style="object-fit: cover;">
                                                    <div class="fw-semibold">{{ $assignment->user->name }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $assignment->title }}</div>
                                                <div class="small text-muted">
                                                    Oleh: {{ $assignment->assigner->name }} <br>
                                                    {{ $assignment->assignment_date->format('d M Y') }},
                                                    {{ $assignment->start_assignment_time->format('H:i') }} -
                                                    {{ $assignment->end_assignment_time->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($assignment->progres === 'Selesai')
                                                    <span class="badge bg-success">✔ Selesai</span>
                                                @elseif ($assignment->progres === 'Pending')
                                                    <span class="badge bg-warning text-dark">⚠ Pending</span>
                                                @else
                                                    <span class="badge bg-dark text-white">❌ Belum</span>
                                                @endif
                                            </td>
                                            <td class="text-end text-nowrap">
                                                @if (auth()->user()->id == $assignment->user_id && $assignment->progres !== 'Selesai')
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            Aksi
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('assignments.markAsComplete', $assignment) }}">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="dropdown-item text-success">
                                                                        <i class="bi bi-check-circle"></i> Tandai Selesai
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-warning"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#penugasanPendingModal{{ $assignment->id }}">
                                                                    <i class="bi bi-clock"></i> Tandai Pending
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    @include('components.modal_pending', [
                                                        'assignment' => $assignment,
                                                    ])
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data penugasan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('chats.modal')

    </div>
@endsection
