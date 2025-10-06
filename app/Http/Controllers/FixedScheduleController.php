<?php

namespace App\Http\Controllers;

use App\Models\FixedTask;
use App\Models\User;
use Illuminate\Http\Request;

class FixedScheduleController extends Controller
{
    public function create(Request $request)
    {
        $selectedUserId = $request->input('user_id');

        if (auth()->user()->isKepalaUnit()) {
            // Ambil ID unit yang dipimpin
            $unitIds = auth()->user()->units->pluck('id')->toArray();

            // Ambil user dari employmentDetail atau unit_user pivot
            $users = User::where(function ($q) use ($unitIds) {
                $q->whereHas('employmentDetail', function ($ed) use ($unitIds) {
                    $ed->whereIn('unit_kerja_id', $unitIds);
                })->orWhereHas('units', function ($unit) use ($unitIds) {
                    $unit->whereIn('unit_kerjas.id', $unitIds);
                });
            })->get();
        } else {
            $users = collect([auth()->user()]);
        }

        return view('fixed_schedule.create', compact('users', 'selectedUserId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'type' => 'required|string',
            'subject' => 'required|string',
            'classroom' => 'required|string',
            'description' => 'nullable|string',
        ]);

        FixedTask::create($request->all());

        return redirect()->route('home')->with('success', 'Jadwal tetap berhasil ditambahkan.');
    }

    // 🔹 Edit Jadwal
    public function edit(FixedTask $fixedTask)
    {
        // Validasi agar kepala hanya bisa edit milik unitnya
        if (auth()->user()->isKepalaUnit()) {
            $unitIds = auth()->user()->units->pluck('id')->toArray();

            if (
                !$fixedTask->user ||
                (
                    $fixedTask->user->employmentDetail &&
                    !in_array($fixedTask->user->employmentDetail->unit_kerja_id, $unitIds)
                )
            ) {
                abort(403, 'Anda tidak memiliki izin untuk mengedit jadwal ini.');
            }

            $users = User::whereHas('employmentDetail', function ($ed) use ($unitIds) {
                $ed->whereIn('unit_kerja_id', $unitIds);
            })->get();
        } else {
            $users = collect([auth()->user()]);
        }

        return view('fixed_schedule.edit', compact('fixedTask', 'users'));
    }

    // 🔹 Update Jadwal
    public function update(Request $request, FixedTask $fixedTask)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'type' => 'required|string',
            'subject' => 'required|string',
            'classroom' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $fixedTask->update($request->all());

        return redirect()->route('home')->with('success', 'Jadwal tetap berhasil diperbarui.');
    }
}
