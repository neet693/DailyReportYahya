@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Edit Jadwal Tetap</h3>

        <form method="POST" action="{{ route('fixed-schedule.update', $fixedTask->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>User</label>
                <select name="user_id" class="form-control" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $fixedTask->user_id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Hari</label>
                <select name="day_of_week" class="form-control" required>
                    @foreach (['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu'] as $key => $day)
                        <option value="{{ $key }}" {{ $fixedTask->day_of_week == $key ? 'selected' : '' }}>
                            {{ $day }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label>Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ $fixedTask->start_time }}" class="form-control"
                        required>
                </div>
                <div class="col">
                    <label>Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ $fixedTask->end_time }}" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Tipe</label>
                <input type="text" name="type" class="form-control" value="{{ $fixedTask->type }}" required>
            </div>

            <div class="mb-3">
                <label>Mata Pelajaran</label>
                <input type="text" name="subject" class="form-control" value="{{ $fixedTask->subject }}" required>
            </div>

            <div class="mb-3">
                <label>Ruangan</label>
                <input type="text" name="classroom" class="form-control" value="{{ $fixedTask->classroom }}" required>
            </div>

            <div class="mb-3">
                <label>Keterangan (opsional)</label>
                <textarea name="description" class="form-control">{{ $fixedTask->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
