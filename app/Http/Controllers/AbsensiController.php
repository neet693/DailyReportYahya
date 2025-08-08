<?php

namespace App\Http\Controllers;

use App\Imports\AbsensiImport;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with('user');

        if ($request->filled('bulan')) {
            $tanggal = \Carbon\Carbon::createFromFormat('Y-m', $request->bulan);
            $query->whereMonth('tanggal', $tanggal->month)
                ->whereYear('tanggal', $tanggal->year);
        }

        if ($request->filled('pegawai')) {
            $query->where('user_id', $request->pegawai);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->get();
        $users = \App\Models\User::orderBy('name')->get();

        return view('absensis.index', compact('absensis', 'users'));
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
