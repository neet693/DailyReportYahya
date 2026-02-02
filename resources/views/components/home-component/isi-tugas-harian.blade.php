{{-- Tugas Harian --}}
@if ($data->tasks->isNotEmpty())
    <h6 class="fw-bold text-dark mb-3">
        <i class="bi bi-list-task text-primary"></i> Tugas Harian
    </h6>

    @foreach ($data->tasks as $task)
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-semibold mb-1">
                            {{ $task->title }}
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
                    <span class="badge rounded-pill {{ $task->progres ? 'bg-success' : 'bg-danger' }}">
                        {{ $task->progres ? 'Selesai' : 'Belum' }}
                    </span>
                </div>

                @if ($task->progres == 0 && $data->id == auth()->id())
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                            data-bs-target="#completeModal{{ $task->id }}">
                            <i class="bi bi-check"></i> Selesai
                        </button>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#pendingModal{{ $task->id }}">
                            <i class="bi bi-hourglass-split"></i> Tunda
                        </button>
                    </div>

                    {{-- Modal kecil bawaan --}}
                    @include('components.task_modal_complete', ['task' => $task])
                    @include('components.task_modal_pending', ['task' => $task])
                @endif
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-light text-muted small">
        <i class="bi bi-info-circle"></i> Belum ada tugas harian.
    </div>
@endif
