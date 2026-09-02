<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranGaji extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_gajis';

    protected $fillable = [
        'kode_slip',
        'karyawan_id',
        'skema_gaji',
        'periode_mulai',
        'periode_selesai',
        'tanggal_bayar',
        'total_jam_kerja',
        'tarif_per_jam',
        'total_upah_jam',
        'total_kepala',
        'total_omset',
        'persentase_komisi',
        'total_komisi',
        'rincian_layanan',
        'jam_training',
        'tarif_training_per_8jam',
        'total_fee_training',
        'bonus',
        'potongan',
        'total_gaji',
        'metode_pembayaran',
        'status',
        'catatan',
    ];

    protected $casts = [
        'periode_mulai'           => 'date',
        'periode_selesai'         => 'date',
        'tanggal_bayar'           => 'date',
        'total_jam_kerja'         => 'decimal:2',
        'tarif_per_jam'           => 'decimal:2',
        'total_upah_jam'          => 'decimal:2',
        'total_kepala'            => 'integer',
        'total_omset'             => 'decimal:2',
        'persentase_komisi'       => 'decimal:2',
        'total_komisi'            => 'decimal:2',
        'rincian_layanan'         => 'array',
        'jam_training'            => 'decimal:2',
        'tarif_training_per_8jam' => 'decimal:2',
        'total_fee_training'      => 'decimal:2',
        'bonus'                   => 'decimal:2',
        'potongan'                => 'decimal:2',
        'total_gaji'              => 'decimal:2',
    ];

    public function karyawan()
    {
        return $this->belongsTo(MasterKaryawan::class, 'karyawan_id');
    }

    public function getFormattedTotalGajiAttribute(): string
    {
        return 'Rp ' . number_format($this->total_gaji ?? 0, 0, ',', '.');
    }

    public function getFormattedUpahJamAttribute(): string
    {
        return 'Rp ' . number_format($this->total_upah_jam ?? 0, 0, ',', '.');
    }

    public function getFormattedFeeTrainingAttribute(): string
    {
        return 'Rp ' . number_format($this->total_fee_training ?? 0, 0, ',', '.');
    }

    public function getFormattedTotalKomisiAttribute(): string
    {
        return 'Rp ' . number_format($this->total_komisi ?? 0, 0, ',', '.');
    }

    public function getFormattedTotalOmsetAttribute(): string
    {
        return 'Rp ' . number_format($this->total_omset ?? 0, 0, ',', '.');
    }
}
