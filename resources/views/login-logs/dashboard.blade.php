@extends('layouts.app')

@section('title', 'Dashboard Aktivitas Unit')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0">Dashboard Aktivitas Unit</h3>
                <small class="text-muted">
                    Monitoring aktivitas login pegawai dalam unit Anda
                </small>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="row">

            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            Total Login Bulan Ini
                        </h6>

                        <h2 class="fw-bold mb-0">
                            {{ number_format($totalLoginBulanIni) }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            Pegawai Aktif
                        </h6>

                        <h2 class="fw-bold mb-0">
                            {{ number_format($pegawaiAktif) }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <h6 class="text-muted mb-2">
                            Login Terakhir
                        </h6>

                        @if ($lastLogin)
                            <div class="fw-bold">
                                {{ $lastLogin->user->name }}
                            </div>

                            <small class="text-muted">
                                {{ $lastLogin->login_at->format('d M Y H:i') }}
                            </small>

                            <br>

                            <small class="text-primary">
                                {{ $lastLogin->login_at->diffForHumans() }}
                            </small>
                        @else
                            <span class="text-muted">
                                Belum ada data login
                            </span>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        {{-- Ranking --}}
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Ranking Aktivitas Login Pegawai
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="80">Rank</th>
                                <th>Nama Pegawai</th>
                                <th width="180">Total Login</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($loginStats as $index => $stat)
                                <tr>

                                    <td>
                                        @if ($index == 0)
                                            🥇
                                        @elseif ($index == 1)
                                            🥈
                                        @elseif ($index == 2)
                                            🥉
                                        @else
                                            #{{ $index + 1 }}
                                        @endif
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $stat->user->name }}
                                        </div>

                                        @if (optional($stat->user->employmentDetail)->position)
                                            <small class="text-muted">
                                                {{ $stat->user->employmentDetail->position }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-primary fs-6">
                                            {{ $stat->total_login }} Login
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Belum ada data login
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>

            </div>
        </div>

    </div>
@endsection
