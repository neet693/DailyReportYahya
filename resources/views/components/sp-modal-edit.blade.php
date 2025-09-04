<!-- Modal Edit SP -->
<div class="modal fade" id="modalEditSP{{ $sp->id }}" tabindex="-1"
    aria-labelledby="modalEditSPLabel{{ $sp->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('surat-peringatan.update', $sp->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditSPLabel{{ $sp->id }}">Edit Surat Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="employee_number" value="{{ $user->employmentDetail->employee_number }}">

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" value="{{ $sp->judul }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="mulai_berlaku" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="mulai_berlaku"
                            value="{{ $sp->mulai_berlaku->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="berakhir_berlaku" class="form-label">Tanggal Berakhir</label>
                        <input type="date" class="form-control" name="berakhir_berlaku"
                            value="{{ $sp->berakhir_berlaku->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan</label>
                        <textarea class="form-control" name="alasan" rows="3" required>{{ $sp->alasan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
