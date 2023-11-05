<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $announcements = Announcement::all();
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        $users = User::all();
        return view('announcements.create', compact('users'));
    }

    public function store(Request $request)
    {
        if ($request->category === 'umum') {
            $request['recipient_id'] = 0;
        }

        // Buat pengumuman
        Announcement::create($request->all());

        return redirect()->route('announcements.index');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $announcement->update($request->all());
        return redirect()->route('announcements.index');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index');
    }
}
