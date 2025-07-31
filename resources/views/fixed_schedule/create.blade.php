@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('fixed-schedule.store') }}" method="POST">
            @csrf

            @if (Auth::user()->isKepalaUnit())
                <div class="mb-3">
                    <label for="user_id" class="form-label">Pilih Guru</label>
                    <select name="user_id" class="form-select" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', $selectedUserId) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            @endif


            <div class="mb-3">
                <label>Hari</label>
                <select name="day_of_week" class="form-select" required>
                    <option value="">Pilih Hari</option>
                    @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                        <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jam Mulai</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Jam Selesai</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Jenis Tugas</label>
                <input type="text" name="type" id="type" class="form-control" value="mengajar" required>
                <small class="text-muted">Contoh: mengajar, piket, koordinasi, administrasi, dll</small>
            </div>

            <div class="mb-3">
                <label>Pelajaran / Tugas</label>
                <input type="text" name="subject" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Ruang Kelas / Lokasi</label>
                <input type="text" name="classroom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan Jadwal Tetap</button>
        </form>
    </div>

@endsection
