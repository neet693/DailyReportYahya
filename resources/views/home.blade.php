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
                    <h5 class="mb-0">🔔 Notifikasi</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Jenis</th>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($notifications as $item)
                                    <tr>
                                        {{-- JENIS --}}
                                        <td class="text-center text-nowrap">
                                            @if ($item->type == 'announcements')
                                                <span class="badge bg-info">📢 Pengumuman</span>
                                            @elseif ($item->type == 'agendas')
                                                <span class="badge bg-primary">🗓 Agenda</span>
                                            @elseif ($item->type == 'meetings')
                                                <span class="badge bg-warning text-dark">🤝 Rapat</span>
                                            @elseif ($item->type == 'assignments')
                                                <span class="badge bg-success">📌 Penugasan</span>
                                            @endif
                                        </td>

                                        {{-- JUDUL --}}
                                        <td>
                                            <div class="fw-semibold">{{ $item->title }}</div>
                                            @if (!empty($item->description))
                                                <small class="text-muted">
                                                    {{ Str::limit(strip_tags($item->description), 60) }}
                                                </small>
                                            @endif
                                        </td>

                                        {{-- TANGGAL --}}
                                        <td class="text-center text-nowrap">
                                            {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($item->date)->format('H:i') }}
                                            </small>
                                        </td>

                                        {{-- STATUS --}}
                                        <td class="text-center">
                                            {{-- Perhatikan 'assignments' pakai 's' agar cocok dengan controller --}}
                                            @if ($item->type === 'assignments')
                                                <span
                                                    class="badge {{ $item->progress === 'Selesai' ? 'bg-success' : ($item->progress === 'Pending' ? 'bg-warning text-dark' : 'bg-dark') }}">
                                                    {{ $item->progress ?? 'Belum Selesai' }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Baru</span>
                                            @endif
                                        </td>

                                        {{-- AKSI --}}
                                        <td class="text-center text-nowrap">
                                            @if (!empty($item->route))
                                                <a href="{{ $item->route }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Tidak ada data tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        @include('chats.modal')
    </div>
@endsection
