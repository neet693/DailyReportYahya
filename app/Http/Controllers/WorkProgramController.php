<?php

namespace App\Http\Controllers;

use App\Models\WorkProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkProgramController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $workPrograms = WorkProgram::latest()->get();

        return view('workprograms.index', compact('workPrograms'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'work_year' => 'nullable|integer',
            'file' => 'required|file|mimes:pdf|mimetypes:application/pdf',
        ]);

        // Handle upload
        $file = $request->file('file');

        // rename file (anti duplicate + clean name)
        $fileName = time() . '_' . Str::slug(pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        )) . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('WorkPrograms'), $fileName);

        // Save DB
        WorkProgram::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'work_year' => $validated['work_year'] ?? null,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $fileName,
            'status' => 'draft',
            'uploaded_by' => Auth::id(),
            'unit_id' => Auth::user()->employmentDetail->unit_kerja_id,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Work Program berhasil diupload.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW (PDF VIEWER)
    |--------------------------------------------------------------------------
    */

    public function show(WorkProgram $workProgram)
    {
        return view('workprograms.show', compact('workProgram'));
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(WorkProgram $workProgram)
    {
        // delete file
        $filePath = public_path('WorkPrograms/' . $workProgram->file_path);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $workProgram->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }
}
