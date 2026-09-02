<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengeluaran_gajis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_slip', 50)->unique();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->date('tanggal_bayar');
            
            // Jam Kerja Reguler
            $table->decimal('total_jam_kerja', 8, 2)->default(0);
            $table->decimal('tarif_per_jam', 12, 2)->default(5000);
            $table->decimal('total_upah_jam', 12, 2)->default(0);
            
            // Fee Training Anggota Baru (Rp 20.000 / 8 jam)
            $table->decimal('jam_training', 8, 2)->default(0);
            $table->decimal('tarif_training_per_8jam', 12, 2)->default(20000);
            $table->decimal('total_fee_training', 12, 2)->default(0);
            
            // Tambahan & Potongan
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('potongan', 12, 2)->default(0);
            
            // Total Gaji Bersih
            $table->decimal('total_gaji', 15, 2)->default(0);
            
            $table->enum('metode_pembayaran', ['cash', 'transfer'])->default('cash');
            $table->enum('status', ['dibayar', 'pending'])->default('dibayar');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_gajis');
    }
};
