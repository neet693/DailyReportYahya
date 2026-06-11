<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        $status = $request->status; // all | Pending | Selesai
        $search = $request->search;

        $assignments = Assignment::query()
            ->when($currentUser->isAdmin() || $currentUser->isHRD(), fn($q) => $q)
            ->when($currentUser->isKepalaUnit(), function ($q) use ($currentUser) {
                $unitId = optional($currentUser->employmentDetail)->unit_kerja_id;
                $q->where('unit_id', $unitId);
            })
            ->when($currentUser->isPegawai(), fn($q) => $q->where('user_id', $currentUser->id))

            // 🔥 FILTER STATUS
            ->when($status && $status !== 'all', function ($q) use ($status) {
                $q->where('progres', $status);
            })

            // 🔥 SEARCH
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%$search%");
            })

            ->with(['user', 'assigner'])
            ->latest()
            ->get();

        return view('assignments.index', compact('assignments', 'currentUser', 'status', 'search'));
    }


    public function create()
    {
        $this->authorize('create', Assignment::class);

        $currentUser = auth()->user();

        if ($currentUser->isAdmin()) {
            // Admin bisa pilih semua user dan semua unit
            $users = User::all();
            $units = UnitKerja::all();
        } elseif ($currentUser->isKepalaUnit()) {
            // Ambil unit_id dari kepala unit (relasi pivot units)
            $unitIds = $currentUser->units->pluck('id');

            // Cari user yang memiliki employmentDetail dan unit_id-nya sesuai dengan kepala unit
            $users = User::whereHas('employmentDetail', function ($query) use ($unitIds) {
                $query->whereIn('unit_kerja_id', $unitIds);
            })->get();

            // Unit tetap pakai dari relasi kepala unit
            $units = $currentUser->units;
        } else {
            // Pegawai hanya bisa pilih dirinya dan unit yang dimiliki
            $users = collect([$currentUser]);
            $units = $currentUser->units;
        }

        return view('assignments.create', compact('users', 'units'));
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

        return redirect()->back()->with('success', 'Penugasan telah ditandai sebagai selesai.');
    }

    public function markAsPending(Assignment $assignment)
    {
        $assignment->update([
            'progres' => 'Pending',
            'kendala' => request('kendala'),
        ]);

        return redirect()->back()->with('success', 'Kendala berhasil dilaporkan.');
    }
}
