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
    
        // Get the 'days_filter' from the request, if present
        $daysFilter = $request->input('days_filter');
    
        // Initialize services count for each range
        $servicesIn35To31Days = 0;
        $servicesIn30To16Days = 0;
        $servicesIn15To8Days = 0;
        $servicesIn7To1Days = 0;
        $servicesExpiringToday = 0;
        $expiredServicesCount = 0;
    
        // Initialize services data array
        $servicesData = [];
    
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
                    $daysLeft = Carbon::now()->diffInDays($expiryDate, false);
                    
                    // Round daysLeft to a whole number (if needed)
                    $daysLeft = floor($daysLeft);  // Use floor or ceil as per your need
    
                    // Increment service counts based on expiry date range
                    if ($daysLeft >= 31 && $daysLeft <= 35) {
                        $servicesIn35To31Days++;
                    } elseif ($daysLeft >= 16 && $daysLeft <= 30) {
                        $servicesIn30To16Days++;
                    } elseif ($daysLeft >= 8 && $daysLeft <= 15) {
                        $servicesIn15To8Days++;
                    } elseif ($daysLeft >= 1 && $daysLeft <= 7) {
                        $servicesIn7To1Days++;
                    } elseif ($daysLeft === 0) {
                        $servicesExpiringToday++;
                    } elseif ($daysLeft < 0) {
                        $expiredServicesCount++;
                    }
    
                    // Add the service to the servicesData array
                    $servicesData[] = [
                        'domain_name' => $client->website,
                        'service_type' => ucfirst($service),
                        'expiry_date' => $expiryDate,
                        'amount' => $client->{$service . '_amount'},
                        'days_left' => $daysLeft // Use days_left without categorizing it as expired or active
                    ];
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
                'expired' => [null, -1],
            ];
    
            if (array_key_exists($daysFilter, $ranges)) {
                list($maxDays, $minDays) = $ranges[$daysFilter];
    
                // Filter the servicesData based on the expiry range
                $servicesData = array_filter($servicesData, function ($service) use ($maxDays, $minDays) {
                    return $service['days_left'] <= $maxDays && $service['days_left'] >= $minDays;
                });
    
                // If the filter is "expired", show services with expired dates
                if ($daysFilter === 'expired') {
                    $servicesData = array_filter($servicesData, function ($service) {
                        return $service['days_left'] < 0;  // Show only expired services (days_left < 0)
                    });
                }
            }
        }
    
        return view('frontends.expiry', compact(
            'servicesData', 
            'servicesIn35To31Days', 
            'servicesIn30To16Days', 
            'servicesIn15To8Days', 
            'servicesIn7To1Days', 
            'servicesExpiringToday', 
            'expiredServicesCount'
        ));
    }
}
