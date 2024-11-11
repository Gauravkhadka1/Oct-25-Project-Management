<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;

class ClientsController extends Controller
{
    public function index()
    {
        return view('frontends.clients');
    }
    public function addclients()
    {
        return view('frontends.add-clients');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name'=> 'nullable|string|max:255',
            'address'=> 'nullable|string|max:255',
           'company_phone'=> 'nullable|string|max:255',
           'company_email'=> 'nullable|string|max:255',
           'contact_person'=> 'nullable|string|max:255',
           'contact_person_phone'=> 'nullable|string|max:255',
           'contact_person_email'=> 'nullable|string|max:255',
           'category'=> 'nullable|string|max:255',
           'website_status'=> 'nullable|string|max:255',
           'issues'=> 'nullable|string|max:255',
           'hosting_start'=> 'nullable|date',
           'hosting_end'=> 'nullable|date|after_or_equal:start_date',
        ]);

        $clients = Clients::create($validatedData);

    
        $clients->save();

        return redirect(url('/clients'))->with('success', 'Client added successfully.');
    }
}
