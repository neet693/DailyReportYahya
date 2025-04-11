<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwitchUserController extends Controller
{
    public function switchUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:unit_kerjas,id',
        ]);

        $user = auth()->user();

        if (!$user->units->pluck('id')->contains($request->unit_id)) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        session(['active_unit_id' => $request->unit_id]);

        return back()->with('success', 'Berhasil mengganti unit.');
    }
}
