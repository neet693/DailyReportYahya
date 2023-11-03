@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Job Descriptions</h2>
        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala')
            <a href="{{ route('jobdesks.create') }}" class="btn btn-primary">Create Job Description</a>
        @endif

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mt-3">
            @foreach ($Jobdesks as $data)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('profile_images/' . $data->user->profile_image) }}" alt="Profil Gambar"
                                    class="rounded-circle" style="width: 50px">
                                <div class="p-2">
                                    <h5 class="card-title">{{ $data->user->name }}</h5>
                                    <p class="card-subtitle text-muted">{{ $data->title }}</p>
                                </div>
                            </div>
                            <p class="card-text mt-3">{!! $data->description !!}</p>
                        </div>
                        <div class="card-footer">
                            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'kepala')
                                <a href="{{ route('jobdesks.edit', $data->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('jobdesks.destroy', $data->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
