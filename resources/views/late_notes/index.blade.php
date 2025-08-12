@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Daftar Keterlambatan</h4>
            <a href="{{ route('keterlambatan.create') }}" class="btn btn-success">+ Tambah</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                @if ($keterlambatan->isEmpty())
                    <div class="p-4 text-center text-muted">
                        Belum ada data keterlambatan.
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Informasi</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keterlambatan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item->user->name ?? 'User tidak ditemukan' }}</strong> <br>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_terlambat)->format('d M Y') }}</small>
                                            <br>
                                            <p class="mb-0">{{ $item->alasan }}</p>
                                        </td>
                                        <td>
                                            @if ($item->foto)
                                                <img src="{{ asset($item->foto) }}" alt="foto keterlambatan" width="60"
                                                    class="img-thumbnail cursor-pointer" data-bs-toggle="modal"
                                                    data-bs-target="#fotoModal" data-src="{{ asset($item->foto) }}"
                                                    style="cursor: zoom-in;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('keterlambatan.show', $item->slug) }}"
                                                class="btn btn-sm btn-outline-primary">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Preview Foto --}}
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body p-0 text-center">
                    <img src="" id="modalImage" class="img-fluid rounded" alt="Preview Foto">
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk modal preview foto --}}
    <script>
        const fotoModal = document.getElementById('fotoModal');
        const modalImage = document.getElementById('modalImage');

        fotoModal.addEventListener('show.bs.modal', function(event) {
            const img = event.relatedTarget; // gambar yang diklik
            const src = img.getAttribute('data-src');
            modalImage.src = src;
        });

        // Clear src saat modal ditutup supaya tidak ada gambar lama tersisa
        fotoModal.addEventListener('hidden.bs.modal', function() {
            modalImage.src = '';
        });
    </script>
@endsection
