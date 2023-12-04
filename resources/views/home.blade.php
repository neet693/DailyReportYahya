@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('toaster')
            @foreach ($usersWithTasks as $data)
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 18rem;">

                        <div class="d-flex align-items-center">
                            <img src="{{ $data->profile_image ? asset('profile_images/' . $data->profile_image) : asset('asset/default_profile.jpg') }}"
                                class="card-img-top rounded-circle" style="width:70px; height:70px;" alt="Foto Profil">
                            <div class="p-2">
                                <h5 class="card-title">{{ $data->name }}</h5>
                                <p class="card-subtitle text-muted">
                                    {{ $data->jobdesk ? $data->jobdesk->title : 'Belum ada Job Desc' }}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Tasks for Today</h6>
                            <ul>
                                @foreach ($data->tasks as $task)
                                    {{-- @if ($task->task_date->isToday())
                                        <!-- Filter tasks for today -->
                                    @endif --}}
                                    <li>
                                        {{ $task->title }} | {{ $task->place }} |
                                        {{ $task->task_start_time->format('H:i') }} s/d
                                        {{ $task->task_end_time->format('H:i') }} |
                                        @if ($task->progres == 1)
                                            <span style="color:  green">✔</span>
                                        @elseif ($task->progres == 0)
                                            <span style="color:  red">✖</span>
                                        @endif |
                                        {{-- Bagian ini di ganti sampai mark as pending --}}
                                        @if (auth()->user()->id === $data->id)
                                            @if ($task->progres === 0)
                                                <a href="{{ route('tasks.markAsComplete', $task) }}" title="Selesai"
                                                    style="text-decoration: none; color:green;" data-bs-toggle="modal"
                                                    data-bs-target="#completeModal{{ $task->id }}">✔</a>
                                                @include('components.task_modal_complete', [
                                                    'task' => $task,
                                                ])
                                                <a href="{{ route('tasks.markAsPending', $task) }}" title="Pending"
                                                    style="text-decoration: none; color:red;" data-bs-toggle="modal"
                                                    data-bs-target="#pendingModal{{ $task->id }}">✖</a>
                                                @include('components.task_modal_pending', [
                                                    'task' => $task,
                                                ])
                                            @endif
                                        @endif
                                    </li>
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
                    {{-- <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                    </a> --}}
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
                                    {{-- <td hidden>{{ $assignment->user->name }}</td> --}}
                                    <td>
                                        @if ($assignment->user->profile_image)
                                            <img src="{{ asset('profile_images/' . $assignment->user->profile_image) }}"
                                                alt="Profil Gambar" title="{{ $assignment->user->name }}"
                                                class="rounded-circle" style="width: 50px">
                                        @else
                                            <img src="{{ asset('asset/logo-itdept.png') }}" alt="Logo IT Dept"
                                                class="rounded-circle" style="width: 50px">
                                        @endif
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
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#penugasanPendingModal{{ $assignment->id }}">
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
