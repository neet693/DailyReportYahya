<?php

namespace App\Http\Controllers;

use App\Models\JobDesk;
use App\Models\User;
use Illuminate\Http\Request;

class JobDeskController extends Controller
{

    public function index()
    {
        $Jobdesks = JobDesk::all();
        return view('jobdesks.index', compact('Jobdesks'));
    }

    public function create()
    {
        $users = User::all(); // Ambil semua pengguna yang dapat dipilih
        return view('jobdesks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'jobdesk_user_id' => 'required|exists:users,id', // Pastikan jobdesk_user_id ada dalam tabel users
        ]);

        $Jobdesk = new JobDesk([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'jobdesk_user_id' => $request->input('jobdesk_user_id'),
        ]);

        $Jobdesk->save();

        return redirect()->route('jobdesks.index')->with('success', 'Job Desc created successfully');
    }

    public function show(JobDesk $jobDesc)
    {
        // return view('jobdesks.show', compact('jobDesc'));
    }

    public function edit(JobDesk $Jobdesk)
    {
        $users = User::all(); // Ambil semua pengguna yang dapat dipilih
        return view('jobdesks.edit', compact('Jobdesk', 'users'));
    }

    public function update(Request $request, JobDesk $Jobdesk)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'jobdesk_user_id' => 'required|exists:users,id', // Pastikan jobdesk_user_id ada dalam tabel users
        ]);

        $Jobdesk->title = $request->input('title');
        $Jobdesk->description = $request->input('description');
        $Jobdesk->jobdesk_user_id = $request->input('jobdesk_user_id');
        $Jobdesk->save();

        return redirect()->route('jobdesks.index')->with('success', 'Job Desc updated successfully');
    }

    public function destroy(JobDesk $Jobdesk)
    {
        $Jobdesk->delete();

        return redirect()->route('jobdesks.index')->with('success', 'Job Desc deleted successfully');
    }
}
