<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::now()->toDateString();

        $usersWithTasks = User::with(['tasks' => function ($query) use ($today) {
            $query->whereDate('task_date', $today);
        }])->get();

        $announcements = Announcement::first();

        return view('home', compact('usersWithTasks', 'announcements'));
    }
}
