<?php

namespace App\Http\Controllers;

use App\Models\EmploymentDetail;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class EmploymentDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = UnitKerja::all(); // Ambil semua unit
        $user = Auth::user(); // Ambil user yang login
        return view('employment-detail.form', compact('units', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_number' => 'required',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'tahun_masuk' => 'required',
            'tahun_sertifikasi' => 'nullable',
            'status_kepegawaian' => 'required|in:Tetap,Tidak Tetap',
        ]);

        $user = Auth::user();

        // Jika employment detail sudah ada, update; jika belum, buat baru
        EmploymentDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'employee_number' => $request->employee_number,
                'unit_kerja_id' => $request->unit_kerja_id,
                'tahun_masuk' => $request->tahun_masuk,
                'tahun_sertifikasi' => $request->tahun_sertifikasi,
                'status_kepegawaian' => $request->status_kepegawaian,
            ]
        );

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(EmploymentDetail $employmentDetail)
    {
        $user = $employmentDetail->user()->with(['employmentDetail.unit', 'educationHistories', 'trainings'])->firstOrFail();

        return view('employment-detail.show', compact('user'));
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmploymentDetail $employmentDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmploymentDetail $employmentDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmploymentDetail $employmentDetail)
    {
        //
    }

    public function cetak(EmploymentDetail $employmentDetail)
    {
        $user = $employmentDetail->user()->with(['employmentDetail.unit', 'educationHistories', 'trainings'])->firstOrFail();

        if (!$user) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $pdf = PDF::loadView('cetak', compact('employmentDetail', 'user'));
        return $pdf->stream("profil_{$user->name}.pdf");
    }
}
