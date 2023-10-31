@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">Buat Task</a>
                <a href="{{ route('report.generateWeeklyReport') }}" class="btn btn-warning text-white">Cetak Laporan
                    Mingguan</a>
                <a href="{{ route('report.generateMonthlyReport') }}" class="btn btn-danger">Cetak Laporan Bulanan</a>

            </div>
            <div class="col-md-8">
                {{-- {{ __('Halaman Task Anda') }} --}}
                <table id="myTable" class="display">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal Task</th>
                            <th>Jam</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            @if ($task->task_user_id == Auth::id())
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->task_date->format('d M Y') }}</td>
                                    <td>{{ $task->task_start_time->format('H:i A') }} s/d
                                        {{ $task->task_end_time->format('H:i A') }}</td>
                                    <td>{!! $task->description !!}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li> <!-- Tombol Edit -->
                                                    <a href="{{ route('tasks.edit', $task->id) }}"
                                                        class="btn btn-warning text-white mb-2"><i
                                                            class="bi bi-pencil-square"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                                <li> <!-- Tombol Show -->
                                                    <a href="{{ route('tasks.show', $task->id) }}"
                                                        class="btn btn-info text-white mb-2"><i class="bi bi-eye"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                                <li> <!-- Tombol Hapus -->
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"><i
                                                                class="bi bi-trash"></i> Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
