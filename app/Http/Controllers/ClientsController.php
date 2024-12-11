<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Category;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $query = Clients::query();
        $filterCount = 0;
    
        $filters = [
            'category' => null,
            'subcategory' => null,
            'additional_subcategory' => null
        ];
    
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('company_name', 'like', "%{$searchTerm}%");
        }
    
        // Filter by Category, Subcategory, and Additional Subcategory
        if ($request->has('filter_category') && !empty($request->filter_category)) {
            $query->where('category', $request->filter_category);
            $filters['category'] = $request->filter_category;
            $filterCount++;
        }
        if ($request->has('filter_subcategory') && !empty($request->filter_subcategory)) {
            $query->where('subcategory', $request->filter_subcategory);
            $filters['subcategory'] = $request->filter_subcategory;
            $filterCount++;
        }
        if ($request->has('filter_additional_subcategory') && !empty($request->filter_additional_subcategory)) {
            $query->where('additional_subcategory', $request->filter_additional_subcategory);
            $filters['additional_subcategory'] = $request->filter_additional_subcategory;
            $filterCount++;
        }
    
          // Sorting
          $sortBy = $request->get('sort_by', 'company_name'); // Default sort column
          $sortOrder = $request->get('sort_order', 'asc');  // Default sort order
          $query->orderBy($sortBy, $sortOrder); // Apply sorting
          
          $clients = $query->get();
          
          // If AJAX request, return only the table rows
          if ($request->ajax()) {
              return view('partials.clients_table_rows', compact('clients'))->render(); // Return updated rows
          }
    
        // Build a descriptive filter label
        $lastSelectedFilter = null;
        if ($filters['category']) {
            $lastSelectedFilter = $filters['category'];
            if ($filters['subcategory']) {
                $lastSelectedFilter .= ' / ' . $filters['subcategory'];
                if ($filters['additional_subcategory']) {
                    $lastSelectedFilter .= ' / ' . $filters['additional_subcategory'];
                }
            }
        }
    
        return view('frontends.clients', compact('clients', 'filterCount', 'lastSelectedFilter'));
    }
    


    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_email' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'additional_subcategory' => 'nullable|string|max:255',
        ]);
    
        // Create a new client entry with validated data
        $clients = Clients::create([
            'company_name' => $validatedData['company_name'],
            'website' => $validatedData['website'],
            'address' => $validatedData['address'],
            'company_phone' => $validatedData['company_phone'],
            'company_email' => $validatedData['company_email'],
            'contact_person' => $validatedData['contact_person'],
            'contact_person_phone' => $validatedData['contact_person_phone'],
            'contact_person_email' => $validatedData['contact_person_email'],
            'category' => $validatedData['category'],
            'subcategory' => $validatedData['subcategory'],
            'additional_subcategory' => $validatedData['additional_subcategory'],
        ]);
    
        // Save the client to the database
        $clients->save();
    
        // Redirect with a success message
        return redirect(url('/clients'))->with('success', 'Client added successfully.');
    }
    public function addclients() {
        return view('frontends.add-clients');
    }
}
