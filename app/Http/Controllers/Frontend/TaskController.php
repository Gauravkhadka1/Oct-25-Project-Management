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
    // ...

    return redirect(url('/projects'))->with('success', 'Task created successfully.');
}


    
    
}