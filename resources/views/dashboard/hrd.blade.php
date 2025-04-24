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
                                <span>{{ $pegawaiCounts[$unit->id] ?? 0 }} Pegawai</span>
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
@endsection
