<!-- Memuat jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Kemudian memuat skrip lain, termasuk inisialisasi DataTables -->
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<!-- Kemudian inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [
                [2, 'asc']
            ]
        });
    });
</script>
