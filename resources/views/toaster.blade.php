@if (count($announcements) > 0)
    <div id="announcements">
        @foreach ($announcements as $announcement)
            @if (
                $announcement->category === 'umum' ||
                    ($announcement->category === 'personal' && $announcement->recipient_id === auth()->user()->id))
                <div class="alert alert-danger alert-dismissible announcement" style="position: relative;">
                    <button type="button" class="btn-close me-2 m-auto" data-dismiss="alert" aria-label="Close"
                        style="position: absolute; top: 50; right: 0;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ $announcement->title }} <br> {!! $announcement->message ?? 'Tidak ada Pengumuman' !!}
                </div>
            @endif
        @endforeach
    </div>
@endif

@if (count($agendas) > 0)
    <div id="agenda_executor_announcement" class="alert alert-danger alert-dismissible executor"
        style="position: relative; display: none;">
        <button type="button" class="btn-close me-2 m-auto" data-dismiss="alert" aria-label="Close"
            style="position: absolute; top: 50; right: 0;">
            <span aria-hidden="true">&times;</span>
        </button>
        Anda tergabung dalam agenda:
        @foreach ($agendas as $agenda)
            @foreach ($agenda->executors as $executor)
                @if ($executor->id === auth()->user()->id)
                    {{ $agenda->title }}
                @endif
            @endforeach
        @endforeach
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agendaExecutorAnnouncement = document.getElementById('agenda_executor_announcement');
        if (agendaExecutorAnnouncement) {
            agendaExecutorAnnouncement.style.display = 'block';

            agendaExecutorAnnouncement.querySelector('.btn-close').addEventListener('click', function() {
                agendaExecutorAnnouncement.style.display = 'none';
            });
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const announcements = document.querySelectorAll('.announcement');

        announcements.forEach(function(announcement) {
            announcement.querySelector('.btn-close').addEventListener('click', function() {
                announcement.style.display = 'none';
            });
        });
    });
</script>
