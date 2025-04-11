<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = UnitKerja::all();
        return view('unit-kerja.index', compact('units'));
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
    public function show(UnitKerja $unitKerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitKerja $unitKerja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitKerja $unitKerja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitKerja $unitKerja)
    {
        //
    }

    public function assignForm(UnitKerja $unit)
    {
        $user = auth()->user();

        // Admin boleh semua, Kepala Unit hanya unit yang dia miliki
        if (! $user->isAdmin() && ! $user->isKepalaUnit()) {
            abort(403, 'Anda tidak berwenang mengakses halaman ini.');
        }

        // Jika kepala unit, cek apakah unit ini termasuk unit yang dia miliki
        if ($user->isKepalaUnit() && !$user->units->contains($unit)) {
            abort(403, 'Unit ini bukan tanggung jawab Anda.');
        }

        $users = User::where('role', '!=', User::ROLE_ADMIN)->get();
        $assignedUserIds = $unit->users->pluck('id')->toArray();

        return view('unit-kerja.assign-user', compact('unit', 'users', 'assignedUserIds'));
    }

    public function assignUsers(Request $request, UnitKerja $unit)
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isKepalaUnit()) {
            abort(403, 'Anda tidak berwenang melakukan aksi ini.');
        }

        if ($user->isKepalaUnit() && !$user->units->contains($unit)) {
            abort(403, 'Unit ini bukan tanggung jawab Anda.');
        }

        $request->validate([
            'user_ids' => 'array',
        ]);

        // Simpan assign ke pivot unit_user
        $unit->users()->sync($request->user_ids);

        return back()->with('success', 'User berhasil ditugaskan ke unit.');
    }
}
