<?php

namespace App\Http\Controllers;

use App\Models\RenunganAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RenunganAbsensiController extends Controller
{

    public function index(Request $request)
    {
        $kepala = Auth::user();
        $unit = $kepala->employmentDetail?->unit;
        $unitUsers = $unit ? $unit->users : collect();

        // default: bulan sekarang
        $month = $request->get('month', now()->format('Y-m'));
        $start = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // generate list tanggal dalam bulan
        $dates = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates->push($date->copy());
        }

        // ambil absensi di bulan ini
        $absensis = RenunganAbsensi::whereIn('user_id', $unitUsers->pluck('id'))
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy('user_id');

        return view('renungan_absensis.index', compact('unitUsers', 'dates', 'absensis', 'month'));
    }




    public function create()
    {
        $kepala = Auth::user();

        // ambil unit kepala (via employment_detail)
        $unit = $kepala->employmentDetail?->unit;

        // kalau ada unit, ambil semua user di unit itu
        $unitUsers = $unit ? $unit->users : collect();

        return view('renungan_absensis.create', compact('unitUsers'));
    }

    public function store(Request $request)
    {
        $kepala = Auth::user();

        $request->validate([
            'hadir'   => 'array',
            'hadir.*' => 'integer|exists:users,id',
            'alasan'  => 'array',
        ]);

        // ambil unit dari employmentDetail
        $unit = $kepala->employmentDetail?->unit;

        if (!$unit) {
            return redirect()->route('renungan_absensis.index')
                ->with('error', 'Anda belum memiliki unit induk, hubungi admin.');
        }

        // Ambil semua ID user di unit tersebut
        $unitUserIds = $unit->users()->pluck('users.id')->toArray();

        foreach ($unitUserIds as $userId) {
            $isHadir = in_array($userId, $request->hadir ?? []);
            $alasan  = $request->alasan[$userId] ?? null;

            RenunganAbsensi::updateOrCreate(
                [
                    'user_id' => $userId,
                    'created_at' => now()->startOfDay(), // tanggal unik (bisa diganti tambah kolom date)
                ],
                [
                    'hadir'      => $isHadir,
                    'alasan'     => $isHadir ? null : $alasan,
                    'created_by' => $kepala->id,
                ]
            );
        }

        return redirect()->route('renungan-absensi.index')
            ->with('success', 'Absensi berhasil disimpan.');
    }


    public function show(RenunganAbsensi $renunganAbsensi)
    {
        //
    }

    public function edit(RenunganAbsensi $renunganAbsensi)
    {
        //
    }

    public function update(Request $request, RenunganAbsensi $renunganAbsensi)
    {
        //
    }

    public function destroy(RenunganAbsensi $renunganAbsensi)
    {
        //
    }
}
