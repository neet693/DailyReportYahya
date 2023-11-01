@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">Buat Penugasan Baru</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('assignments.update', $assignment->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="user_id">Pengguna yang Ditugaskan:</label>
                                <select name="user_id" class="form-control">
                                    @foreach ($users as $user)
                                        @if ($user->id != auth()->user()->id)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Judul Penugasan:</label>
                                <input id="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror" name="title"
                                    value="{{ old('title', $assignment->title) }}" required autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="assignment_date">Tanggal Penugasan:</label>
                                <input id="assignment_date" type="date"
                                    class="form-control @error('assignment_date') is-invalid @enderror"
                                    name="assignment_date"
                                    value="{{ old('assignment_date', $assignment->assignment_date) }}" required autofocus>

                                @error('assignment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="start_assignment_time">Dari Jam:</label>
                                <input id="start_assignment_time" type="time"
                                    class="form-control @error('start_assignment_time') is-invalid @enderror"
                                    name="start_assignment_time"
                                    value="{{ old('start_assignment_time', $assignment->start_assignment_time) }}" required
                                    autocomplete="start_assignment_time" autofocus>

                                @error('start_assignment_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="end_assignment_time">Sampai Jam:</label>
                                <input id="end_assignment_time" type="time"
                                    class="form-control @error('end_assignment_time') is-invalid @enderror"
                                    name="end_assignment_time"
                                    value="{{ old('end_assignment_time', $assignment->end_assignment_time) }}" required
                                    autocomplete="end_assignment_time" autofocus>

                                @error('end_assignment_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Penugasan:</label>
                                <input id="description" type="hidden"
                                    class="form-control @error('description') is-invalid @enderror" name="description"
                                    value="{{ old('description', $assignment->description) }}" required
                                    autocomplete="description" autofocus>
                                <trix-editor input="description"></trix-editor>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <label for="kendala">Kendala:</label>
                                <textarea id="kendala" class="form-control @error('kendala') is-invalid @enderror" name="kendala"
                                    value="{{ old('kendala') }}" rows="4" autofocus></textarea>

                                @error('kendala')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Buat Penugasan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
