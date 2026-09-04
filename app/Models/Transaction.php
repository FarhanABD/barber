<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'no_antrian',
        'customer_name',
        'nama_kasir',
        'barber_id',
        'diskon',
        'total_price',
        'metode_pembayaran',
        'booking_id',
    ];

    /* ================= RELATION ================= */

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}