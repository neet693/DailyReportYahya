@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($announcement) ? 'Edit' : 'Tambah' }} Pengumuman</h1>

        <form method="POST" action="{{ route('announcements.store') }}">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Judul Pengumuman</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Isi Pengumuman</label>
                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Buat Pengumuman</button>
        </form>
    </div>
@endsection
