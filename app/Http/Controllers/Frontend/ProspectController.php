<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;

class ProspectController extends Controller
{
    // Change this method name from 'prospects' to 'index'
    public function index(Request $request)
    {
        // Query builder to fetch prospects
        $query = Prospect::query();

        // Sorting by Name A-Z or Z-A
        if ($request->has('sort_name') && in_array($request->sort_name, ['asc', 'desc'])) {
            $query->orderBy('company_name', $request->sort_name);
        }

        // Filtering by Category
        if ($request->has('filter_category') && $request->filter_category != '') {
            $query->where('category', $request->filter_category);
        }

   

        // Apply date range filter if both dates are provided
        if ($request->has('from_date') && $request->has('to_date') && $request->sort_inquirydate === 'range') {
            $query->whereBetween('inquirydate', [$request->from_date, $request->to_date]);
        }

        // Apply sorting if sort_inquirydate is set to 'asc' or 'desc', regardless of date range
        if ($request->has('sort_inquirydate') && in_array($request->sort_inquirydate, ['asc', 'desc'])) {
            $query->orderBy('inquirydate', $request->sort_inquirydate);
        }



        // Sorting by Probability (Higher to Lower or Lower to Higher)
        if ($request->has('sort_probability') && in_array($request->sort_probability, ['asc', 'desc'])) {
            $query->orderBy('probability', $request->sort_probability);
        }

        // Filtering by Status
        if ($request->has('sort_status') && $request->sort_status != '') {
            $query->where('status', $request->sort_status);
        }

        // Fetch the sorted and filtered data
        $prospects = $query->get();

        return view('frontends.prospects', compact('prospects'));
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
        ]);

        Prospect::create($validatedData);

        return redirect(url('/prospects'))->with('success', 'Prospect created successfully.');
    }

    public function destroy($id)
    {
        $prospect = Prospect::findOrFail($id); // Find the prospect or fail
        $prospect->delete(); // Delete the prospect

        return redirect()->route('prospects.index')->with('success', 'Prospect deleted successfully.');
    }



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
}
