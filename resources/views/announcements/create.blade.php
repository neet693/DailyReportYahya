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

            {{-- @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala') --}}
            <div class="form-group mb-3">
                <label for="category" class="form-label">Kategori</label>
                <select name="category" id="category" class="form-select" required>
                    <option value="umum">Umum</option>
                    <option value="personal">Personal</option>
                </select>
            </div>

            @if (Auth::user()->isAdmin() || Auth::user()->isKepalaUnit() || Auth::user()->isHRD())
                <div class="form-group mb-3">
                    <label for="recipient_id" class="form-label">Penerima <span style="color: red;">(hanya jika
                            personal):</span></label>
                    <select name="recipient_id" id="recipient_id" class="form-select">
                        <option></option> {{-- Untuk allowClear --}}
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label for="message" class="form-label">Isi Pengumuman</label>
                <input id="message" type="hidden" class="form-control @error('message') is-invalid @enderror"
                    name="message" value="{{ old('message') }}" required autocomplete="message" autofocus>
                <trix-editor input="message"></trix-editor>
            </div>

            <button type="submit" class="btn btn-primary">Buat Pengumuman</button>
        </form>
    </div>
@endsection
