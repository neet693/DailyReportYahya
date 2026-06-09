@extends('layouts.app')

@section('content')
    <div class="container">

        <h3>Kalender Piket</h3>

        @if (auth()->user()->role === 'kepala')
            <a href="{{ route('piket-harian.pengaturan') }}" class="btn btn-primary">
                ⚙️ Pengaturan Kuota
            </a>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-5" id="calendar"></div>
        @include('components.piket-modal')

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth',
                locale: 'id',
                height: 'auto',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },

                events: '/piket-harian/events',

                dateClick: function(info) {
                    openPiketModal(info.dateStr);
                },

                eventContent: function(arg) {

                    const title = String(arg.event.title || '').trim();
                    const sisa = arg.event.extendedProps?.sisa_kuota;

                    const sisaNum = Number(sisa);

                    const color =
                        sisaNum === 0 ? '#dc3545' :
                        sisaNum <= 1 ? '#ffc107' :
                        '#198754';

                    return {
                        html: `
                    <div style="
                        padding:4px;
                        font-size:11px;
                        line-height:1.3;
                        border-left:3px solid ${color};
                    ">
                        <div style="font-weight:600;">
                            ${title}
                        </div>
                        <div style="opacity:0.85;">
                            Personel Tersedia: ${sisaNum} ORANG
                        </div>
                    </div>
                `
                    };
                }

            });

            calendar.render();
        });

        function openPiketModal(dateStr) {
            document.getElementById('tanggal_piket').value = dateStr;

            const modal = new bootstrap.Modal(document.getElementById('piketModal'));
            modal.show();
        }
    </script>
@endsection
