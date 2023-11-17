<div class="modal fade" id="permissionRequests{{ $data->id }}" tabindex="-1"
    aria-labelledby="permissionRequests{{ $data->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="permissionRequests{{ $data->id }}Label">Detail Laporan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Nama: {{ $data->nama }}</p>
                <p>Jabatan: {{ $data->jabatan }}</p>
                <p>Status Pegawai: {{ $data->jenis_pegawai }}</p>
                <p>Memohon izin tidak bekerja selama:
                    {{ $durations[$data->id]['days'] }} hari,
                    {{ $durations[$data->id]['hours'] }} jam,
                    {{ $durations[$data->id]['minutes'] }} menit
                </p>
                <p>Hari / Tanggal: <br>{{ $data->start_date->format('l, d F Y') }} s/d
                    {{ $data->end_date->format('l, d F Y') }} </p>
                <p>Keterangan: <br> {{ $data->description }}</p>
                <p>Status Permohonan:
                    @if ($data->status_permohonan == 'Disetujui')
                        <span class="badge text-bg-success">{{ $data->status_permohonan }}</span>
                    @else
                        <span class="badge text-bg-danger">{{ $data->status_permohonan }}</span>
                    @endif
                </p>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <p>Yang Memberi Izin:</p>
                    <p>{{ $data->approver ? $data->approver->name : 'Under Review' }}</p>
                </div>
                <div>
                    <p>Pemohon Izin:</p>
                    <p>{{ $data->nama }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
