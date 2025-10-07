<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

<script>
    // pastikan variabel env dari Laravel
    const pusherKey = "{{ env('PUSHER_APP_KEY') }}";
    const pusherCluster = "{{ env('PUSHER_APP_CLUSTER') }}";

    // Inisialisasi Pusher
    window.Pusher = Pusher;

    // Inisialisasi Laravel Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: pusherCluster,
        forceTLS: true,
        encrypted: true,
        wsHost: `ws-${pusherCluster}.pusher.com`,
        wsPort: 443,
        wssPort: 443,
        enabledTransports: ['ws', 'wss'],
        disableStats: true,
        logToConsole: true // tambahkan agar bisa lihat koneksi di console
    });
</script>


<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "pageLength": 5,
            "order": [
                [2, 'asc']
            ]
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#participant_id').select2({
            theme: 'bootstrap-5',
            placeholder: $('#participant_id').data('placeholder'),
            width: '100%'
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#recipient_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih penerima',
            allowClear: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#unit_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Unit',
            allowClear: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#executors').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih executor",
            allowClear: true
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.toggle-tasks').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const moreTasks = this.nextElementSibling;
                const totalExtraTasks = moreTasks.children.length;

                moreTasks.classList.toggle('d-none');

                if (moreTasks.classList.contains('d-none')) {
                    this.innerHTML = `
                        Lihat tugas lainnya
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            ${totalExtraTasks}
                        </span>
                    `;
                } else {
                    this.innerHTML = `
                        Tutup tugas lainnya
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            ${totalExtraTasks}
                        </span>
                    `;
                }
            });
        });
    });
</script>

<!-- JS: Tahun Otomatis & Tooltip Aktif -->
<script>
    document.getElementById("currentYear").textContent = new Date().getFullYear();
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
</script>



{{-- Script untuk upload file rapat trix --}}
<script>
    document.addEventListener("trix-attachment-add", function(event) {
        const attachment = event.attachment;

        if (attachment.file) {
            uploadAttachment(attachment);
        }
    });

    function uploadAttachment(attachment) {
        const file = attachment.file;
        const formData = new FormData();
        formData.append("attachment", file);

        fetch("{{ route('meetings.uploadAttachment') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.url) {
                    attachment.setAttributes({
                        url: result.url,
                        href: result.url
                    });
                } else {
                    alert("Upload gagal!");
                }
            })
            .catch(error => {
                console.error("Upload error:", error);
                alert("Terjadi kesalahan saat mengunggah file.");
            });
    }
</script>

<script>
    function handleLogout() {
        sessionStorage.removeItem('toastsShown'); // Hapus flag
        document.getElementById('logout-form').submit(); // Submit form logout
    }
</script>


<script>
    $(document).ready(function() {
        $('#absensiTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [
                [2, 'asc']
            ], // urut default berdasarkan tanggal
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            }
        });
        // Select2 untuk pegawai
        $('#pegawai').select2({
            placeholder: "Pilih pegawai",
            allowClear: true
        });
    });
</script>

{{-- End Script --}}
