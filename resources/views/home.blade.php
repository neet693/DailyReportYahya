@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('toaster')
            @foreach ($usersWithTasks as $data)
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 18rem;">
                        <img src="https://adminlte.io/themes/v3/dist/img/default-150x150.png"
                            class="card-img-top rounded-circle" style="width: 30%" alt="Foto Profil">
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
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Buat Task Anda</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Penugasan</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Yang Bertugas</th>
                                <th>Tugas</th>
                                <th>Tanggal di tugaskan</th>
                                <th>Ditugaskan oleh</th>
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
                                    <td>
                                        {{ $assignment->assigner->name }}
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
