<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();

        $assignments = Assignment::query()
            ->when($currentUser->isKepalaUnit(), function ($query) use ($currentUser) {
                return $query->whereHas('user.units', function ($q) use ($currentUser) {
                    $q->whereIn('unit_id', $currentUser->units->pluck('id'));
                });
            })
            ->when($currentUser->isPegawai(), function ($query) use ($currentUser) {
                return $query->where('user_id', $currentUser->id);
            })
            ->with(['user', 'assigner'])
            ->get();

        return view('assignments.index', compact('assignments', 'currentUser'));
    }

    public function create()
    {
        $this->authorize('create', Assignment::class);

        $currentUser = auth()->user();

        if ($currentUser->isAdmin()) {
            $users = User::where('id', '!=', $currentUser->id)->get(); // semua user kecuali diri sendiri
        } else {
            // Kepala unit hanya bisa menugaskan user yang satu unit dengannya
            $users = User::whereHas('units', function ($q) use ($currentUser) {
                $q->whereIn('unit_id', $currentUser->units->pluck('id'));
            })
                ->where('id', '!=', $currentUser->id)
                ->get();
        }

        return view('assignments.create', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required',
            'description' => 'required',
            'assignment_date' => 'required|date',
            'start_assignment_time' => 'required',
            'end_assignment_time' => 'required',
            'unit_id' => 'required|exists:unit_kerjas,id',
        ]);

        $currentUser = auth()->user();
        $targetUser = User::findOrFail($request->user_id);

        // Jika kepala unit, pastikan dia assign user dari unit yang sama
        if ($currentUser->isKepalaUnit()) {
            $unitValid = $currentUser->units->pluck('id')->contains($request->unit_id);
            $userInUnit = $targetUser->units->pluck('id')->contains($request->unit_id);

            if (! $unitValid || ! $userInUnit) {
                abort(403, 'Anda hanya bisa menugaskan pegawai yang berada di unit Anda.');
            }
        }

        Assignment::create([
            'user_id' => $request->user_id,
            'assigner_id' => $currentUser->id,
            'title' => $request->title,
            'assignment_date' => $request->assignment_date,
            'start_assignment_time' => $request->start_assignment_time,
            'end_assignment_time' => $request->end_assignment_time,
            'description' => $request->description,
            'unit_id' => $request->unit_id,
        ]);

        return redirect()->route('assignments.index')->with('success', 'Penugasan berhasil dibuat.');
    }



    public function show(Assignment $assignment)
    {
        //
    }

    public function edit(Assignment $assignment)
    {
        $users = User::all();
        return view('assignments.edit', compact('assignment', 'users'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $assignment->update($request->all());
        return redirect()->route('assignments.index');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('assignments.index');
    }

    public function markAsComplete(Assignment $assignment)
    {

        $assignment->update(['progres' => 'Selesai']); // Ubah nilai 'progress' menjadi 'Selesai'

        return redirect()->route('assignments.index')->with('success', 'Penugasan telah ditandai sebagai selesai.');
    }

    public function reportKendala(Assignment $assignment)
    {
        $assignment->update([
            'progres' => 'Pending',
            'kendala' => request('kendala'),
        ]);

        return redirect()->back()->with('success', 'Kendala berhasil dilaporkan.');
    }
}
