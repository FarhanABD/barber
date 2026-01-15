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
      Schema::table('transaction_angkringan', function (Blueprint $table) {
    $table->string('metode_pembayaran', 30)
          ->default('cash')
          ->after('total');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_angkringan', function (Blueprint $table) {
            //
        });
    }
};