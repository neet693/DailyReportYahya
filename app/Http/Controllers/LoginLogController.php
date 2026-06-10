<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unitKerjaId = auth()->user()
            ->employmentDetail
            ->unit_kerja_id;

        // Ranking Login
        $loginStats = LoginLog::selectRaw(
            'user_id, COUNT(*) as total_login'
        )
            ->whereHas(
                'user.employmentDetail',
                fn($q) => $q->where('unit_kerja_id', $unitKerjaId)
            )
            ->with([
                'user',
                'user.employmentDetail'
            ])
            ->groupBy('user_id')
            ->orderByDesc('total_login')
            ->get();

        // Total login bulan ini
        $totalLoginBulanIni = LoginLog::whereMonth(
            'login_at',
            now()->month
        )
            ->whereYear(
                'login_at',
                now()->year
            )
            ->whereHas(
                'user.employmentDetail',
                fn($q) => $q->where('unit_kerja_id', $unitKerjaId)
            )
            ->count();

        // Login terakhir
        $lastLogin = LoginLog::with('user')
            ->whereHas(
                'user.employmentDetail',
                fn($q) => $q->where('unit_kerja_id', $unitKerjaId)
            )
            ->latest('login_at')
            ->first();

        // Jumlah pegawai yang pernah login
        $pegawaiAktif = $loginStats->count();

        return view('login-logs.dashboard', compact(
            'loginStats',
            'totalLoginBulanIni',
            'lastLogin',
            'pegawaiAktif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LoginLog $loginLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoginLog $loginLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoginLog $loginLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoginLog $loginLog)
    {
        //
    }
}
