@extends('layouts.app')

@section('content')
    <div class="container">

        <h3 class="mb-4">Program Kerja</h3>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (Auth::user()->isKepalaUnit() || Auth::user()->isHRD())
            {{-- FORM UPLOAD --}}
            <div class="card mb-4">
                <div class="card-body">

                    <form action="{{ route('work-programs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-2">
                            <label>Judul</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <div class="mb-2">
                            <label>Tahun</label>
                            <input type="number" name="work_year" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Upload PDF</label>
                            <input type="file" name="file" class="form-control" accept="application/pdf" required>
                        </div>

                        <button class="btn btn-primary">
                            Upload
                        </button>

                    </form>

                </div>
            </div>
        @endif




        {{-- LIST DATA --}}
        <div class="card">
            <div class="card-body">

                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($workPrograms as $wp)
                            <tr>
                                <td>{{ $wp->title }}</td>
                                <td>{{ $wp->work_year }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $wp->status }}
                                    </span>
                                </td>
                                <td>

                                    {{-- PREVIEW --}}
                                    <a href="{{ route('work-programs.show', $wp->id) }}" class="btn btn-sm btn-info">
                                        Preview
                                    </a>
                                    @if (Auth::user()->isKepalaUnit() || Auth::user()->isHRD())
                                        {{-- DELETE --}}
                                        <form action="{{ route('work-programs.destroy', $wp->id) }}" method="POST"
                                            style="display:inline">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>

                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>
        </div>

    </div>
@endsection
