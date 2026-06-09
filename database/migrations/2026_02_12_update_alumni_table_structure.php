<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $colsToDrop = ['tahun_masuk', 'preferensi_studi', 'tahun_lulus'];
        foreach ($colsToDrop as $col) {
            if (Schema::hasColumn('alumni', $col)) {
                Schema::table('alumni', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }

        Schema::table('alumni', function (Blueprint $table) {
            // === INPUT VARIABLES (dari SMA) ===
            $table->year('tahun_masuk_sma')->nullable()->after('kelompok_asal');
            $table->year('tahun_lulus_sma')->nullable()->after('tahun_masuk_sma');
            
            // Minat & Karir
            $table->string('minat')->nullable()->change();
            $table->string('cita_cita')->nullable()->change();
            $table->enum('preferensi_studi_lanjutan', ['Praktik_Langsung', 'DuDi', 'Project_Based', 'Blended'])->nullable()->after('cita_cita');
            $table->text('prestasi')->nullable()->change();
            
            // === OUTPUT VARIABLES (di Polije) ===
            $table->year('tahun_masuk_polije')->nullable()->after('major_masuk');
            $table->year('tahun_lulus_polije')->nullable()->after('tahun_masuk_polije');
            
            // === METADATA ===
            if (!Schema::hasColumn('alumni', 'notes')) {
                $table->text('notes')->nullable()->after('catatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            //
        });
    }
};
