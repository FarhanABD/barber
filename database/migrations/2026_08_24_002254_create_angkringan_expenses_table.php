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
        Schema::create('angkringan_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengeluaran');
            $table->integer('jumlah');
            $table->dateTime('tanggal');
            $table->string('nama_kasir')->nullable();
            $table->string('bukti_resi')->nullable();
            $table->string('status')->default('pengajuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkringan_expenses');
    }
};
