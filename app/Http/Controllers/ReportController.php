<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDF;

class ReportController extends Controller
{

    public function filteredReport(Request $request)
    {
        $month = $request->input('month');
        $week = $request->input('week');

        $year = now()->year; // atau sesuaikan tahun dari form kalau ada
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        if ($week) {
            // Hitung minggu keberapa dalam bulan
            $startDate = $startOfMonth->copy()->addWeeks($week - 1)->startOfWeek(Carbon::MONDAY);
            $endDate = $startDate->copy()->endOfWeek(Carbon::SUNDAY);

            // Biar tetap dalam bulan
            if ($startDate < $startOfMonth) $startDate = $startOfMonth;
            if ($endDate > $endOfMonth) $endDate = $endOfMonth;
        } else {
            // Kalau tidak pilih minggu, ambil 1 bulan penuh
            $startDate = $startOfMonth;
            $endDate = $endOfMonth;
        }

        $tasks = Task::where('task_user_id', auth()->id())
            ->whereBetween('task_date', [$startDate, $endDate])
            ->get();

        $assignments = Assignment::where('user_id', auth()->id())
            ->whereBetween('assignment_date', [$startDate, $endDate])
            ->get();

        $pdf = PDF::loadView('report.monthly', compact('tasks', 'assignments', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan.pdf');
    }
}
