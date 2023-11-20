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
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $agenda->status }}
                                <span class="visually-hidden">unread messages</span>
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
                                <a href="{{ route('agendas.edit', $agenda->id) }}" class="btn btn-warning btn-sm me-2"><i
                                        class="bi bi-pencil-square"></i></a>
                                <a href="{{ route('agendas.print', $agenda->id) }}" class="btn btn-primary btn-sm"><i
                                        class="bi bi-printer"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
