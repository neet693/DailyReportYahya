<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\EmploymentDetail;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

        if (($user->role === 'hrd' || $user->role === 'admin') && !request()->has('lihat_home')) {
            $units = UnitKerja::all();

            $pegawaiCounts = $units->mapWithKeys(function ($unit) {
                $userIdsFromPrimary = EmploymentDetail::where('unit_kerja_id', $unit->id)->pluck('user_id')->toArray();
                $userIdsFromPivot = DB::table('unit_user')->where('unit_id', $unit->id)->pluck('user_id')->toArray();
                $uniqueUserIds = collect(array_merge($userIdsFromPrimary, $userIdsFromPivot))->unique();
                return [$unit->id => $uniqueUserIds->count()];
            });

            $lk = User::where('gender', 'Laki-laki')->count();
            $pr = User::where('gender', 'Perempuan')->count();
            $total_user = User::count();
            $total_pegawai = User::count();

            return view('dashboard.hrd', compact('units', 'pegawaiCounts', 'lk', 'pr', 'total_user', 'total_pegawai'));
        }
        $units = UnitKerja::all();
        $activeUnitId = session('active_unit_id'); // Unit yang dipilih

        if ($user->isAdmin() || $activeUnitId) {
            $usersWithTasks = $this->getUsersWithTasks($today, $activeUnitId);
            $assignments = $this->getAssignmentsWithUsers($month, $activeUnitId);
        } else {
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

        return view('home', compact('units', 'usersWithTasks', 'announcements', 'assignments'));
    }


    private function getUsersWithTasks(string $today, ?int $unitId = null)
    {
        $query = User::with([
            'jobdesk',
            'tasks' => function ($query) use ($today, $unitId) {
                $query->todayOrPending($today);
                if ($unitId) {
                    $query->where('unit_id', $unitId);
                }
            },
            'agendas',
            'units'
        ])->where('role', '!=', User::ROLE_ADMIN);

        if (!is_null($unitId)) {
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

    public function showPegawaiByUnit($unitId)
    {
        $unit = UnitKerja::findOrFail($unitId);

        // Ambil ID user dari employment_detail
        $primaryUserIds = EmploymentDetail::where('unit_kerja_id', $unitId)
            ->pluck('user_id')
            ->toArray();

        // Ambil ID user dari pivot table unit_user
        $pivotUserIds = DB::table('unit_user')
            ->where('unit_id', $unitId)
            ->pluck('user_id')
            ->toArray();

        // Gabungkan semua user id tanpa duplikat
        $userIds = collect(array_merge($primaryUserIds, $pivotUserIds))->unique();

        // Ambil user lengkap
        $pegawai = User::whereIn('id', $userIds)
            ->whereIn('role', ['pegawai', 'kepala', 'hrd']) // role yang relevan
            ->with('employmentDetail.unit', 'units') // load relasi
            ->get();

        return view('dashboard.pegawai-unit', compact('pegawai', 'unit'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data pengguna berhasil diimpor.');
    }
}
