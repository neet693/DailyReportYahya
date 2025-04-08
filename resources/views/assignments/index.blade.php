@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            {{-- Tombol Buat Penugasan --}}
            <div class="col-md-8 mb-3">
                @if ($currentUser->isAdmin() || $currentUser->isKepalaUnit())
                    <a href="{{ route('assignments.create') }}" class="btn btn-primary">Buat Penugasan</a>
                @endif
            </div>

            {{-- Tabel Penugasan --}}
            <div class="col-md-8">
                <table id="myTable" class="display table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Yang Bertugas</th>
                            <th>Judul Tugas</th>
                            <th>Tanggal</th>
                            <th>Jam Tugas</th>
                            <th>Ditugaskan Oleh</th>
                            <th>Progres</th>
                            <th>Kendala</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->user->name ?? '-' }}</td>
                                <td>{{ $assignment->title }}</td>
                                <td>{{ $assignment->assignment_date->format('d M Y') }}</td>
                                <td>{{ $assignment->start_assignment_time->format('H:i A') }} s/d
                                    {{ $assignment->end_assignment_time->format('H:i A') }}
                                </td>
                                <td>{{ $assignment->assigner->name ?? '-' }}</td>

                                {{-- Badge Progres --}}
                                <td>
                                    @php
                                        $status = $assignment->progres;
                                        $badgeClass = match ($status) {
                                            'Selesai' => 'success',
                                            'Pending' => 'warning',
                                            default => 'danger',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}"
                                        title="{{ $status }}">{{ $status }}</span>
                                </td>

                                {{-- Kendala --}}
                                <td>
                                    <span class="{{ $assignment->kendala ? 'text-danger' : '' }}">
                                        {{ $assignment->kendala ?? 'Tidak ada kendala' }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td>
                                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala')
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('assignments.edit', $assignment->id) }}"
                                                        class="dropdown-item">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('assignments.show', $assignment->id) }}"
                                                        class="dropdown-item">
                                                        <i class="bi bi-eye"></i> Show
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('assignments.destroy', $assignment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
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
@endsection
