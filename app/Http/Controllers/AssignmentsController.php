<?php

namespace App\Http\Controllers;

use App\Models\Assignments;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Assignment::class);
        $assignments = Assignments::all();
        return view('assignments.index', compact('assignments'));

        // if ($this->authorize('viewAny', Assignment::class)) {
        //     $assignments = Assignments::all();
        //     return view('assignments.index', compact('assignments'));
        // } else {
        //     return redirect()
        //         ->back()
        //         ->with('error', 'Anda tidak diotorisasi untuk mengakses halaman ini.')
        //         ->with('timeout', 2);
        // }
    }

    public function create()
    {
        $users = User::all();
        return view('assignments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required',
            'description' => 'required',
            'assignment_date' => 'required',
            'start_assignment_time' => 'required',
            'end_assignment_time' => 'required',
            // 'kendala' => 'required',
            'description' => 'required',
        ]);

        Assignments::create([
            'user_id' => $request->user_id,
            'assigner_id' => auth()->user()->id, // Gunakan auth() untuk mendapatkan pengguna yang sedang masuk
            'title' => $request->title,
            'assignment_date' => $request->assignment_date,
            'start_assignment_time' => $request->start_assignment_time,
            'end_assignment_time' => $request->end_assignment_time,
            'description' => $request->description,
            // 'kendala' => $request->kendala,
        ]);
        return redirect()->route('assignments.index');
    }

    public function show(Assignments $assignments)
    {
        //
    }

    public function edit(Assignments $assignments)
    {
        //
    }

    public function update(Request $request, Assignments $assignments)
    {
        //
    }

    public function destroy(Assignments $assignments)
    {
        //
    }

    public function markAsComplete(Assignments $assignment)
    {

        $assignment->update(['progres' => 'Selesai']); // Ubah nilai 'progress' menjadi 'Selesai'

        return redirect()->route('assignments.index')->with('success', 'Penugasan telah ditandai sebagai selesai.');
    }

    public function reportKendala(Assignments $assignment)
    {
        $assignment->update([
            'progres' => 'Pending',
            'kendala' => request('kendala'),
        ]);

        return redirect()->back()->with('success', 'Kendala berhasil dilaporkan.');
    }
}
