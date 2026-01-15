<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Foreign Key Pelanggan
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Foreign Key Barber
            $table->foreignId('barber_id')->constrained()->cascadeOnDelete();
            // Foreign Key Layanan
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            // Waktu Booking (mulai dan selesai)
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            // Status Booking
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending');
            // Total Harga
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
            // Tambahkan index unik untuk menghindari booking ganda di waktu yang sama oleh barber yang sama
            $table->unique(['barber_id', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};