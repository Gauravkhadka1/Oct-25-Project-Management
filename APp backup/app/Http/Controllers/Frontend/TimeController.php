<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function start(Request $request)
    {
        // Save start time to the database
    }

    public function pause(Request $request)
    {
        // Save pause time and elapsed time to the database
    }

    public function stop(Request $request)
    {
        // Save stop time and finalize the record in the database
    }
}
