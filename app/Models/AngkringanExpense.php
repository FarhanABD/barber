<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngkringanExpense extends Model
{
    use HasFactory;

    protected $table = 'angkringan_expenses';

    protected $fillable = [
        'nama_pengeluaran',
        'jumlah',
        'tanggal',
        'nama_kasir',
        'bukti_resi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];
}
