<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

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
