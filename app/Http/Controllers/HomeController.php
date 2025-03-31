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
        $user = Auth::user();

        // Jika user tidak memiliki employmentDetail, arahkan ke halaman profil
        if (!$user->employmentDetail) {
            return redirect()->route('profile')->with('error', 'Lengkapi data unit kerja terlebih dahulu.');
        }

        // Query untuk menampilkan user dengan tugasnya
        $usersWithTasks = User::with([
            'jobdesk',
            'tasks' => function ($query) use ($today) {
                $query->todayOrPending($today);
            },
            'agendas',
            'employmentDetail.unit'
        ])->whereHas('employmentDetail', function ($query) {
            $query->whereNotNull('unit_kerja_id');
        })->where('role', '!=', 'admin')->get();

        // Ambil assignments sesuai bulan ini
        $assignments = Assignment::whereMonth('assignment_date', $month)
            ->orderBy('assignment_date', 'asc')
            ->when($user->role !== 'admin', function ($query) use ($user) {
                return $query->whereHas('user.employmentDetail', function ($q) use ($user) {
                    $q->where('unit_kerja_id', $user->employmentDetail?->unit_kerja_id);
                });
            })
            ->get();

        // Ambil pengumuman (general & personal)
        $generalAnnouncements = Announcement::general()->get();
        $personalAnnouncements = Announcement::personal()->where('recipient_id', $user->id)->get();
        $announcements = $generalAnnouncements->merge($personalAnnouncements);

        return view('home', compact('usersWithTasks', 'announcements', 'assignments'));
    }
}
