<?php

// app/Models/Barber.php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barber extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     */

    protected $table = 'barbers';

    protected $fillable = ['name'];
    
    /**
     * Relasi ke User: Setiap tukang cukur memiliki satu akun pengguna (user).
     */
    public function user(): BelongsTo
    {
        // Secara default, kunci yang digunakan adalah 'user_id'
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Booking: Satu tukang cukur dapat memiliki banyak janji temu (bookings).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'barber_id');
    }

}