<?php

namespace App\Http\Controllers;

use App\Models\PermissionRequest;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua izin, dengan relasi: user, unit kerja saat input izin, dan yang menyetujui
        $permissionRequestsQuery = PermissionRequest::with([
            'approver',
            'user',
            'unitKerja',
        ]);

        // Filter jika bukan admin
        if ($user->role !== 'admin' && optional($user->employmentDetail)->unit_kerja_id) {
            $permissionRequestsQuery->where('unit_kerja_id', $user->employmentDetail->unit_kerja_id);
        }

        // Eksekusi query
        $permissionRequests = $permissionRequestsQuery->get();

        // Hitung durasi per izin
        $durations = [];
        foreach ($permissionRequests as $request) {
            $durations[$request->id] = $this->calculateDuration($request->start_date, $request->end_date);
        }

        return view('permission-requests.index', compact('permissionRequests', 'durations'));
    }


    /**
     * Hitung durasi perizinan antara dua tanggal.
     */
    private function calculateDuration($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($start->eq($end)) {
            return ['days' => 0, 'hours' => 23, 'minutes' => 59];
        }

        return [
            'days' => $start->diffInDays($end) . ' hari',
            'hours' => 23,
            'minutes' => 59
        ];
    }


    public function create()
    {
        $units = UnitKerja::all(); // Ambil semua unit
        return view('permission-requests.create', compact('units'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'jenis_pegawai' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
        ]);

        // Ambil unit_id dari employmentDetail user
        $unitId = $request->unit_kerja_id;

        $approver = User::whereHas('employmentDetail', function ($query) use ($unitId) {
            $query->where('unit_kerja_id', $unitId);
        })->where('role', 'kepala')->first();

        $validatedData['approver_id'] = $approver?->id ?? null;

        // Simpan ke database
        PermissionRequest::create($validatedData);

        return redirect('/permissionrequest')->with('success', 'Permohonan berhasil dibuat.');
    }


    public function show(PermissionRequest $permissionRequest)
    {
        //
    }

    public function edit(PermissionRequest $permissionRequest)
    {
        //
    }

    public function update(Request $request, PermissionRequest $permissionRequest)
    {
        //
    }

    public function destroy($id)
    {
        $permissionRequest = PermissionRequest::findOrFail($id);
        $this->authorize('delete', $permissionRequest);
        $permissionRequest->delete();

        return redirect('/permissionrequest')
            ->with('success', 'Permission request berhasil dihapus.');
    }


    public function approve(PermissionRequest $permissionRequest)
    {
        $this->authorize('approve', $permissionRequest);

        $permissionRequest->update([
            'status_permohonan' => 'Disetujui',
            'approver_id' => Auth::id(),
        ]);
        return redirect()->back()->with('success', 'Permohonan berhasil disetujui.');
    }

    public function reject(PermissionRequest $permissionRequest)
    {
        $this->authorize('reject', $permissionRequest);

        $permissionRequest->update([
            'status_permohonan' => 'Ditolak',
            'approver_id' => Auth::id(),
        ]);
        return redirect()->back()->with('success', 'Permohonan berhasil ditolak.');
    }
}
