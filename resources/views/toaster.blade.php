{{-- <div class="position-fixed bottom-0 start-0 p-3" style="z-index: 5">
    <div id="announcement-toast" class="toast">
        <div class="toast-header">
            <strong class="me-auto">{{ $announcements->title ?? 'Pengumuman' }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ $announcements->message ?? 'Tidak ada pengumuman' }}
        </div>
    </div>
</div> --}}
@if (count($announcements) > 0)
    <div id="announcements">
        @foreach ($announcements as $announcement)
            <div class="alert alert-info alert-dismissible announcement" style="position: relative;">
                <button type="button" class="btn-close me-2 m-auto" data-dismiss="alert" aria-label="Close"
                    style="position: absolute; top: 50; right: 0;">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ $announcement->title }} - {{ $announcement->message ?? 'Tidak ada Pengumuman' }}
            </div>
        @endforeach
    </div>
@endif

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
