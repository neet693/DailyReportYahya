@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">Buat Penugasan Baru</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('assignments.store') }}">
                            @csrf

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
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="assignment_date">Tanggal Penugasan:</label>
                                <input type="date" name="assignment_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Penugasan:</label>
                                <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="kendala">Kendala:</label>
                                <textarea name="kendala" class="form-control" rows="4" required></textarea>
                            </div>

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
