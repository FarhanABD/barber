<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKaryawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $fillable = [
        'nik',
        'nama',
        'divisi',
        'jabatan',
        'no_hp',
        'email',
        'alamat',
        'status',
        'gaji_pokok',
        'tanggal_masuk',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'gaji_pokok' => 'decimal:2',
    ];

    public function getFormattedGajiAttribute(): string
    {
        return 'Rp ' . number_format($this->gaji_pokok ?? 0, 0, ',', '.');
    }
}
