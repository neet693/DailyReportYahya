@extends('layouts.app')

@section('content')
    <div class="container">

        <h3>Pengaturan Kuota Piket</h3>

        {{-- FORM INPUT --}}
        <div class="card mb-4">
            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-3">
                        <label>Unit Kerja</label>

                        <input type="text" class="form-control" value="{{ $unit?->name ?? 'Tidak ada unit' }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label>Tanggal Mulai</label>

                        <input type="date" id="tanggal_mulai" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Tanggal Selesai</label>

                        <input type="date" id="tanggal_selesai" class="form-control">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="generateTanggal" class="btn btn-primary w-100">
                            Generate Tanggal
                        </button>
                    </div>

                </div>

                <form action="{{ route('piket-harian.store') }}" method="POST">

                    @csrf

                    <input type="hidden" name="unit_kerja_id"
                        value="{{ auth()->user()->employmentDetail?->unit_kerja_id }}">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="40%">Tanggal</th>
                                <th width="40%">Kuota</th>
                            </tr>
                        </thead>

                        <tbody id="tanggalContainer">

                        </tbody>
                    </table>

                    <button class="btn btn-success">
                        Simpan Semua Kuota
                    </button>

                </form>

            </div>
        </div>

        {{-- LIST DATA KUOTA --}}
        <div class="card">
            <div class="card-body">

                <h5>Daftar Kuota Piket</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Tanggal</th>
                            <th>Kuota</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($piketKuotas as $item)
                            <tr>
                                <td>{{ $item->unitKerja->name }}</td>

                                <td>
                                    {{ $item->tanggal_mulai->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $item->total_kuota }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    Belum ada pengaturan kuota
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

        {{-- LIST PENGAJUAN --}}
        <div class="card mt-4">
            <div class="card-body">

                <h5>Daftar Pengajuan Piket</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal Piket</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pengajuans as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>

                                <td>{{ \Carbon\Carbon::parse($item->tanggal_piket)->format('d M Y') }}</td>

                                <td>
                                    @if ($item->status === 'approved')
                                        <span class="badge bg-success">Di Setujui</span>
                                    @elseif ($item->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($item->status === 'rejected')
                                        <span class="badge bg-danger">Di Tolak</span>
                                    @endif
                                </td>

                                <td>
                                    {{-- APPROVE --}}
                                    @if ($item->status === 'pending')
                                        <form action="{{ route('piket.approve', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')

                                            <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Approve pengajuan ini?')">
                                                Approve
                                            </button>
                                        </form>

                                        {{-- REJECT --}}
                                        <form action="{{ route('piket.reject', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')

                                            <button class="btn btn-sm btn-danger">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    Belum ada pengajuan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>
    <script>
        document
            .getElementById('generateTanggal')
            .addEventListener('click', function() {

                const mulai =
                    document.getElementById('tanggal_mulai').value;

                const selesai =
                    document.getElementById('tanggal_selesai').value;

                if (!mulai || !selesai) {
                    alert('Pilih tanggal terlebih dahulu');
                    return;
                }

                const start = new Date(mulai);
                const end = new Date(selesai);

                const container =
                    document.getElementById('tanggalContainer');

                container.innerHTML = '';

                for (
                    let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)
                ) {

                    const tanggal =
                        d.toISOString().split('T')[0];

                    container.innerHTML += `
                <tr>

                    <td>
                        ${tanggal}

                        <input
                            type="hidden"
                            name="tanggal[]"
                            value="${tanggal}">
                    </td>

                    <td>
                        <input
                            type="number"
                            name="kuota[]"
                            class="form-control"
                            value="1"
                            min="0">
                    </td>

                </tr>
            `;
                }
            });
    </script>
@endsection
