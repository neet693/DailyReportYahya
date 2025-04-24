<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            color: #fff;
            display: inline-block;
        }

        .text-bg-success {
            background-color: #28a745;
        }

        .text-bg-danger {
            background-color: #dc3545;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            font-size: 10px;
            text-align: right;
            padding-right: 30px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature p {
            margin: 0;
        }
    </style>
</head>

<body>

    <header>
        {{-- Ganti dengan URL logo jika ada --}}
        {{-- <img src="{{ public_path('images/logo.png') }}" class="logo"> --}}
        <h1>Laporan Bulanan</h1>
        <div class="subtitle">
            Nama: {{ auth()->user()->name }} |
            Periode: {{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}
        </div>

    </header>

    <h3 style="margin-bottom: 10px;">Daftar Task Pribadi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Tempat</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Deskripsi</th>
                <th>Progres</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $item)
                @if ($item->task_user_id == auth()->user()->id)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->place }}</td>
                        <td>{{ $item->task_date->format('d M Y') }}</td>
                        <td>{{ $item->task_start_time->format('H:i') }} s/d {{ $item->task_end_time->format('H:i') }}
                        </td>
                        <td>{!! $item->description !!}</td>
                        <td>
                            @if ($item->progres == 1)
                                <span class="badge text-bg-success">Selesai</span>
                            @else
                                <span class="badge text-bg-danger">Belum</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-bottom: 10px;">Daftar Penugasan</h3>
    <table>
        <thead>
            <tr>
                <th>Yang Bertugas</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Ditugaskan Oleh</th>
                <th>Progres</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assignments as $assignment)
                @if ($assignment->user_id == auth()->user()->id)
                    <tr>
                        <td>{{ $assignment->user->name }}</td>
                        <td>{{ $assignment->title }}</td>
                        <td>{{ $assignment->assignment_date->format('d M Y') }}</td>
                        <td>{{ $assignment->start_assignment_time->format('H:i') }} s/d
                            {{ $assignment->end_assignment_time->format('H:i') }}</td>
                        <td>{{ $assignment->assigner->name }}</td>
                        <td>
                            @if ($assignment->progres === 'Selesai')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">{{ $assignment->progres }}</span>
                            @endif
                        </td>
                        <td>{{ $assignment->description }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        <p style="margin-top: 40px;">__________________________</p>
        <p>{{ auth()->user()->name }}</p>
    </div>

    <footer>
        Sistem Laporan - {{ config('app.name') }}
    </footer>

</body>

</html>
