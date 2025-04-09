@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">Detail Agenda</h2>

        <div class="card shadow-lg border rounded-3 p-4 mb-4">
            <div class="card-body">
                <h4 class="fw-bold">{{ $agenda->title }}</h4>
                <span class="badge bg-{{ $agenda->status == 'planned' ? 'danger' : 'success' }}">
                    {{ ucfirst($agenda->status) }}
                </span>
                <hr>
                <p><strong>Periode:</strong> {{ $agenda->period }}</p>
                <p class="mb-1">
                    <strong>Dari:</strong> {{ $agenda->start_date->format('d M Y') }}
                    <strong>sampai</strong> {{ $agenda->end_date->format('d M Y') }}
                </p>
                <p class="mb-3">{!! $agenda->description !!}</p>

                <div class="d-flex align-items-center">
                    <strong class="me-2">Eksekutor:</strong>
                    @foreach ($agenda->executors as $executor)
                        <img src="{{ $executor->profile_image ? asset('profile_images/' . $executor->profile_image) : asset('asset/logo-itdept.png') }}"
                            class="rounded-circle border border-2 me-2" width="45" height="45"
                            title="{{ $executor->name }}" alt="Foto Profil">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Log Agenda -->
        <h3>Log Agenda</h3>
        <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addLogModal">
            <i class="bi bi-plus"></i> Tambah Log Agenda
        </button>
        @include('components.modal_add_log_agenda', ['agendaId' => $agenda->id])

        @foreach ($agenda->logs as $log)
            <div class="card shadow-lg border rounded-3 p-3 mb-3">
                <div class="card-body">
                    <p class="fw-bold">Oleh: {{ $log->executor->name }}</p>
                    <p>Detail: {!! $log->log_detail !!}</p>
                    <p class="text-muted small">Dibuat pada: {{ $log->created_at->format('d M Y H:i:s') }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection
