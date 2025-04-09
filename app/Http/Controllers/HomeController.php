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
        $user = Auth::user()->load('employmentDetail.unit');

        if (!$user->isAdmin() && !$user->employmentDetail) {
            return redirect()->route('profile.index')->with('error', 'Lengkapi data unit kerja terlebih dahulu.');
        }

        if ($user->isAdmin()) {
            $usersWithTasks = $this->getUsersWithTasks($today);
        } elseif ($user->employmentDetail?->unit_kerja_id) {
            $usersWithTasks = $this->getUsersWithTasks($today, $user->employmentDetail->unit_kerja_id);
        } else {
            $usersWithTasks = collect([$user->load([
                'jobdesk',
                'tasks' => fn($query) => $query->todayOrPending($today),
                'agendas',
                'employmentDetail.unit'
            ])]);
        }

        $assignments = Assignment::with(['user.employmentDetail.unit', 'assigner'])
            ->whereMonth('assignment_date', $month)
            ->orderBy('assignment_date', 'asc')
            ->when(
                !$user->isAdmin(),
                fn($query) =>
                $query->whereHas(
                    'user.employmentDetail',
                    fn($q) =>
                    $q->where('unit_kerja_id', $user->employmentDetail?->unit_kerja_id)
                )
            )
            ->get();

        $announcements = Announcement::where(function ($query) use ($user) {
            $query->where('category', 'umum')
                ->orWhere(function ($q) use ($user) {
                    $q->where('category', 'personal')->where('recipient_id', $user->id);
                });
        })->get();

        return view('home', compact('usersWithTasks', 'announcements', 'assignments'));
    }

    private function getUsersWithTasks(string $today, ?int $unitId = null)
    {
        $query = User::with([
            'jobdesk',
            'tasks' => fn($query) => $query->todayOrPending($today),
            'agendas',
            'employmentDetail.unit'
        ])->where('role', '!=', User::ROLE_ADMIN);

        if (is_null($unitId)) {
            return $query->whereHas('employmentDetail', fn($q) => $q->whereNotNull('unit_kerja_id'))->get();
        }

        return $query->whereHas('employmentDetail', fn($q) => $q->where('unit_kerja_id', $unitId))->get();
    }
}
