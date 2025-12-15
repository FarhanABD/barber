<?php

// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'customer_name',
        'barber_id',
        'total_price',
        'diskon',
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