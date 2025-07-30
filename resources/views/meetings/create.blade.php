@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Form Tambah Rapat Baru</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('meetings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Judul Rapat</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}"
                    required>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="meeting_date" class="form-label">Tanggal</label>
                    <input type="date" name="meeting_date" id="meeting_date" class="form-control"
                        value="{{ old('meeting_date') }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="meeting_start_time" class="form-label">Jam Mulai</label>
                    <input type="time" name="meeting_start_time" id="meeting_start_time" class="form-control"
                        value="{{ old('meeting_start_time') }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="meeting_end_time" class="form-label">Jam Selesai</label>
                    <input type="time" name="meeting_end_time" id="meeting_end_time" class="form-control"
                        value="{{ old('meeting_end_time') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="meeting_location" class="form-label">Lokasi</label>
                    <input type="text" name="meeting_location" id="meeting_location" class="form-control"
                        value="{{ old('meeting_location') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="participant_id" class="form-label">Peserta Rapat</label>
                    <select name="participant_id[]" id="participant_id" class="form-select select2" multiple required
                        data-placeholder="Pilih peserta rapat">
                        <option disabled>Nama Anda otomatis terpilih. Pilih peserta lainnya…</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ collect(old('participant_id'))->contains($user->id) || $user->id === auth()->id() ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->units->pluck('name')->implode(', ') ?: '-' }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-red-500">pilih peserta rapat lainnya</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="meeting_result" class="form-label">Hasil Rapat</label>
                <input id="meeting_result" type="hidden" name="meeting_result" value="{{ old('meeting_result') }}">
                <trix-editor input="meeting_result"></trix-editor>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
