@extends('layouts.app')
@section('content')
    <section class="vh-100" style="background-color: #3da2c3;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-8 col-xl-6">
                    <div class="card rounded-3">
                        <div class="card-body p-4">

                            {{-- <p class="mb-2"><span class="h2 me-2">{{ $task->title }}</span> <span
                                    class="badge bg-danger">checklist</span></p> --}}
                            <p class="mb-2"><span class="h2 me-2">{{ $task->title }}</p>
                            <p class="text-muted pb-2">{{ $task->task_date->format('j F Y') }} •
                                {{ $task->task_start_time->format('H:i') }} - {{ $task->task_end_time->format('H:i') }} •
                                {{ $task->place }}</p>

                            <ul class="list-group rounded-0">
                                <li class="list-group-item border-0 d-flex align-items-center ps-0">
                                    <input class="form-check-input me-3" type="checkbox" value="" aria-label="..." />
                                    {{-- <s>Task list and assignments</s> --}}
                                    {!! $task->description !!}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
