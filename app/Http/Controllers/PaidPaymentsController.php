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

        if ($request->has('filter_date') && $request->filter_date !== 'all') {
            if ($request->filter_date === 'today') {
                $paidLabel = 'Total Paid Today';
            } elseif ($request->filter_date === 'this_week') {
                $paidLabel = 'Total Paid This Week';
            } elseif ($request->filter_date === 'this_month') {
                $paidLabel = 'Total Paid This Month';
            }
        }
        
        if ($request->has('days')) {
            $days = $request->input('days');
            $paidLabel = "Total Paid Last $days Days";
        }
    
        if ($request->has('filter_date') && $request->filter_date !== 'all') {
            $filterDate = $request->filter_date;
    
            if ($filterDate === 'today') {
                $query->whereDate('paid_date', Carbon::today());
            } elseif ($filterDate === 'this_week') {
                $query->whereBetween('paid_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filterDate === 'this_month') {
                $query->whereMonth('paid_date', Carbon::now()->month)->whereYear('paid_date', Carbon::now()->year);
            }
        }
    
        if ($request->has('days')) {
            $days = $request->input('days');
            $dateLimit = Carbon::now()->subDays($days);
            $query->where('paid_date', '>=', $dateLimit);
        }
    
        $payments = $query->get();
    
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