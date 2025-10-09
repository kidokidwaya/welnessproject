<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $query = Task::query();

    if ($request->user_name) {
        $query->where('user_name', $request->user_name);
    }

    if ($request->mood_tag) {
        $query->where('mood_tag', $request->mood_tag);
    }

    if (!is_null($request->completed)) {
        $query->where('completed', $request->completed);
    }

    $tasks = $query->orderByRaw("FIELD(priority, 'high','medium','low')")
                   ->latest()
                   ->get();

    // Always return data
    return response()->json([
        'message' => $tasks->isEmpty() ? 'No tasks found.' : 'Tasks retrieved successfully.',
        'data' => $tasks
    ]);
}

    // 🟩 Add a task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:50',
            'task' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'mood_tag' => 'nullable|in:happy,motivated,relaxed,calm,tired,okay,sad,angry,stressed',
            'timer_start' => 'nullable|date',
            'timer_duration' => 'nullable|integer'
        ]);

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task added successfully!',
            'data' => $task
        ]);
    }
// ✏️ Update a task
public function update(Request $request, $id)
{
    $task = Task::find($id);

    if (!$task) {
        return response()->json(['message' => 'Task not found.'], 404);
    }

    $validated = $request->validate([
        'task' => 'sometimes|string|max:255',
        'completed' => 'sometimes|boolean',
        'priority' => 'sometimes|in:low,medium,high',
        'mood_tag' => 'sometimes|in:happy,motivated,relaxed,calm,tired,okay,sad,angry,stressed',
        'timer_start' => 'nullable|date',
        'timer_duration' => 'nullable|integer'
    ]);

    $task->update($validated);

    return response()->json(['message' => 'Task updated successfully!', 'data' => $task]);
}

// 🗑️ Delete a task
public function destroy($id)
{
    $task = Task::find($id);

    if (!$task) {
        return response()->json(['message' => 'Task not found.'], 404);
    }

    $task->delete();

    return response()->json(['message' => 'Task deleted successfully!']);
}
    // 📊 Task summary: total tasks, completed, grouped by mood_tag
    public function summary(Request $request)
    {
        $userName = $request->user_name;

        $summary = Task::where('user_name', $userName)
                       ->selectRaw('mood_tag, COUNT(*) as total, SUM(completed) as completed_count')
                       ->groupBy('mood_tag')
                       ->get();

        return response()->json($summary);
    }
}
