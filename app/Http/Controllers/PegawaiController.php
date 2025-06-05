<?php

namespace App\Http\Controllers;

use App\Models\EmploymentDetail;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function create()
    {
        $units = UnitKerja::all();
        return view('register-pegawai', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'employee_number' => 'required|string|unique:employment_details,employee_number',
            'tahun_masuk' => 'required',
            'tahun_sertifikasi' => 'required',
            'status_kepegawaian' => 'required|string',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'marital_status' => 'nullable',
            'birth_date' => 'nullable|date'
            // tambah validasi lain sesuai kebutuhan
        ]);

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'marital_status' => $request->marital_status,
        ]);

        // Buat employment detail
        EmploymentDetail::create([
            'user_id' => $user->id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'employee_number' => 'EMP-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_sertifikasi' => $request->tahun_sertifikasi ?? null,
            'status_kepegawaian' => $request->status_kepegawaian,
            // field lain...
        ]);

        return redirect()->route('home')->with('success', 'Pegawai berhasil didaftarkan.');
    }
}
