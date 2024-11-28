<?php

namespace App\Http\Controllers\Frontend;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusUpdatedNotification;
use App\Notifications\TaskCommentAddedNotification;
use app\Mail\TaskStatusUpdatedMail;
use app\Mail\TaskCommentAddedMail;
use App\Http\Controllers\Controller;

use App\Mail\TaskAssignedMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;
use App\Models\User;
use App\Models\Project;
use App\Models\TaskSession;
use App\Models\ProspectTaskSession;
use App\Models\PaymentTaskSession;
use Illuminate\Support\Facades\Log;
use App\Notifications\TaskStatusUpdated;
use App\Notifications\TaskCommentAdded;


use Illuminate\Support\Facades\Auth;

class TaskController extends Controller {
     /**
     * Store a new task and notify the assigned user
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
   // In TaskController
   public function store(Request $request)
   {
       \Log::info("Incoming project_id: " . $request->input('project_id'));
   
       // Validate the request data
       $request->validate([
        'name' => 'required|string|max:255',
            'assigned_to' => 'required|integer|exists:users,id', // Ensure the assigned user exists
            'project_id' => 'required|exists:projects,id',
            'due_date' => 'nullable|date', // Optional due date validation
            'priority' => 'nullable|string|in:Normal,High,Urgent', // Optional priority validation
    ]);

    $assignedByUserId = Auth::id();
       // Create the task
       $task = Task::create([
        'name' => $request->input('name'),
        'assigned_to' => $request->input('assigned_to'),
        'assigned_by' => $assignedByUserId,
        'project_id' => $request->input('project_id'),
        'due_date' => $request->input('due_date'),
        'priority' => $request->input('priority'),
    ]);
   
       // Send a notification to the assigned user
       $assignedToUser = User::find($task->assigned_to);
       $assignedToUser->notify(new TaskAssignedNotification($task));

       \Log::info('Task created and notification sent to user: ' . $assignedToUser->id);

       return redirect()->back()->with('success', 'Task created successfully.');
   }
   
   
   
public function startTimer(Request $request, $taskId)
{
    $taskCategory = $request->input('task_category');
    
    try {
        switch ($taskCategory) {
            case 'Prospect':
                $task = ProspectTask::find($taskId);
                if (!$task) return response()->json(['error' => 'Task not found'], 404);

                // Create ProspectTaskSession
                ProspectTaskSession::create([
                    'user_id' => auth()->id(),
                    'prospect_task_id' => $task->id,
                    'prospect_id' => $task->prospect_id,
                    'started_at' => now(),
                ]);
                break;
            
            case 'Payment':
                $task = PaymentTask::find($taskId);
                if (!$task) return response()->json(['error' => 'Task not found'], 404);

                // Create PaymentTaskSession
                PaymentTaskSession::create([
                    'user_id' => auth()->id(),
                    'payment_task_id' => $task->id,
                    'payment_id' => $task->payments_id,
                    'started_at' => now(),
                ]);
                break;
            
                case 'Project':
                    $task = Task::find($taskId);
                    if (!$task) return response()->json(['error' => 'Task not found'], 404);
    
                    // Create TaskSession
                    TaskSession::create([
                        'user_id' => auth()->id(),
                        'task_id' => $task->id,
                        'project_id' => $task->project_id,
                        'started_at' => now(),
                    ]);
                    break;

            default:
                return response()->json(['error' => 'Invalid task category'], 400);
        }

        return response()->json(['message' => 'Timer started', 'task_id' => $task->id]);
    } catch (\Exception $e) {
        Log::error("Error in startTimer for task ID $taskId: " . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function pauseTimer(Request $request, $taskId)
{
    $taskCategory = $request->input('task_category');
    
    try {
        switch ($taskCategory) {
            case 'Prospect':
                $task = ProspectTask::find($taskId);
                if (!$task) return response()->json(['error' => 'Task not found'], 404);

                // Find the latest active session for ProspectTask
                $session = ProspectTaskSession::where('prospect_task_id', $task->id)
                    ->where('user_id', auth()->id())
                    ->whereNull('paused_at')
                    ->latest()
                    ->first();

                if ($session) {
                    $session->update(['paused_at' => now()]);
                }
                break;

            case 'Payment':
                $task = PaymentTask::find($taskId);
                if (!$task) return response()->json(['error' => 'Task not found'], 404);

                // Find the latest active session for PaymentTask
                $session = PaymentTaskSession::where('payment_task_id', $task->id)
                    ->where('user_id', auth()->id())
                    ->whereNull('paused_at')
                    ->latest()
                    ->first();

                if ($session) {
                    $session->update(['paused_at' => now()]);
                }
                break;
            
            case 'Project':
                $task = Task::find($taskId);
                if (!$task) return response()->json(['error' => 'Task not found'], 404);

                // Find the latest active session for Task
                $session = TaskSession::where('task_id', $task->id)
                    ->where('user_id', auth()->id())
                    ->whereNull('paused_at')
                    ->latest()
                    ->first();

                if ($session) {
                    $session->update(['paused_at' => now()]);
                }
                break;

            default:
                return response()->json(['error' => 'Invalid task category'], 400);
        }

        // Update the elapsed time on the task
        $task->elapsed_time = $request->input('elapsed_time');
        $task->save();

        return response()->json(['message' => 'Timer paused', 'elapsed_time' => $task->elapsed_time]);
    } catch (\Exception $e) {
        Log::error("Error in pauseTimer for task ID $taskId: " . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
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
public function updateStatusComment(Request $request)
{
    $taskId = $request->taskId;
    $taskType = $request->taskType;
    $status = $request->status;

    // Determine the task based on taskType
    if ($taskType === 'payment') {
        $task = PaymentTask::findOrFail($taskId);
    } elseif ($taskType === 'prospect') {
        $task = ProspectTask::findOrFail($taskId);
    } else {
        $task = Task::findOrFail($taskId);
    }

    // Update the status
    $task->status = $status;
    $task->save();

    // Notify the assigned user (task assigned by user)
    $assignedByUser = $task->assignedBy; // User who assigned the task
    $assignedByUser->notify(new TaskStatusUpdatedNotification($task, $status));

    // Send an email to the assigned user
    Mail::to($assignedByUser->email)->send(new TaskStatusUpdatedMail($task, $status));

    return response()->json(['success' => true]);
}


public function addComment(Request $request)
{
    $taskId = $request->task_id;
    $taskType = $request->taskType;
    $comment = $request->comment;

    // Determine the task based on taskType
    if ($taskType === 'payment') {
        $task = PaymentTask::findOrFail($taskId);
    } elseif ($taskType === 'prospect') {
        $task = ProspectTask::findOrFail($taskId);
    } else {
        $task = Task::findOrFail($taskId);
    }

    // Update the comment
    $task->comment = $comment;
    $task->save();

    $user = auth()->user();

    // Notify the assigned user (task assigned by user)
    $assignedByUser = $task->assignedBy; // User who assigned the task
    $assignedByUser->notify(new TaskCommentAddedNotification($task, $comment, $user));

    // Send an email to the assigned user
    Mail::to($assignedByUser->email)->send(new TaskCommentAddedMail($task, $comment));

    return response()->json(['success' => true]);
}

public function show($id, Request $request)

{
    $task = Task::findOrFail($id); // or respective model for each controller
    $users = User::all();
    $query = Project::query();
    $project = $task->project; 
    return view('frontends.taskdetail', compact('task', 'users', 'project'));
}
}