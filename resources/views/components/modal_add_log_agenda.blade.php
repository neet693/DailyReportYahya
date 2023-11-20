        <!-- Modal Tambah Log Agenda -->
        <div class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLogModalLabel">Tambah Log Agenda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Tambah Log Agenda -->
                        <form action="{{ route('logAgendas.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="agenda_id" value="{{ $agendaId }}">
                            <input type="hidden" name="executor_id" value="{{ auth()->id() }}">

                            <div class="mb-3">
                                <label for="log_detail" class="form-label">Detail Log</label>
                                <input id="log_detail" type="hidden" name="log_detail">
                                <trix-editor input="log_detail"></trix-editor>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Log</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
