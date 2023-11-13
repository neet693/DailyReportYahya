<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    // Menampilkan daftar semua rapat
    public function index()
    {
        $meetings = Meeting::all();
        $participants = [];

    foreach ($meetings as $meeting) {
        $participants[$meeting->id] = $meeting->participants;
    }
        return view('meetings.index', compact('meetings','participants'));
    }

    // Menampilkan formulir untuk membuat rapat baru
    public function create()
    {
        $users = User::all();
        return view('meetings.create', compact('users'));
    }

    // Menyimpan rapat baru ke dalam database
    public function store(Request $request)
    {
        // Validasi data rapat
        $request->validate([
            'title' => 'required',
            'meeting_date' => 'required|date',
            'meeting_start_time' => 'required',
            'meeting_end_time' => 'required',
            'meeting_location' => 'required',
            'meeting_result' => 'required',
            'participant_id' => 'required|array', // Pastikan 'users' adalah array
        ]);

        // Simpan data rapat
        $meeting = Meeting::create($request->all());

        // Simpan peserta rapat
        $meeting->participants()->attach($request->input('participant_id'));

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil ditambahkan.');
    }

    // Menampilkan detail rapat
    public function show($id)
    {
        $meeting = Meeting::findOrFail($id);
        $peserta = $meeting->participants;
        return view('meetings.show', compact('meeting','peserta'));
    }

    // Menampilkan formulir untuk mengedit rapat
    public function edit($id)
    {
        $meeting = Meeting::findOrFail($id);
        $users = User::all();
        $selectedUsers = $meeting->participants->pluck('id')->toArray();
        return view('meetings.edit', compact('meeting', 'users', 'selectedUsers'));
    }

    // Menyimpan perubahan data rapat ke dalam database
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'title' => 'required',
            'meeting_date' => 'required|date',
            'meeting_start_time' => 'required',
            'meeting_end_time' => 'required',
            'meeting_location' => 'required',
            'meeting_result' => 'required',
            'participant_id' => 'required|array', // Pastikan 'users' adalah array
        ]);

        // Temukan rapat yang akan diperbarui berdasarkan ID
        $meeting = Meeting::findOrFail($id);

        // Update data rapat dengan data yang diterima
        $meeting->update($request->all());

        // Sinkronkan peserta rapat
        $meeting->participants()->sync($request->input('participant_id'));

        // Redirect ke halaman daftar rapat dengan pesan sukses
        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil diperbarui.');
    }

    // Menghapus rapat dari database
    public function destroy($id)
    {
        // Temukan rapat yang akan dihapus berdasarkan ID
        $meeting = Meeting::findOrFail($id);

        // Hapus rapat dari database
        $meeting->delete();

        // Redirect ke halaman daftar rapat dengan pesan sukses
        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil dihapus.');
    }
}
