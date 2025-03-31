<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::where('user_id', Auth::id())->get();
        return view('trainings.index', compact('trainings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'training_name' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'training_date' => 'nullable|date',
            'training_expiry' => 'nullable|date',
            'certificate_number' => 'nullable|string|max:255',
            'certificate_url' => 'nullable|url',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->except('certificate_file');
        $data['user_id'] = Auth::id();

        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('certificates', $filename, 'public');
            $data['certificate_file'] = $filename;
        }

        Training::create($data);

        return back()->with('success', 'Diklat berhasil ditambahkan.');
    }

    public function edit(Training $training)
    {
        if ($training->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('trainings.edit', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        if ($training->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'training_name' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'training_date' => 'nullable|date',
            'training_expiry' => 'nullable|date',
            'certificate_number' => 'nullable|string|max:255',
            'certificate_url' => 'nullable|url',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->except('certificate_file');

        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('certificates', $filename, 'public');
            $data['certificate_file'] = $filename;
        }

        $training->update($data);

        return back()->with('success', 'Diklat berhasil diperbarui.');
    }

    public function destroy(Training $training)
    {
        if ($training->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $training->delete();

        return back()->with('success', 'Diklat berhasil dihapus.');
    }
}
