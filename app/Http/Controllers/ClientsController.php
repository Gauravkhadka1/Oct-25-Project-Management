<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
            'contract' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            'seo_contract' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            'maintenance_contract' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',

            'domain_active_date' => 'nullable|date',
            'domain_expiry_date' => 'nullable|date',
            'domain_amount' => 'nullable|integer',
            'hosting_active_date' => 'nullable|date',
            'hosting_expiry_date' => 'nullable|date',
            'hosting_space' => 'nullable|string',
            'hosting_type' => 'nullable|string',
            'hosting_amount' => 'nullable|integer',
            'microsoft_active_date' => 'nullable|date',
            'microsoft_expiry_date' => 'nullable|date',
            'microsoft_type' => 'nullable|string',
            'no_of_license' => 'nullable|integer',
            'microsoft_amount' => 'nullable|integer',
            'maintenance_active_date' => 'nullable|date',
            'maintenance_expiry_date' => 'nullable|date',
            'maintenance_type' => 'nullable|string',
            'maintenance_amount' => 'nullable|integer',
            'seo_active_date' => 'nullable|date',
            'seo_expiry_date' => 'nullable|date',
            'seo_type' => 'nullable|string',
            'seo_amount' => 'nullable|integer',
            'vat_no' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        // Create a new client entry with validated data
        $clients = Clients::create([
            'company_name' => $validatedData['company_name'],
            'website' => $validatedData['website'],
            'address' => $validatedData['address'],
            'subcategory' => $validatedData['subcategory'],
            'company_phone' => $validatedData['company_phone'],
            'company_email' => $validatedData['company_email'],
            'contact_person' => $validatedData['contact_person'],
            'contact_person_phone' => $validatedData['contact_person_phone'],
            'contact_person_email' => $validatedData['contact_person_email'],

            'domain_active_date' => $validatedData['domain_active_date'],
            'domain_expiry_date' => $validatedData['domain_expiry_date'],
            'domain_amount' => $validatedData['domain_amount'],
            'hosting_active_date' => $validatedData['hosting_active_date'],
            'hosting_expiry_date' => $validatedData['hosting_expiry_date'],
            'hosting_space' => $validatedData['hosting_space'],
            'hosting_type' => $validatedData['hosting_type'],
            'hosting_amount' => $validatedData['hosting_amount'],
            'microsoft_active_date' => $validatedData['microsoft_active_date'],
            'microsoft_expiry_date' => $validatedData['microsoft_expiry_date'],
            'microsoft_type' => $validatedData['microsoft_type'],
            'no_of_license' => $validatedData['no_of_license'],
            'microsoft_amount' => $validatedData['microsoft_amount'],
            'maintenance_active_date' => $validatedData['maintenance_active_date'],
            'maintenance_expiry_date' => $validatedData['maintenance_expiry_date'],
            'maintenance_type' => $validatedData['maintenance_type'],
            'maintenance_amount' => $validatedData['maintenance_amount'],
            'seo_active_date' => $validatedData['seo_active_date'],
            'seo_expiry_date' => $validatedData['seo_expiry_date'],
            'seo_type' => $validatedData['seo_type'],
            'seo_amount' => $validatedData['seo_amount'],
            'vat_no' => $validatedData['vat_no'],
            'seo_amount' => $validatedData['additional_info'],

            // 'category' => $validatedData['category'],
            // 'subcategory' => $validatedData['subcategory'],
            // 'additional_subcategory' => $validatedData['additional_subcategory'],
        ]);
        // Handle file upload if provided
        if ($request->hasFile('contract')) {
            // Store the uploaded file and update client entry
            $filePath = $request->file('contract')->store('contracts', 'public');
            $clients->contract = $filePath;
        }

        if ($request->hasFile('seo_contract')) {
            // Store the uploaded SEO contract
            $seoFilePath = $request->file('seo_contract')->store('contracts/seo', 'public');
            $clients->seo_contract = $seoFilePath;
        }

        if ($request->hasFile('maintenance_contract')) {
            // Store the uploaded maintenance contract
            $maintenanceFilePath = $request->file('maintenance_contract')->store('contracts/maintenance', 'public');
            $clients->maintenance_contract = $maintenanceFilePath;
        }

        // Save the client to the database
        $clients->save();

        // Redirect with a success message
        return redirect(url('/clients'))->with('success', 'Client added successfully.');
    }
    public function addclients()
    {
        return view('frontends.add-clients');
    }



    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_email' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|string|max:255',
            'contract' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            'seo_contract' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            'maintenance_contract' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',

            'domain_active_date' => 'nullable|date',
            'domain_expiry_date' => 'nullable|date',
            'domain_amount' => 'nullable|integer',
            'hosting_active_date' => 'nullable|date',
            'hosting_expiry_date' => 'nullable|date',
            'hosting_space' => 'nullable|string',
            'hosting_type' => 'nullable|string',
            'hosting_amount' => 'nullable|integer',
            'microsoft_active_date' => 'nullable|date',
            'microsoft_expiry_date' => 'nullable|date',
            'microsoft_type' => 'nullable|string',
            'no_of_license' => 'nullable|integer',
            'microsoft_amount' => 'nullable|integer',
            'maintenance_active_date' => 'nullable|date',
            'maintenance_expiry_date' => 'nullable|date',
            'maintenance_type' => 'nullable|string',
            'maintenance_amount' => 'nullable|integer',
            'seo_active_date' => 'nullable|date',
            'seo_expiry_date' => 'nullable|date',
            'seo_type' => 'nullable|string',
            'seo_amount' => 'nullable|integer',
            'vat_no' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        // Find the client by ID
        $client = Clients::findOrFail($id);

        // Handle file upload
        // Handle file uploads
        if ($request->hasFile('contract')) {
            // Delete old file if it exists
            if ($client->contract) {
                Storage::delete($client->contract);
            }

            // Store new file and update the validated data
            $filePath = $request->file('contract')->store('contracts', 'public');
            $validatedData['contract'] = $filePath;
        }

        if ($request->hasFile('seo_contract')) {
            if ($client->seo_contract) {
                Storage::delete($client->seo_contract);
            }

            $seoFilePath = $request->file('seo_contract')->store('contracts/seo', 'public');
            $validatedData['seo_contract'] = $seoFilePath;
        }

        if ($request->hasFile('maintenance_contract')) {
            if ($client->maintenance_contract) {
                Storage::delete($client->maintenance_contract);
            }

            $maintenanceFilePath = $request->file('maintenance_contract')->store('contracts/maintenance', 'public');
            $validatedData['maintenance_contract'] = $maintenanceFilePath;
        }

        // Update the client with validated data
        $client->update($validatedData);



        // Redirect back with a success message
        return redirect()->back()->with('success', true);
    }
    public function details($id)
    {
        $client = Clients::findOrFail($id);
        return view('frontends.client-details', compact('client'));
    }
}
