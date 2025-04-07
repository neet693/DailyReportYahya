@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Buat Permohonan Surat Izin/Surat Sakit Baru</h1>

        <form method="POST" action="{{ route('permissionrequest.store') }}">
            @csrf

            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jabatan">Jabatan:</label>
                <input type="text" name="jabatan" id="jabatan" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jenis_pegawai">Jenis Pegawai:</label>
                <select name="jenis_pegawai" id="jenis_pegawai" class="form-control" required>
                    <option value="Pegawai Tetap">Pegawai Tetap</option>
                    <option value="Pegawai Tidak Tetap">Pegawai Tidak Tetap</option>
                </select>
            </div>

            <div class="form-group">
                <label for="unit_kerja_id">Unit:</label>
                <select name="unit_kerja_id" id="unit_kerja_id" class="form-control" required>
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group">
                <label for="start_date">Tanggal Mulai:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="end_date">Tanggal Selesai:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi:</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Permohonan</button>
        </form>
    </div>
@endsection
