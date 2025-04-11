@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Assign User ke Unit: <strong>{{ $unit->name }}</strong></h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('unit.assign.users', $unit->id) }}" method="POST">
            @csrf

            <label for="user_ids">Pilih User:</label>
            <select name="user_ids[]" multiple class="form-control">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, $assignedUserIds) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
