<div class="modal fade" id="completeModal{{ $task->id }}" style="z-index: 1060 !important;" tabindex="-1"
    aria-labelledby="completeModal{{ $task->id }}Label" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModal{{ $task->id }}Label">Selesaikan Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.markAsComplete', $task) }}">
                @csrf
                <div class="modal-body">
                    Apakah Anda yakin ingin menandai task "{{ $task->title }}" selesai?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Selesaikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
