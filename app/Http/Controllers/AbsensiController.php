<?php

namespace App\Http\Controllers;

use App\Imports\AbsensiImport;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{

    public function index(Request $request)
    {
        $auth = auth()->user();

        // === Ambil unit sesuai role ===
        if ($auth->role === 'kepala' && $auth->employmentDetail) {
            $unitId = $auth->employmentDetail->unit_kerja_id;

            // Unit terbatas hanya unit dia
            $units = \App\Models\UnitKerja::where('id', $unitId)->get();

            // Pegawai terbatas ke unit dia
            $users = User::whereHas('employmentDetail', function ($q) use ($unitId) {
                $q->where('unit_kerja_id', $unitId);
            })->with('employmentDetail.unit')->get();
        } else {
            // HRD/Admin → semua unit & semua user
            $units = \App\Models\UnitKerja::all();
            $users = User::with('employmentDetail.unit')->get();
        }

        // === Query absensi ===
        $query = Absensi::with(['user.employmentDetail.unit', 'latenote']);

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('bulan')) {
            $bulan = \Carbon\Carbon::parse($request->bulan);
            $query->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year);
        }

        if ($request->filled('pegawai')) {
            $query->where('user_id', $request->pegawai);
        }

        if ($request->filled('unit')) {
            $query->whereHas('user.employmentDetail', function ($q) use ($request) {
                $q->where('unit_kerja_id', $request->unit);
            });
        }

        // 🔒 Kalau kepala → wajib filter unitnya sendiri
        if ($auth->role === 'kepala' && isset($unitId)) {
            $query->whereHas('user.employmentDetail', function ($q) use ($unitId) {
                $q->where('unit_kerja_id', $unitId);
            });
        }

        $absensis = $query->get();

        $selectedUser = $request->filled('pegawai')
            ? User::find($request->pegawai)
            : null;

        $totalMenit = $absensis->sum('terlambat');
        $totalHari  = $absensis->where('terlambat', '>', 0)->count();

        return view('absensis.index', compact(
            'users',
            'absensis',
            'selectedUser',
            'totalMenit',
            'totalHari',
            'units'
        ));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        Excel::import(new AbsensiImport, $request->file('file'));

        return back()->with('success', 'Data absensi berhasil diimpor.');
    }
}
