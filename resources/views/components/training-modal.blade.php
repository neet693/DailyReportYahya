<div class="modal fade" id="addTrainingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Diklat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('training.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="training_name" class="form-label">Nama Diklat</label>
                    <input type="text" name="training_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="organizer" class="form-label">Penyelenggara</label>
                    <input type="text" name="organizer" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="training_date" class="form-label">Tanggal Dikeluarkan</label>
                    <input type="date" name="training_date" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="training_expiry" class="form-label">Tanggal Berakhir</label>
                    <input type="date" name="training_expiry" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="certificate_number" class="form-label">Nomor Kredensial Sertifikat</label>
                    <input type="text" name="certificate_number" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="certificate_url" class="form-label">URL Sertifikat</label>
                    <input type="url" name="certificate_url" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="certificate_file" class="form-label">Upload Media</label>
                    <input type="file" name="certificate_file" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
