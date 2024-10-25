<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller {
        public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
        ]);
    
        // Create a new task
        Task::create([
            'name' => $request->name,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => auth()->user()->username, // Assuming the authenticated user assigns the task
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'project_id' => $request->project_id,
        ]);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Task created successfully!');
    }
    
}