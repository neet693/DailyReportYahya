<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $query->where(function ($query) use ($today) {
                $query->whereDate('task_date', $today)
                    ->orWhere(function ($query) use ($today) {
                        $query->where('progres', 0)
                            ->whereDate('task_date', '<', $today);
                    });
            });
        }])->where('role', '!=', 'admin')->get();

        $usersWithTasks->each(function ($user) {
            $user->tasks = $user->tasks->sortBy('task_date');
        });

        $usersWithTasks = $usersWithTasks->sortBy(function ($user) {
            return $user->id !== auth()->user()->id;
        });

        $announcements = Announcement::all();

        $assignments = Assignment::orderBy('assignment_date', 'asc')->get();

        $user = Auth::user();

        return view('home', compact('usersWithTasks', 'announcements', 'assignments', 'user'));
    }
}
