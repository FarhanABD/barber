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
        Schema::table('pengeluaran_gajis', function (Blueprint $table) {
            $table->enum('skema_gaji', ['jam_kerja', 'komisi_barber'])->default('jam_kerja')->after('karyawan_id');
            $table->integer('total_kepala')->default(0)->after('total_upah_jam');
            $table->decimal('total_omset', 15, 2)->default(0)->after('total_kepala');
            $table->decimal('persentase_komisi', 5, 2)->default(45.00)->after('total_omset');
            $table->decimal('total_komisi', 15, 2)->default(0)->after('persentase_komisi');
            $table->json('rincian_layanan')->nullable()->after('total_komisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluaran_gajis', function (Blueprint $table) {
            $table->dropColumn([
                'skema_gaji',
                'total_kepala',
                'total_omset',
                'persentase_komisi',
                'total_komisi',
                'rincian_layanan',
            ]);
        });
    }
};
