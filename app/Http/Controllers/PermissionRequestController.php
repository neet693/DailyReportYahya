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
        $daysDifference = [];

        foreach ($permissionRequests as $request) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $differenceInDays = $startDate->diffInDays($endDate);

            // Tambahkan hasil perhitungan ke array
            $daysDifference[$request->id] = $differenceInDays;
        }

        return view('permission-requests.index', compact('permissionRequests', 'daysDifference'));
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
