<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaction_angkringan', function (Blueprint $table) {
            $table->string('nama_kasir')->after('mitra_id');
        });
    }

    public function down()
    {
        Schema::table('transaction_angkringan', function (Blueprint $table) {
            $table->dropColumn('nama_kasir');
        });
    }
};