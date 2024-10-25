<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Mail\TaskAssigned;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller {
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'assigned_to' => 'required|email', // Ensure it's a valid email
            'project_id' => 'required|exists:projects,id', // Ensure the project exists
            // Add other validations as necessary
        ]);
    
        // Create the task
        $task = Task::create([
            'name' => $request->input('name'),
            'assigned_to' => $request->input('assigned_to'),
            'project_id' => $request->input('project_id'),
            // Add other fields as necessary
        ]);
    
        // Send email notification to the assigned user
        Mail::to($request->input('assigned_to'))->send(new TaskAssigned($task, $request->input('assigned_to')));
    
        // Redirect back to tasks index with a success message
        return redirect(url('/projects'))->with('success', 'Task created successfully.');
    }
    

    
    
}