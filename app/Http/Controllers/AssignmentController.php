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
                // Kepala unit hanya melihat assignment pegawai di unitnya
                return $query->whereHas('user.employmentDetail', function ($q) use ($currentUser) {
                    $q->where('unit_kerja_id', $currentUser->employmentDetail?->unit_kerja_id);
                });
            })
            ->when($currentUser->isPegawai(), function ($query) use ($currentUser) {
                // Pegawai hanya melihat assignment dirinya sendiri
                return $query->where('user_id', $currentUser->id);
            })
            ->with(['user', 'assigner']) // eager loading biar hemat query
            ->get();

        return view('assignments.index', compact('assignments', 'currentUser'));
    }


    public function create()
    {
        $this->authorize('create', Assignment::class);

        $currentUser = auth()->user();

        // Admin bisa lihat semua user
        if ($currentUser->role === 'admin') {
            $users = User::where('id', '!=', $currentUser->id)->get(); // exclude diri sendiri
        } else {
            // Kepala unit hanya bisa menugaskan ke unit yang sama
            $users = User::whereHas('employmentDetail', function ($q) use ($currentUser) {
                $q->where('unit_kerja_id', $currentUser->employmentDetail->unit_kerja_id);
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
        ]);

        $currentUser = auth()->user();
        $targetUser = User::findOrFail($request->user_id);

        // Admin bebas assign siapa saja, kepala unit harus sama unit
        if (
            $currentUser->role !== 'admin' &&
            $targetUser->employmentDetail->unit_kerja_id !== $currentUser->employmentDetail->unit_kerja_id
        ) {
            abort(403, 'Kamu hanya boleh menugaskan pegawai di unit kamu.');
        }

        Assignment::create([
            'user_id' => $request->user_id,
            'assigner_id' => $currentUser->id,
            'title' => $request->title,
            'assignment_date' => $request->assignment_date,
            'start_assignment_time' => $request->start_assignment_time,
            'end_assignment_time' => $request->end_assignment_time,
            'description' => $request->description,
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
