@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">📌 Penugasan</h5>

            @if ($currentUser->isAdmin() || $currentUser->isKepalaUnit())
                <a href="{{ route('assignments.create') }}" class="btn-ui primary">
                    +
                </a>
            @endif
        </div>

        {{-- SEARCH + FILTER --}}
        <div class="d-flex flex-column flex-md-row gap-2 mb-3">

            <form method="GET" class="flex-grow-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-lg rounded-pill px-3" placeholder="🔍 Cari penugasan...">
            </form>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
                    class="btn-ui {{ !request('status') ? 'primary' : '' }}">
                    Semua
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => 'Ditugaskan']) }}"
                    class="btn-ui warn {{ request('status') == 'Ditugaskan' ? 'primary' : '' }}">
                    📌
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => 'Pending']) }}"
                    class="btn-ui warn {{ request('status') == 'Pending' ? 'warn' : '' }}">
                    ⏱
                </a>

                <a href="{{ request()->fullUrlWithQuery(['status' => 'Selesai']) }}"
                    class="btn-ui success {{ request('status') == 'Selesai' ? 'success' : '' }}">
                    ✔
                </a>

            </div>
        </div>

        {{-- LIST --}}
        <div class="row g-3">

            @forelse ($assignments as $assignment)
                @php
                    $status = $assignment->progres;
                    $badgeClass = match ($status) {
                        'Selesai' => 'success',
                        'Pending' => 'warning',
                        default => 'secondary',
                    };
                @endphp

                <div class="col-12 col-md-6 col-xl-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-3">

                            {{-- TOP --}}
                            <div class="d-flex justify-content-between align-items-start">

                                <div class="d-flex align-items-center">

                                    <img src="{{ asset($assignment->user->profile_image ? 'profile_images/' . $assignment->user->profile_image : 'asset/logo-itdept.png') }}"
                                        class="rounded-circle me-2" width="40" height="40"
                                        style="object-fit: cover;">

                                    <div>
                                        <div class="fw-semibold fs-6">
                                            {{ $assignment->user->name }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $assignment->assignment_date->format('d M') }}
                                        </div>
                                    </div>

                                </div>

                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ $status }}
                                </span>

                            </div>

                            {{-- TITLE --}}
                            <div class="mt-2 fw-semibold fs-6">
                                {{ $assignment->title }}
                            </div>

                            {{-- META --}}
                            <div class="text-muted small mt-1">
                                Oleh {{ $assignment->assigner->name }}
                                • {{ $assignment->start_assignment_time->format('H:i') }}
                                - {{ $assignment->end_assignment_time->format('H:i') }}
                            </div>

                            {{-- KENDALA --}}
                            <div class="mt-2 small">
                                @if ($assignment->kendala)
                                    <span class="text-danger">
                                        ⚠ {{ Str::limit($assignment->kendala, 60) }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        Tidak ada kendala
                                    </span>
                                @endif
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex gap-2 mt-3 flex-wrap">

                                <a href="{{ route('assignments.show', $assignment->id) }}" class="btn-ui primary">
                                    👁
                                </a>

                                @if (Auth::user()->isAdmin() || Auth::user()->isKepalaUnit() || Auth::user()->isHRD())
                                    <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn-ui warn">
                                        ✏
                                    </a>

                                    <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus assignment ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn-ui danger" type="submit">
                                            🗑
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('assignments.markAsComplete', $assignment) }}">
                                        @csrf

                                        <button class="btn-ui success" type="submit">
                                            ✔
                                        </button>
                                    </form>

                                    <button type="button" class="btn-ui warn" data-bs-toggle="modal"
                                        data-bs-target="#penugasanPendingModal{{ $assignment->id }}">
                                        ⏱
                                    </button>

                                    @include('components.modal_pending', ['assignment' => $assignment])
                                @endif

                            </div>

                        </div>
                    </div>

                </div>

            @empty
                <div class="text-center text-muted py-5">
                    Tidak ada penugasan.
                </div>
            @endforelse

        </div>
    </div>

    {{-- BUTTON SYSTEM CSS --}}
    <style>
        .btn-ui {
            width: 44px;
            height: 44px;
            border-radius: 14px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: none;
            cursor: pointer;
            text-decoration: none;

            font-size: 16px;

            background: #f1f3f5;
            color: #212529;

            transition: all .15s ease;

            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .btn-ui:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }

        .btn-ui:active {
            transform: scale(0.95);
        }

        .btn-ui.primary {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .btn-ui.warn {
            background: #fff4e6;
            color: #f08c00;
        }

        .btn-ui.danger {
            background: #ffe3e3;
            color: #e03131;
        }

        .btn-ui.success {
            background: #e6fcf5;
            color: #099268;
        }

        @media (max-width: 768px) {
            .btn-ui {
                width: 48px;
                height: 48px;
                border-radius: 16px;
                font-size: 17px;
            }
        }
    </style>
@endsection
