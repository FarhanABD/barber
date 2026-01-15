<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
        $table->id('id_menu');
        $table->foreignId('category')
            ->constrained('categories', 'id_category')
            ->cascadeOnDelete();
        $table->string('nama');
        $table->decimal('harga', 12, 2);
        $table->decimal('hpp', 12, 2);
        $table->boolean('status')->default(true);
        $table->string('gambar')->nullable();
        $table->text('deskripsi')->nullable();
        $table->foreignId('mitra_id')->nullable()->constrained('mitras', 'id_mitra')->nullOnDelete();
        $table->timestamps();
});

    }
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};