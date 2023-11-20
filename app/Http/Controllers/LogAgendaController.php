<?php

namespace App\Http\Controllers;

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
        // Validasi form jika diperlukan
        $request->validate([
            'agenda_id' => 'required|exists:agendas,id',
            'executor_id' => 'required',
            'log_detail' => 'required',
        ]);

        // Simpan log baru ke basis data
        LogAgenda::create([
            'agenda_id' => $request->input('agenda_id'),
            'executor_id' => auth()->id(),
            'log_detail' => $request->input('log_detail'),
        ]);

        // Redirect kembali ke halaman agenda.show
        return redirect()->route('agendas.show', $request->input('agenda_id'))
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
