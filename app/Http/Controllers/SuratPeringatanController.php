<?php

namespace App\Http\Controllers;

use App\Models\EmploymentDetail;
use App\Models\SuratPeringatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratPeringatanController extends Controller
{
    // Method untuk menyimpan SP dengan parameter employee_number
    public function store(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|exists:employment_details,employee_number',
            'judul' => 'required|string|max:255',
            'mulai_berlaku' => 'required|date',
            'berakhir_berlaku' => 'required|date|after_or_equal:mulai_berlaku',
            'alasan' => 'required|string',
        ]);

        // Ambil user_id dari employee_number
        $employmentDetail = EmploymentDetail::where('employee_number', $request->employee_number)->firstOrFail();
        $user = $employmentDetail->user;

        // Cek otorisasi
        if (
            !in_array(Auth::user()->role, ['admin', 'hrd']) &&
            !(Auth::user()->role === 'kepala' && Auth::user()->units->pluck('id')->contains($user->employmentDetail->unit_id))
        ) {
            abort(403);
        }

        SuratPeringatan::create([
            'user_id' => $user->id,
            'judul' => $request->judul,
            'mulai_berlaku' => $request->mulai_berlaku,
            'berakhir_berlaku' => $request->berakhir_berlaku,
            'alasan' => $request->alasan,
            'is_active' => now()->lte($request->berakhir_berlaku), // aktif kalau belum lewat
        ]);


        return back()->with('success', 'Surat Peringatan berhasil ditambahkan.');
    }


    public function update(Request $request, SuratPeringatan $suratPeringatan)
    {
        $user = $suratPeringatan->user;

        if (
            !in_array(Auth::user()->role, ['admin', 'hrd']) &&
            !(Auth::user()->role === 'kepala' && Auth::user()->employmentDetail->unit_id === $user->employmentDetail->unit_id)
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'mulai_berlaku' => 'required|date',
            'berakhir_berlaku' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
        ]);

        $validated['is_active'] = now()->lte($validated['berakhir_berlaku']);
        $suratPeringatan->update($validated);


        return redirect()->route('employment-detail.show', $user->employmentDetail->employee_number)->with('success', 'Surat Peringatan berhasil diperbarui.');
    }

    public function destroy(SuratPeringatan $suratPeringatan)
    {
        $user = $suratPeringatan->user;

        if (
            !in_array(Auth::user()->role, ['admin', 'hrd']) &&
            !(Auth::user()->role === 'kepala' && Auth::user()->employmentDetail->unit_id === $user->employmentDetail->unit_id)
        ) {
            abort(403);
        }

        $suratPeringatan->delete();

        return back()->with('success', 'Surat Peringatan dihapus.');
    }
}
