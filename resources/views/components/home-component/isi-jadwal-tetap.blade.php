{{-- Jadwal Tetap --}}
@if ($data->fixedSchedules->isNotEmpty())
    <h6 class="fw-bold text-dark mt-4 mb-3">
        <i class="bi bi-calendar-event text-success"></i> Jadwal Tetap
    </h6>

    @php
        $daysIndo = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];
    @endphp

    <div class="d-flex flex-wrap gap-3" style="max-height: 350px; overflow-y: auto;">
        @foreach ($data->fixedSchedules as $fixed)
            <div class="card border-0 shadow-sm rounded-3 position-relative" style="flex: 0 0 48%;">
                <div class="card-body p-3">
                    {{-- Tombol Edit & Hapus --}}
                    @if (Auth::user()->isKepalaUnit() || Auth::id() == $fixed->user_id)
                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                            <a href="{{ route('fixed-schedule.edit', $fixed->id) }}"
                                class="btn btn-sm btn-outline-primary" title="Edit Jadwal">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('fixed-schedule.destroy', $fixed->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Jadwal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif

                    <h6 class="fw-semibold mb-1">
                        {{ $fixed->subject ?? ucfirst($fixed->type) }}
                    </h6>

                    {{-- Hari + Jam --}}
                    <small class="text-muted d-block">
                        <i class="bi bi-calendar-event"></i>
                        {{ $daysIndo[strtolower($fixed->day_of_week)] ?? ucfirst($fixed->day_of_week) }}
                        |
                        {{ \Carbon\Carbon::parse($fixed->start_time)->format('H:i') }}
                        -
                        {{ \Carbon\Carbon::parse($fixed->end_time)->format('H:i') }}
                    </small>

                    {{-- Lokasi --}}
                    <small class="text-muted d-block">
                        <i class="bi bi-geo-alt"></i>
                        {{ $fixed->classroom ?? '-' }}
                    </small>

                    {{-- Deskripsi --}}
                    @if ($fixed->description)
                        <small class="text-muted d-block">
                            <i class="bi bi-info-circle"></i>
                            {{ \Illuminate\Support\Str::limit($fixed->description, 60) }}
                        </small>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
