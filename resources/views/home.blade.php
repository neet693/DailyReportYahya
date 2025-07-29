@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container">
            @include('toaster')
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold mb-0">Tugas Hari Ini</h5>
                <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center" role="search">
                    <input type="text" name="search" class="form-control me-2" style="min-width: 250px;"
                        placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                </form>
            </div>


            @if ($usersWithTasks->isEmpty())
                <div class="alert alert-info py-2 px-3 small">
                    <i class="bi bi-info-circle-fill me-2"></i> Belum ada data tugas.
                </div>
            @else
                <div class="row g-3">
                    @foreach ($usersWithTasks as $data)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="p-3 rounded shadow-sm" style="background-color: #f4f8e1;">
                                {{-- Header: Profil & tombol --}}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $data->profile_image ? asset('profile_images/' . $data->profile_image) : asset('asset/default_profile.jpg') }}"
                                            class="rounded-circle" width="36" height="36" alt="Foto">
                                        <div class="ms-2">
                                            <a href="{{ route('employment-detail.show', $data->employmentDetail) }}"
                                                class="text-decoration-none text-dark">
                                                <div class="fw-semibold small mb-0">{{ $data->name }}</div>
                                            </a>
                                            <small
                                                class="text-muted d-block mb-1">{{ $data->jobdesk->title ?? '-' }}</small>

                                            @if ($data->id === auth()->id())
                                                <form action="{{ route('switchUnit') }}" method="POST">
                                                    @csrf
                                                    <select name="unit_id" onchange="this.form.submit()"
                                                        class="form-select form-select-sm border-0"
                                                        style="background-color: rgba(244, 248, 225, 0.9); font-size: 0.75rem; width: 140px; padding: 2px 6px; border-radius: 0.3rem; box-shadow: none;">
                                                        @foreach (auth()->user()->units as $unit)
                                                            <option value="{{ $unit->id }}"
                                                                {{ session('active_unit_id') == $unit->id ? 'selected' : '' }}>
                                                                {{ $unit->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @endif
                                        </div>
                                    </div>


                                    {{-- Tombol tambah tugas untuk user yang sedang login --}}
                                    @if ($data->id == auth()->id())
                                        <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-plus-circle me-1"></i>
                                        </a>
                                    @endif
                                </div>

                                {{-- Konten tugas --}}
                                @if ($data->tasks->isNotEmpty())
                                    {{-- Tombol collapse --}}
                                    <button class="btn btn-sm btn-outline-dark w-100 fw-semibold mt-2" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTugas{{ $data->id }}"
                                        aria-expanded="false" aria-controls="collapseTugas{{ $data->id }}">
                                        Lihat Semua Tugas ({{ $data->tasks->count() }})
                                    </button>

                                    {{-- Isi tugas --}}
                                    <div class="collapse mt-2" id="collapseTugas{{ $data->id }}">
                                        @foreach ($data->tasks as $task)
                                            <div class="mb-3 small">
                                                <div class="fw-semibold">{{ $task->title }}</div>
                                                <div class="text-muted">
                                                    <i class="bi bi-geo-alt"></i> {{ $task->place }}<br>
                                                    <i class="bi bi-clock"></i> {{ $task->task_start_time->format('H:i') }}
                                                    -
                                                    {{ $task->task_end_time->format('H:i') }}
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span
                                                        class="badge rounded-pill {{ $task->progres ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $task->progres ? 'Selesai' : 'Belum' }}
                                                    </span>
                                                    @if ($data->id == auth()->id() && $task->progres == 0)
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                                data-bs-target="#completeModal{{ $task->id }}">
                                                                <i class="bi bi-check"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                                data-bs-target="#pendingModal{{ $task->id }}">
                                                                <i class="bi bi-hourglass-split"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                                @if (!$loop->last)
                                                    <hr class="my-2">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <small class="text-muted">Belum membuat tugas hari ini.</small>
                                @endif
                            </div>
                        </div>

                        {{-- Modal untuk tiap task --}}
                        @foreach ($data->tasks as $task)
                            @include('components.task_modal_complete', ['task' => $task])
                            @include('components.task_modal_pending', ['task' => $task])
                        @endforeach
                    @endforeach

                </div>
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
