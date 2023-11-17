<!-- resources/views/agendas/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Agenda</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('agendas.update', $agenda->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $agenda->title }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                    value="{{ $agenda->start_date }}" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $agenda->end_date }}"
                    required>
            </div>

            <!-- Pilihan executor (bisa lebih dari 1) -->
            <div class="mb-3">
                <label for="executors" class="form-label">Executor(s)</label>
                <select multiple class="form-select" id="executors" name="executors[]" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, $selectedExecutors) ? 'selected' : '' }}>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input id="description" type="hidden" name="description" value="{{ $agenda->description }}">
                <trix-editor input="description"></trix-editor>
            </div>

            <button type="submit" class="btn btn-primary">Update Agenda</button>
        </form>
    </div>
@endsection
