<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'no_antrian',
        'nama_customer',
        'barber_id',
        'service_id',
        'start_time',
        'end_time',
        'status',
        'total_price',
    ];

    /**
     * Relasi ke Barber
     */
    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    /**
     * Relasi ke Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope booking hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }
}