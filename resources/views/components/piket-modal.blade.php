<div id="piketModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Ajukan Piket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('piket.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <label>Tanggal Piket</label>
                    <input type="date" name="tanggal_piket" id="tanggal_piket" class="form-control" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-primary" type="submit">
                        Ajukan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
