<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\View;


class AgendaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Admin bisa lihat semua agenda
            $agendas = Agenda::with('executors')->get();
        } else {
            // User biasa hanya lihat agenda yang mereka ikuti
            $agendas = $user->agendas()->with('executors')->get();
        }

        return view('agendas.index', compact('agendas'));
    }


    public function create()
    {
        $users = User::all();

        return view('agendas.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'executors' => 'required|array',
            'executors.*' => 'exists:users,id',
            'description' => 'required',
        ]);

        $agenda = new Agenda();
        $agenda->title = $request->input('title');
        $agenda->start_date = $request->input('start_date');
        $agenda->end_date = $request->input('end_date');
        $agenda->description = $request->input('description');
        $agenda->save();

        $agenda->executors()->sync($request->input('executors'));

        return redirect()->route('agendas.index')->with('success', 'Agenda created successfully');
    }

    public function edit(Agenda $agenda)
    {
        $users = User::all();
        $selectedExecutors = $agenda->executors->pluck('id')->toArray();

        return view('agendas.edit', compact('agenda', 'users', 'selectedExecutors'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $this->authorize('update', $agenda);
        $request->validate([
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'executors' => 'required|array',
            'executors.*' => 'exists:users,id',
            'description' => 'required',
        ]);

        $agenda->title = $request->input('title');
        $agenda->start_date = $request->input('start_date');
        $agenda->end_date = $request->input('end_date');
        $agenda->description = $request->input('description');
        $agenda->save();

        $agenda->executors()->sync($request->input('executors'));

        return redirect()->route('agendas.index')->with('success', 'Agenda updated successfully');
    }

    public function show(Agenda $agenda)
    {
        return view('agendas.show', compact('agenda'));
    }

    public function destroy(Agenda $agenda)
    {
        $this->authorize('delete', $agenda);
        $agenda->executors()->detach();
        $agenda->delete();

        return redirect()->route('agendas.index')->with('success', 'Agenda deleted successfully');
    }

    public function generateAgenda($id)
    {
        $agenda = Agenda::findOrFail($id);

        $pdf = PDF::loadHTML(View::make('report.agenda', compact('agenda')))
            ->setPaper('landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

        return $pdf->download('agenda.pdf');
    }

    public function updateStatus(Request $request, Agenda $agenda, $id)
    {
        if ($this->authorize('updateStatus', $agenda)) {
            $agenda = Agenda::findOrFail($id);

            $agenda->status = $request->input('status');
            $agenda->save();

            return redirect()->route('agendas.index')->with('success', 'Status agenda berhasil diperbarui.');
        }

        abort(403, 'Unauthorized action.');
    }
}
