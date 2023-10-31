@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <a href="{{ route('assignments.create') }}" class="btn btn-primary">Buat Penugasan</a>
            </div>
            <div class="col-md-8">
                <table id="myTable" class="display">
                    <thead>
                        <tr>
                            <th>Yang bertugas</th>
                            <th>Tugas</th>
                            <th>Tanggal Tugas</th>
                            <th>Jam Tugas</th>
                            <th>Ditugaskan Oleh</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->user->name }}</td>
                                <td>{{ $assignment->title }}</td>
                                <td>{{ $assignment->assignment_date->format('d M Y') }}</td>
                                <td>{{ $assignment->start_assignment_time->format('H:i A') }} s/d
                                    {{ $assignment->end_assignment_time->format('H:i A') }}
                                </td>
                                <td>{{ $assignment->assigner->name }}</td>
                                <td>
                                    @if ($assignment->progres === 'Selesai')
                                        <span class="badge bg-success">{{ $assignment->progres }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $assignment->progres }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li> <!-- Tombol Edit -->
                                                <a href="{{ route('assignments.edit', $assignment->id) }}"
                                                    class="btn btn-warning text-white mb-2"><i
                                                        class="bi bi-pencil-square"></i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li> <!-- Tombol Show -->
                                                <a href="{{ route('assignments.show', $assignment->id) }}"
                                                    class="btn btn-info text-white mb-2"><i class="bi bi-eye"></i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li> <!-- Tombol Hapus -->
                                                <form action="{{ route('assignments.destroy', $assignment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"><i
                                                            class="bi bi-trash"></i> Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                {{-- <td>{{ $task->description }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
