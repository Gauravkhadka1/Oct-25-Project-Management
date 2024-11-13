<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Category;

class ClientsController extends Controller
{
    public function index()
    {
        return view('frontends.clients');
    }
    public function addclients()
    {
        $categories = Category::all();
        return view('frontends.add-clients', compact('categories'));
    }


    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'company_name' => 'nullable|string|max:255',
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
    
}
