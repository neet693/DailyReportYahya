@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('toaster')
            @foreach ($usersWithTasks as $data)
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset('asset/logo-itdept.png') }}" class="card-img-top rounded-circle" style="width: 30%"
                            alt="Foto Profil">
                        <div class="card-body">
                            <h5 class="card-title">{{ $data->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Tasks for Today</h6>
                            <ul>
                                @foreach ($data->tasks as $task)
                                    @if ($task->task_date->isToday())
                                        <!-- Filter tasks for today -->
                                        <li>{{ $task->title }} | {{ $task->place }} |
                                            {{ $task->task_start_time->format('h:i') }} s/d
                                            {{ $task->task_end_time->format('h:i') }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala')
                                <a href="{{ route('assignments.create') }}" class="btn btn-primary">Tugaskan</a>
                            @elseif (auth()->user()->id === $data->id)
                                <a href="{{ route('tasks.create') }}" class="btn btn-primary">Buat Task Anda</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <h3 class="card-title">Penugasan</h3>
                <div class="card-tools">
                    <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Yang Bertugas</th>
                                <th>Tugas</th>
                                <th>Tanggal di tugaskan</th>
                                <th>Jam penugasan</th>
                                <th>Ditugaskan oleh</th>
                                <th>Progres</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $assignment)
                                <tr>
                                    <td>
                                        <img src="https://adminlte.io/themes/v3/dist/img/default-150x150.png"
                                            alt="Product 1" class="rounded-circle" style="width: 10%">
                                        {{ $assignment->user->name }}
                                    </td>
                                    <td>{{ $assignment->title }}</td>
                                    <td>{{ $assignment->assignment_date->format('d M Y') }}</td>
                                    <td>{{ $assignment->start_assignment_time->format('H:i') }} s/d
                                        {{ $assignment->end_assignment_time->format('H:i') }}
                                    </td>
                                    <td>{{ $assignment->assigner->name }}</td>
                                    <td>
                                        @if ($assignment->progres === 'Selesai')
                                            <span class="badge bg-success">{{ $assignment->progres }}</span>
                                        @elseif ($assignment->progres === 'Pending')
                                            <span class="badge bg-warning">{{ $assignment->progres }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $assignment->progres }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (auth()->user()->id === $assignment->user_id)
                                            <form method="POST"
                                                action="{{ route('assignments.markAsComplete', $assignment) }}">
                                                @csrf
                                                <button type="submit" title="Selesai" class="btn btn-success mb-2"><i
                                                        class="bi bi-check-circle-fill bg-green"></i>
                                                </button>
                                            </form>

                                            <!-- Button trigger modal -->
                                            <button type="button" title="Pending" class="btn btn-warning text-white"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                <i class="bi bi-dash-circle bg-warning"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Laporkan
                                                                Kendala</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('assignments.report-kendala', $assignment) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="kendala">Kendala:</label>
                                                                        <textarea id="kendala" name="kendala" class="form-control @error('kendala') is-invalid @enderror" rows="4"
                                                                            autofocus></textarea>

                                                                        @error('kendala')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                        changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
