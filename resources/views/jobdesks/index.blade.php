@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold mb-0">📋 Daftar Jobdesk Pegawai</h5>
            @if (auth()->user()->isAdmin() || auth()->user()->isKepalaUnit())
                <a href="{{ route('jobdesks.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jobdesk
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($Jobdesks->isEmpty())
            <div class="alert alert-warning">
                <i class="bi bi-info-circle me-2"></i> Belum ada data jobdesk.
            </div>
        @else
            <div class="row g-3">
                @foreach ($Jobdesks as $jobdesk)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="p-3 rounded shadow-sm" style="background-color: #f4f8e1;">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $jobdesk->user->profile_image ? asset('profile_images/' . $jobdesk->user->profile_image) : asset('asset/default_profile.jpg') }}"
                                    class="rounded-circle" width="48" height="48" alt="Foto">
                                <div class="ms-2">
                                    <div class="fw-semibold mb-0">{{ $jobdesk->user->name }}</div>
                                    <small class="text-muted">{{ $jobdesk->title }}</small>
                                </div>
                            </div>

                            <button class="btn btn-sm btn-outline-dark w-100 fw-semibold mt-2" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseJobdesk{{ $jobdesk->id }}"
                                aria-expanded="false" aria-controls="collapseJobdesk{{ $jobdesk->id }}">
                                Lihat Detail Jobdesk
                            </button>

                            <div class="collapse mt-2" id="collapseJobdesk{{ $jobdesk->id }}">
                                <div class="small">
                                    {!! $jobdesk->description !!}
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                @if (auth()->user()->isAdmin() || auth()->user()->isKepalaUnit())
                                    <a href="{{ route('jobdesks.edit', $jobdesk->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('jobdesks.destroy', $jobdesk->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus jobdesk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
