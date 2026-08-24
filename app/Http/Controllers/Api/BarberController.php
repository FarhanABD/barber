<?php

namespace App\Http\Controllers\Api;

use App\Models\Barber;
use App\Http\Controllers\Controller;

class BarberController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Barber::select('id','name')->get()
        ]);
    }
}