@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('content')
    <div class="container">
        <h1 class="mb-4">Daftar Absensi Pegawai</h1>

        {{-- Filter --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                            value="{{ request('tanggal_awal') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                            value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div class="col-md-12">
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
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>

            {{-- Ringkasan --}}
            <div class="col-md-6 d-flex">
                <div class="card shadow-sm w-100 align-self-stretch"
                    style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        @if ($selectedUser)
                            <h5 class="card-title fw-bold mb-2">{{ $selectedUser->name }}</h5>
                            <p class="fs-5 mb-0 text-primary">
                                <strong>{{ $totalHari }}</strong> Hari
                                (<strong>{{ $totalMenit }}</strong> Menit)
                            </p>
                        @else
                            <div class="text-muted">
                                Pilih pegawai untuk melihat ringkasan keterlambatan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Terlambat</th>
                    <th>Izin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensis as $absen)
                    @php
                        $latenote = $absen->latenote ?? null;
                    @endphp
                    <tr>
                        <td>{{ $absen->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }} </td>
                        <td>{{ $absen->jam_masuk ?? '-' }}</td>
                        <td>{{ $absen->jam_keluar ?? '-' }}</td>
                        <td>
                            {{ $absen->terlambat > 0 ? $absen->terlambat . ' menit' : '-' }}
                        </td>
                        <td id="izin-status-{{ $latenote->id ?? 'none' }}">
                            @if ($latenote && $latenote->izin)
                                Ya
                            @else
                                Tidak
                            @endif
                        </td>
                        <td>
                            @if ($latenote)
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalLateNote-{{ $latenote->id }}">
                                    Lihat Notes
                                </button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    {{-- Modal --}}
                    @if ($latenote)
                        <div class="modal fade" id="modalLateNote-{{ $latenote->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Late Notes - {{ $absen->user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            <strong>Tanggal:</strong>
                                            {{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}
                                            <span class="text-muted">
                                                ({{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '-' }})
                                            </span>
                                        </p>
                                        <p><strong>Catatan:</strong></p>
                                        <p>{{ $latenote->alasan }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        @if (!$latenote->izin)
                                            <button class="btn btn-success btn-acc" data-slug="{{ $latenote->slug }}"
                                                data-id="{{ $latenote->id }}">
                                                ACC
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-acc')) {
                let slug = e.target.dataset.slug;
                let id = e.target.dataset.id;

                if (!confirm('Yakin ACC izin ini?')) return;

                fetch(`/keterlambatan/${slug}/acc`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            document.querySelector(`#izin-status-${id}`).innerHTML = 'Ya';
                            e.target.remove();
                        }
                    });
            }
        });
    </script>
@endsection
