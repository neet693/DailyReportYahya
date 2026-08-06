<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'task_user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:50',
            'place' => 'required|string|max:50',
            'task_date' => 'required|date',
            'task_start_time' => 'required',
            'task_end_time' => 'required',
            'description' => 'required|string',
        ]);


        $task = Task::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Task berhasil dibuat',
            'data' => $task
        ], 201);
    }


    public function show(Task $task)
    {
        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }


    public function update(Request $request, Task $task)
    {

        $task->update($request->all());


        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diupdate',
            'data' => $task
        ]);
    }


    public function destroy(Task $task)
    {

        $task->delete();


        return response()->json([
            'success' => true,
            'message' => 'Task berhasil dihapus'
        ]);
    }
}
