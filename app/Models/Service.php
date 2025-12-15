<?php

// app/Models/Service.php

namespace App\Models;

use AppModels\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     */
    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * Relasi ke Booking: Satu layanan dapat memiliki banyak janji temu (bookings).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function transactionItems()
{
    return $this->hasMany(TransactionItem::class);
}

}