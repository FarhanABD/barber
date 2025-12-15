<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('frontend.dashboard');
    }

    public function hairstyle()
    {
        return view('frontend.hairstyle');
    }

    public function about()
    {
        return view('frontend.about');
    }
}