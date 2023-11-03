@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('toaster')
            @foreach ($usersWithTasks as $data)
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 18rem;">
                        @if ($data->profile_image)
                            <img src="{{ asset('profile_images/' . $data->profile_image) }}"
                                class="card-img-top rounded-circle" style="width: 30%" alt="Foto Profil">
                        @else
                            <img src="{{ asset('asset/logo-itdept.png') }}" class="card-img-top rounded-circle"
                                style="width: 30%" alt="Foto Profil">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $data->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Tasks for Today</h6>
                            <ul>
                                @foreach ($data->tasks as $task)
                                    @if ($task->task_date->isToday())
                                        <!-- Filter tasks for today -->
                                        <li>{{ $task->title }} | {{ $task->place }} |
                                            {{ $task->task_start_time->format('H:i') }} s/d
                                            {{ $task->task_end_time->format('H:i') }}
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
                                        @if ($assignment->user->profile_image)
                                            <img src="{{ asset('profile_images/' . $assignment->user->profile_image) }}"
                                                alt="Profil Gambar" class="rounded-circle" style="width: 50px">
                                        @else
                                            <img src="{{ asset('asset/logo-itdept.png') }}" alt="Logo IT Dept"
                                                class="rounded-circle" style="width: 50px">
                                        @endif
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
                                            @if ($assignment->progres !== 'Selesai')
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
