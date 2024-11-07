<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\User;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all payments from the database
        $query = Payments::query();
        $filterCount = 0;

        // Initialize the total dues text
        $totalDuesText = 'Total Due:';


        // Filtering by Category
        if ($request->filled('filter_category')) {
            $query->where('category', $request->filter_category);
            $filterCount++;
        }

        // Filtering by Amount Date
        if ($request->filled('amount')) {
            $filterCount++;
            if ($request->amount == 'high-to-low') {
                $query->orderBy('amount', 'desc');
            } elseif ($request->amount == 'low-to-high') {
                $query->orderBy('amount', 'asc');
            }
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('company_name', 'like', "%{$searchTerm}%"); // Search by project name
        }

        // Get filtered payments and calculate total due
        $payments = $query->get();
        $filteredTotalAmount = $payments->sum('amount');
        $totalDuesText = $request->filled('filter_category') ? "Total {$request->filter_category} Dues:" : 'Total Dues from All Categories:';

        // Fetch the sorted and filtered data
        $payments = $query->get();

        // Fetch all users
        $users = User::all();

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
            'activities' => 'nullable|string|max:255',
        ]);

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
}
