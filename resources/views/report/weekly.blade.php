<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link href="{{ asset('bootstrap-5.3.2-dist/css/bootstrap.min.css') }}">
<h1>Laporan Mingguan</h1>

<table id="myTable" class="display">
    <thead>
        <tr>
            <th>No.</th>
            <th>Judul Task</th>
            <th>Tempat</th>
            <th>Tanggal Task</th>
            <th>Waktu Mulai Task</th>
            <th>Waktu Selesai Task</th>
            <th>Deskripsi</th>
            <!-- Tambahkan kolom-kolom lain sesuai data yang Anda ingin tampilkan -->
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->place }}</td>
                <td>{{ $item->task_date->format('d M Y') }}</td>
                <td>{{ $item->task_start_time->format('H:i A') }}</td>
                <td>{{ $item->task_end_time->format('H:i A') }}</td>
                <td>{{ $item->description }}</td>
                <!-- Tambahkan sel-sel lain sesuai data yang Anda ingin tampilkan -->
            </tr>
        @endforeach
    </tbody>
</table>
