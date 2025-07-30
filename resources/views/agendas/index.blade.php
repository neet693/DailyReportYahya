@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Header dengan ikon dan tombol -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="d-flex align-items-center">
                <i class="bi bi-calendar2-check text-primary me-2"></i> Agenda Unit dan Kalender Akademik
            </h2>
            @if (Auth::user()->isHRD() || Auth::user()->isAdmin())
                <a class="btn btn-outline-primary" href="{{ route('agenda.byUnit') }}">
                    <i class="bi bi-filter-circle"></i> Agenda Semua Unit
                </a>
            @endif
        </div>

        <div class="col">
            @if (Auth::user()->isAdmin() || Auth::user()->isKepalaUnit() || Auth::user()->isHRD())
                <a href="{{ route('agendas.create') }}" class="btn btn-primary">+ Buat Agenda</a>
            @endif
        </div>

        <div id='calendar'></div>
    </div>

    {{-- Script Calender --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($agendas as $agenda)
                        {
                            title: '{{ $agenda->title }}',
                            start: '{{ $agenda->start_date }}',
                            end: '{{ $agenda->end_date }}',
                            url: '{{ route('agendas.show', $agenda->id) }}' // opsional
                        },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>

    {{-- End Script Calender --}}
@endsection
