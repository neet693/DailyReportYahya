<?php

namespace App\Http\Controllers;

use App\Models\PermissionRequest;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionRequestController extends Controller
{
    public function index()
    {
        $permissionRequests = PermissionRequest::all();
        $durations = [];

        foreach ($permissionRequests as $request) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            // Hitung selisih waktu dalam menit
            $durationInMinutes = $startDate->diffInMinutes($endDate);

            // Hitung jumlah hari
            $days = $startDate->diffInDays($endDate);

            // Hitung selisih waktu dalam jam dan menit
            $hours = floor($durationInMinutes / 60);
            $minutes = $durationInMinutes % 60;

            // Tampilkan 23 jam 59 menit jika start_date dan end_date sama
            if ($startDate->eq($endDate)) {
                $displayDays = 0;
                $hours = 23;
                $minutes = 59;
            } else {
                $displayDays = $days . " hari ";
                $hours = 23;
                $minutes = 59;
            }

            // Tambahkan hasil perhitungan ke array
            $durations[$request->id] = [
                'days' => $displayDays,
                'hours' => $hours,
                'minutes' => $minutes,
            ];
        }


        return view('permission-requests.index', compact('permissionRequests', 'durations'));
    }


    public function create()
    {
        return view('permission-requests.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'jenis_pegawai' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required',
        ]);

        // Simpan permohonan ke dalam database
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
