<?php

namespace App\Http\Controllers;

use App\Models\ClientTask;
use App\Models\Clients;
use App\Models\User;
use Illuminate\Http\Request;

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

    // Create the task using the validated data
    $task = ClientTask::create([
        'name' => $validated['name'],
        'client_id' => $validated['client_id'],
        'assigned_to' => $validated['assigned_to'],
        'due_date' => $validated['due_date'],
        'priority' => $validated['priority'],
        'status' => $validated['status'], // Store the status
    ]);

    // Return a JSON response for frontend integration
    return redirect()->back()->with('success', 'Task created successfully!');
}

}
