<?php

namespace App\Http\Controllers;

use App\Models\EducationHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationHistoryController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'degree' => 'required|string',
            'institution' => 'required|string',
            'year_of_graduation' => 'nullable|digits:4',
            'user_id' => 'required|exists:users,id',
        ]);

        $currentUser = Auth::user();
        $targetUser = User::findOrFail($request->user_id);

        if (
            $currentUser->id === $targetUser->id ||
            $currentUser->isHRD() || $currentUser->isAdmin()
        ) {
            $targetUser->educationHistories()->create([
                'degree' => $request->degree,
                'institution' => $request->institution,
                'year_of_graduation' => $request->year_of_graduation,
            ]);

            return redirect()->back()->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
        }

        abort(403, 'Tidak memiliki izin untuk menambahkan data.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationHistory $educationHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EducationHistory $educationHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EducationHistory $educationHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationHistory $educationHistory)
    {
        $currentUser = Auth::user();

        if (
            $currentUser->id === $educationHistory->user_id ||
            $currentUser->isHRD() || $currentUser->isAdmin()
        ) {
            $educationHistory->delete();
            return redirect()->back()->with('success', 'Riwayat pendidikan berhasil dihapus.');
        }

        abort(403, 'Tidak memiliki izin untuk menghapus data.');
    }
}
