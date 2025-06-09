@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .card-mini {
            padding: 1rem !important;
            font-size: 0.9rem;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.25s ease-in-out;
        }

        .card h6 {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .card span {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h4 mb-0 text-gray-800">Dashboard HRD</h1>
        </div>

        <a href="{{ route('home') }}?lihat_home=1" class="btn btn-outline-primary mb-3">
            🔁 Lihat Task Harian (Home)
        </a>

        <a href="{{ route('pegawai.create') }}" class="btn btn-outline-primary mb-3">
            ➕ Tambah Pegawai Manual
        </a>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#importModal">
            Import Users
        </button>

        <!-- Import Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import Data Pengguna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <a class="btn btn-primary"
                                    href="{{ asset('attachments/1748918910_Akun Pegawai Yahya.xlsx') }}"
                                    download="template-import.xlsx" target="_blank"><i
                                        class="bi bi-file-earmark-x-fill"></i> Unduh Template
                                </a>
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih file Excel</label>
                                <input class="form-control" type="file" name="file" id="file" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notification -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Data pengguna berhasil diimpor!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>




        <div class="row g-3">
            @foreach ($units as $unit)
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <a href="{{ route('unit.pegawai', $unit->id) }}"
                        class="card card-border-primary rounded-3 card-mini card-hover h-100 text-decoration-none">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="icon-shape bg-light-primary rounded-circle">
                                <i class="bi bi-building text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark">{{ $unit->name }}</h6>
                                <span>{{ $pegawaiCounts[$unit->id] ?? 0 }} Pegawai</span> <br>
                                <span>Laki-laki: {{ $lkPerUnit[$unit->id] ?? 0 }}</span>
                                <span>Perempuan: {{ $prPerUnit[$unit->id] ?? 0 }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

            <div class="col-xl-3 col-md-4 col-sm-6">
                <a href="#!" class="card card-border-info rounded-3 card-mini card-hover h-100 text-decoration-none">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-shape bg-light-info rounded-circle">
                            <i class="fas fa-mars text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark">Laki-Laki</h6>
                            <span>{{ $lk }}</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6">
                <a href="#!" class="card card-border-danger rounded-3 card-mini card-hover h-100 text-decoration-none">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-shape bg-light-danger rounded-circle">
                            <i class="fas fa-venus text-danger"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark">Perempuan</h6>
                            <span>{{ $pr }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tutup modal dulu
                var modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
                if (modal) {
                    modal.hide();
                }

                // Lalu tampilkan toast
                var toastLiveExample = document.getElementById('successToast');
                var toast = new bootstrap.Toast(toastLiveExample);
                toast.show();
            });
        </script>
    @endif
@endsection
