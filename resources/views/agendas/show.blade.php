@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">📅 Detail Agenda</h4>
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-light border shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>
        <div class="border rounded p-3 mb-4 bg-white">
            <div class="mb-2">
                <h5 class="mb-1">{{ $agenda->title }}</h5>
                <span class="badge bg-{{ $agenda->status == 'planned' ? 'danger' : 'success' }}">
                    {{ ucfirst($agenda->status) }}
                </span>
            </div>

            <div class="text-muted small">
                <p class="mb-1"><strong>Periode:</strong> {{ $agenda->period }}</p>
                <p class="mb-1">
                    <strong>Dari:</strong> {{ $agenda->start_date->format('d M Y') }} &mdash;
                    <strong>Sampai:</strong> {{ $agenda->end_date->format('d M Y') }}
                </p>
            </div>

            <p class="mt-2">{!! $agenda->description !!}</p>

            <div class="d-flex justify-content-between align-items-center mt-3">
                {{-- Executor avatar --}}
                <div class="d-flex align-items-center gap-1">
                    @foreach ($agenda->executors as $executor)
                        <img src="{{ asset($executor->profile_image ? 'profile_images/' . $executor->profile_image : 'asset/logo-itdept.png') }}"
                            class="rounded-circle" width="26" height="26" title="{{ $executor->name }}">
                    @endforeach
                </div>

                {{-- Aksi --}}
                <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle shadow-sm"
                        data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 6px;">
                        <i class="bi bi-gear"></i> Aksi
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @can('edit', $agenda)
                            <li><a href="{{ route('agendas.edit', $agenda->id) }}" class="dropdown-item">✏ Edit</a></li>
                        @endcan
                        <li><a href="{{ route('agendas.print', $agenda->id) }}" class="dropdown-item">🖨 Print</a></li>
                        @can('delete', $agenda)
                            <li>
                                <form action="{{ route('agendas.destroy', $agenda) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">🗑 Hapus</button>
                                </form>
                            </li>
                        @endcan
                        @can('updateStatus', $agenda)
                            @if (!in_array(strtolower($agenda->status), ['finish']))
                                <li>
                                    <form action="{{ route('agendas.updateStatus', [$agenda->id, 'status' => 'selesai']) }}"
                                        method="POST">
                                        @csrf @method('PATCH')
                                        <button class="dropdown-item text-success">✔ Tandai Selesai</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('agendas.updateStatus', [$agenda->id, 'status' => 'pending']) }}"
                                        method="POST">
                                        @csrf @method('PATCH')
                                        <button class="dropdown-item text-warning">⏳ Pending</button>
                                    </form>
                                </li>
                            @endif
                        @endcan
                    </ul>
                </div>
            </div>
        </div>

        {{-- Accordion Log Agenda --}}
        <div class="mb-4">
            <button class="btn btn-sm btn-outline-dark w-100 fw-semibold" type="button" data-bs-toggle="collapse"
                data-bs-target="#logAgendaCollapse" aria-expanded="false">
                📘 Lihat Log Agenda ({{ $agenda->logs->count() }})
            </button>

            <div class="collapse mt-3" id="logAgendaCollapse">
                @if ($agenda->status !== 'selesai')
                    <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLogModal">
                        + Tambah Log Agenda
                    </button>
                    @include('components.modal_add_log_agenda', ['agendaId' => $agenda->id])
                @endif

                @if ($agenda->logs->isEmpty())
                    <p class="text-muted">Belum ada log agenda.</p>
                @else
                    <div class="row g-3">
                        @foreach ($agenda->logs as $log)
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <p class="fw-bold mb-1">{{ $log->executor->name }}</p>
                                    <div class="small text-muted mb-2">{!! $log->log_detail !!}</div>
                                    <div class="text-muted small">{{ $log->created_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
