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

        $activeUnitId = session('active_unit_id'); // Unit yang dipilih

        // Kondisi untuk mengambil penugasan sesuai dengan unit yang dipilih
        if ($user->isAdmin()) {
            $usersWithTasks = $this->getUsersWithTasks($today);
            $assignments = $this->getAssignmentsWithUsers($month);
        } elseif ($activeUnitId) {
            // Menampilkan tugas dan penugasan hanya untuk unit yang dipilih
            $usersWithTasks = $this->getUsersWithTasks($today, $activeUnitId);
            $assignments = $this->getAssignmentsWithUsers($month, $activeUnitId);
        } else {
            // Default menggunakan unit yang dimiliki user
            $unitId = $user->employmentDetail?->unit_kerja_id;
            $usersWithTasks = $this->getUsersWithTasks($today, $unitId);
            $assignments = $this->getAssignmentsWithUsers($month, $unitId);
        }

        // Urutkan user login di posisi pertama
        $usersWithTasks = $usersWithTasks->sortByDesc(fn($u) => $u->id === auth()->id());

        // Pengumuman
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
            'tasks' => function ($query) use ($today, $unitId) {
                $query->todayOrPending($today);
                if ($unitId) {
                    $query->where('unit_id', $unitId); // filter task by selected unit
                }
            },
            'agendas',
            'units'
        ])->where('role', '!=', User::ROLE_ADMIN);

        if (!is_null($unitId)) {
            // filter user yang punya relasi ke unit tersebut lewat pivot
            $query->whereHas('units', fn($q) => $q->where('unit_kerjas.id', $unitId));
        }

        return $query->get();
    }

    private function getAssignmentsWithUsers(string $month, ?int $unitId = null)
    {
        $query = Assignment::with([
            'user.employmentDetail.unit',
            'user.units', // include user units dari pivot
            'assigner'
        ])
            ->whereMonth('assignment_date', $month)
            ->orderBy('assignment_date', 'asc');

        // Filter penugasan berdasarkan unit_id
        if ($unitId) {
            $query->where('unit_id', $unitId); // Filter penugasan dengan unit_id
        }

        return $query->get();
    }
}
