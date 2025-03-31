<?php

namespace App\Http\Controllers;

use App\Models\EducationHistory;
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
        ]);

        Auth::user()->educationHistories()->create($request->all());

        return redirect()->back()->with('success', 'Pendidikan berhasil ditambahkan.');
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
        //
    }
}
