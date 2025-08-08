@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('content')
    <div class="container">
        <h1 class="mb-4">Daftar Absensi Pegawai</h1>

        {{-- Filter bulan & pegawai (optional) --}}
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="bulan" class="form-label">Bulan</label>
                <input type="month" name="bulan" id="bulan" class="form-control" value="{{ request('bulan') }}">
            </div>
            <div class="col-md-3">
                <label for="pegawai" class="form-label">Pegawai</label>
                <select name="pegawai" id="pegawai" class="form-control">
                    <option value="">Semua Pegawai</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('pegawai') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        {{-- Table --}}
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Terlambat</th>
                    <th>Izin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensis as $absen)
                    <tr>
                        <td>{{ $absen->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $absen->jam_masuk ?? '-' }}</td>
                        <td>{{ $absen->jam_keluar ?? '-' }}</td>
                        <td>
                            {{ $absen->terlambat > 0 ? $absen->terlambat . ' menit' : '-' }}
                        </td>
                        <td>{{ $absen->izin ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
