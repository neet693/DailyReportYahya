<!-- resources/views/unit/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Daftar Unit</h3>
        <ul class="list-group">
            @foreach ($units as $unit)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $unit->name }}
                    @if (auth()->user()->isAdmin() || auth()->user()->isKepalaUnit() || auth()->user()->units->contains($unit))
                        <a href="{{ route('unit.assign.form', $unit->id) }}" class="btn btn-sm btn-outline-primary">Assign
                            User</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
