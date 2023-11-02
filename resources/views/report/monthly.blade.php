<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link href="{{ asset('bootstrap-5.3.2-dist/css/bootstrap.min.css') }}">
<h1>Laporan Bulanan</h1>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Judul Task</th>
            <th>Tempat</th>
            <th>Tanggal Task</th>
            <th>Jam</th>
            <th>Deskripsi</th>
            <!-- Tambahkan kolom-kolom lain sesuai data yang Anda ingin tampilkan -->
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            @if ($assignment->user->id === auth()->user()->id)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->place }}</td>
                    <td>{{ $item->task_date->format('d M Y') }}</td>
                    <td>{{ $item->task_start_time->format('H:i A') }} s/d {{ $item->task_end_time->format('H:i A') }}
                    </td>
                    <td>{!! $item->description !!}</td>
                    <!-- Tambahkan sel-sel lain sesuai data yang Anda ingin tampilkan -->
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<h1>Laporan Penugasan Bulanan Bulanan</h1>
<table id="myTable" class="display">
    <thead>
        <tr>
            <th>Yang bertugas</th>
            <th>Tugas</th>
            <th>Tanggal Tugas</th>
            <th>Jam Tugas</th>
            <th>Ditugaskan Oleh</th>
            <th>Progres</th>
            <th>Deskripsi</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($assignments as $assignment)
            @if ($assignment->user->id === auth()->user()->id)
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
                    {{-- <td>
                    <div class="dropdown">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li> <!-- Tombol Edit -->
                                <a href="{{ route('assignments.edit', $assignment->id) }}"
                                    class="btn btn-warning text-white mb-2"><i class="bi bi-pencil-square"></i>
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
                                <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i>
                                        Hapus</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td> --}}
                    <td>{{ $assignment->description }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
