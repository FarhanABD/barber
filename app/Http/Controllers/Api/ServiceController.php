<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
   public function index()
{
    $services = Service::all()->map(function($s){

        return [
            'id' => $s->id,
            'name' => $s->name,
            'price' => $s->price,
            'description' => $s->description,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $services
    ]);
}


}