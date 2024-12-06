<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\User;
use App\Notifications\PaymentsStatusUpdated;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;


class PaidPaymentsController extends Controller
{
    public function index(Request $request)
{
    $query = Payments::where('status', 'paid');
    $paidLabel = 'Total Paid'; // Default label

    $today = Carbon::today();
    // Calculate the available days based on the current date
    $availableDays = 21 + $today->diffInDays(Carbon::parse('2024-12-06')); // Assuming 21 days is for 2024-12-06

    // Handle 'days' input
    if ($request->has('days')) {
        $days = $request->input('days');
        if ($days > $availableDays) {
            // Show warning if more than available days are requested
            $paidLabel = "Data available for only $availableDays days. Showing data for the last $availableDays days.";
            $days = $availableDays; // Limit the days to available
        } else {
            // If the days entered is less than or equal to available days, update the label accordingly
            $paidLabel = "Total Paid Last $days Days"; 
        }
        $query->where('paid_date', '>=', $today->subDays($days));
    }

    // Filter by specific date range (today, this week, this month)
    if ($request->has('filter_date') && $request->filter_date !== 'all') {
        if ($request->filter_date === 'today') {
            $paidLabel = 'Total Paid Today';
            $query->whereDate('paid_date', Carbon::today());
        } elseif ($request->filter_date === 'this_week') {
            $paidLabel = 'Total Paid This Week';
            $query->whereBetween('paid_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($request->filter_date === 'this_month') {
            $paidLabel = 'Total Paid This Month';
            $query->whereMonth('paid_date', Carbon::now()->month)->whereYear('paid_date', Carbon::now()->year);
        }
    }

    // Get the filtered payments
    $payments = $query->get();

    // Calculate totals for each category
    $totalPaidAmount = $payments->sum('amount');
    $totalWebsite = $payments->where('category', 'Website')->sum('amount');
    $totalMicrosoft = $payments->where('category', 'Microsoft')->sum('amount');
    $totalRenewal = $payments->where('category', 'Renewal')->sum('amount');
    $totalOthers = $payments->whereNotIn('category', ['Website', 'Microsoft', 'Renewal'])->sum('amount');

    return view('frontends.paid-payments', compact(
        'payments',
        'totalPaidAmount',
        'totalWebsite',
        'totalMicrosoft',
        'totalRenewal',
        'totalOthers',
        'paidLabel'
    ));
}



}