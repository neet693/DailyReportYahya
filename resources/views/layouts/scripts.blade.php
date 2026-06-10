<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5.3.8 Bundle (SUDAH TERMASUK POPPER) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
</script>

<!-- FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Trix -->
<script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

<!-- Datatables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Pusher -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<!-- Laravel Echo -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

{{-- ========================================= --}}
{{-- SIDEBAR --}}
{{-- ========================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');

        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            sidebar?.classList.toggle('collapsed');
            main?.classList.toggle('expanded');
        });

        document.getElementById('mobileToggle')?.addEventListener('click', () => {
            sidebar?.classList.toggle('show');
            document.getElementById('sidebarOverlay')
                ?.classList.toggle('show');
        });

        document.getElementById('sidebarOverlay')?.addEventListener('click', () => {
            sidebar?.classList.remove('show');
            document.getElementById('sidebarOverlay')
                ?.classList.remove('show');
        });

    });
</script>

{{-- ========================================= --}}
{{-- DARK MODE --}}
{{-- ========================================= --}}

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        if (!themeToggleBtn || !themeIcon) return;

        const htmlElement = document.documentElement;

        const savedTheme =
            localStorage.getItem('bsTheme') || 'light';

        htmlElement.setAttribute('data-bs-theme', savedTheme);

        themeIcon.className =
            savedTheme === 'dark' ?
            'bi bi-sun-fill' :
            'bi bi-moon-fill';

        themeToggleBtn.addEventListener('click', (e) => {

            e.preventDefault();

            const currentTheme =
                htmlElement.getAttribute('data-bs-theme');

            const newTheme =
                currentTheme === 'dark' ?
                'light' :
                'dark';

            htmlElement.setAttribute('data-bs-theme', newTheme);

            localStorage.setItem('bsTheme', newTheme);

            themeIcon.className =
                newTheme === 'dark' ?
                'bi bi-sun-fill' :
                'bi bi-moon-fill';
        });

    });
</script>

{{-- ========================================= --}}
{{-- PUSHER + ECHO --}}
{{-- ========================================= --}}

<script>
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: "{{ config('broadcasting.connections.pusher.key') }}",
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: true,
        encrypted: true,
        wsPort: 443,
        wssPort: 443,
        enabledTransports: ['ws', 'wss'],
        disableStats: true
    });
</script>

{{-- ========================================= --}}
{{-- DATATABLES --}}
{{-- ========================================= --}}

<script>
    $(function() {

        if ($('#myTable').length) {
            $('#myTable').DataTable({
                pageLength: 5,
                order: [
                    [2, 'asc']
                ]
            });
        }

        if ($('#absensiTable').length) {
            $('#absensiTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [2, 'asc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        }

    });
</script>

{{-- ========================================= --}}
{{-- SELECT2 --}}
{{-- ========================================= --}}

<script>
    $(function() {

        if ($('#participant_id').length) {
            $('#participant_id').select2({
                theme: 'bootstrap-5',
                placeholder: $('#participant_id').data('placeholder'),
                width: '100%'
            });
        }

        if ($('#recipient_id').length) {
            $('#recipient_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih penerima',
                allowClear: true
            });
        }

        if ($('#unit_id').length) {
            $('#unit_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Unit',
                allowClear: true
            });
        }

        if ($('#executors').length) {
            $('#executors').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih executor',
                allowClear: true
            });
        }

        if ($('#pegawai').length) {
            $('#pegawai').select2({
                placeholder: 'Pilih pegawai',
                allowClear: true
            });
        }

    });
</script>

{{-- ========================================= --}}
{{-- TOGGLE TASK --}}
{{-- ========================================= --}}

<script>
    document.addEventListener("DOMContentLoaded", () => {

        document.querySelectorAll('.toggle-tasks').forEach((btn) => {

            btn.addEventListener('click', function() {

                const moreTasks = this.nextElementSibling;

                if (!moreTasks) return;

                const totalExtraTasks =
                    moreTasks.children.length;

                moreTasks.classList.toggle('d-none');

                this.innerHTML = moreTasks.classList.contains('d-none') ?
                    `
                Lihat tugas lainnya
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    ${totalExtraTasks}
                </span>
                ` :
                    `
                Tutup tugas lainnya
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    ${totalExtraTasks}
                </span>
                `;
            });

        });

    });
</script>

{{-- ========================================= --}}
{{-- FOOTER + TOOLTIP --}}
{{-- ========================================= --}}

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const currentYear =
            document.getElementById("currentYear");

        if (currentYear) {
            currentYear.textContent =
                new Date().getFullYear();
        }

        const tooltipTriggerList =
            document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'
            );

        [...tooltipTriggerList].forEach(el => {
            new bootstrap.Tooltip(el);
        });

    });
</script>

{{-- ========================================= --}}
{{-- TRIX FILE UPLOAD --}}
{{-- ========================================= --}}

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

                console.error(error);

                alert(
                    "Terjadi kesalahan saat mengunggah file."
                );

            });
    }
</script>

{{-- ========================================= --}}
{{-- LOGOUT --}}
{{-- ========================================= --}}

<script>
    function handleLogout() {

        sessionStorage.removeItem('toastsShown');

        document
            .getElementById('logout-form')
            ?.submit();
    }
</script>

{{-- END SCRIPTS --}}
