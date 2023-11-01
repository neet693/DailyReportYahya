<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today = Carbon::now()->toDateString();

        $usersWithTasks = User::with(['tasks' => function ($query) use ($today) {
            $query->whereDate('task_date', $today);
        }])->get();

        $announcements = Announcement::all();

        $assignments = Assignments::all();

        return view('home', compact('usersWithTasks', 'announcements', 'assignments'));
    }
}
