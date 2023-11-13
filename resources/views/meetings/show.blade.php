{{-- resources/views/meetings/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Detail Rapat</h1>

    <dl class="row">
        <dt class="col-sm-3">Judul</dt>
        <dd class="col-sm-9">{{ $meeting->title }}</dd>

        <dt class="col-sm-3">Tanggal</dt>
        <dd class="col-sm-9">{{ $meeting->meeting_date }}</dd>

        <dt class="col-sm-3">Jam Mulai</dt>
        <dd class="col-sm-9">{{ $meeting->meeting_start_time }}</dd>

        <dt class="col-sm-3">Jam Selesai</dt>
        <dd class="col-sm-9">{{ $meeting->meeting_end_time }}</dd>

        <dt class="col-sm-3">Lokasi</dt>
        <dd class="col-sm-9">{{ $meeting->meeting_location }}</dd>

        <dt class="col-sm-3">Risalah Rapat</dt>
        <dd class="col-sm-9">{!!  $meeting->meeting_result !!}</dd>


        <dt class="col-sm-3">Peserta Rapat</dt>
        <dd class="col-sm-9">
            @foreach ($peserta as $data )
            <li>{{ $data->name }}</li>
            @endforeach
        </dd>
    </dl>

    <a href="{{ route('meetings.index') }}" class="btn btn-primary">Kembali ke Daftar Rapat</a>
@endsection
