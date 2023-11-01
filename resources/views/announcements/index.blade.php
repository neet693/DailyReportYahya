@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Pengumuman</h1>
        <a href="{{ route('announcements.create') }}" class="btn btn-primary">Buat Pemberitahuan</a>

        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Isi Pengumuman</th>
                    <th>Tanggal Buat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->title }}</td>
                        <td>{!! $announcement->message !!}</td>
                        <td>{{ $announcement->created_at->format('j F Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li> <!-- Tombol Edit -->
                                        <a href="{{ route('announcements.edit', $announcement->id) }}"
                                            class="btn btn-warning text-white mb-2"><i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>
                                    </li>
                                    <li> <!-- Tombol Show -->
                                        <a href="{{ route('announcements.show', $announcement->id) }}"
                                            class="btn btn-info text-white mb-2"><i class="bi bi-eye"></i>
                                            Show
                                        </a>
                                    </li>
                                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala')
                                        <li> <!-- Tombol Hapus -->
                                            <form action="{{ route('announcements.destroy', $announcement->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i>
                                                    Hapus</button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
