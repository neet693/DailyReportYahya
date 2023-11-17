<!-- print.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap">

    <title>{{ $agenda->title }} - Print</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            margin: 2cm 0cm;
            font-family: 'Arial', sans-serif;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .agenda-details {
            margin-bottom: 20px;
        }

        .agenda-details p {
            margin: 5px 0;
        }

        .executors {
            display: flex;
            justify-content: space-between;
        }

        .executors img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $agenda->title }}</h1>
    </div>

    <div class="agenda-details">
        <p><strong>Periode:</strong> {{ $agenda->period }}</p>
        <p><strong>Tanggal:</strong> {{ $agenda->start_date->format('d F Y') }} s/d
            {{ $agenda->end_date->format('d F Y') }}</p>
        <p><strong>Deskripsi:</strong></p>
        <p>{!! $agenda->description !!}</p>

    </div>

    <div class="executors">
        <p><strong>Executors:</strong></p>
        <p>{{ $agenda->executors->pluck('name')->implode(', ') }}</p>
    </div>

    <div class="footer">
        <p>&copy; {{ now()->year }} - Made with ❤ by Yahya IT Dept
        </p>
    </div>
</body>

</html>
