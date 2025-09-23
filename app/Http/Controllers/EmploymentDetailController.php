<?php

namespace App\Http\Controllers;

use App\Models\EmploymentDetail;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function create(EmploymentDetail $employment_detail)
    {
        if (!$employment_detail) {
            abort(404, 'Employment detail tidak ditemukan.');
        }

        $units = UnitKerja::all();
        $authUser = Auth::user();
        $user = $employment_detail->user;

        if (
            $authUser->id !== $user->id &&
            $authUser->role !== 'admin' &&
            $authUser->role !== 'hrd'
        ) {
            return redirect()->back()->with('error', 'Kamu tidak punya akses untuk mengubah data ini.');
        }

        return view('employment-detail.form', compact('units', 'user', 'employment_detail'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'employee_number' => 'required',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'tahun_masuk' => 'required',
            'tahun_sertifikasi' => 'nullable',
            'status_kepegawaian' => 'required|in:Tetap,Tidak Tetap',
            'is_active' => 'required|boolean',
            'tahun_keluar' => 'nullable|string|max:100',
        ]);

        $authUser = Auth::user();
        $targetUserId = (int) $request->user_id;

        if (
            $authUser->id !== $targetUserId &&
            $authUser->role !== 'admin' &&
            $authUser->role !== 'hrd'
        ) {
            return redirect()->back()->with('error', 'Kamu tidak punya akses untuk mengubah data ini.');
        }

        EmploymentDetail::updateOrCreate(
            ['user_id' => $targetUserId],
            [
                'employee_number' => $request->employee_number,
                'unit_kerja_id' => $request->unit_kerja_id,
                'tahun_masuk' => $request->tahun_masuk,
                'tahun_sertifikasi' => $request->tahun_sertifikasi,
                'status_kepegawaian' => $request->status_kepegawaian,
                'is_active' => $request->is_active,
                'tahun_keluar' => $request->tahun_keluar,
            ]
        );

        return redirect()->route('employment-detail.show', $request->employee_number)
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(EmploymentDetail $employmentDetail)
    {
        $user = $employmentDetail->user()
            ->with(['employmentDetail.unit', 'educationHistories', 'trainings', 'statusPeringatanAktif'])
            ->firstOrFail();

        $currentUser = Auth::user();
        $canManageSP = $currentUser->canManageSP($user);

        // cek session berdasarkan employee_number
        $verified = session()->get("employment_verified_{$employmentDetail->employee_number}", false);

        return view('employment-detail.show', compact('user', 'canManageSP', 'verified'));
    }

    public function verify(Request $request, $employeeNumber)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password salah, coba lagi.');
        }

        // Simpan session khusus untuk employee ini
        session()->put("employment_verified_{$employeeNumber}", true);

        return redirect()->route('employment-detail.show', $employeeNumber)
            ->with('success', 'Verifikasi berhasil.');
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
