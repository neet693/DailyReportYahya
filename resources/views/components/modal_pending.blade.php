<div class="modal fade" id="penugasanPendingModal{{ $assignment->id }}" tabindex="-1"
    aria-labelledby="penugasanPendingModalLabel{{ $assignment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('assignments.markAsPending', $assignment) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="penugasanPendingModalLabel{{ $assignment->id }}">
                        Laporkan Kendala
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kendala">Kendala:</label>
                        <textarea id="kendala" name="kendala" class="form-control @error('kendala') is-invalid @enderror" rows="4"
                            autofocus></textarea>

                        @error('kendala')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
