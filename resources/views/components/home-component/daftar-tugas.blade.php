                <div class="row g-3">
                    @foreach ($usersWithTasks as $data)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="p-3 rounded shadow-sm" style="background-color: #f4f8e1;">
                                @include('components.home-component.daftar-tugas-header')
                                @include('components.home-component.daftar-tugas-tombol')


                                {{-- Konten tugas --}}
                                @if ($data->tasks->isNotEmpty() || $data->fixedSchedules->isNotEmpty() || $data->assignments->isNotEmpty())
                                    {{-- Tombol Pemicu --}}
                                    <div class="hstack gap-3">
                                        <button class="btn btn-sm btn-outline-dark flex-fill fw-semibold mt-2"
                                            type="button"
                                            onclick="document.getElementById('taskPopup{{ $data->id }}').style.display='flex'">
                                            📋 Lihat Semua Tugas
                                            (
                                            {{ $data->tasks->count() +
                                                $data->fixedSchedules->count() +
                                                $data->assignments->whereBetween('assignment_date', [now()->startOfMonth(), now()->endOfMonth()])->count() }}
                                            )
                                        </button>
                                    </div>
                                    {{-- Popup Card --}}
                                    <div id="taskPopup{{ $data->id }}"
                                        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;background:rgba(0,0,0,0.5); z-index:1050; justify-content:center; align-items:center;">

                                        <div
                                            style="background:#fff; width:90%; max-width:800px; border-radius:1rem;box-shadow:0 5px 25px rgba(0,0,0,0.2); display:flex; flex-direction:column;max-height:90vh;">

                                            {{-- HEADER --}}
                                            <div
                                                style="background:#0d6efd; color:#fff; border-top-left-radius:1rem; border-top-right-radius:1rem;padding:1rem; display:flex; justify-content:space-between; align-items:center;">
                                                <h5 style="margin:0; font-weight:bold;">📋 Daftar Tugas -
                                                    {{ $data->name }}</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    onclick="document.getElementById('taskPopup{{ $data->id }}').style.display='none'"></button>
                                            </div>

                                            {{-- BODY --}}
                                            <div style="padding:1.5rem; overflow-y:auto; flex:1;">
                                                @include('components.home-component.isi-tugas-harian')
                                                @include('components.home-component.isi-jadwal-tetap')
                                                @include('components.home-component.isi-penugasan')
                                            </div>

                                            {{-- FOOTER --}}
                                            <div style="padding:1rem; border-top:1px solid #eee; text-align:right;">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                    onclick="document.getElementById('taskPopup{{ $data->id }}').style.display='none'">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <small class="text-muted">Belum membuat tugas hari ini.</small>
                                @endif
                            </div>
                        </div>

                        {{-- Modal untuk tiap task --}}
                        @foreach ($data->tasks as $task)
                            @include('components.task_modal_complete', ['task' => $task])
                            @include('components.task_modal_pending', ['task' => $task])
                        @endforeach
                    @endforeach
                </div>
