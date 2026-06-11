@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container mt-4">
            @include('toaster')
            @include('components.home-component.header')
            @if ($usersWithTasks->isEmpty())
                <div class="alert alert-info py-2 px-3 small">
                    <i class="bi bi-info-circle-fill me-2"></i> Belum ada data tugas.
                </div>
            @else
                @include('components.home-component.daftar-tugas')
            @endif
        </div>

        <div class="container mt-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">ðŸ”” Timeline Tugas yang belum selesai</h5>
                </div>

                <div class="card-body">

                    @forelse ($notifications as $month => $items)
                        {{-- HEADER BULAN --}}
                        <div class="mb-3">
                            <div class="fw-bold text-primary mb-2">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                            </div>

                            <div class="list-group">

                                @foreach ($items as $item)
                                    <div class="list-group-item border-0 shadow-sm mb-2 rounded">

                                        <div class="d-flex justify-content-between align-items-start">

                                            {{-- LEFT CONTENT --}}
                                            <div class="me-3">

                                                <div class="fw-semibold">
                                                    @if ($item->type == 'announcements')
                                                        ðŸ“¢
                                                    @elseif ($item->type == 'agendas')
                                                        ðŸ—“
                                                    @elseif ($item->type == 'assignments')
                                                        ðŸ“Œ
                                                    @endif

                                                    {{ $item->title }}
                                                </div>

                                                @if (!empty($item->description))
                                                    <small class="text-muted d-block">
                                                        {{ Str::limit(strip_tags($item->description), 80) }}
                                                    </small>
                                                @endif

                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($item->date)->format('d M Y H:i') }}
                                                </small>

                                            </div>

                                            {{-- RIGHT BADGE + ACTION --}}
                                            <div class="text-end">

                                                @if ($item->type === 'assignments')
                                                    @if ($item->progress === 'Pending')
                                                        <span class="badge bg-warning text-dark">
                                                            Pending
                                                        </span>
                                                    @elseif ($item->progress === 'Selesai')
                                                        <span class="badge bg-success">
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span class="badge bg-info">
                                                            Ditugaskan
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">
                                                        Baru
                                                    </span>
                                                @endif

                                                <div class="mt-2">
                                                    @if (!empty($item->route))
                                                        <a href="{{ $item->route }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            Lihat
                                                        </a>
                                                    @endif
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        </div>

                    @empty
                        <div class="text-center text-muted py-4">
                            Tidak ada notifikasi.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>



        @include('chats.modal')
    </div>
@endsection
