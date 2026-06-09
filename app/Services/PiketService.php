<?php

namespace App\Services;

use App\Models\PiketKuota;
use App\Models\PiketPengajuan;

class PiketService
{
    public function getSisaKuota($unitId, $tanggal)
    {
        $tanggal = \Carbon\Carbon::parse($tanggal)->format('Y-m-d');

        $kuota = PiketKuota::where('unit_kerja_id', $unitId)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->first();

        if (!$kuota) {
            return 0;
        }

        // $terpakai = PiketPengajuan::whereHas('user.employmentDetail', function ($q) use ($unitId) {
        //     $q->where('unit_kerja_id', $unitId);
        // })
        //     // ->whereDate('tanggal_piket', $tanggal)
        //     ->where('status', 'approved')
        //     ->count();

        $terpakai = PiketPengajuan::query()
            ->whereDate('tanggal_piket', $tanggal)
            ->whereIn('status', [
                'pending',
                'approved'
            ])
            ->count();

        $jumlah = $kuota->total_kuota;

        return max(0, $jumlah - $terpakai);
    }

    public function checkKuota(int $unitKerjaId, string $tanggal): bool
    {
        return $this->getSisaKuota($unitKerjaId, $tanggal) > 0;
    }
}
