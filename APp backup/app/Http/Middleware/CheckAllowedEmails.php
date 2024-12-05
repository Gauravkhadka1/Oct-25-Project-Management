<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAllowedEmails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Define the allowed emails
        $allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];

        // Check if the user is authenticated and their email is in the allowed list
        if (Auth::check() && in_array(Auth::user()->email, $allowedEmails)) {
            return $next($request);
        }

        // If the user is not allowed, redirect them or return a forbidden response
        return redirect('/')->with('error', 'You are not authorized to access this page.');
    }
}
