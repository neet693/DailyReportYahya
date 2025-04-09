<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\LogAgenda;
use Illuminate\Http\Request;

class LogAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validasi awal
        $request->validate([
            'agenda_id' => 'required|exists:agendas,id',
            'log_detail' => 'required|string',
        ]);

        $agenda = Agenda::findOrFail($request->input('agenda_id'));

        // Cek apakah user adalah eksekutor (pakai ID, bukan user object)
        if (!$agenda->executors->contains(auth()->id())) {
            abort(403, 'Kamu tidak berhak menambahkan log untuk agenda ini.');
        }

        // Simpan log
        LogAgenda::create([
            'agenda_id' => $agenda->id,
            'executor_id' => auth()->id(),
            'log_detail' => $request->input('log_detail'),
        ]);

        return redirect()->route('agendas.show', $agenda->id)
            ->with('success', 'Log berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(LogAgenda $logAgenda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogAgenda $logAgenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogAgenda $logAgenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogAgenda $logAgenda)
    {
        //
    }
}
