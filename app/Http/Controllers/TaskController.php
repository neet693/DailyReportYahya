<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::all();
        return view('task.index', compact('tasks'));
    }


    public function create()
    {
        return view('task.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:50',
            'place' => 'required|max:50',
            'task_date' => 'required|date',
            'task_start_time' => 'required|date_format:H:i',
            'task_end_time' => 'required|date_format:H:i|after:start_task_time',
            'description' => 'required'
        ]);

        $task = new Task();
        $task->task_user_id = auth()->id(); // ID pengguna yang sedang login
        $task->title = $request->input('title');
        $task->place = $request->input('place');
        $task->task_date = $request->input('task_date');
        $task->task_start_time = $request->input('task_start_time');
        $task->task_end_time = $request->input('task_end_time');
        $task->description = $request->input('description');
        $task->save();

        return redirect('/tasks')->with('success', 'Data Berhasil dibuat');
    }

    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('task.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        return redirect('/tasks')->with('success', 'Data Berhasil dibuat');
    }

    public function destroy(Task $task)
    {
        //
    }

    public function markAsComplete(Request $request, Task $task)
    {
        $task->update([
            'progres' => 1,
        ]);

        return back()->with('success', 'Tugas selesai.');
    }

    public function markAsPending(Request $request, Task $task)
    {
        $request->validate([
            'description' => 'required',
        ]);
        $task->update([
            'progres' => 0,
            'description' => $request->input('description')
        ]);

        return back()->with('success', 'Tugas ditandai sebagai pending.');
    }
}
