<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentTask;
use App\Models\User;

class PaymentTaskController extends Controller
{
    public function store(Request $request)
    {
        \Log::info("Incoming payments_id: " . $request->input('payments_id'));
        \Log::info('Request data:', $request->all());

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'assigned_to' => 'required|email',
            'payments_id' => 'required|exists:payments,id',
            'start_date' => 'nullable|date', // Optional validation
            'due_date' => 'nullable|date',   // Optional validation
            'priority' => 'nullable|string',  // Optional validation
            // Other validations as necessary
        ]);

        // Get the ID of the user assigned to the task
        $assignedToUser = User::where('email', $request->input('assigned_to'))->firstOrFail();

        $assignedByUserId = auth()->id();

        // Create the task
        $paymentTask = PaymentTask::create([
            'name' => $request->input('name'),
            'assigned_to' => $assignedToUser->id, // Use the user's ID
            'assigned_by' => $assignedByUserId, // Store the ID of the user who assigned the task
            'payments_id' => $request->input('payments_id'),
            'start_date' => $request->input('start_date'),
            'due_date' => $request->input('due_date'),
            'priority' => $request->input('priority'),
        ]);

        // Send email notification, etc.
        // Mail::to($request->input('assigned_to'))->send(new TaskAssigned($task, $request->input('assigned_to')));

        return redirect(url('/payments'))->with('success', 'Payment Task created successfully.');
    }
}
