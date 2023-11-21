{{-- resources/views/agendas/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Agenda List</h2>
        <a href="{{ route('agendas.create') }}" class="btn btn-primary">Buat Agenda</a>
        <div class="row">
            @foreach ($agendas as $agenda)
                <div class="col-md-6 mt-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $agenda->title }}</h5>
                            <span>
                                Periode: {{ $agenda->period }}
                            </span>
                            <br>
                            <span>
                                Dari: {{ $agenda->start_date->format('d F Y') }} sampai
                                {{ $agenda->end_date->format('d F Y') }}
                            </span>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill
                            {{ $agenda->status === 'on progress' ? 'bg-warning' : ($agenda->status === 'selesai' ? 'bg-success' : 'bg-danger') }}">
                                {{ $agenda->status }}
                                <span class="visually-hidden">Status Agenda</span>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{!! $agenda->description !!}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <div>
                                <div class="d-flex">
                                    @foreach ($agenda->executors as $executor)
                                        @if ($executor->profile_image)
                                            <img src="{{ asset('profile_images/' . $executor->profile_image) }}"
                                                class="card-img-top rounded-circle" style="width: 30px; height: 30px;"
                                                title="{{ $executor->name }}" alt="Foto Profil">
                                        @else
                                            <img src="{{ asset('asset/logo-itdept.png') }}"
                                                class="card-img-top rounded-circle" style="width: 30px; height: 30px;"
                                                alt="Foto Profil" title="{{ $executor->name }}">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="">
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
                                @can('updateStatus', $agenda)
                                    <form action="{{ route('agendas.updateStatus', $agenda->id) }}" method="post">
                                        @csrf
                                        @method('patch')

                                        <button type="submit" name="status" value="on progress" class="btn btn-info">On
                                            Progress</button>
                                        <button type="submit" name="status" value="finish"
                                            class="btn btn-success">Selesai</button>
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
