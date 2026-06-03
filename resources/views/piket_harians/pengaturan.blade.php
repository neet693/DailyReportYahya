@extends('layouts.app')

@section('content')
    <div class="container">

        <h3>Pengaturan Kuota Piket</h3>

        {{-- FORM INPUT --}}
        <div class="card mb-4">
            <div class="card-body">

                <form action="{{ route('piket-harian.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-3">
                            <label>Unit Kerja</label>

                            <input type="text" class="form-control" value="{{ $unit?->name ?? 'Tidak ada unit' }}" disabled>

                            <input type="hidden" name="unit_kerja_id"
                                value="{{ auth()->user()->employmentDetail?->unit_kerja_id }}">
                        </div>

                        <div class="col-md-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Limit Kuota Piket</label>
                            <input type="number" name="total_kuota" class="form-control">
                        </div>

                    </div>

                    <button class="btn btn-primary mt-3">
                        Simpan
                    </button>

                </form>

            </div>
        </div>

        {{-- LIST DATA --}}
        <div class="card">
            <div class="card-body">

                <table class="table">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Periode</th>
                            <th>Limit Kuota Piket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($piketKuotas as $item)
                            <tr>
                                <td>{{ $item->unitKerja->name }}</td>
                                <td>
                                    {{ $item->tanggal_mulai->format('d M Y') }} -
                                    {{ $item->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td>{{ $item->total_kuota }}</td>
                            </tr>
                        @endforeach
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
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($item->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($item->status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
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
                                        <form action="{{ route('piket.cancel', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')

                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Reject pengajuan ini?')">
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
@endsection
