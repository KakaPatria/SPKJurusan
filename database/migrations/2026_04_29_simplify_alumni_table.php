<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Simplify alumni table - keep only essential columns
     * Remove: success_status, ranking_saat_rekomendasi, predicted_score, ipk_lulus, karir_outcome
     * Update: preferensi_studi dengan 5 universal options
     * Keep: Essential academic & career data only
     */
    public function up(): void
    {
        $colsToDrop = ['success_status', 'ranking_saat_rekomendasi', 'predicted_score', 'ipk_lulus', 'karir_outcome', 'preferensi_studi'];
        foreach ($colsToDrop as $col) {
            if (Schema::hasColumn('alumni', $col)) {
                Schema::table('alumni', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }

        Schema::table('alumni', function (Blueprint $table) {
            // Add kembali preferensi_studi dengan enum values yang tepat
            $table->enum('preferensi_studi', [
                'Sains & Teknologi',
                'Pertanian & Lingkungan',
                'Kesehatan & Ilmu Hayat',
                'Bisnis & Manajemen',
                'Sosial & Humaniora'
            ])->nullable()->after('cita_cita');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            //
        });
    }
};

