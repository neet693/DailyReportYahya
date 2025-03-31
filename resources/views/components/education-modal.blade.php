<div class="modal fade" id="addEducationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('education.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Gelar</label>
                        <input type="text" name="degree" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Institusi</label>
                        <input type="text" name="institution" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Lulus</label>
                        <input type="number" name="year_of_graduation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
