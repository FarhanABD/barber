<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAngkringan extends Model
{
    protected $table = 'transaction_angkringan';
    protected $primaryKey = 'id_transaction';

     protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'total',
        'metode_pembayaran',
        'jumlah_bayar',
        'kembalian',
        'status',
        'mitra_id', // ⬅️ tambah
    ];

     protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(
            TransactionItemAngkringan::class,
            'transaction_id',
            'id_transaction'
        );
    }
    
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'id_mitra');
    }
}