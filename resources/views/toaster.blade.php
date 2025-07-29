<!-- Toast Container (bisa diatur posisinya) -->
<div class="toast-container position-fixed start-0 p-3" style="z-index: 9999; bottom:90px;">
    @foreach ($announcements as $announcement)
        <div class="toast announcement-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true"
            data-bs-delay="10000">
            <div class="toast-header bg-info text-white">
                <strong class="me-auto">{{ $announcement->title }}</strong>
                <small class="text-light ms-2">{{ $announcement->created_at->diffForHumans() }}</small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {!! $announcement->message ?? 'Tidak ada Pengumuman' !!}
            </div>
        </div>
    @endforeach

    @auth
        @if ($agendas->isEmpty())
            <div class="toast agenda-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true"
                data-bs-delay="10000">
                <div class="toast-header bg-danger text-white">
                    <strong class="me-auto">Agenda</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <p>{{ auth()->user()->name }} tidak tergabung dalam agenda apapun saat ini.</p>
                </div>
            </div>
        @else
            <div class="toast agenda-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true"
                data-bs-delay="10000">
                <div class="toast-header bg-warning text-dark">
                    <strong class="me-auto">Agenda</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <p>{{ auth()->user()->name }} tergabung dalam agenda:</p>
                    <ul>
                        @foreach ($agendas as $agenda)
                            <li>
                                <strong>{{ $agenda->title }}</strong>
                                – <a href="{{ route('agendas.show', $agenda->id) }}" class="text-decoration-underline">Lihat
                                    progress agenda</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    @endauth
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah toast sudah ditampilkan sebelumnya dalam sesi browser ini
        if (!sessionStorage.getItem('toastsShown')) {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.forEach(function(toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            });

            // Tandai bahwa toast sudah ditampilkan agar tidak tampil lagi saat refresh
            sessionStorage.setItem('toastsShown', 'true');
        }
    });
</script>
