<?php
namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpiryController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all clients
        $clients = Clients::all();

        $sortBy = $request->input('sort_by', 'domain_name'); // Default sort column
        $sortOrder = $request->input('sort_order', 'asc'); // Default sort order
    
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
                'seo' => $client->seo_expiry_date
            ];
    
            foreach ($services as $service => $expiryDate) {
                if ($expiryDate) {
                    // Adjust expiry date and current time to Nepali time zone
                    $expiryDateNepalTime = Carbon::parse($expiryDate)->setTimezone('Asia/Kathmandu')->startOfDay(); // Set to 00:00:00 for the expiry day
    
                    // Calculate days left from current time in Nepal, ignoring time (using startOfDay for both)
                    $daysLeft = $nowNepalTime->diffInDays($expiryDateNepalTime, false);
    
                    // Round daysLeft to a whole number (if needed)
                    $daysLeft = floor($daysLeft);  // Use floor or ceil as per your need
    
                    // Create a unique key for client and expiry date
                    $key = $client->id . '-' . $expiryDateNepalTime->format('Y-m-d');
    
                    // If this combination already exists, update it
                    if (isset($servicesData[$key])) {
                        $servicesData[$key]['service_type'] .= ' & ' . ucfirst($service);
                        $servicesData[$key]['amount'] += $client->{$service . '_amount'};
                    } else {
                        // Add the new service entry
                        $servicesData[$key] = [
                            'domain_name' => $client->website,
                            'service_type' => ucfirst($service),
                            'expiry_date' => $expiryDateNepalTime,
                            'amount' => $client->{$service . '_amount'},
                            'days_left' => $daysLeft,
                            'client_id' => $client->id // Add client id for linking to the details page
                        ];
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
        // Sorting the servicesData array dynamically
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


        
    
        return view('frontends.expiry', compact(
            'servicesData',
        ));
    }
}
