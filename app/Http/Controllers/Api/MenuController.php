<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
  public function index()
{
    $menus = Menu::where('status', 'aktif')
        ->get()
        ->map(function($m){

            return [
                'id' => $m->id_menu,
                'nama' => $m->nama,
                'harga' => $m->harga,
                'gambar' => $m->gambar,
                'deskripsi' => $m->deskripsi,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $menus
    ]);
}


}