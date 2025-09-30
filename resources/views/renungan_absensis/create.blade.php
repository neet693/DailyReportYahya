@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Absensi Renungan Pagi</h3>
        <form action="{{ route('renungan-absensi.store') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Hadir</th>
                        <th>Alasan (jika tidak hadir)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unitUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>
                                <input type="checkbox" name="hadir[]" value="{{ $user->id }}">
                            </td>
                            <td>
                                <input type="text" name="alasan[{{ $user->id }}]" class="form-control"
                                    placeholder="Isi alasan jika tidak hadir">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Simpan Absensi</button>
        </form>
    </div>
@endsection
