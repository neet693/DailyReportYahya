{{-- resources/views/meetings/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Detail Rapat</h3>
            </div>

            <div class="card-body">
                <dl class="row mb-4">
                    <dt class="col-sm-4 fw-semibold">Judul</dt>
                    <dd class="col-sm-8">{{ $meeting->title }}</dd>

                    <dt class="col-sm-4 fw-semibold">Tanggal</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</dd>

                    <dt class="col-sm-4 fw-semibold">Jam Mulai</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($meeting->meeting_start_time)->format('H:i') }} WIB</dd>

                    <dt class="col-sm-4 fw-semibold">Jam Selesai</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($meeting->meeting_end_time)->format('H:i') }} WIB</dd>

                    <dt class="col-sm-4 fw-semibold">Lokasi</dt>
                    <dd class="col-sm-8">{{ $meeting->meeting_location }}</dd>

                    <dt class="col-sm-4 fw-semibold">Risalah Rapat</dt>
                    <dd class="col-sm-8">
                        <div class="border rounded p-3" style="background: #f9f9f9;">
                            {!! $meeting->meeting_result !!}
                        </div>
                    </dd>

                    <dt class="col-sm-4 fw-semibold">Peserta Rapat</dt>
                    <dd class="col-sm-8">
                        @if ($meeting->participants->isEmpty())
                            <span class="text-muted">Belum ada peserta</span>
                        @else
                            <ul class="d-flex justify-content-start flex-wrap gap-2 list-unstyled p-0 m-0">
                                @foreach ($meeting->participants as $participant)
                                    <li>
                                        <img src="{{ $participant->profile_image
                                            ? asset('profile_images/' . $participant->profile_image)
                                            : asset('asset/logo-itdept.png') }}"
                                            alt="Foto Profil" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover; vertical-align: middle;">
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </dd>

                </dl>

                <a href="{{ route('meetings.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Daftar Rapat
                </a>
            </div>
        </div>
    </div>
@endsection
