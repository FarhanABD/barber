<?php

namespace App\Models;

use AppModels\Booking;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number', // Dari migration kita
        'role',         // Dari migration kita (customer, barber, admin)
    ];

    /**
     * Kolom yang harus disembunyikan untuk serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts: Tipe data yang harus diubah secara otomatis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Menggunakan 'hashed' untuk hashing otomatis
    ];

    // --- RELATIONS (Hubungan) ---

    /**
     * Relasi ke Booking: User (Customer) dapat membuat banyak booking.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Relasi ke Barber: Jika User ini adalah seorang Tukang Cukur.
     */
    public function barberProfile()
    {
        return $this->hasOne(Barber::class);
    }
}
    /**
     * Relasi ke Service: Jika User ini adalah seorang Admin yang mengelola layanan.
     */
    // Tambahkan relasi lain jika diperlukan