<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {
        $today = now()->toDateString();
        $month = now()->month;

        $usersWithTasks = User::with([
            'jobdesk',
            'tasks' => function ($query) use ($today) {
                $query->todayOrPending($today);
            },
            'agendas', // Memanggil relasi agendas
        ])->where('role', '!=', 'admin')->get()->unique('id');


        // $agendas = Auth::user()->agendas;
        $assignments = Assignment::whereMonth('assignment_date', $month)
            ->orderBy('assignment_date', 'asc')->get();

        $generalAnnouncements = Announcement::general()->get();
        $personalAnnouncements = Announcement::personal()->where('recipient_id', auth()->user()->id)->get();
        $announcements = $generalAnnouncements->merge($personalAnnouncements);
        return view('home', compact('usersWithTasks', 'announcements', 'assignments'));
    }
}
