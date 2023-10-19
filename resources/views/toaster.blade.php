<div class="position-fixed bottom-0 start-0 p-3" style="z-index: 5">
    <div id="announcement-toast" class="toast">
        <div class="toast-header">
            <strong class="me-auto">{{ $announcements->title ?? 'Pengumuman' }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ $announcements->message ?? 'Tidak ada pengumuman' }}
        </div>
    </div>
</div>
