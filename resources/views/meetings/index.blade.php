{{-- resources/views/meetings/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Rapat</h1>
        <a href="{{ route('meetings.create') }}" class="btn btn-success">Tambah Rapat Baru</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>Rapat</th>
                    <th>Peserta Rapat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($meetings as $meeting)
                    <tr>
                        <td>{{ $meeting->title }} <br>
                            {{ $meeting->meeting_date->format('l, d F Y') }} <br>
                            {{ $meeting->meeting_start_time->format('H:i') }} s/d
                            {{ $meeting->meeting_end_time->format('H:i') }} <br>
                            {{ $meeting->meeting_location }}
                        </td>
                        <td>
                            @foreach ($participants[$meeting->id] as $participant)
                                <li hidden>{{ $participant->name  }}</li>
                                @if ($participant->profile_image)
                                    <img src="{{ asset('profile_images/' . $participant->profile_image) }}"
                                        class="card-img-top rounded-circle" style="width: 50px" title="{{ $participant->name }}" alt="Foto Profil">
                                @else
                                    <img src="{{ asset('asset/logo-itdept.png') }}" class="card-img-top rounded-circle"
                                    style="width: 50px" alt="Foto Profil" TITLE>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus rapat ini?')"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </li>
                                    <li> <!-- Tombol Edit -->
                                        <a href="{{ route('meetings.edit', $meeting->id) }}" title="Edit"
                                            class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                    </li>
                                    <li> <!-- Tombol Show -->
                                        <a href="{{ route('meetings.show', $meeting->id) }}" title="Lihat"
                                            class="btn btn-info"><i class="bi bi-eye"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
