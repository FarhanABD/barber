<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_services_table.php

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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            // Nama Layanan (misal: "Haircut Pria Dewasa")
            $table->string('name', 100)->unique();
            // Deskripsi singkat
            $table->text('description')->nullable();
            // Harga dalam mata uang (DECIMAL untuk presisi)
            $table->decimal('price', 10, 2);
            // Durasi dalam menit
            $table->integer('duration_minutes'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};