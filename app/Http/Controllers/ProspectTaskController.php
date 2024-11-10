<?php

namespace App\Http\Controllers;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProspectTask;
use App\Mail\TaskAssignedMail;
use Illuminate\Support\Facades\Mail;

class ProspectTaskController extends Controller
{
    public function store(Request $request)
    {
        \Log::info("Incoming prospect_id: " . $request->input('prospect_id'));
        \Log::info('Request data:', $request->all());

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'assigned_to' => 'required|email',
            'prospect_id' => 'required|exists:prospects,id',
            'start_date' => 'nullable|date', // Optional validation
            'due_date' => 'nullable|date',   // Optional validation
            'priority' => 'nullable|string',  // Optional validation
            // Other validations as necessary
        ]);

        // Get the ID of the user assigned to the task
        $assignedToUser = User::where('email', $request->input('assigned_to'))->firstOrFail();

        $assignedByUserId = auth()->id();

        // Create the task
        $prospectTask = ProspectTask::create([
            'name' => $request->input('name'),
            'assigned_to' => $assignedToUser->id, // Use the user's ID
            'assigned_by' => $assignedByUserId, // Store the ID of the user who assigned the task
            'prospect_id' => $request->input('prospect_id'),
            'start_date' => $request->input('start_date'),
            'due_date' => $request->input('due_date'),
            'priority' => $request->input('priority'),
        ]);
 // Send a notification to the assigned user
 $assignedToUser->notify(new TaskAssignedNotification($prospectTask));
        // Send email notification, etc.
        Mail::to($request->input('assigned_to'))->send(new TaskAssignedMail($prospectTask, $request->input('assigned_to')));

        return redirect(url('/prospects'))->with('success', 'Task created successfully.');
    }
   

}
