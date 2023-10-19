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
                </tr>
            </thead>
            <tbody>
                @foreach ($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->title }}</td>
                        <td>{{ $announcement->message }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
