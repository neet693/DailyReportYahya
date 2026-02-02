 {{-- Tombol tambah tugas & jadwal tetap --}}
 <div class="hstack gap-2">
     @if ($data->id == auth()->id())
         <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary btn-sm flex-fill">
             <i class="bi bi-plus-circle"></i> Tugas Harian
         </a>
         <a href="{{ route('fixed-schedule.create') }}" class="btn btn-outline-success btn-sm flex-fill">
             <i class="bi bi-calendar-plus"></i> Jadwal Tetap
         </a>
     @elseif (Auth::user()->isKepalaUnit() || Auth::user()->isAdmin())
         <a href="{{ route('fixed-schedule.create', ['user_id' => $data->id]) }}"
             class="btn btn-outline-success btn-sm flex-fill">
             <i class="bi bi-calendar-plus"></i> Jadwal Tetap
         </a>
     @endif
 </div>
