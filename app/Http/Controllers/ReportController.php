<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Task;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function generateWeeklyReport()
    {
        $startOfWeek = now()->startOfWeek(); // Mulai minggu ini
        $endOfWeek = now()->endOfWeek(); // Akhir minggu ini

        // Ambil data yang memiliki tanggal dalam rentang minggu ini
        $data = Task::whereBetween('task_date', [$startOfWeek, $endOfWeek])->get();
        $assignments = Assignment::all();

        $pdf = PDF::loadView('report.weekly', compact('data', 'assignments'))->setPaper('a4', 'landscape');

        return $pdf->stream('weekly_report.pdf');
    }

    public function generateMonthlyReport()
    {
        $data = Task::whereMonth('task_date', now()->month)->get();
        $assignments = Assignment::all();

        $pdf = PDF::loadView('report.monthly', compact('data', 'assignments'))->setPaper('a4', 'landscape');

        return $pdf->stream('monthly_report.pdf');
    }
}
