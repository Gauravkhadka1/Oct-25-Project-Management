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

        // Sorting logic for the selected column
        if ($sortColumn == 'days_left') {
            // For 'Days Left', we calculate it dynamically
            $clients = $clients->sortBy(function ($client) {
                $endDate = Carbon::parse($client->hosting_expiry_date);
                return now()->diffInDays($endDate, false);
            });
            if ($sortOrder == 'desc') {
                $clients = $clients->reverse();
            }
        } elseif ($sortColumn == 'status') {
            // For 'Status', we calculate the 'Days Left' dynamically and sort by status ('Active' or 'Expired')
            $clients = $clients->sortBy(function ($client) {
                $endDate = Carbon::parse($client->hosting_expiry_date);
                return now()->diffInDays($endDate, false) > 0 ? 'Active' : 'Expired';
            });
            if ($sortOrder == 'desc') {
                $clients = $clients->reverse();
            }
        } else {
            // For other columns, use orderBy
            $clients = $clients->sortBy($sortColumn);
            if ($sortOrder == 'desc') {
                $clients = $clients->reverse();
            }
        }

        return view('frontends.expiry', compact('clients'));
    }
}
