@extends('layouts.app')

@section('content')
    <style>
        .profile-card {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            min-height: 120px;
            z-index: 0;
        }

        .profile-card.inactive {
            opacity: 0.6;
            border: 1px dashed #dc3545;
        }

        .profile-img {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 15px;
        }

        .badge-inactive {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            font-size: 11px;
            border-radius: 6px;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .profile-info {
            flex-grow: 1;
            z-index: 1;
        }

        .profile-info h6 {
            margin-bottom: 2px;
            font-size: 11px;
        }

        .profile-info small {
            color: #6c757d;
            font-size: 10px;
        }

        .profile-actions {
            margin-top: 6px;
        }

        .profile-actions a,
        .profile-actions form {
            display: inline-block;
            margin-right: 6px;
        }

        .profile-actions i {
            font-size: 13px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <div class="container-fluid">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Daftar Pegawai</h4>
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="row">
                @if ($pegawai->isNotEmpty())
                    @foreach ($pegawai as $p)
                        @if (Auth::user()->isAdminOrHRD() || $p->employmentDetail->is_active)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                                <div
                                    class="profile-card {{ optional($p->employmentDetail)->is_active == false ? 'inactive' : '' }}">
                                    <img src="{{ $p->profile_image ? asset('profile_images/' . $p->profile_image) : asset('asset/default_profile.jpg') }}"
                                        class="profile-img" alt="Foto Profil">

                                    <div class="profile-info">
                                        <h6 class="fw-bold mb-0">{{ $p->name }}</h6>
                                        <small>{{ $p->employmentDetail?->unit?->name ?? '-' }} |
                                            {{ $p->jobdesk?->title ?? 'Belum ada jobdesk' }}</small>
                                        @if (optional($p->employmentDetail)->is_active == false)
                                            <div class="badge-inactive">Tidak Aktif</div>
                                        @endif
                                        <div class="profile-actions">
                                            <a href="{{ route('employment-detail.show', $p->employmentDetail) }}"
                                                title="Detail">
                                                <i class="fa-solid fa-address-card"></i>
                                            </a>
                                            <a href="{{ route('employment-detail.cetak', $p->employmentDetail) }}"
                                                title="Cetak">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                            @can('delete', $p)
                                                <form action="{{ route('users.destroy', $p->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 text-danger" title="Hapus">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <h6>Tidak Ada Data Pegawai</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
