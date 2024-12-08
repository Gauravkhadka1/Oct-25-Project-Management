<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\User;
use App\Notifications\ProspectStatusUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;


class ProspectController extends Controller
{
    // Change this method name from 'prospects' to 'index'
    public function index(Request $request)
    {
        $query = Prospect::query();
        $filterCount = 0;

        // Filtering by Inquiry Date
        if ($request->filled('inquiry_date')) {
            $filterCount++;
            switch ($request->inquiry_date) {
                case 'recent':
                    $query->orderBy('inquirydate', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('inquirydate', 'asc');
                    break;
                case 'date-range':
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('inquirydate', [$request->from_date, $request->to_date]);
                        $filterCount++;
                    }
                    break;
            }
        }

        // Filtering by Category
        if ($request->filled('filter_category')) {
            $query->where('category', $request->filter_category);
            $filterCount++;
        }

        // Filtering by Status
        if ($request->filled('sort_status')) {
            $query->where('status', $request->sort_status);
            $filterCount++;
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('company_name', 'like', "%{$searchTerm}%"); // Search by project name
        }
        // Fetch the sorted and filtered data
        $prospects = $query->get();

        // Fetch all users
        $users = User::all();

        $newProspects = Prospect::where('status', 'new')->count();
        $dealingProspects = Prospect::where('status', 'dealing')->count();
        $quotesentProspects = Prospect::where('status', 'quote_sent')->count();
        $agreementsentProspects = Prospect::where('status', 'aggrement_sent')->count();
        $convertedProspects = Prospect::where('status', 'converted')->count();

        return view('frontends.prospects', compact('users', 'prospects', 'filterCount', 'newProspects', 'dealingProspects', 'quotesentProspects', 'agreementsentProspects', 'convertedProspects'));
    }



    public function store(Request $request)
    {
        // Validate and save project
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'inquirydate' => 'nullable|date',
            'probability' => 'nullable|numeric',
            'activities' => 'nullable|string',
            'status' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Prospect::create($validatedData);

        return redirect(url('/prospects'))->with('success', 'Prospect created successfully.');
    }

    // delete prospects 
    public function destroy($id)
    {
        $prospect = Prospect::findOrFail($id); // Find the prospect or fail
        $prospect->delete(); // Delete the prospect

        return redirect()->route('prospects.index')->with('success', 'Prospect deleted successfully.');
    }

    // update prospects 
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'probability' => 'nullable|numeric|min:0|max:100',
            'inquirydate' => 'nullable|date',
            'activities' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'message' => 'nullable|string',
        ]);


        // Find the prospect by ID
        $prospect = Prospect::findOrFail($id);

        // Update the prospect's attributes
        $prospect->company_name = $request->input('company_name');
        $prospect->category = $request->input('category');
        $prospect->contact_person = $request->input('contact_person');
        $prospect->phone_number = $request->input('phone_number');
        $prospect->email = $request->input('email');
        $prospect->address = $request->input('address');
        $prospect->message = $request->input('message');
        $prospect->probability = $request->input('probability');
        $prospect->inquirydate = $request->input('inquirydate');
        $prospect->activities = $request->input('activities');
        $prospect->status = $request->input('status');



        // Save the updated prospect
        $prospect->save();

        // Redirect back with a success message
        return redirect()->route('prospects.index')->with('success', 'Prospect updated successfully.');
    }
    public function updateStatus(Request $request)
{
    $validatedData = $request->validate([
        'taskId' => 'required|integer|exists:prospects,id',
        'status' => 'required|string|max:255'
    ]);

    // Find the prospect by ID and update the status
    $prospect = Prospect::findOrFail($validatedData['taskId']);
    $prospect->status = $validatedData['status'];
    $prospect->save();

    // Send notifications to specified emails
    $emails = ['gaurav@webtech.com.np'];
    Notification::route('mail', $emails)->notify(new ProspectStatusUpdated($prospect, $validatedData['status']));

    // Return a success response
    return response()->json(['success' => true, 'message' => 'Status updated successfully']);
}

public function show($id)
{
    // Fetch the specific payment by ID
    $prospect = prospect::findOrFail($id);

    // Calculate due_days for this specific payment
    $now = Carbon::now();
    if ($prospect->due_date) {
        $dueDate = Carbon::parse($prospect->due_date);
        $prospect->due_days = $dueDate->startOfDay()->diffInDays($now->startOfDay(), false);
    } else {
        $prospect->due_days = null; // Explicitly set null
    }

    $users = User::all();

    // Pass the data to the view
    return view('frontends.prospect-details', compact('prospect', 'users'));
}
}
