<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all payments from the database
        $query = Payments::query();
        $payments = Payments::all();

        // Apply sorting based on project name if provided
        if ($request->has('sort_payments_name') && $request->sort_payments_name != '') {
            if ($request->sort_payments_name == 'a_to_z') {
                $query->orderBy('company_name', 'asc'); // Sort by project name A to Z
            } elseif ($request->sort_payments_name == 'z_to_a') {
                $query->orderBy('company_name', 'desc'); // Sort by project name Z to A
            }
        }
        // Apply filter if 'filter_status' is provided in the request
        if ($request->has('filter_category') && $request->filter_category != '') {
            $query->where('category', $request->filter_category);
        }

        // Apply sorting based on amount if provided
        if ($request->has('sort_amount') && $request->sort_amount != '') {
            if ($request->sort_amount == 'high_to_low') {
                $query->orderBy('amount', 'desc'); // Sort by Amount High to Low
            } elseif ($request->sort_amount == 'low_to_high') {
                $query->orderBy('amount', 'asc'); // Sort by Amount Low to High
            }
        }


        $payments = $query->get();

        return view('frontends.payments', compact('payments'));
    }

    // payments store
    public function store(Request $request)
    {
        // Validate and save project
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
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
            'phone' => 'nullable|string|max:20',
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
