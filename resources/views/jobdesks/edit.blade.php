@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Job Description</h2>
        <form method="POST" action="{{ route('jobdesks.update', $jobdesk->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $jobdesk->title }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input id="description" type="hidden" name="description" value="{{ $jobdesk->description }}">
                <trix-editor input="description"></trix-editor>
            </div>
            <div class="form-group">
                <label for="jobdesk_user_id">Assigned User:</label>
                <select name="jobdesk_user_id" id="jobdesk_user_id" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if ($user->id == $jobdesk->jobdesk_user_id) selected @endif>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
