<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'id_category';

    protected $fillable = [
        'nama',
        'status',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'category', 'id_category');
    }
}