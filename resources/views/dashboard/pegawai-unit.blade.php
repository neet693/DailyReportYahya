@extends('layouts.app')

@section('content')
    <style>
        .profile-card-1 {
            font-family: "Open Sans", Arial, sans-serif;
            position: relative;
            width: 100%;
            color: #ffffff;
            text-align: center;
            height: 300px;
            /* Ramping */
            border-radius: 20px;
            background-color: #2b98ff;
            /* Biru soft */
            padding: 20px 10px;
            box-shadow: 0 0.15rem 0.6rem rgba(0, 0, 0, 0.1);
            /* Lembut */
        }

        .profile-card-1 .profile {
            position: relative;
            max-width: 120px;
            /* Diperbesar */
            width: 100%;
            height: auto;
            margin: 0 auto 15px auto;
            border-radius: 12px;
            /* Tidak bulat */
            object-fit: cover;
        }

        .profile-card-1 .card-content {
            width: 100%;
            padding: 0 10px;
            position: relative;
            top: 0;
        }

        .profile-card-1 h2 {
            margin: 5px 0;
            font-weight: 600;
            font-size: 18px;
        }

        .profile-card-1 h2 small {
            display: block;
            font-size: 13px;
            margin-top: 5px;
        }

        .profile-card-1 i {
            font-size: 14px;
            color: #ffffff;
            border: 1px solid #fff;
            width: 28px;
            height: 28px;
            line-height: 28px;
            border-radius: 50%;
            margin: 0 3px;
        }

        .profile-card-1 .icon-block {
            margin-top: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <div class="container-fluid">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8 col-lg-6 mb-3">
                    <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s"
                        style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        <h3>List Pegawai <span>Sekolah Kristen Yahya</span></h3>
                        <div class="line"></div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">Kembali</span>
                </a>
            </div>

            <div class="row">
                @if ($pegawai->isNotEmpty())
                    @foreach ($pegawai as $p)
                        <div class="col-12 col-sm-6 col-lg-3 mb-4">
                            <div class="profile-card-1">
                                <img src="{{ $p->profile_image ? asset('profile_images/' . $p->profile_image) : asset('asset/default_profile.jpg') }}"
                                    alt="Foto Profil" class="profile" />

                                <div class="card-content">
                                    <h2>{{ $p->name }}
                                        <small>Unit {{ $p->employmentDetail?->unit?->name ?? '-' }}</small>
                                        <small>{{ $p->jobdesk?->title ?? 'Belum ada jobdesk' }}</small>

                                    </h2>
                                    <div class="icon-block d-flex justify-content-center">
                                        <a href="{{ route('employment-detail.show', $p->employmentDetail) }}">
                                            <i class="fa-solid fa-address-card"></i>
                                        </a>
                                        @if (auth()->user()->role !== 'pegawai')
                                            <a href="{{ route('employment-detail.cetak', $p->employmentDetail) }}"
                                                title="Cetak">
                                                <i class="fa-solid fa-print"></i>
                                            </a>

                                            <form action="{{ route('users.destroy', $p->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?')"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Pengguna"
                                                    style="background: none; border: none; padding: 0; cursor: pointer;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>
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
