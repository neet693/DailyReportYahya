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
                                {{ $agenda->status === 'on progress' ? 'bg-warning text-dark' : ($agenda->status === 'selesai' ? 'bg-success' : 'bg-danger') }}">
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
                            <div>
                                <a href="{{ route('agendas.show', $agenda->id) }}" class="btn btn-info btn-sm me-2"><i
                                        class="bi bi-eye"></i></a>
                                @can('edit', $agenda)
                                    <a href="{{ route('agendas.edit', $agenda->id) }}" class="btn btn-warning btn-sm me-2"><i
                                            class="bi bi-pencil-square"></i></a>
                                @endcan
                                <a href="{{ route('agendas.print', $agenda->id) }}" class="btn btn-primary btn-sm"><i
                                        class="bi bi-printer"></i></a>
                                @can('delete', $agenda)
                                    <form action="{{ route('agendas.destroy', $agenda) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
