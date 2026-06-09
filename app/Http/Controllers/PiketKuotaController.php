<?php

namespace App\Http\Controllers;

use App\Models\PiketKuota;
use App\Models\PiketPengajuan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class PiketKuotaController extends Controller
{
    public function index()
    {
        $kuotas = PiketKuota::with('unit')
            ->latest()
            ->paginate(10);

        return view('piket-harian.index', compact('kuotas'));
    }

    public function create()
    {

        $unit = \App\Models\UnitKerja::find(
            auth()->user()->employmentDetail?->unit_kerja_id
        );

        $piketKuotas = PiketKuota::with('unitKerja')->get();


        $pengajuans = PiketPengajuan::with([
            'user',
            'approver'
        ])->get();

        return view('piket_harians.pengaturan', compact(
            'unit',
            'piketKuotas',
            'pengajuans'
        ));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'unit_kerja_id' => ['required', 'exists:unit_kerjas,id'],
    //         'tanggal_mulai' => ['required', 'date'],
    //         'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
    //         'total_kuota' => ['required', 'integer', 'min:1'],
    //     ]);

    //     $validated['dibuat_oleh'] = auth()->id();

    //     PiketKuota::create($validated);

    //     return redirect()
    //         ->back()
    //         ->with('success', 'Kuota berhasil dibuat.');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_kerja_id' => [
                'required',
                'exists:unit_kerjas,id'
            ],

            'tanggal' => [
                'required',
                'array'
            ],

            'kuota' => [
                'required',
                'array'
            ],
        ]);

        foreach ($request->tanggal as $index => $tanggal) {

            PiketKuota::updateOrCreate(
                [
                    'unit_kerja_id' => $request->unit_kerja_id,
                    'tanggal_mulai' => $tanggal,
                    'tanggal_selesai' => $tanggal,
                ],
                [
                    'total_kuota' => $request->kuota[$index],
                    'dibuat_oleh' => auth()->id(),
                ]
            );
        }

        return back()->with(
            'success',
            'Semua kuota berhasil disimpan.'
        );
    }

    public function edit(PiketKuota $piketKuota)
    {
        $units = UnitKerja::all();

        return view('piket-kuota.edit', compact(
            'piketKuota',
            'units'
        ));
    }

    public function update(
        Request $request,
        PiketKuota $piketKuota
    ) {
        $validated = $request->validate([
            'unit_kerja_id' => ['required', 'exists:unit_kerjas,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'total_kuota' => ['required', 'integer', 'min:1'],
        ]);

        $piketKuota->update($validated);

        return redirect()
            ->route('piket-kuota.index')
            ->with('success', 'Kuota berhasil diperbarui.');
    }

    public function destroy(PiketKuota $piketKuota)
    {
        $piketKuota->delete();

        return back()->with(
            'success',
            'Kuota berhasil dihapus.'
        );
    }
}
