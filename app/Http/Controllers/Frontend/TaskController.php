<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Mail\TaskAssigned;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;
use App\Models\User;
use App\Models\TaskSession;

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
    $session = TaskSession::create([
        'user_id' => auth()->id(),
        'task_id' => $task->id,
        'project_id' => $task->project_id,
        'started_at' => now(),
    ]);
    \Log::info("TaskSession created:", $session->toArray());

    $task->elapsed_time = $request->input('elapsed_time', 0);
    $task->save();

    return response()->json(['message' => 'Timer started', 'elapsed_time' => $task->elapsed_time]);
}

public function pauseTimer(Request $request, Task $task)
{
    $session = TaskSession::where('task_id', $task->id)
                ->where('user_id', auth()->id())
                ->whereNull('paused_at')
                ->latest()
                ->first();

    if ($session) {
        $session->update(['paused_at' => now()]);
    }

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

    // Retrieve tasks for all types: Project, Payment, and Prospect
    $tasks = Task::where('assigned_to', $user->id)
        ->get()
        ->map(function ($task) {
            $task->category = 'Project';  // Set category type as Project
            $task->category_name = $task->project->name ?? 'N/A';  // Set category name from related project
            $task->assignedBy = $task->assignedBy->username ?? 'N/A';  // Get assigned by username
            return $task;
        });

    $prospectTasks = ProspectTask::where('assigned_to', $user->id)
        ->get()
        ->map(function ($task) {
            $task->category = 'Prospect';  // Set category type as Prospect
            $task->category_name = $task->prospect->company_name ?? 'N/A';  // Set category name from related prospect
            $task->assignedBy = $task->assignedBy->username ?? 'N/A';  // Get assigned by username
            return $task;
        });

    $paymentTasks = PaymentTask::where('assigned_to', $user->id)
        ->get()
        ->map(function ($task) {
            // Ensure the relationship with the 'payment' model is correct
            $payment = $task->payment;  // Get the associated Payment model
            
            $task->category = 'Payment';  // Set category type as Payment
            $task->category_name = $payment ? $payment->company_name : 'N/A';  // Set category name from related payment
            $task->assignedBy = $task->assignedBy->username ?? 'N/A';  // Get assigned by username
            return $task;
        });

        

    // Combine all tasks into one collection
    $allTasks = $tasks->merge($prospectTasks)->merge($paymentTasks);

    return response()->json($allTasks);
}
}