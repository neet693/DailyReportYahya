@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3 px-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 mb-3">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Buat Task
                            </a>
                        </div>

                        <form action="{{ route('report.filtered') }}" method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-5">
                                    <label for="month" class="form-label mb-1">Pilih Bulan</label>
                                    <select name="month" id="month" class="form-select" required>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ now()->month == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-12 col-md-5">
                                    <label for="week" class="form-label mb-1">Pilih Minggu (Opsional)</label>
                                    <select name="week" id="week" class="form-select">
                                        <option value="">Semua Minggu (Bulanan)</option>
                                        <option value="1">Minggu 1</option>
                                        <option value="2">Minggu 2</option>
                                        <option value="3">Minggu 3</option>
                                        <option value="4">Minggu 4</option>
                                        <option value="5">Minggu 5</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-file-earmark-text"></i> Cetak
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-10">
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Tanggal Task</th>
                                <th>Jam</th>
                                <th>Deskripsi</th>
                                <th>Progres</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->task_date->format('d M Y') }}</td>
                                    <td>{{ $task->task_start_time->format('H:i') }} s/d
                                        {{ $task->task_end_time->format('H:i') }}</td>
                                    <td>{!! $task->taskExcerpt() !!}</td>
                                    <td>
                                        @if ($task->progres == 1)
                                            <span class="text-success">✔</span>
                                        @elseif ($task->progres == 0)
                                            <span class="text-danger">✖</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </a>
                                            <ul class="dropdown-menu p-2">
                                                {{-- Edit --}}
                                                <li>
                                                    <a href="{{ route('tasks.edit', $task->id) }}"
                                                        class="btn btn-warning w-100 text-start rounded-pill mb-2 d-flex align-items-center gap-2 text-white">
                                                        <i class="bi bi-pencil-square"></i> Edit Task
                                                    </a>
                                                </li>

                                                {{-- Show --}}
                                                <li>
                                                    <a href="{{ route('tasks.show', $task->id) }}"
                                                        class="btn btn-info w-100 text-start rounded-pill mb-2 d-flex align-items-center gap-2 text-white">
                                                        <i class="bi bi-eye"></i> Lihat Detail
                                                    </a>
                                                </li>

                                                @if ($task->progres == 0)
                                                    {{-- Complete --}}
                                                    <li>
                                                        <button
                                                            class="btn btn-success w-100 text-start rounded-pill mb-2 d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#completeModal{{ $task->id }}">
                                                            <i class="bi bi-check-circle"></i> Selesaikan
                                                        </button>
                                                    </li>

                                                    {{-- Pending --}}
                                                    <li>
                                                        <button
                                                            class="btn btn-secondary w-100 text-start rounded-pill d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#pendingModal{{ $task->id }}">
                                                            <i class="bi bi-hourglass-split"></i> Tunda
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>

                                        </div>
                                    </td>
                                </tr>
                                @include('components.task_modal_complete', [
                                    'task' => $task,
                                ])
                                @include('components.task_modal_pending', [
                                    'task' => $task,
                                ])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
