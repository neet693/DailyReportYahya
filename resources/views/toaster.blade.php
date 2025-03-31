<div id="announcements">
    @foreach ($announcements as $announcement)
        <div class="alert alert-danger alert-dismissible announcement" style="position: relative;">
            <button type="button" class="btn-close me-2 m-auto" data-dismiss="alert" aria-label="Close"
                style="position: absolute; top: 50; right: 0;">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ $announcement->title }} <br> {!! $announcement->message ?? 'Tidak ada Pengumuman' !!}
        </div>
    @endforeach
</div>



<div id="agenda_executor_announcement" class="alert alert-danger alert-dismissible executor">
    <button type="button" class="btn-close me-2 m-auto" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    @auth
        @if ($agendas = auth()->user()->agendas)
            <p>{{ auth()->user()->name }} tergabung dalam agenda:</p>
            <ul>
                @foreach ($agendas as $agenda)
                    <li><strong>{{ $agenda->title }}</strong></li>
                @endforeach
            </ul>
        @else
            <p>{{ auth()->user()->name }} tidak tergabung dalam agenda apapun saat ini.</p>
        @endif
    @endauth
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agendaExecutorAnnouncement = document.getElementById('agenda_executor_announcement');
        if (agendaExecutorAnnouncement) {
            agendaExecutorAnnouncement.style.display = 'block';

            // Sembunyikan setelah 10 detik
            setTimeout(function() {
                agendaExecutorAnnouncement.style.display = 'none';
            }, 10000);

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
            // Sembunyikan setelah 10 detik
            setTimeout(function() {
                announcement.style.display = 'none';
            }, 10000);

            announcement.querySelector('.btn-close').addEventListener('click', function() {
                announcement.style.display = 'none';
            });
        });
    });
</script>
