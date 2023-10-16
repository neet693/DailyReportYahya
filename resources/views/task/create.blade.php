@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Buat Task') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label for="title"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Judul Kegiatan') }}</label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror" name="title"
                                        value="{{ old('title') }}" required autocomplete="title" autofocus>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="place"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Judul Kegiatan') }}</label>

                                <div class="col-md-6">
                                    <input id="place" type="text"
                                        class="form-control @error('place') is-invalid @enderror" name="place"
                                        value="{{ old('place') }}" required autocomplete="place" autofocus>

                                    @error('place')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="task_date"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Tanggal Kegiatan') }}</label>

                                <div class="col-md-6">
                                    <input id="task_date" type="date"
                                        class="form-control @error('task_date') is-invalid @enderror" name="task_date"
                                        value="{{ old('task_date') }}" required autofocus>

                                    @error('task_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="task_start_time"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Dari Jam') }}</label>

                                <div class="col-md-6">
                                    <input id="task_start_time" type="time"
                                        class="form-control @error('task_start_time') is-invalid @enderror"
                                        name="task_start_time" value="{{ old('task_start_time') }}" required
                                        autocomplete="task_start_time" autofocus>

                                    @error('task_start_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="task_end_time"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Sampai Jam') }}</label>

                                <div class="col-md-6">
                                    <input id="task_end_time" type="time"
                                        class="form-control @error('task_end_time') is-invalid @enderror"
                                        name="task_end_time" value="{{ old('task_end_time') }}" required
                                        autocomplete="task_end_time" autofocus>

                                    @error('task_end_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Keterangan') }}</label>

                                <div class="">
                                    <input id="description" type="hidden"
                                        class="form-control @error('description') is-invalid @enderror" name="description"
                                        value="{{ old('description') }}" required autocomplete="description" autofocus>
                                    <trix-editor input="description"></trix-editor>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
