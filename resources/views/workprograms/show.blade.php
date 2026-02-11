@extends('layouts.app')

@section('content')
    <h1>{{ $workProgram->title }}</h1>

    @if ($workProgram->file_name)
        <iframe src="{{ asset('WorkPrograms/' . $workProgram->file_path) }}" width="100%" height="600px"
            style="border: none;">
        </iframe>
    @else
        <p>File tidak tersedia.</p>
    @endif
@endsection
