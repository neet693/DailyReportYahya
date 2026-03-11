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

        // 🔁 Redirect jika bukan admin, kepala, atau hrd
        if (!in_array($user->role, ['admin', 'kepala', 'hrd'])) {
            return redirect()->route('permissionrequest.create');
        }

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
            $durations[$request->id] = $this->calculateDuration(
                $request->start_date,
                $request->end_date,
                $request->start_time,
                $request->end_time
            );
        }

        return view('permission-requests.index', compact('permissionRequests', 'durations'));
    }



    /**
     * Hitung durasi perizinan antara dua tanggal.
     */
    private function calculateDuration($startDate, $endDate, $startTime = null, $endTime = null)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($startTime) {
            $start->setTimeFromTimeString($startTime);
        } else {
            $start->setTime(0, 0);
        }

        if ($endTime) {
            $end->setTimeFromTimeString($endTime);
        } else {
            $end->setTime(23, 59);
        }

        $diff = $start->diff($end);

        $parts = [];

        if ($diff->d > 0) {
            $parts[] = $diff->d . ' hari';
        }

        if ($diff->h > 0) {
            $parts[] = $diff->h . ' jam';
        }

        if ($diff->i > 0) {
            $parts[] = $diff->i . ' menit';
        }

        if (empty($parts)) {
            return 'Kurang dari 1 menit';
        }

        return implode(' ', $parts);
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
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'required|date',
            'end_time' => 'nullable|date_format:H:i',
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
