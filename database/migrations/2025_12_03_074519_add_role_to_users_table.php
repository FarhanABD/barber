<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_role_to_users_table.php

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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'role' dengan nilai default 'customer'
            $table->enum('role', ['customer', 'barber', 'admin'])->default('customer')->after('email');
            // Tambahkan nomor telepon
            $table->string('phone_number')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('phone_number');
        });
    }
};