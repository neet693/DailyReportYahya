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

        return redirect()->back()->with('success', 'Data Berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
