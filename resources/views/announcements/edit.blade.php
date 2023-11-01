@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($announcement) ? 'Edit' : 'Tambah' }} Pengumuman</h1>

        <form method="POST" action="{{ route('announcements.update', $announcement->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class=" mb-3">
                <label for="title">{{ __('Judul Pengumuman') }}</label>
                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                    value="{{ old('title', $announcement->title) }}" required autocomplete="title" autofocus>
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="message">{{ __('Isi Pengumuman') }}</label>
                <input id="message" type="hidden" class="form-control @error('message') is-invalid @enderror"
                    name="message" value="{{ old('message', $announcement->message) }}" required autocomplete="message"
                    autofocus>
                <trix-editor input="message"></trix-editor>
                @error('message')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Edit Pengumuman</button>
        </form>
    </div>
@endsection
