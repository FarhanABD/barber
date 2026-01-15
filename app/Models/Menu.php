<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

     protected $fillable = [
        'category',
        'mitra_id',
        'nama',
        'hpp',
        'harga',
        'status',
        'gambar',
        'deskripsi',
    ];

    public function categoryData()
    {
        return $this->belongsTo(Category::class, 'category', 'id_category');
    }

     public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'id_mitra');
    }
}