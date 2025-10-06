@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container">
            @include('toaster')
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold mb-0">Tugas Hari Ini</h5>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center" role="search">
                        <input type="text" name="search" class="form-control me-2" style="min-width: 250px;"
                            placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                    </form>

                    @if (Auth::user()->isKepalaUnit())
                        <a href="{{ route('unit.pegawai', auth()->user()->employmentDetail?->unit?->id) }}"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-users me-1"></i> Lihat Anggota Unit
                        </a>
                    @endif
                </div>
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
                                {{-- Header: Profil & unit --}}
                                <div class="hstack gap-2">
                                    <div class="p-2">
                                        <img src="{{ $data->profile_image ? asset('profile_images/' . $data->profile_image) : asset('asset/default_profile.jpg') }}"
                                            class="rounded-circle" width="100" height="100" alt="Foto">
                                    </div>
                                    <div class="p-2">
                                        <a href="{{ route('employment-detail.show', $data->employmentDetail) }}"
                                            class="text-decoration-none text-dark">
                                            <div class="fw-semibold small mb-0">{{ $data->name }}</div>
                                        </a>
                                        <small class="text-muted d-block mb-1">{{ $data->jobdesk->title ?? '-' }}
                                        </small>
                                        {{-- Dropdown ganti unit jika user sedang login --}}
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

                                {{-- Tombol tambah tugas & jadwal tetap --}}
                                <div class="hstack gap-2">
                                    @if ($data->id == auth()->id())
                                        <a href="{{ route('tasks.create') }}"
                                            class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="bi bi-plus-circle"></i> Tugas Harian
                                        </a>
                                        <a href="{{ route('fixed-schedule.create') }}"
                                            class="btn btn-outline-success btn-sm flex-fill">
                                            <i class="bi bi-calendar-plus"></i> Jadwal Tetap
                                        </a>
                                    @elseif (Auth::user()->isKepalaUnit() || Auth::user()->isAdmin())
                                        <a href="{{ route('fixed-schedule.create', ['user_id' => $data->id]) }}"
                                            class="btn btn-outline-success btn-sm flex-fill">
                                            <i class="bi bi-calendar-plus"></i> Jadwal Tetap
                                        </a>
                                    @endif
                                </div>


                                {{-- Konten tugas --}}
                                @if ($data->tasks->isNotEmpty() || $data->fixedSchedules->isNotEmpty())
                                    {{-- Tombol Pemicu --}}
                                    <div class="hstack gap-3">
                                        <button class="btn btn-sm btn-outline-dark flex-fill fw-semibold mt-2"
                                            type="button"
                                            onclick="document.getElementById('taskPopup{{ $data->id }}').style.display='flex'">
                                            📋 Lihat Semua Tugas
                                            ({{ $data->tasks->count() + $data->fixedSchedules->count() }})
                                        </button>
                                    </div>
                                    {{-- Popup Card --}}
                                    <div id="taskPopup{{ $data->id }}"
                                        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;background:rgba(0,0,0,0.5); z-index:1050; justify-content:center; align-items:center;">

                                        <div
                                            style="background:#fff; width:90%; max-width:800px; border-radius:1rem;box-shadow:0 5px 25px rgba(0,0,0,0.2); display:flex; flex-direction:column;max-height:90vh;">

                                            {{-- HEADER --}}
                                            <div
                                                style="background:#0d6efd; color:#fff; border-top-left-radius:1rem; border-top-right-radius:1rem;padding:1rem; display:flex; justify-content:space-between; align-items:center;">
                                                <h5 style="margin:0; font-weight:bold;">📋 Daftar Tugas -
                                                    {{ $data->name }}</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    onclick="document.getElementById('taskPopup{{ $data->id }}').style.display='none'"></button>
                                            </div>

                                            {{-- BODY --}}
                                            <div style="padding:1.5rem; overflow-y:auto; flex:1;">
                                                {{-- Tugas Harian --}}
                                                @if ($data->tasks->isNotEmpty())
                                                    <h6 class="fw-bold text-dark mb-3">
                                                        <i class="bi bi-list-task text-primary"></i> Tugas Harian
                                                    </h6>

                                                    @foreach ($data->tasks as $task)
                                                        <div class="card mb-3 border-0 shadow-sm rounded-3">
                                                            <div class="card-body p-3">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h6 class="fw-semibold mb-1">{{ $task->title }}
                                                                        </h6>
                                                                        <i class="bi bi-geo-alt"></i>
                                                                        {{ $task->place ?? '-' }} |
                                                                        {{ $task->task_start_time->format('H:i') }} -
                                                                        {{ $task->task_end_time->format('H:i') }}
                                                                        </small>
                                                                        @if ($task->description)
                                                                            {!! $task->description !!}
                                                                        @endif
                                                                        @if ($task->progres == 0 && !empty($task->pending_note))
                                                                            <small class="text-warning d-block mt-1">
                                                                                <i class="bi bi-exclamation-triangle"></i>
                                                                                <strong>Pending:</strong>
                                                                                {{ $task->pending_note }}
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                    <span
                                                                        class="badge rounded-pill {{ $task->progres ? 'bg-success' : 'bg-danger' }}">
                                                                        {{ $task->progres ? 'Selesai' : 'Belum' }}
                                                                    </span>
                                                                </div>

                                                                @if ($task->progres == 0 && $data->id == auth()->id())
                                                                    <div class="d-flex justify-content-end gap-2 mt-3">
                                                                        <button class="btn btn-sm btn-success"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#completeModal{{ $task->id }}">
                                                                            <i class="bi bi-check"></i> Selesai
                                                                        </button>
                                                                        <button class="btn btn-sm btn-warning"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#pendingModal{{ $task->id }}">
                                                                            <i class="bi bi-hourglass-split"></i> Tunda
                                                                        </button>
                                                                    </div>

                                                                    {{-- Modal kecil bawaan --}}
                                                                    @include(
                                                                        'components.task_modal_complete',
                                                                        ['task' => $task]
                                                                    )
                                                                    @include(
                                                                        'components.task_modal_pending',
                                                                        ['task' => $task]
                                                                    )
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-light text-muted small">
                                                        <i class="bi bi-info-circle"></i> Belum ada tugas harian.
                                                    </div>
                                                @endif

                                                {{-- Jadwal Tetap --}}
                                                @if ($data->fixedSchedules->isNotEmpty())
                                                    <h6 class="fw-bold text-dark mt-4 mb-3">
                                                        <i class="bi bi-calendar-event text-success"></i> Jadwal Tetap
                                                    </h6>

                                                    @php
                                                        $daysIndo = [
                                                            'monday' => 'Senin',
                                                            'tuesday' => 'Selasa',
                                                            'wednesday' => 'Rabu',
                                                            'thursday' => 'Kamis',
                                                            'friday' => 'Jumat',
                                                            'saturday' => 'Sabtu',
                                                            'sunday' => 'Minggu',
                                                        ];
                                                    @endphp

                                                    <div class="d-flex flex-wrap gap-3"
                                                        style="max-height: 350px; overflow-y: auto;">
                                                        @foreach ($data->fixedSchedules as $fixed)
                                                            <div class="card border-0 shadow-sm rounded-3"
                                                                style="flex: 0 0 48%;">
                                                                <div class="card-body p-3">
                                                                    {{-- Tombol Edit hanya muncul untuk kepala unit atau pemilik jadwal --}}
                                                                    @if (Auth::user()->isKepalaUnit() || Auth::id() === $fixed->user_id)
                                                                        <a href="{{ route('fixed-schedule.edit', $fixed->id) }}"
                                                                            class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-2"
                                                                            title="Edit Jadwal">
                                                                            <i class="bi bi-pencil-square"></i>
                                                                        </a>
                                                                    @endif
                                                                    <h6 class="fw-semibold mb-1">
                                                                        {{ $fixed->subject ?? ucfirst($fixed->type) }}
                                                                    </h6>


                                                                    {{-- Hari + Jam --}}
                                                                    <small class="text-muted d-block">
                                                                        <i class="bi bi-calendar-event"></i>
                                                                        {{ $daysIndo[strtolower($fixed->day_of_week)] ?? ucfirst($fixed->day_of_week) }}
                                                                        |
                                                                        {{ \Carbon\Carbon::parse($fixed->start_time)->format('H:i') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($fixed->end_time)->format('H:i') }}
                                                                    </small>

                                                                    {{-- Lokasi --}}
                                                                    <small class="text-muted d-block">
                                                                        <i class="bi bi-geo-alt"></i>
                                                                        {{ $fixed->classroom ?? '-' }}
                                                                    </small>

                                                                    {{-- Deskripsi --}}
                                                                    @if ($fixed->description)
                                                                        <small class="text-muted d-block">
                                                                            <i class="bi bi-info-circle"></i>
                                                                            {{ \Illuminate\Support\Str::limit($fixed->description, 60) }}
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                            </div>

                                            {{-- FOOTER --}}
                                            <div style="padding:1rem; border-top:1px solid #eee; text-align:right;">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                    onclick="document.getElementById('taskPopup{{ $data->id }}').style.display='none'">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
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
