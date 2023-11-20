<!-- resources/views/agendas/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Detail Agenda</h2>

        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title">{{ $agenda->title }}</h4>
                <span>Periode: {{ $agenda->period }}</span>
            </div>
            <div class="card-body">
                {!! $agenda->description !!}
                <p class="card-text">Status: {{ $agenda->status }}</p>
            </div>
            <div class="card-footer">
                @foreach ($agenda->executors as $executor)
                    @if ($executor->profile_image)
                        <img src="{{ asset('profile_images/' . $executor->profile_image) }}"
                            class="card-img-top rounded-circle" style="height:50px; width: 50px;"
                            title="{{ $executor->name }}" alt="Foto Profil">
                    @else
                        <img src="{{ asset('asset/logo-itdept.png') }}" class="card-img-top rounded-circle"
                            style="height:50px; width: 50px;" alt="Foto Profil" title="{{ $executor->name }}">
                    @endif
                @endforeach
            </div>
        </div>

        <h3>Log Agenda</h3>

        <!-- Tampilkan log-log agenda -->
        {{-- @foreach ($agenda->logs as $log)
            <div class="card mb-3">
                <div class="card-body">
                    <p class="card-text">Aksi: {{ $log->action }}</p>
                    <p class="card-text">Detail: {{ $log->details }}</p>
                    <p class="card-text">Dibuat pada: {{ $log->created_at->format('d M Y H:i:s') }}</p>
                </div>
            </div>
        @endforeach --}}
    </div>
@endsection
