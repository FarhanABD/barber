<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $primaryKey = 'id_mitra';

    protected $fillable = [
        'nama_mitra',
        'kontak',
        'alamat',
        'status',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'mitra_id', 'id_mitra');
    }
}