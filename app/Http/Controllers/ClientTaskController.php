<?php

namespace App\Http\Controllers;

use App\Models\ClientTask;
use App\Models\ProspectTask;
use App\Models\PaymentTask;
use App\Models\Clients;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TaskAssignedNotification;

class ClientTaskController extends Controller
{
    public function create()
    {
        $clients = Clients::all(); // Fetch clients
        $users = User::all();     // Fetch users
        return view('tasks.create', compact('clients', 'users'));
    }

    public function store(Request $request)
{
    // Log the received status for debugging
    \Log::info('Task Status:', ['status' => $request->status]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'client_id' => 'required|exists:clients,id',
        'assigned_to' => 'required|exists:users,id',
        'due_date' => 'required|date',
        'priority' => 'required|in:Normal,High,Urgent',
        'status' => 'required|string', // Validate the status field
    ]);
    $assignedByUserId = auth()->id();
    // Create the task using the validated data
    $task = ClientTask::create([
        'name' => $validated['name'],
        'client_id' => $validated['client_id'],
        'assigned_to' => $validated['assigned_to'],
        'assigned_by' => $assignedByUserId, 
        'due_date' => $validated['due_date'],
        'priority' => $validated['priority'],
        'status' => $validated['status'], // Store the status
    ]);
// Send a notification to the assigned user
$assignedToUser = User::find($task->assigned_to);
$assignedToUser->notify(new TaskAssignedNotification($task));

\Log::info('Task created and notification sent to user: ' . $assignedToUser->id);

return redirect()->back()->with('success', 'Task created successfully.');
}
public function updateElapsedTime(Request $request, $taskId)
{
    $task = ClientTask::findOrFail($taskId);
    $task->elapsed_time = $request->elapsed_time;
    $task->save();

    return response()->json(['success' => true, 'message' => 'Elapsed time updated successfully.']);
}
public function updateStatusComment(Request $request)
{
    $taskId = $request->taskId;
    $status = $request->status;
    $taskType = $request->taskType;

    if ($taskType === 'payment') {
        $task = PaymentTask::findOrFail($taskId);
    } elseif ($taskType === 'prospect') {
        $task = ProspectTask::findOrFail($taskId);
    } else {
        $task = ClientTask::findOrFail($taskId);
    }

    $task->status = $status;
    $task->save();

    return response()->json(['success' => true, 'message' => 'Task status updated.']);
}

}
