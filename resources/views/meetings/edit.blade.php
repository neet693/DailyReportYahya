{{-- resources/views/meetings/edit.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Form Edit Rapat</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Judul Rapat</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $meeting->title }}" required>
        </div>
        <div class="form-group">
            <label for="meeting_date">Tanggal</label>
            <input type="date" name="meeting_date" id="meeting_date" class="form-control" value="{{ $meeting->meeting_date }}" required>
        </div>
        <div class="form-group">
            <label for="meeting_start_time">Jam Mulai</label>
            <input type="time" name="meeting_start_time" id="meeting_start_time" class="form-control" value="{{ $meeting->meeting_start_time }}" required>
        </div>
        <div class="form-group">
            <label for="meeting_end_time">Jam Selesai</label>
            <input type="time" name="meeting_end_time" id="meeting_end_time" class="form-control" value="{{ $meeting->meeting_end_time }}" required>
        </div>
        <div class="form-group">
            <label for="meeting_location">Lokasi</label>
            <input type="text" name="meeting_location" id="meeting_location" class="form-control" value="{{ $meeting->meeting_location }}" required>
        </div>
        <div class="form-group">
            <label for="participant_id">Peserta Rapat</label>
            <select name="participant_id[]" id="participant_id" class="form-control" multiple required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="meeting_result">Hasil Rapat</label>
            <trix-editor input="meeting_result" class="trix-content" required>{{ $meeting->meeting_result }}</trix-editor>
            <input id="meeting_result" type="hidden" name="meeting_result" value="{{ $meeting->meeting_result }}">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
@endsection
