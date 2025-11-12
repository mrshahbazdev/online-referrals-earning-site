<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('level')->latest()->paginate(10);
        $levels = Level::all();
        return view('admin.tasks.index', compact('tasks', 'levels'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'title' => 'required|string|max:255',
            'task_type' => 'required|string',
            'task_url' => 'required|url',
            'reward_amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:0',
        ]);

        $task = Task::create($validatedData);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Task Management',
            'action' => 'Task Created',
            'description' => 'Admin created a new task: ' . $task->title,
        ]);

        return back()->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'title' => 'required|string|max:255',
            'task_type' => 'required|string',
            'task_url' => 'required|url',
            'reward_amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:0',
        ]);

        $task->update($validatedData);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Task Management',
            'action' => 'Task Updated',
            'description' => 'Admin updated task: ' . $task->title,
        ]);

        return back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $taskTitle = $task->title;
        $task->delete();

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Task Management',
            'action' => 'Task Deleted',
            'description' => 'Admin deleted task: ' . $taskTitle,
        ]);

        return back()->with('success', 'Task deleted successfully.');
    }
}
