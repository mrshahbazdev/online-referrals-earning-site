<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\CompletedTask;
use App\Models\Transaction;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\TaskCompleted;
class UserTaskController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('level');

        $completedTodayIds = CompletedTask::where('user_id', $user->id)
            ->whereDate('completed_at', Carbon::today())
            ->pluck('task_id');

        $tasksCompletedCount = $completedTodayIds->count();
        $dailyLimit = $user->level->daily_task_limit ?? 0;

        // **NAYA LOGIC:** Sirf user ke current level ke tasks haasil karein
        $tasks = Task::where('is_active', true)
            ->where('level_id', $user->level_id) // Yeh line add ki gayi hai
            ->whereNotIn('id', $completedTodayIds)
            ->take($dailyLimit - $tasksCompletedCount)
            ->get();

        return view('tasks.index', compact('tasks', 'tasksCompletedCount', 'dailyLimit', 'completedTodayIds'));
    }

    public function complete(Request $request, Task $task)
    {
        $user = Auth::user();

        $tasksCompletedCount = CompletedTask::where('user_id', $user->id)
            ->whereDate('completed_at', Carbon::today())
            ->count();

        if ($tasksCompletedCount >= $user->level->daily_task_limit) {
            return response()->json(['error' => 'You have reached your daily task limit.'], 403);
        }

        $alreadyCompleted = CompletedTask::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->whereDate('completed_at', Carbon::today())
            ->exists();

        if ($alreadyCompleted) {
            return response()->json(['error' => 'You have already completed this task today.'], 403);
        }

        CompletedTask::create(['user_id' => $user->id, 'task_id' => $task->id]);
        $user->balance += $task->reward_amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $task->reward_amount,
            'type' => 'task_reward',
            'description' => 'Reward for completing task: ' . $task->title,
        ]);

        UserActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Task Completed',
            'description' => 'Completed task: ' . $task->title,
        ]);

        // Naya Event Fire Karein
        event(new TaskCompleted($task, $user));

        return response()->json([
            'success' => 'Task completed successfully!',
            'new_balance' => $user->balance
        ]);
    }
}
