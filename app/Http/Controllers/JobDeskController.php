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
        if (auth()->user()->role === 'admin') {
            $users = User::all();
        } elseif (auth()->user()->role === 'kepala') {
            // Ambil unit_id dari employment_detail user yang login
            $unitId = auth()->user()->employmentDetail->unit_id ?? null;

            $users = User::whereHas('employmentDetail', function ($query) use ($unitId) {
                $query->where('unit_kerja_id', $unitId);
            })->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('jobdesks.create', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'jobdesk_user_id' => 'required|exists:users,id',
        ]);

        // Cek siapa yang login
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Admin boleh buat ke siapapun
            $jobdeskUser = User::findOrFail($request->input('jobdesk_user_id'));
        } elseif ($user->role === 'kepala') {
            // Kepala hanya boleh ke anggota unitnya

            $unitId = $user->employmentDetail->unit_id ?? null;

            $jobdeskUser = User::whereHas('employmentDetail', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
                ->where('id', $request->input('jobdesk_user_id'))
                ->first();

            if (!$jobdeskUser) {
                return back()->withErrors(['jobdesk_user_id' => 'User yang dipilih bukan bagian unit Anda.']);
            }
        } else {
            abort(403, 'Unauthorized');
        }

        // Jika semua validasi lolos, buat JobDesk
        $jobdesk = new JobDesk([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'jobdesk_user_id' => $jobdeskUser->id,
        ]);

        $jobdesk->save();

        // Kalau Admin dan title ada kata 'kepala', ubah role user yang ditugaskan
        if ($user->role === 'admin' && str_contains(strtolower($request->input('title')), 'kepala')) {
            $jobdeskUser->update([
                'role' => 'kepala', // atau sesuai naming role di database kamu
            ]);
        }

        return redirect()->route('jobdesks.index')->with('success', 'Jobdesk berhasil dibuat.');
    }


    public function show(JobDesk $jobDesc)
    {
        // return view('jobdesks.show', compact('jobDesc'));
    }

    public function edit(JobDesk $jobdesk)
    {
        if (auth()->user()->role === 'admin') {
            $users = User::all();
        } elseif (auth()->user()->role === 'kepala') {
            $unitId = auth()->user()->employmentDetail->unit_id ?? null;

            if (!$unitId) {
                abort(403, 'Anda tidak memiliki data unit kerja.');
            }

            $users = User::whereHas('employmentDetail', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('jobdesks.edit', compact('jobdesk', 'users'));
    }


    public function update(Request $request, JobDesk $jobdesk)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'jobdesk_user_id' => 'required|exists:users,id',
        ]);

        $user = auth()->user();

        if ($user->role === 'admin') {
            $jobdeskUser = User::findOrFail($request->input('jobdesk_user_id'));
        } elseif ($user->role === 'kepala') {
            $unitId = $user->employmentDetail->unit_id ?? null;

            if (!$unitId) {
                abort(403, 'Anda tidak memiliki data unit kerja.');
            }

            $jobdeskUser = User::whereHas('employmentDetail', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
                ->where('id', $request->input('jobdesk_user_id'))
                ->first();

            if (!$jobdeskUser) {
                return back()->withErrors(['jobdesk_user_id' => 'User yang dipilih bukan bagian unit Anda.']);
            }
        } else {
            abort(403, 'Unauthorized');
        }

        $jobdesk->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'jobdesk_user_id' => $jobdeskUser->id,
        ]);

        return redirect()->route('jobdesks.index')->with('success', 'Jobdesk berhasil diperbarui.');
    }


    public function destroy(JobDesk $Jobdesk)
    {
        $Jobdesk->delete();

        return redirect()->route('jobdesks.index')->with('success', 'Job Desc deleted successfully');
    }
}
