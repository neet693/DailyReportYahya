@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Form Edit Rapat</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Judul Rapat</label>
                <input type="text" name="title" id="title" class="form-control"
                    value="{{ old('title', $meeting->title) }}" required>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="meeting_date" class="form-label">Tanggal</label>
                    <input type="date" name="meeting_date" id="meeting_date" class="form-control"
                        value="{{ old('meeting_date', $meeting->meeting_date->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="meeting_start_time" class="form-label">Jam Mulai</label>
                    <input type="time" name="meeting_start_time" id="meeting_start_time" class="form-control"
                        value="{{ old('meeting_start_time', $meeting->meeting_start_time)->format('H:i') }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="meeting_end_time" class="form-label">Jam Selesai</label>
                    <input type="time" name="meeting_end_time" id="meeting_end_time" class="form-control"
                        value="{{ old('meeting_end_time', $meeting->meeting_end_time)->format('H:i') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="meeting_location" class="form-label">Lokasi</label>
                    <input type="text" name="meeting_location" id="meeting_location" class="form-control"
                        value="{{ old('meeting_location', $meeting->meeting_location) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="participant_id" class="form-label">Peserta Rapat</label>
                    <select name="participant_id[]" id="participant_id" class="form-select select2" multiple required
                        data-placeholder="Pilih peserta rapat">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ in_array($user->id, old('participant_id', $selectedUsers)) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->units->pluck('name')->implode(', ') ?: '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mb-3">
                <label for="meeting_result" class="form-label">Hasil Rapat</label>
                <input id="meeting_result" type="hidden" name="meeting_result"
                    value="{{ old('meeting_result', $meeting->meeting_result) }}">
                <trix-editor input="meeting_result"></trix-editor>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
