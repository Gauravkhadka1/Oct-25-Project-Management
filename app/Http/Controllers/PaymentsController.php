<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\User;
use App\Notifications\PaymentsStatusUpdated;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;


class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch and filter payments
        $query = Payments::query();
        $filterCount = 0;

        // Filtering by Category
        if ($request->filled('filter_category')) {
            $query->where('category', $request->filter_category);
            $filterCount++;
        }

        // Filtering by Amount
        if ($request->filled('amount')) {
            if ($request->amount == 'high-to-low') {
                $query->orderBy('amount', 'desc');
            } elseif ($request->amount == 'low-to-high') {
                $query->orderBy('amount', 'asc');
            }
            $filterCount++;
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('company_name', 'like', "%{$request->search}%");
        }

        // Fetch and calculate due_days
        $payments = $query->get()->map(function ($payment) {
            $now = Carbon::now();

            if ($payment->due_date) {
                $dueDate = Carbon::parse($payment->due_date);
                $payment->due_days = $dueDate->startOfDay()->diffInDays($now->startOfDay(), false); // Ensure days only
            } else {
                $payment->due_days = null; // Set null explicitly
            }

            return $payment;
        });


        // Sorting by Days Remaining or Overdue
        if ($request->filled('due_date')) {
            if ($request->due_date == 'less-days') {
                // Sort by overdue days, highest first
                $payments = $payments->sortByDesc('due_days');
            } elseif ($request->due_date == 'more-days') {
                // Sort by remaining days, highest first
                $payments = $payments->sortBy('due_days');
            }
            $filterCount++;
        }


        // Calculate total dues text and filtered amount
        $filteredTotalAmount = $payments->sum('amount');
        $totalDuesText = $request->filled('filter_category')
            ? "Total {$request->filter_category} Dues:"
            : 'Total Dues from All Categories:';

        // Fetch all users
        $users = User::all();

        // Return view
        return view('frontends.payments', compact('payments', 'users', 'filteredTotalAmount', 'totalDuesText', 'filterCount'));
    }


    // payments store
    public function store(Request $request)
    {
        // Validate and save project
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'activities' => 'nullable|string|max:255',
        ]);
        $validatedData['status'] = 'due'; // Default status to "due"
        Payments::create($validatedData);

        return redirect(url('/payments'))->with('success', 'Payments created successfully.');
    }

    // payments delete 
    public function destroy($id)
    {
        $payments = Payments::findOrFail($id); // Find the prospect or fail
        $payments->delete(); // Delete the prospect

        return redirect()->route('payments.index')->with('success', 'Payments deleted successfully.');
    }

    // update payments
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'amount' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'activities' => 'nullable|string|max:255',
        ]);
        // Find the prospect by ID
        $payments = Payments::findOrFail($id);

        // Update the prospect's attributes
        $payments->company_name = $request->input('company_name');
        $payments->contact_person = $request->input('contact_person');
        $payments->phone = $request->input('phone');
        $payments->email = $request->input('email');
        $payments->category = $request->input('category');
        $payments->amount = $request->input('amount');
        $payments->activities = $request->input('activities');

        // Save the updated prospect
        $payments->save();

        // Redirect back with a success message
        return redirect()->route('payments.index')->with('success', 'Payments updated successfully.');
    }
    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'taskId' => 'required|integer|exists:payments,id',
            'status' => 'required|string|max:255'
        ]);

        // Find the prospect by ID and update the status
        $payment = Payments::findOrFail($validatedData['taskId']);
        $payment->status = $validatedData['status'];
        $payment->save();

        // Send notifications to specified emails
        $emails = ['gaurav@webtech.com.np'];
        Notification::route('mail', $emails)->notify(new PaymentsStatusUpdated($payment, $validatedData['status']));

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function paymentDetails(Request $request)
    {
        $query = Payments::query();
        // Fetch and calculate due_days
        $payments = $query->get()->map(function ($payment) {
            $now = Carbon::now();

            if ($payment->due_date) {
                $dueDate = Carbon::parse($payment->due_date);
                $payment->due_days = $dueDate->startOfDay()->diffInDays($now->startOfDay(), false); // Ensure days only
            } else {
                $payment->due_days = null; // Set null explicitly
            }

            return $payment;
        });
        $users = User::all();

        return view('frontends.payment-details', compact('payments', 'users'));
    }
    public function show($id)
    {
        // Fetch the specific payment by ID
        $payment = Payments::findOrFail($id);

        // Calculate due_days for this specific payment
        $now = Carbon::now();
        if ($payment->due_date) {
            $dueDate = Carbon::parse($payment->due_date);
            $payment->due_days = $dueDate->startOfDay()->diffInDays($now->startOfDay(), false);
        } else {
            $payment->due_days = null; // Explicitly set null
        }

        $users = User::all();

        // Pass the data to the view
        return view('frontends.payment-details', compact('payment', 'users'));
    }
    public function showPaymentTasks($paymentId)
{
    $payment = Payments::with('payment_tasks.assignedTo') // Assuming the tasks table has an `assigned_user_id`
        ->findOrFail($paymentId);

    return view('frontends.payment-details', compact('payment'));
}

}
