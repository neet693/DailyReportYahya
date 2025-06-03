<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil rapat sesuai role user
        $meetings = Meeting::with('participants.employmentDetail.unit') // eager load untuk efisiensi
            ->when(!$user->isAdmin(), function ($query) use ($user) {
                $query->whereHas('participants.employmentDetail', function ($q) use ($user) {
                    $q->where('unit_kerja_id', $user->employmentDetail->unit_kerja_id);
                });
            })
            ->orderByDesc('meeting_date') // urutan terbaru duluan (opsional)
            ->get();

        // Ambil peserta untuk tiap meeting
        $participants = [];
        foreach ($meetings as $meeting) {
            $participants[$meeting->id] = $meeting->participants;
        }

        return view('meetings.index', compact('meetings', 'participants'));
    }

    public function create()
    {
        // Ambil semua pengguna
        $users = User::all();  // Ambil semua pengguna dari database

        return view('meetings.create', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'meeting_date' => 'required|date',
            'meeting_start_time' => 'required',
            'meeting_end_time' => 'required',
            'meeting_location' => 'required',
            'meeting_result' => 'required',
            'participant_id' => 'required|array',
        ]);

        $meeting = Meeting::create($request->all());
        $meeting->participants()->attach($request->input('participant_id'));

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil ditambahkan.');
    }

    public function show($id)
    {
        $meeting = Meeting::with('participants')->findOrFail($id);
        return view('meetings.show', compact('meeting'));
    }

    public function edit($id)
    {
        $meeting = Meeting::with(['participants'])->findOrFail($id);
        $users = User::all();  // Ambil semua pengguna
        $selectedUsers = $meeting->participants->pluck('id')->toArray(); // ID peserta yang sudah dipilih

        return view('meetings.edit', compact('meeting', 'users', 'selectedUsers'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'meeting_date' => 'required|date',
            'meeting_start_time' => 'required',
            'meeting_end_time' => 'required',
            'meeting_location' => 'required',
            'meeting_result' => 'required',
            'participant_id' => 'required|array',
        ]);

        $meeting = Meeting::findOrFail($id);
        $meeting->update($request->all());
        $meeting->participants()->sync($request->input('participant_id'));

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->delete();

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil dihapus.');
    }

    public function uploadAttachment(Request $request)
    {
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('attachments'), $filename);

            return response()->json([
                'url' => asset('attachments/' . $filename),
            ]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
