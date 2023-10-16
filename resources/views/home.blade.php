@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($usersWithTasks as $data)
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
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
@endsection
