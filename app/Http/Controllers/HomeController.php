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
        $search = request('search');
        $user = Auth::user()->load('employmentDetail.unit');
        $agendas = $user->agendas->where('status', '!=', 'selesai');
        $authId = $user->id;
        $chatUsers = User::where('id', '!=', $authId)
            ->withCount([
                'sentMessages as unread_count' => function ($query) use ($authId) {
                    $query->where('receiver_id', $authId)
                        ->where('is_read', false);
                }
            ])
            ->get();


        if (!$user->isAdmin() && !$user->employmentDetail) {
            return redirect()->route('profile.index')->with('error', 'Lengkapi data unit kerja terlebih dahulu.');
        }

        if (($user->role === 'hrd' || $user->role === 'admin') && !request()->has('lihat_home')) {
            $units = UnitKerja::all();

            // Total semua pegawai per unit
            $pegawaiCounts = $units->mapWithKeys(function ($unit) {
                $userIdsFromPrimary = EmploymentDetail::where('unit_kerja_id', $unit->id)->pluck('user_id')->toArray();
                $userIdsFromPivot = DB::table('unit_user')->where('unit_id', $unit->id)->pluck('user_id')->toArray();
                $uniqueUserIds = collect(array_merge($userIdsFromPrimary, $userIdsFromPivot))->unique();
                return [$unit->id => $uniqueUserIds->count()];
            });

            // Hitung total semua unit (semua laki-laki dan perempuan)
            $lk = User::where('gender', 'Laki-laki')->count();
            $pr = User::where('gender', 'Perempuan')->count();
            $total_user = User::count();
            $total_pegawai = User::count();

            // Tambahan: jumlah laki-laki dan perempuan per unit
            $lkPerUnit = [];
            $prPerUnit = [];

            foreach ($units as $unit) {
                $userIdsFromPrimary = EmploymentDetail::where('unit_kerja_id', $unit->id)->pluck('user_id')->toArray();
                $userIdsFromPivot = DB::table('unit_user')->where('unit_id', $unit->id)->pluck('user_id')->toArray();
                $uniqueUserIds = collect(array_merge($userIdsFromPrimary, $userIdsFromPivot))->unique();

                $lkPerUnit[$unit->id] = User::whereIn('id', $uniqueUserIds)->where('gender', 'Laki-laki')->count();
                $prPerUnit[$unit->id] = User::whereIn('id', $uniqueUserIds)->where('gender', 'Perempuan')->count();
            }

            return view('dashboard.hrd', compact(
                'agendas',
                'units',
                'pegawaiCounts',
                'lk',
                'pr',
                'total_user',
                'total_pegawai',
                'lkPerUnit',
                'prPerUnit'
            ));
        }

        // Untuk pengguna biasa
        $units = UnitKerja::all();
        $activeUnitId = session('active_unit_id'); // Unit yang dipilih

        if ($user->isAdmin() || $activeUnitId) {
            $usersWithTasks = $this->getUsersWithTasks($today, $activeUnitId, $search);
            $assignments = $this->getAssignmentsWithUsers($month, $activeUnitId);
        } else {
            $unitId = $user->employmentDetail?->unit_kerja_id;
            $usersWithTasks = $this->getUsersWithTasks($today, $unitId, $search);
            $assignments = $this->getAssignmentsWithUsers($month, $unitId);
        }

        $usersWithTasks = $usersWithTasks->sortByDesc(fn($u) => $u->id === auth()->id());

        $todayAssignments = $this->getAssignmentsWithUsers($month, $unitId, true);

        /** tempelin ke user object */
        $todayAssignments->groupBy('user_id')->each(function ($items, $userId) use ($usersWithTasks) {
            if ($user = $usersWithTasks->firstWhere('id', $userId)) {
                $user->todayAssignments = $items;
            }
        });

        /** default empty collection biar blade aman */
        $usersWithTasks->each(fn($u) => $u->todayAssignments ??= collect());

        $announcements = Announcement::where(function ($query) use ($user) {
            $query->where('category', 'umum')
                ->orWhere(function ($q) use ($user) {
                    $q->where('category', 'personal')->where('recipient_id', $user->id);
                });
        })->get();

        $notifications = $this->buildUserTimeline($user);


        return view('home', compact('units', 'usersWithTasks', 'announcements', 'assignments', 'chatUsers', 'agendas', 'notifications'));
    }


    private function getUsersWithTasks(string $today, ?int $unitId = null, ?string $search = null)
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
        ])->where('role', '!=', User::ROLE_ADMIN)
            ->whereHas('employmentDetail', function ($q) {
                $q->where('is_active', true);   // ✅ hanya pegawai aktif
            });

        if ($unitId) {
            $query->whereHas('units', fn($q) => $q->where('unit_kerjas.id', $unitId));
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    private function getAssignmentsWithUsers(
        int|string $month,
        ?int $unitId = null,
        bool $onlyToday = false
    ) {
        $today = now()->toDateString();
        $currentYear = now()->year; // Tambahkan ini (2026)

        $query = Assignment::with([
            'user.employmentDetail.unit',
            'user.units',
            'assigner'
        ])
            ->whereMonth('assignment_date', $month)
            ->whereYear('assignment_date', $currentYear) // WAJIB: Kunci di tahun 2026 saja
            ->whereHas('user.employmentDetail', fn($q) => $q->where('is_active', true))
            ->orderBy('assignment_date', 'asc');

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        $onlyToday
            ? $query->whereDate('assignment_date', $today)
            : $query->whereDate('assignment_date', '!=', $today);

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
            ->with('employmentDetail.unit', 'units', 'jobdesk') // load relasi
            ->get();

        return view('dashboard.pegawai-unit', compact('pegawai', 'unit'));
    }

    private function buildUserTimeline(User $user)
    {
        // 1. Ambil Pengumuman
        $announcements = Announcement::where('category', 'umum')
            ->orWhere(function ($q) use ($user) {
                $q->where('category', 'personal')->where('recipient_id', $user->id);
            })->get();

        // 2. Ambil Agenda (Pastikan relasi agendas di model User sudah benar)
        $agendas = $user->agendas()->get();

        // 3. Ambil Penugasan
        $assignments = Assignment::where('user_id', $user->id)->get();

        // 4. Gabungkan secara manual agar struktur field-nya seragam
        $data = collect();

        foreach ($announcements as $a) {
            $data->push((object)[
                'type' => 'announcements',
                'title' => $a->title,
                'description' => $a->content,
                'date' => $a->created_at,
                'progress' => null,
                // 'route' => route('announcements.show', $a->id)
                'route' => route('announcements.index')
            ]);
        }

        foreach ($agendas as $ag) {
            $data->push((object)[
                'type' => 'agendas',
                'title' => $ag->title,
                'description' => $ag->description,
                'date' => $ag->agenda_date,
                'progress' => null,
                // 'route' => route('agendas.show', $ag->id)
                'route' => route('agendas.index')
            ]);
        }

        foreach ($assignments as $as) {
            $data->push((object)[
                'type' => 'assignments',
                'title' => $as->title,
                'description' => $as->description,
                'date' => $as->assignment_date,
                'progress' => $as->progres,
                // 'route' => route('assignments.show', $as->id)
                'route' => route('assignments.index')
            ]);
        }
        return $data->sortByDesc('date')->values();
    }



    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data pengguna berhasil diimpor.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        // Hapus relasi yang ada, kalau null gak masalah karena akan di-skip
        $user->employmentDetail()->delete();
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
