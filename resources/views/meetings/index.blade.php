@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Daftar Rapat</h1>
            <a href="{{ route('meetings.create') }}" class="btn btn-success">Tambah Rapat Baru</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center" id="myTable">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 30%">Rapat</th>
                        <th>Peserta</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meetings as $meeting)
                        <tr>
                            <td class="text-start">
                                <strong>{{ $meeting->title }}</strong><br>
                                <small>{{ $meeting->meeting_date->format('l, d F Y') }}</small><br>
                                <small>{{ $meeting->meeting_start_time->format('H:i') }} -
                                    {{ $meeting->meeting_end_time->format('H:i') }}</small><br>
                                <small><i class="bi bi-geo-alt-fill"></i> {{ $meeting->meeting_location }}</small>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                    @foreach ($participants[$meeting->id] as $participant)
                                        <div class="text-center" title="{{ $participant->name }}">
                                            <img src="{{ $participant->profile_image
                                                ? asset('profile_images/' . $participant->profile_image)
                                                : asset('asset/logo-itdept.png') }}"
                                                alt="Foto Profil" class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-gear-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="{{ route('meetings.show', $meeting->id) }}" class="dropdown-item">
                                                <i class="bi bi-eye-fill"></i> Lihat
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('meetings.edit', $meeting->id) }}" class="dropdown-item">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus rapat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash-fill"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
