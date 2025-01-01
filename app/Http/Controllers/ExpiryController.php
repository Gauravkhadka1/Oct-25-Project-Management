<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ExpiryController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all clients
        $clientsQuery = Clients::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $clientsQuery->where('company_name', 'like', '%' . $searchTerm . '%');
        }
        $clients = $clientsQuery->get();

        $sortBy = $request->input('sort_by', 'days_left'); // Default sort column
        $sortOrder = $request->input('sort_order', 'asc'); // Default sort order

        $filterCategories = $request->input('filter_categories', []);
        // Get the 'days_filter' from the request, if present
        $daysFilter = $request->input('days_filter');

        // Initialize services data array
        $servicesData = [];

        // Get current date and time in Nepali Time (Asia/Kathmandu)
        $nowNepalTime = Carbon::now('Asia/Kathmandu')->startOfDay();  // Set time to 00:00:00 for the current day

   

        // Calculate services count for each range and build services data
        foreach ($clients as $client) {
            $services = [
                'hosting' => $client->hosting_expiry_date,
                'domain' => $client->domain_expiry_date,
                'microsoft' => $client->microsoft_expiry_date,
                'maintenance' => $client->maintenance_expiry_date,
                'seo' => $client->seo_expiry_date,
                'web_design_1st_installment' => [
                    'expiry_date' => $client->first_installment,
                    'amount' => $client->first_installment_amount
                ],
                'web_design_2nd_installment' => [
                    'expiry_date' => $client->second_installment,
                    'amount' => $client->second_installment_amount
                ],
                'web_design_3rd_installment' => [
                    'expiry_date' => $client->third_installment,
                    'amount' => $client->third_installment_amount
                ],
                'web_design_Final_installment' => [
                    'expiry_date' => $client->fourth_installment,
                    'amount' => $client->fourth_installment_amount
                ]
            ];

            foreach ($services as $service => $data) {
                // Check if it's a web design installment
                if (is_array($data)) {
                    // This is an installment, so we check if both expiry_date and amount are present
                    if ($data['expiry_date']) {
                        $expiryDateNepalTime = Carbon::parse($data['expiry_date'])->setTimezone('Asia/Kathmandu')->startOfDay(); // Adjust to Nepali time
                        
                        $daysLeft = $nowNepalTime->diffInDays($expiryDateNepalTime, false);
                        $daysLeft = floor($daysLeft);  // Round days left
                        
                        // Generate a unique key for each installment
                        $key = $client->id . '-' . $expiryDateNepalTime->format('Y-m-d') . '-' . $service;
                        
                        // If this combination already exists, update it
                        if (isset($servicesData[$key])) {
                            $servicesData[$key]['service_type'] .= ' & ' . ucfirst(str_replace('_', ' ', $service)); // Add the service name to the list
                            $servicesData[$key]['amount'] += $data['amount']; // Sum the amounts
                        } else {
                            // Add the new installment entry
                            $servicesData[$key] = [
                                'domain_name' => $client->website,
                                'service_type' => ucfirst(str_replace('_', ' ', $service)),
                                'expiry_date' => $expiryDateNepalTime,
                                'amount' => $data['amount'],
                                'days_left' => $daysLeft,
                                'client_id' => $client->id,
                             'client_email' => $client->contact_person_email ?? 'N/A'
                            ];
                        }
                    }
                } else {
                    // Handle other non-installment services as before
                    if ($data) {
                        // Process other services like hosting, domain, etc.
                        $expiryDateNepalTime = Carbon::parse($data)->setTimezone('Asia/Kathmandu')->startOfDay(); // Adjust to Nepali time
                        $daysLeft = $nowNepalTime->diffInDays($expiryDateNepalTime, false);
                        $daysLeft = floor($daysLeft);  // Round days left
            
                        $key = $client->id . '-' . $expiryDateNepalTime->format('Y-m-d');
            
                        if (isset($servicesData[$key])) {
                            $servicesData[$key]['service_type'] .= ' & ' . ucfirst(str_replace('_', ' ', $service));
                            $servicesData[$key]['amount'] += $client->{$service . '_amount'};
                        } else {
                            $servicesData[$key] = [
                                'domain_name' => $client->website,
                                'service_type' => ucfirst(str_replace('_', ' ', $service)),
                                'expiry_date' => $expiryDateNepalTime,
                                'amount' => $client->{$service . '_amount'},
                                'days_left' => $daysLeft,
                                'client_id' => $client->id,
                                'client_email' => $client->contact_person_email ?? 'N/A' 
                            ];
                        }
                    }
                }
            }
            
        }

        // Filter clients based on the selected filter
        if ($daysFilter) {
            $ranges = [
                '35-31' => [35, 31],
                '30-16' => [30, 16],
                '15-8' => [15, 8],
                '7-1' => [7, 1],
                'today' => [0, 0],
            ];

            if (array_key_exists($daysFilter, $ranges)) {
                list($maxDays, $minDays) = $ranges[$daysFilter];

                // Filter the servicesData based on the expiry range
                $servicesData = array_filter($servicesData, function ($service) use ($maxDays, $minDays) {
                    return $service['days_left'] <= $maxDays && $service['days_left'] >= $minDays;
                });
            }

            // If the filter is "expired", show services with expired dates
            if ($daysFilter === 'expired') {
                $servicesData = array_filter($servicesData, function ($service) {
                    return $service['days_left'] < 0;  // Show only expired services (days_left < 0)
                });
            }
        }

        // Sorting the servicesData array dynamically
        usort($servicesData, function ($a, $b) use ($sortBy, $sortOrder) {
            $valueA = $a[$sortBy] ?? null;
            $valueB = $b[$sortBy] ?? null;

            // Handle different data types
            if ($sortBy === 'expiry_date') {
                // Date comparison
                $valueA = $valueA instanceof \Carbon\Carbon ? $valueA : \Carbon\Carbon::parse($valueA);
                $valueB = $valueB instanceof \Carbon\Carbon ? $valueB : \Carbon\Carbon::parse($valueB);
                return $sortOrder === 'asc' ? $valueA->getTimestamp() <=> $valueB->getTimestamp() : $valueB->getTimestamp() <=> $valueA->getTimestamp();
            } elseif (is_numeric($valueA) && is_numeric($valueB)) {
                // Numeric comparison (for amount, days_left)
                return $sortOrder === 'asc' ? $valueA <=> $valueB : $valueB <=> $valueA;
            } else {
                // String comparison (for domain_name, service_type, or others)
                $valueA = strtolower(trim($valueA ?? ''));
                $valueB = strtolower(trim($valueB ?? ''));
                return $sortOrder === 'asc' ? strcmp($valueA, $valueB) : strcmp($valueB, $valueA);
            }
        });
        if ($daysFilter !== null) {
            $daysFilter = (int) $daysFilter;
            $servicesData = array_filter($servicesData, function ($service) use ($daysFilter) {
                return $service['days_left'] <= $daysFilter;
            });
        }
        
        // Handle category filters (including 'website')
        if (!empty($filterCategories) && !in_array('all', $filterCategories)) {
            $selectedCategories = array_map('strtolower', $filterCategories);
        
            // Special case for 'website'
            if (in_array('website', $selectedCategories)) {
                $servicesData = array_filter($servicesData, function ($service) {
                    $installmentServices = [
                        'web design 1st installment',
                        'web design 2nd installment',
                        'web design 3rd installment',
                        'web design final installment',
                    ];
                    $serviceTypes = array_map('strtolower', explode(' & ', $service['service_type']));
                    return !empty(array_intersect($installmentServices, $serviceTypes));
                });
            } else {
                // Filter by other categories
                $servicesData = array_filter($servicesData, function ($service) use ($selectedCategories) {
                    $serviceTypes = array_map('strtolower', explode(' & ', $service['service_type']));
                    return !empty(array_intersect($selectedCategories, $serviceTypes));
                });
            }
        }
        
        
        
        
        return view('frontends.expiry', compact('servicesData', 'daysFilter'));
    }
   
    public function sendExpiryEmail(Request $request)
{
    $to = $request->input('client_email');
    
    if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['success' => false, 'message' => 'Invalid or missing email address.']);
    }

    $cc = ['testuser1@comeonnepal.com', 'testuser2@comeonnepal.com'];
    $serviceType = $request->input('service_type');
    $expiryDate = $request->input('expiry_date');
    $daysLeft = $request->input('days_left');
    $domainName = $request->input('domain_name');

    $subject = "Service Expiry Notice";
    $message = "
        Dear Sir/Madam,

        The $serviceType service associated with your domain ($domainName) is going to expire on $expiryDate ($daysLeft days remaining). 
        Please renew the service for uninterrupted service.

        Thank you.
    ";

    try {
        Mail::raw($message, function ($mail) use ($to, $cc, $subject) {
            $mail->from('testuser3@comeonnepal.com', 'Webtech Nepal')
                ->to($to)
                ->cc($cc)
                ->subject($subject);
        });

        return response()->json(['success' => true, 'email_sent_on' => now()->format('Y-m-d H:i:s')]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
    }
}

    
}
