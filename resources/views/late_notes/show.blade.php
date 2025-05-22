@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-4">Detail Keterlambatan</h4>

                <p><strong>Waktu Keterlambatan:</strong>
                    {{ \Carbon\Carbon::parse($keterlambatan->waktu_terlambat)->format('d M Y H:i') }}</p>
                <p><strong>Alasan:</strong> {{ $keterlambatan->alasan }}</p>

                @if ($keterlambatan->foto)
                    <div class="mt-3">
                        <strong>Bukti Foto:</strong><br>
                        <img src="{{ asset($keterlambatan->foto) }}" alt="Foto Keterlambatan"
                            class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                    </div>
                @else
                    <p><em>Tidak ada foto bukti.</em></p>
                @endif

                <a href="{{ route('keterlambatan.index') }}" class="btn btn-secondary mt-4">Kembali</a>
            </div>
        </div>
    </div>
@endsection
