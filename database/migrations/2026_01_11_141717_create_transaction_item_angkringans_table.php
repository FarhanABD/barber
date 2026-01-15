<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items_angkringan', function (Blueprint $table) {
            $table->id('id_transaction_item');

            $table->foreignId('transaction_id')
                ->constrained('transaction_angkringan', 'id_transaction')
                ->cascadeOnDelete();

            $table->foreignId('menu_id')
                ->constrained('menus', 'id_menu')
                ->restrictOnDelete();

            $table->decimal('harga', 12, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items_angkringan');
    }
};