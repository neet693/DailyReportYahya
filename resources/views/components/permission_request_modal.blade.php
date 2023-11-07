<!-- Modal -->
<div class="modal fade" id="exampleModal{{ $data->id }}" tabindex="-1"
    aria-labelledby="exampleModalLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Laporan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Nama: {{ $permissionRequests->nama }}</p>
                <p>Jabatan: {{ $permissionRequests->jabatan }}</p>
                <p>Status Pegawai: {{ $permissionRequests->jenis_pegawai }}</p>
                <p>Memohon ijin tidak bekerja selama: {{ $daysDifference[$permissionRequests->id] }} hari</p>
                <p>Hari / Tanggal: <br>{{ $permissionRequests->start_date->format('l, d F Y') }} s/d
                    {{ $permissionRequests->end_date->format('l, d F Y') }} </p>
                <p>Keterangan: <br> {{ $permissionRequests->description }}</p>
                <p>Status Permohonan:
                    @if ($permissionRequests->status_permohonan == 'Disetujui')
                        <span class="badge text-bg-success">{{ $permissionRequests->status_permohonan }}</span>
                    @else
                        <span class="badge text-bg-danger">{{ $permissionRequests->status_permohonan }}</span>
                    @endif
                </p>

            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <p>Yang Memberi Izin:</p>
                    <p>{{ $permissionRequests->approver ? $permissionRequests->approver->name : 'Under Review' }}</p>
                </div>
                <div>
                    <p>Pemohon Izin:</p>
                    <p>{{ $permissionRequests->nama }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
