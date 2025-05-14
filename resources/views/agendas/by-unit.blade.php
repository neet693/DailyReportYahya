@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-calendar-range text-primary me-2"></i> Agenda Semua Unit</h2>
            <a href="{{ route('agendas.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Agenda Saya
            </a>
        </div>

        <form method="GET" action="{{ route('agenda.byUnit') }}" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <select name="unit_id" id="unit_id" class="form-select">
                        <option></option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            @forelse ($agendas as $agenda)
                <div class="card shadow-lg border-0 rounded-3 h-100">
                    <div class="card-header bg-light position-relative">
                        <h5 class="card-title mb-0">{{ $agenda->title }}</h5>
                        <span
                            class="position-absolute top-0 end-0 m-2 badge rounded-pill
                                {{ $agenda->status === 'pending' ? 'bg-warning text-dark' : ($agenda->status === 'selesai' ? 'bg-success' : 'bg-danger') }}">
                            {{ ucfirst($agenda->status) }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <p class="mb-1"><strong>Periode:</strong> {{ $agenda->period }}</p>
                        <p class="mb-1">
                            <strong>Dari:</strong> {{ $agenda->start_date->format('d M Y') }}
                            <strong>sampai</strong> {{ $agenda->end_date->format('d M Y') }}
                        </p>
                        <p class="flex-grow-1">{!! $agenda->description !!}</p> <!-- Deskripsi tetap ada -->
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div class="d-flex">
                            @foreach ($agenda->executors as $executor)
                                @if ($executor->profile_image)
                                    <img src="{{ asset('profile_images/' . $executor->profile_image) }}"
                                        class="card-img-top rounded-circle" style="width: 30px; height: 30px;"
                                        title="{{ $executor->name }}" alt="Foto Profil">
                                @else
                                    <img src="{{ asset('asset/logo-itdept.png') }}" class="card-img-top rounded-circle"
                                        style="width: 30px; height: 30px;" alt="Foto Profil" title="{{ $executor->name }}">
                                @endif
                            @endforeach
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                                Aksi
                            </button>
                            <ul class="dropdown-menu"
                                style="border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                                <li>
                                    <a href="{{ route('agendas.show', $agenda->id) }}" class="dropdown-item text-info"
                                        style="color: #333; padding: 8px 16px; font-weight: 500;">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </li>
                                @can('edit', $agenda)
                                    <li>
                                        <a href="{{ route('agendas.edit', $agenda->id) }}" class="dropdown-item"
                                            style="color: #333; padding: 8px 16px; font-weight: 500;">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <a href="{{ route('agendas.print', $agenda->id) }}" class="dropdown-item"
                                        style="color: #333; padding: 8px 16px; font-weight: 500;">
                                        <i class="bi bi-printer"></i> Print
                                    </a>
                                </li>
                                @can('delete', $agenda)
                                    <li>
                                        <form action="{{ route('agendas.destroy', $agenda) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Are you sure?')"
                                                style="padding: 8px 16px; font-weight: 500;">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                @endcan

                                {{-- Tombol ubah status --}}
                                @can('updateStatus', $agenda)
                                    @if (!in_array(strtolower($agenda->status), ['finish']))
                                        <li>
                                            <form
                                                action="{{ route('agendas.updateStatus', ['id' => $agenda->id, 'status' => 'selesai']) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="dropdown-item text-success"
                                                    style="padding: 8px 16px; font-weight: 500; border-radius: 5px;">
                                                    <i class="bi bi-check-square-fill"></i> Selesai
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form
                                                action="{{ route('agendas.updateStatus', ['id' => $agenda->id, 'status' => 'pending']) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="dropdown-item text-warning"
                                                    style="padding: 8px 16px; font-weight: 500; border-radius: 5px;">
                                                    <i class="bi bi-exclamation-circle-fill"></i> Pending
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                @endcan
                            </ul>
                        </div>

                    </div>

                </div>
            @empty
                <div class="col">
                    <p class="text-muted">Tidak ada agenda ditemukan untuk unit ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
