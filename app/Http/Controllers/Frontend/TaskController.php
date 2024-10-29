<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Mail\TaskAssigned;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller {
   // In TaskController
public function store(Request $request)
{
    \Log::info("Incoming project_id: " . $request->input('project_id'));
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'assigned_to' => 'required|email',
        'project_id' => 'required|exists:projects,id',
        'start_date' => 'nullable|date', // Optional validation
        'due_date' => 'nullable|date',   // Optional validation
        'priority' => 'nullable|string',  // Optional validation
        // Other validations as necessary
    ]);

    // Get the ID of the user assigned to the task
    $assignedToUser = User::where('email', $request->input('assigned_to'))->firstOrFail();

    $assignedByUserId = auth()->id();

    // Create the task
    $task = Task::create([
        'name' => $request->input('name'),
        'assigned_to' => $assignedToUser->id, // Use the user's ID
        'assigned_by' => $assignedByUserId, // Store the ID of the user who assigned the task
        'project_id' => $request->input('project_id'),
        'start_date' => $request->input('start_date'),
        'due_date' => $request->input('due_date'),
        'priority' => $request->input('priority'),
    ]);

    

    // Send email notification, etc.
    // Mail::to($request->input('assigned_to'))->send(new TaskAssigned($task, $request->input('assigned_to')));

    return redirect(url('/projects'))->with('success', 'Task created successfully.');
}


public function startTimer(Request $request, Task $task)
{
    // Save current elapsed time when starting the timer
    $task->elapsed_time = $request->input('elapsed_time', 0);
    $task->save();

    return response()->json(['message' => 'Timer started', 'elapsed_time' => $task->elapsed_time]);
}

public function pauseTimer(Request $request, Task $task)
{
    // Update elapsed time when pausing
    $task->elapsed_time = $request->input('elapsed_time');
    $task->save();

    return response()->json(['message' => 'Timer paused', 'elapsed_time' => $task->elapsed_time]);
}  

public function getTasksForUsername(Request $request)
{
    $username = $request->query('username');
    $user = User::where('username', $username)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $tasks = Task::with(['assignedBy', 'project']) // Ensure to include the project relationship
        ->where('assigned_to', $user->id)
        ->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority', 'project_id')
        ->get();

    // Map tasks to include the project name
    $tasksWithProjectName = $tasks->map(function ($task) {
        return [
            'id' => $task->id,
            'name' => $task->name,
            'assignedBy' => $task->assignedBy,
            'start_date' => $task->start_date,
            'due_date' => $task->due_date,
            'priority' => $task->priority,
            'comment' => $task->comment,
            'project_name' => $task->project ? $task->project->name : 'N/A', // Check if project exists
        ];
    });

    return response()->json($tasksWithProjectName);
}
}