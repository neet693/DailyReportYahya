@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('toaster')
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

    {{-- Script Toast --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastElement = document.querySelector('.toast');
            var toast = new bootstrap.Toast(toastElement);

            // Fungsi untuk menampilkan pengumuman
            function showAnnouncement(title, message) {
                var titleElement = toastElement.querySelector('.toast-header strong');
                var bodyElement = toastElement.querySelector('.toast-body');

                titleElement.textContent = title;
                bodyElement.textContent = message;

                toast.show();
            }

            // Cek apakah ada data pengumuman
            @if (isset($announcements))
                showAnnouncement('{{ $announcements->title }}', '{{ $announcements->message }}');
            @else
                // Jika tidak ada pengumuman, tampilkan teks alternatif
                showAnnouncement('Pengumuman', 'Tidak ada pengumuman hari ini');
            @endif
        });
    </script>
@endsection
