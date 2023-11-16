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
                            <div class="dropdown">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @can('approve', $data)
                                        <li>
                                            <form method="POST" action="{{ route('permissionrequest.approve', $data) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success" title="Approve"><i
                                                        class="bi bi-check"></i></button>
                                            </form>
                                        </li>
                                    @endcan
                                    @can('reject', $data)
                                        <li>
                                            <form method="POST" action="{{ route('permissionrequests.reject', $data) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger" title="Reject"><i
                                                        class="bi bi-x"></i></button>
                                            </form>
                                        </li>
                                    @endcan
                                    @can('delete', $data)
                                        <li>
                                            <form action="{{ route('permissionrequest.destroy', $data->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </li>
                                    @endcan
                                    <li>
                                        <button type="button" title="Lihat Detail" class="btn btn-info text-white"
                                            data-bs-toggle="modal" data-bs-target="#permissionRequests{{ $data->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    {{-- Modal Start Here --}}
                    @include('components.permission_request_modal', [
                        'permissionRequests' => $data,
                        'daysDifference' => $daysDifference,
                    ])
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
