<?php

namespace App\Helpers;

use App\Models\Clients;
use Carbon\Carbon;

class ServiceHelper
{
    public static function getServiceCount($daysFilter)
    {
        $servicesCount = 0;
        $clients = Clients::all();
        $nowNepalTime = Carbon::now('Asia/Kathmandu')->startOfDay();

        foreach ($clients as $client) {
            $services = [
                'hosting' => $client->hosting_expiry_date,
                'domain' => $client->domain_expiry_date,
                'microsoft' => $client->microsoft_expiry_date,
                'maintenance' => $client->maintenance_expiry_date,
                'seo' => $client->seo_expiry_date,
                'web_design_1st_installment' => $client->first_installment,
                'web_design_2nd_installment' => $client->second_installment,
                'web_design_3rd_installment' => $client->third_installment,
                'web_design_Final_installment' => $client->fourth_installment,
            ];

            $uniqueExpiryDates = [];
            foreach ($services as $service => $expiryDate) {
                if ($expiryDate) {
                    $expiryDateNepalTime = Carbon::parse($expiryDate)->setTimezone('Asia/Kathmandu')->startOfDay();
                    $uniqueExpiryDates[$expiryDateNepalTime->toDateString()] = true;
                }
            }

            foreach ($uniqueExpiryDates as $expiryDate => $value) {
                $expiryDateNepalTime = Carbon::parse($expiryDate)->setTimezone('Asia/Kathmandu')->startOfDay();
                $daysLeft = $nowNepalTime->diffInDays($expiryDateNepalTime, false);

                if ($daysFilter == 'all') {
                    $servicesCount++;
                } elseif ($daysFilter === 'expired' && $daysLeft < 0) {
                    $servicesCount++;
                } elseif ($daysFilter === 'today' && $expiryDateNepalTime->isToday()) {
                    $servicesCount++;
                } elseif ($daysFilter === '35-31' && $daysLeft >= 31 && $daysLeft <= 35) {
                    $servicesCount++;
                } elseif ($daysFilter === '30-16' && $daysLeft >= 16 && $daysLeft <= 30) {
                    $servicesCount++;
                } elseif ($daysFilter === '15-8' && $daysLeft >= 8 && $daysLeft <= 15) {
                    $servicesCount++;
                } elseif ($daysFilter === '7-1' && $daysLeft >= 1 && $daysLeft <= 7) {
                    $servicesCount++;
                }
            }
        }

        return $servicesCount;
    }
}
