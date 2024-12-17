<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpiryController extends Controller
{
    public function index(Request $request)
    {
        $sortOrder = $request->get('sort', 'asc'); // Default to 'asc' if no sort parameter is passed
        $sortColumn = $request->get('column', 'website'); // Default to 'website' if no column parameter is passed
        $daysFilter = $request->get('days_filter'); // Get the days filter from the request

        // Sorting and filtering logic
        $clients = Clients::all();

        if ($daysFilter) {
            // Apply day ranges based on selected filter
            $clients = $clients->filter(function ($client) use ($daysFilter) {
                $endDate = Carbon::parse($client->hosting_expiry_date);
                $daysLeft = now()->diffInDays($endDate, false); // Get the days remaining

                switch ($daysFilter) {
                    case '35-31':
                        return $daysLeft >= 31 && $daysLeft <= 35;
                    case '30-16':
                        return $daysLeft >= 16 && $daysLeft <= 30;
                    case '15-8':
                        return $daysLeft >= 8 && $daysLeft <= 15;
                    case '7-1':
                        return $daysLeft >= 1 && $daysLeft <= 7;
                    case 'today':
                        return $daysLeft == 0; // Expiring today
                    case 'expired':
                        return $daysLeft < 0; // Expired
                    default:
                        return true;
                }
            });
        }

        // Add service type and expiry logic for both domain and hosting
        foreach ($clients as $client) {
            // Get expiry dates for domain and hosting
            $expiryDates = [
                'Domain' => Carbon::parse($client->domain_expiry_date),
                'Hosting' => Carbon::parse($client->hosting_expiry_date),
            ];

            // Find the closest expiry date for either domain or hosting
            $closestService = collect($expiryDates)->sortBy(function ($date) {
                return $date->diffInDays(now());
            })->keys()->first();

            // Calculate days left for the closest expiry
            $endDate = $expiryDates[$closestService];
            $daysLeft = now()->diffInDays($endDate, false);

            // Store calculated values in the client model
            $client->service_type = $closestService;
            $client->days_left = $daysLeft;
            $client->expiry_date = $endDate->format('Y-m-d');
            $client->amount = $client->{$closestService . '_amount'};  // Assuming you have a field for amount like hosting_amount, domain_amount etc.
        }

        // Sorting logic for the selected column
        if ($sortColumn == 'days_left') {
            $clients = $clients->sortBy('days_left');
            if ($sortOrder == 'desc') {
                $clients = $clients->reverse();
            }
        } elseif ($sortColumn == 'status') {
            $clients = $clients->sortBy(function ($client) {
                return $client->days_left > 0 ? 'Active' : 'Expired';
            });
            if ($sortOrder == 'desc') {
                $clients = $clients->reverse();
            }
        } else {
            // For other columns, use the orderBy functionality
            $clients = $clients->sortBy($sortColumn);
            if ($sortOrder == 'desc') {
                $clients = $clients->reverse();
            }
        }

        return view('frontends.expiry', compact('clients'));
    }
}
