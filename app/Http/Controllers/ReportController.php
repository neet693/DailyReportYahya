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
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $data = Task::where('task_user_id', auth()->id())
            ->whereBetween('task_date', [$startOfWeek, $endOfWeek])
            ->get();

        $assignments = Assignment::where('user_id', auth()->id())->get(); // Pastikan kolom ini benar juga

        $pdf = PDF::loadView('report.weekly', compact('data', 'assignments'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('weekly_report.pdf');
    }

    public function generateMonthlyReport()
    {
        $data = Task::where('task_user_id', auth()->id())
            ->whereMonth('task_date', now()->month)
            ->get();

        $assignments = Assignment::where('user_id', auth()->id())->get(); // Sama juga cek ini

        $pdf = PDF::loadView('report.monthly', compact('data', 'assignments'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('monthly_report.pdf');
    }
}
