<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profil Pegawai - {{ $user->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 70px;
            margin-bottom: 10px;
        }

        h2 {
            margin: 0;
            font-size: 18px;
        }

        hr {
            border: none;
            height: 1px;
            background: #000;
            margin: 20px 0;
        }

        .section-title {
            font-weight: bold;
            margin-top: 25px;
            text-transform: uppercase;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .info-table td.label {
            font-weight: bold;
            width: 30%;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Logo Sekolah">
        <h2>SEKOLAH KRISTEN YAHYA</h2>
        <small>Divisi Sumber Daya Manusia</small>
    </div>

    <hr>

    <div class="section-title">Informasi Data Diri</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $user->name }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td>: {{ $user->email }}</td>
        </tr>
        <tr>
            <td class="label">Employee Number</td>
            <td>: {{ $user->employmentDetail->employee_number }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td>: {{ \Carbon\Carbon::parse($user->birth_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>: {{ $user->address }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>: {{ $user->gender }}</td>
        </tr>
        <tr>
            <td class="label">Status Perkawinan</td>
            <td>: {{ $user->marital_status }}</td>
        </tr>
    </table>

    <div class="section-title">Informasi Kepegawaian</div>
    <table class="info-table">
        <tr>
            <td class="label">Unit</td>
            <td>: {{ $user->employmentDetail->unit->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tahun Masuk</td>
            <td>: {{ $user->employmentDetail->tahun_masuk ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tahun Sertifikasi</td>
            <td>:
                {{ $user->employmentDetail->tahun_sertifikasi ? \Carbon\Carbon::parse($user->employmentDetail->tahun_sertifikasi)->format('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>: {{ $user->employmentDetail->status_kepegawaian ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Riwayat Pendidikan</div>
    <table class="info-table">
        @foreach ($user->educationHistories as $edu)
            <tr>
                <td class="label">Gelar</td>
                <td>: {{ $edu->degree ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Institusi</td>
                <td>: {{ $edu->institution ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Sertifikasi</td>
                <td>:
                    {{ $edu->year_of_graduation ? \Carbon\Carbon::parse($edu->year_of_graduation)->format('d F Y') : '-' }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="section-title">Riwayat Diklat</div>
    <table class="info-table">
        @foreach ($user->trainings as $data)
            <tr>
                <td class="label">Nama Sertifikat</td>
                <td>: {{ $data->training_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Penyelenggara</td>
                <td>: {{ $data->organizer ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Mulai</td>
                <td>:
                    {{ $data->training_date ? \Carbon\Carbon::parse($data->training_date)->format('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="label">Masa Sertifikat</td>
                <td>:
                    {{ $data->training_expiry ? \Carbon\Carbon::parse($data->training_expiry)->format('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="label">Credential Sertifikat</td>
                <td>: {{ $data->certificate_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">URL Gambar (Drive dan lainnya)</td>
                <td>: {{ $data->certificate_url ?? '-' }}</td>
            </tr>

            <hr>
        @endforeach
    </table>

    <div class="footer">
        Dicetak oleh Sistem Informasi SIMPEG - {{ now()->format('d M Y H:i') }}
    </div>
</body>

</html>
