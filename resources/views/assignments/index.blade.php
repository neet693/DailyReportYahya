@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📌 Penugasan</h5>
                @if ($currentUser->isAdmin() || $currentUser->isKepalaUnit())
                    <a href="{{ route('assignments.create') }}" class="btn btn-light btn-sm">Buat Penugasan</a>
                @endif
            </div>

            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table id="myTable" class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Petugas</th>
                                <th>Tugas</th>
                                <th>Progres</th>
                                <th>Kendala</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $assignment)
                                <tr>
                                    <td class="text-nowrap">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($assignment->user->profile_image ? 'profile_images/' . $assignment->user->profile_image : 'asset/logo-itdept.png') }}"
                                                class="rounded-circle me-2" width="40" height="40"
                                                style="object-fit: cover;">
                                            <div class="fw-semibold">{{ $assignment->user->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $assignment->title }}</div>
                                        <div class="small text-muted">
                                            Oleh: {{ $assignment->assigner->name }} <br>
                                            {{ $assignment->assignment_date->format('d M Y') }},
                                            {{ $assignment->start_assignment_time->format('H:i') }} -
                                            {{ $assignment->end_assignment_time->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $status = $assignment->progres;
                                            $badgeClass = match ($status) {
                                                'Selesai' => 'success',
                                                'Pending' => 'warning',
                                                default => 'dark',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }} text-white">{{ $status }}</span>
                                    </td>
                                    <td>
                                        <span class="{{ $assignment->kendala ? 'text-danger' : '' }}">
                                            {{ $assignment->kendala ?? 'Tidak ada kendala' }}
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala')
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('assignments.edit', $assignment->id) }}"
                                                            class="dropdown-item">
                                                            <i class="bi bi-pencil-square"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('assignments.show', $assignment->id) }}"
                                                            class="dropdown-item">
                                                            <i class="bi bi-eye"></i> Show
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('assignments.destroy', $assignment->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @else
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('assignments.markAsComplete', $assignment) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="bi bi-check-circle"></i> Tandai Selesai
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                            data-bs-target="#penugasanPendingModal{{ $assignment->id }}">
                                                            <i class="bi bi-clock"></i> Tandai Pending
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            @include('components.modal_pending', [
                                                'assignment' => $assignment,
                                            ])
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
