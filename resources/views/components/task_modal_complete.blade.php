<!-- Modal -->
<div class="modal fade" id="completeModal{{ $task->id }}" tabindex="-1"
    aria-labelledby="completeModalLabel{{ $task->id }}"aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="completeModalLabel{{ $task->id }}">
                    Selesaikan Task</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tasks.markAsComplete', $task) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            Apakah Anda Yakin?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
