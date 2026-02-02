<div class="row mt-4">

    {{-- KOLOM KIRI : PENUGASAN HARI INI --}}
    <div class="col-md-6">
        <h6 class="fw-bold text-dark mb-3">
            <i class="bi bi-briefcase text-warning"></i> Penugasan Hari Ini
        </h6>

        @forelse ($data->todayAssignments as $assignment)
            <div class="card mb-3 border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $assignment->title }}</h6>
                            <small class="text-muted d-block">
                                Oleh: {{ $assignment->assigner->name }} <br>
                                {{ $assignment->start_assignment_time->format('H:i') }}
                                -
                                {{ $assignment->end_assignment_time->format('H:i') }}
                            </small>
                        </div>

                        <div class="d-flex flex-column align-items-end gap-2">
                            <span
                                class="badge
                                {{ $assignment->progres === 'Selesai'
                                    ? 'bg-success'
                                    : ($assignment->progres === 'Pending'
                                        ? 'bg-warning text-dark'
                                        : 'bg-secondary') }}">
                                {{ $assignment->progres }}
                            </span>

                            @if (Auth::user()->isAdmin() || Auth::user()->isKepalaUnit() || Auth::user()->isHRD())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('assignments.edit', $assignment->id) }}"
                                                class="dropdown-item">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('assignments.show', $assignment->id) }}"
                                                class="dropdown-item">
                                                <i class="bi bi-eye"></i> Show
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('assignments.destroy', $assignment->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form method="POST"
                                                action="{{ route('assignments.markAsComplete', $assignment) }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-check-circle"></i> Tandai Selesai
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-warning" data-bs-toggle="modal"
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
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <small class="text-muted">Tidak ada penugasan hari ini.</small>
        @endforelse
    </div>


    {{-- KOLOM KANAN : PENUGASAN MENDATANG --}}
    <div class="col-md-6">
        <h6 class="fw-bold text-dark mb-3">
            <i class="bi bi-calendar-event text-primary"></i> Penugasan Mendatang
        </h6>

        @forelse ($data->assignments
    ->where('assignment_date', '>=', now()->startOfDay()) // Pastikan hanya hari ini ke depan
    ->reject(fn ($a) => $data->todayAssignments->pluck('id')->contains($a->id))
            as $assignment)
            <div class="card mb-3 border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $assignment->title }}</h6>
                            <small class="text-muted d-block">
                                {{ $assignment->assignment_date->format('d M Y') }} <br>
                                {{ $assignment->start_assignment_time->format('H:i') }}
                                -
                                {{ $assignment->end_assignment_time->format('H:i') }}
                            </small>
                        </div>

                        <span class="badge bg-info">Mendatang</span>
                    </div>
                </div>
            </div>
        @empty
            <small class="text-muted">Tidak ada penugasan mendatang.</small>
        @endforelse
    </div>

</div>
