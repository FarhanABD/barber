<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('frontend.dashboard');
    }

   public function dashboardAngkringan()
    {
        $listCategories = Category::with(['menus' => function ($q) {
            $q->where('status', true);
        }])
        ->where('status', true)
        ->orderBy('nama', 'asc')
        ->get();

        return view('frontend-angkringan.dashboard', compact('listCategories'));
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