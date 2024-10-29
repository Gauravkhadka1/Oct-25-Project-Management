<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign1;

class HomeController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function admin()
    {
        return view('frontends.admin');
    }

    public function admindashboard()
    {
        return view('frontends.admin-dashboard');
    }

    public function payments()
    {
        return view('frontends.payments');
    }

    public function user()
    {
        return view('frontends.user-dashboard');
    }

    public function prospects()
    {
        return view('frontends.prospects');
    }
}
