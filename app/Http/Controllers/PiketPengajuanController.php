<?php

namespace App\Http\Controllers;

// use App\Models\PiketKuota;
use App\Services\PiketService;
use App\Models\PiketPengajuan;
use Illuminate\Http\Request;

class PiketPengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = PiketPengajuan::with([
            'user',
            'approver'
        ])->get();

        return view(
            'piket_harians.index',
            compact('pengajuans')
        );
    }

    // public function events(PiketService $service)
    // {
    //     $events = PiketPengajuan::with('user')
    //         ->get()
    //         ->map(function ($item) use ($service) {

    //             $sisa = $service->getSisaKuota(
    //                 $item->user->unit_kerja_id,
    //                 $item->tanggal_piket
    //             );

    //             return [
    //                 'title' => $item->user->name,
    //                 'start' => $item->tanggal_piket,
    //                 'color' => $item->status === 'approved' ? '#198754' : '#ffc107',

    //                 'extendedProps' => [
    //                     'sisa_kuota' => $sisa
    //                 ]
    //             ];
    //         });

    //     dd($service->getSisaKuota(
    //         $item->user->unit_kerja_id,
    //         $item->tanggal_piket
    //     ));
    //     return response()->json($events);
    // }

    public function events(PiketService $service)
    {
        $events = PiketPengajuan::with('user')
            ->get()
            ->map(function ($item) use ($service) {

                $tanggal = \Carbon\Carbon::parse($item->tanggal_piket)->toDateString();

                $sisa = $service->getSisaKuota(
                    $item->user->employmentDetail->unit_kerja_id,
                    $tanggal
                );

                return [
                    'title' => $item->user->name,
                    'start' => $tanggal,
                    'color' => $item->status === 'approved' ? '#198754' : '#ffc107',

                    'extendedProps' => [
                        'sisa_kuota' => $sisa
                    ]
                ];
            });

        return response()->json($events);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('piket_harians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_piket' => [
                'required',
                'date',
            ],
        ]);

        $user = auth()->user();

        $exists = PiketPengajuan::query()
            ->where('user_id', $user->id)
            ->whereDate(
                'tanggal_piket',
                $validated['tanggal_piket']
            )
            ->exists();

        // if ($exists) {
        //     return back()->withErrors([
        //         'tanggal_piket' =>
        //         'Anda sudah mengajukan tanggal tersebut.'
        //     ]);
        // }
        if ($exists) {
            return back()->with(
                'error',
                'Anda sudah mengajukan tanggal tersebut.'
            );
        }

        $service = app(PiketService::class);

        $sisaKuota = $service->getSisaKuota(
            $user->employmentDetail->unit_kerja_id,
            $validated['tanggal_piket']
        );

        if ($sisaKuota <= 0) {
            return back()->with(
                'error',
                'Kuota piket pada tanggal tersebut sudah penuh.'
            );
        }


        $status = 'pending';

        if ($user->isKepalaUnit()) {
            $status = 'approved';
        }

        PiketPengajuan::create([
            'user_id' => $user->id,
            'tanggal_piket' => $validated['tanggal_piket'],
            'status' => $status,
            'approved_by' => $status === 'approved'
                ? $user->id
                : null,
            'approved_at' => $status === 'approved'
                ? now()
                : null,
        ]);

        return back()->with(
            'success',
            'Pengajuan berhasil dibuat.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PiketPengajuan $piketPengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PiketPengajuan $piketPengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PiketPengajuan $piketPengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PiketPengajuan $piketPengajuan)
    {
        //
    }

    public function approve(PiketPengajuan $piketPengajuan)
    {
        $user = auth()->user();

        if (!$user->isKepalaUnit()) {
            abort(403);
        }

        if (
            $piketPengajuan->user->employmentDetail->unit_kerja_id
            !==
            $user->employmentDetail->unit_kerja_id
        ) {
            abort(403);
        }

        if ($piketPengajuan->status !== 'pending') {
            return back()->withErrors([
                'error' => 'Pengajuan sudah diproses.'
            ]);
        }

        $piketPengajuan->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'Pengajuan disetujui.'
        );
    }

    public function cancel(PiketPengajuan $piketPengajuan)
    {
        if (
            $piketPengajuan->user_id !== auth()->id()
        ) {
            abort(403);
        }

        $piketPengajuan->update([
            'status' => 'cancelled'
        ]);

        return back()->with(
            'success',
            'Pengajuan dibatalkan.'
        );
    }

    public function reject(PiketPengajuan $piketPengajuan)
    {
        $user = auth()->user();

        if (!$user->isKepalaUnit()) {
            abort(403);
        }

        if (
            $piketPengajuan->user->employmentDetail->unit_kerja_id
            !==
            $user->employmentDetail->unit_kerja_id
        ) {
            abort(403);
        }

        if ($piketPengajuan->status !== 'pending') {
            return back()->with(
                'error',
                'Pengajuan sudah diproses.'
            );
        }

        $piketPengajuan->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'Pengajuan ditolak.'
        );
    }

    public function approvalList()
    {
        $unitKerjaId = auth()->user()->unit_kerja_id;

        $pengajuans = PiketPengajuan::query()
            ->whereRelation(
                'user',
                'unit_kerja_id',
                $unitKerjaId
            )
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view(
            'piket-pengajuan.approval',
            compact('pengajuans')
        );
    }

    public function checkUserQuota()
    {
        $user = auth()->user();

        $aktif = PiketPengajuan::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        return response()->json([
            'can_apply' => !$aktif
        ]);
    }
}
