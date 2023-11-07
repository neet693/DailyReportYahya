@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Permohonan Surat Izin/Surat Sakit</h1>
        <div class="col-md-8 mb-3">
            <a href="{{ route('permissionrequest.create') }}" class="btn btn-primary">Buat Izin</a>

        </div>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Tanggal Permohonan</th>
                    <th>Status</th>
                    <th>Disetujui oleh</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissionRequests as $data)
                    <tr>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->jabatan }}</td>
                        <td>{{ $data->created_at->format('Y-m-d') }}</td>
                        <td>
                            @if ($data->status_permohonan == 'Disetujui')
                                <span class="badge text-bg-success">{{ $data->status_permohonan }}</span>
                            @else
                                <span class="badge text-bg-danger">{{ $data->status_permohonan }}</span>
                            @endif
                        </td>
                        <td>{{ $data->approver ? $data->approver->name : 'Under Review' }}</td>
                        <td>
                            @can('approve', $data)
                                <form method="POST" action="{{ route('permission-requests.approve', $data) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                            @endcan

                            @can('reject', $data)
                                <form method="POST" action="{{ route('permission-requests.reject', $data) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            @endcan

                            <button type="button" title="Lihat Detail" class="btn btn-info text-white"
                                data-bs-toggle="modal" data-bs-target="#exampleModal{{ $data->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            @include('components.permission_request_modal', [
                                'permissionRequests' => $data,
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
