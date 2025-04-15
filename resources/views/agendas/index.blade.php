@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Header dengan ikon dan tombol -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="d-flex align-items-center">
                <i class="bi bi-calendar2-check text-primary me-2"></i> Agenda List
            </h2>
            <a href="{{ route('agendas.create') }}" class="btn btn-primary">+ Buat Agenda</a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($agendas as $agenda)
                <div class="col">
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
                                            style="width: 30px; height: 30px;" alt="Foto Profil"
                                            title="{{ $executor->name }}">
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
                                            <form action="{{ route('agendas.destroy', $agenda) }}" method="POST"
                                                class="d-inline">
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
                </div>
            @endforeach
        </div>
    </div>
@endsection
