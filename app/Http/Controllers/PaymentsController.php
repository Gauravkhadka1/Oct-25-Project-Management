<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;

class PaymentsController extends Controller
{
    public function index()
    {
        // Fetch all payments from the database
        $payments = Payments::all();

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

