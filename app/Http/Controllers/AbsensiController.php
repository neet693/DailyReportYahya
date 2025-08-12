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
        $users = User::all();

        $query = Absensi::with(['user', 'latenote']);


        if ($request->filled('bulan')) {
            $bulan = \Carbon\Carbon::parse($request->bulan);
            $query->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year);
        }

        if ($request->filled('pegawai')) {
            $query->where('user_id', $request->pegawai);
        }

        $absensis = $query->get();

        // Kirim selectedUser biar Blade tidak error
        $selectedUser = null;
        if ($request->filled('pegawai')) {
            $selectedUser = User::find($request->pegawai);
        }

        // Hitung akumulasi keterlambatan
        $totalMenit = $absensis->sum('terlambat');
        $totalHari  = $absensis->where('terlambat', '>', 0)->count();

        return view('absensis.index', compact('users', 'absensis', 'selectedUser', 'totalMenit', 'totalHari'));
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
