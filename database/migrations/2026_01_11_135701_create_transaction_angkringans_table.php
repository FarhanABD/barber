<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_angkringan', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->string('kode_transaksi')->unique();
            $table->dateTime('tanggal');
            $table->decimal('total', 12, 2);
            $table->enum('status', ['paid', 'unpaid', 'cancel'])->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_angkringan');
    }
};