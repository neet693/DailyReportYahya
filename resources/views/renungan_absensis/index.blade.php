@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h3>Rekap Absensi Renungan Pagi</h3>
            <div class="d-flex">
                <form method="GET" action="{{ route('renungan-absensi.index') }}" class="me-2">
                    <input type="month" name="month" value="{{ $month }}" class="form-control d-inline w-auto">
                    <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                </form>
                <a href="{{ route('renungan-absensi.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Absensi Renungan
                </a>
            </div>
        </div>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Nama</th>
                    @foreach ($dates as $date)
                        <th>{{ $date->format('j') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($unitUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        @foreach ($dates as $date)
                            @php
                                $record = optional($absensis[$user->id] ?? collect())->first(function ($item) use (
                                    $date,
                                ) {
                                    return $item->created_at->isSameDay($date);
                                });
                            @endphp
                            <td class="text-center">
                                @if ($record)
                                    {{ $record->hadir ? '✅' : '❌' }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
