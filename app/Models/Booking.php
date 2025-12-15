<?php

// app/Models/Booking.php

namespace AppModels;

use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'nama_customer',
        'barber_id',
        'service_id',
        'start_time',
        'end_time',
        'status',
        'total_price',
    ];

    /**
     * Casting otomatis untuk kolom waktu.
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // --- Relasi (Hubungan) ---

    /**
     * Relasi ke Customer (User): Booking dimiliki oleh satu user (customer).
     */
    public function customer(): BelongsTo
    {
        // Kita menggunakan nama 'customer' di sini, tetapi merujuk ke Model User
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Barber: Booking ditujukan untuk satu barber.
     */
    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class, 'barber_id');
    }

    /**
     * Relasi ke Service: Booking ini memesan satu jenis layanan.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}