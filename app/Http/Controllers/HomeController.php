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

        // Jika bukan admin dan belum isi unit kerja
        if (!$user->isAdmin() && !$user->employmentDetail) {
            return redirect()->route('profile.index')->with('error', 'Lengkapi data unit kerja terlebih dahulu.');
        }

        // Ambil users sesuai role dan unit
        $usersWithTasks = collect(); // default kosong

        if ($user->isAdmin()) {
            // Admin lihat semua user yang punya unit
            $usersWithTasks = User::with([
                'jobdesk',
                'tasks' => fn($query) => $query->todayOrPending($today),
                'agendas',
                'employmentDetail.unit'
            ])->whereHas('employmentDetail', fn($query) => $query->whereNotNull('unit_kerja_id'))
                ->where('role', '!=', User::ROLE_ADMIN)
                ->get();
        } elseif ($user->employmentDetail?->unit_kerja_id) {
            // Selain admin, lihat hanya rekan satu unit
            $usersWithTasks = User::with([
                'jobdesk',
                'tasks' => fn($query) => $query->todayOrPending($today),
                'agendas',
                'employmentDetail.unit'
            ])->whereHas(
                'employmentDetail',
                fn($query) =>
                $query->where('unit_kerja_id', $user->employmentDetail->unit_kerja_id)
            )->where('role', '!=', User::ROLE_ADMIN)
                ->get();
        } else {
            // Tidak punya unit: tampilkan hanya dirinya
            $usersWithTasks = collect([$user->load(['jobdesk', 'tasks', 'agendas', 'employmentDetail.unit'])]);
        }

        // Assignment sesuai bulan ini, filter berdasarkan unit
        $assignments = Assignment::whereMonth('assignment_date', $month)
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

        // Ambil pengumuman
        $generalAnnouncements = Announcement::general()->get();
        $personalAnnouncements = Announcement::personal()->where('recipient_id', $user->id)->get();
        $announcements = $generalAnnouncements->merge($personalAnnouncements);

        return view('home', compact('usersWithTasks', 'announcements', 'assignments'));
    }
}
