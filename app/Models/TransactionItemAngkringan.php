<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItemAngkringan extends Model
{
    protected $table = 'transaction_items_angkringan';
    protected $primaryKey = 'id_transaction_item';

    protected $fillable = [
        'transaction_id',
        'menu_id',
        'harga',
        'qty',
        'subtotal'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id_menu');
    }
}