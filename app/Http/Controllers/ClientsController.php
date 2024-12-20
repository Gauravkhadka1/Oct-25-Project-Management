<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
            'domain_type' => 'nullable|string',
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

            'no_of_installments' => 'nullable|integer|min:2|max:4',
        'first_installment' => 'nullable|date',
        'second_installment' => 'nullable|date',
        'third_installment' => 'nullable|date',
        'fourth_installment' => 'nullable|date',
        ]);

       
    $filePaths = [];

    // Handle 'contract' file
    if ($request->hasFile('contract')) {
        $filePaths['contract'] = $this->uploadFile($request->file('contract'), 'contracts');
    }

    // Handle other file uploads...
    if ($request->hasFile('seo_contract')) {
        $filePaths['seo_contract'] = $this->uploadFile($request->file('seo_contract'), 'contracts/seo');
    }
    
    // Handle 'maintenance_contract' file
    if ($request->hasFile('maintenance_contract')) {
        $filePaths['maintenance_contract'] = $this->uploadFile($request->file('maintenance_contract'), 'contracts/maintenance');
    }


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
           'contract' => $filePaths['contract'] ?? null,
        'seo_contract' => $filePaths['seo_contract'] ?? null,
        'maintenance_contract' => $filePaths['maintenance_contract'] ?? null,

            'domain_active_date' => $validatedData['domain_active_date'],
            'domain_expiry_date' => $validatedData['domain_expiry_date'],
            'domain_amount' => $validatedData['domain_amount'],
            'domain_type' => $validatedData['domain_type'],
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
            'additional_info' => $validatedData['additional_info'],

            'no_of_installments' => $validatedData['no_of_installments'],
            'first_installment' => $validatedData['first_installment'] ?? null,
            'second_installment' => $validatedData['second_installment'] ?? null,
            'third_installment' => $validatedData['third_installment'] ?? null,
            'fourth_installment' => $validatedData['fourth_installment'] ?? null,

            // 'category' => $validatedData['category'],
            // 'subcategory' => $validatedData['subcategory'],
            // 'additional_subcategory' => $validatedData['additional_subcategory'],
        ]);

       

       
    

        // Save the client to the database
        $clients->save();

    // Log the creator's activity
    UserActivity::create([
        'user_id' => auth()->id(),
        'activity' => 'Created client: ' . $clients->company_name,
        'client_id' => $clients->id,
        'created_at' => Carbon::now('Asia/Kathmandu'),
        'updated_at' => Carbon::now('Asia/Kathmandu'),
    ]);

        // Redirect with a success message
        return redirect(url('/clients'))->with('success', 'Client added successfully.');
     
    }
    private function uploadFile($file, $directory)
    {
        // Get the original file name
        $originalFileName = $file->getClientOriginalName();
        
        // Clean the file name by replacing spaces with hyphens
        $cleanedFileName = str_replace(' ', '-', $originalFileName);
        
        // Remove the timestamp from the filename (if it exists)
        $cleanedFileName = preg_replace('/^\d+_/', '', $cleanedFileName); // Removes timestamp at the start
        
        // Generate a unique name (optional, you could also omit this if you want just the cleaned name)
        $fileName = time() . '_' . $cleanedFileName;  // You can keep or omit the time if it's not needed
        
        // Store the file
        return $file->storeAs($directory, $fileName, 'public');
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
            'domain_type' => 'nullable|string',
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

            'no_of_installments' => 'nullable|integer|in:2,3,4',
        'first_installment' => 'nullable|date',
        'second_installment' => 'nullable|date',
        'third_installment' => 'nullable|date',
        'fourth_installment' => 'nullable|date',
        ]);

        // Find the client by ID
        $client = Clients::findOrFail($id);
        $oldValues = $client->getOriginal(); // Get original values before update

         // Log old and validated data for debugging
    \Log::info('Old Values:', $oldValues);
    \Log::info('Validated Data:', $validatedData);

        // Handle file upload
        // Handle file uploads
        if ($request->hasFile('contract')) {
            // Delete old file if it exists
            if ($client->contract) {
                Storage::delete($client->contract);
            }
    
            // Upload the new file
            $validatedData['contract'] = $this->uploadFile($request->file('contract'), 'contracts');
        }
    
        if ($request->hasFile('seo_contract')) {
            // Delete old file if it exists
            if ($client->seo_contract) {
                Storage::delete($client->seo_contract);
            }
    
            // Upload the new file
            $validatedData['seo_contract'] = $this->uploadFile($request->file('seo_contract'), 'contracts/seo');
        }
    
        if ($request->hasFile('maintenance_contract')) {
            // Delete old file if it exists
            if ($client->maintenance_contract) {
                Storage::delete($client->maintenance_contract);
            }
    
            // Upload the new file
            $validatedData['maintenance_contract'] = $this->uploadFile($request->file('maintenance_contract'), 'contracts/maintenance');
        }
    

        // Update the client with validated data
        $client->update($validatedData);

       // Get changes
       $changes = [];

       foreach ($validatedData as $key => $newValue) {
           $oldValue = $oldValues[$key] ?? null;
       
           if ((string) $newValue !== (string) $oldValue) {
               $changes[$key] = [
                'old_value' => $oldValue === null || $oldValue === '' ? 'empty' : $oldValue,
                'new_value' => $newValue === null || $newValue === '' ? 'empty' : $newValue,
               ];
           }
       }
       
       if (!empty($changes)) {
           foreach ($changes as $field => $change) {
               UserActivity::create([
                   'user_id' => auth()->id(),
                   'activity' => 'Updated ' . $field . '. From "' . $change['old_value'] . '" to "' . $change['new_value'] . '"',
                   'field' => $field,
                   'old_value' => $change['old_value'],
                   'new_value' => $change['new_value'],
                   'client_id' => $client->id,  // Save client ID here
                   'created_at' => Carbon::now('Asia/Kathmandu'), // Save activity time in Nepali timezone
                   'updated_at' => Carbon::now('Asia/Kathmandu'),
               ]);
           }
       } else {
           \Log::info('No changes detected.');
       }
       

    return redirect()->back()->with('success', 'Client updated successfully.');
}
public function details($id)
{
    // Find the client by ID
    $client = Clients::findOrFail($id);

    // Get all activities related to the client (without filtering by user)
    $activities = UserActivity::where('client_id', $id) // Filter by the client ID only
                               ->latest()  // Get the latest activities first
                               ->get()
                               ->map(function ($activity) {
                                // Convert timestamps to Nepali timezone
                                $activity->created_at = Carbon::parse($activity->created_at)->timezone('Asia/Kathmandu')->toDateTimeString();
                                $activity->updated_at = Carbon::parse($activity->updated_at)->timezone('Asia/Kathmandu')->toDateTimeString();
                                return $activity;
                            });

    // Pass the client and activities data to the view
    return view('frontends.client-details', compact('client', 'activities'));
}

}
