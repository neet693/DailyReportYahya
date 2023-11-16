<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

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
