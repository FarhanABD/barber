<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('transaction_code')->unique(); // ID transaksi otomatis
            $table->string('customer_name');

            $table->foreignId('barber_id')
                  ->constrained('barbers')
                  ->onDelete('restrict');

            $table->decimal('total_price', 12, 2)->default(0);
            Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->nullOnDelete();
        });


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};