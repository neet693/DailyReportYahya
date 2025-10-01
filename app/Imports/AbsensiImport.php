<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class AbsensiImport implements OnEachRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // Ambil nama dari kolom ke-3 (index 3 = kolom ke-4 di Excel)
        $namaExcel = trim($data[0] ?? '');
        if (!$namaExcel) return;

        // Cari user berdasarkan nama yang mirip (menggunakan LIKE)
        $user = User::where('name', 'like', '%' . $namaExcel . '%')->first();
        if (!$user) return; // skip jika user tidak ditemukan

        // Ambil dan parse tanggal dari kolom ke-5 (index 5 = "Tanggal")
        $tanggalRaw = trim($data[1] ?? '');
        try {
            $tanggal = Carbon::createFromFormat('d/m/Y', $tanggalRaw);
        } catch (\Exception $e) {
            \Log::warning("Format tanggal salah: {$tanggalRaw}", ['user' => $namaExcel]);
            return;
        }

        // Simpan atau update data absensi
        Absensi::updateOrCreate([
            'user_id' => $user->id,
            'tanggal' => $tanggal->format('Y-m-d'),
        ], [
            'jam_masuk' => $this->parseTime($data[2] ?? null),     // Scan Masuk
            'jam_keluar' => $this->parseTime($data[3] ?? null),    // Scan Pulang
            'terlambat' => $this->durasiKeMenit($data[4] ?? '00.00'), // Terlambat
            'izin' => Str::lower(trim($data[5] ?? '')) === 'true',    // Absent
        ]);
    }

    // Convert string jam (misalnya "06.30") menjadi format time "HH:MM:SS"
    private function parseTime($jamString)
    {
        if (!$jamString) return null;

        $jamString = str_replace(',', '.', $jamString); // Ubah koma ke titik
        $parts = explode('.', $jamString);
        $jam = str_pad($parts[0] ?? '00', 2, '0', STR_PAD_LEFT);
        $menit = str_pad($parts[1] ?? '00', 2, '0', STR_PAD_RIGHT);

        return "{$jam}:{$menit}:00";
    }

    // Convert durasi string (misal: "01.15") menjadi menit integer
    private function durasiKeMenit($durasi)
    {
        if (!$durasi || $durasi === '0') return 0;

        $durasi = str_replace(',', '.', $durasi); // Pastikan titik pemisah
        [$jam, $menit] = explode('.', $durasi . '.00');

        return intval($jam) * 60 + intval($menit);
    }
}
