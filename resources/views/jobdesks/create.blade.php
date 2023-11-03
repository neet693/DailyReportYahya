@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Job Description</h2>
        <form method="POST" action="{{ route('jobdesks.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input id="description" type="hidden" name="description">
                <trix-editor input="description"></trix-editor>
            </div>
            <div class="form-group">
                <label for="jobdesk_user_id">Assigned User:</label>
                <select name="jobdesk_user_id" id="jobdesk_user_id" class="form-control">
                    @foreach ($users as $user)
                        @if ($user->id != auth()->user()->id)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
