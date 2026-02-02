            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold mb-0">Tugas Hari Ini</h5>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center" role="search">
                        <input type="text" name="search" class="form-control me-2" style="min-width: 250px;"
                            placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                    </form>

                    @if (Auth::user()->isKepalaUnit())
                        <a href="{{ route('unit.pegawai', auth()->user()->employmentDetail?->unit?->id) }}"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-users me-1"></i> Lihat Anggota Unit
                        </a>
                    @endif
                </div>
            </div>
