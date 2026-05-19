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
        Schema::table('alumni', function (Blueprint $table) {
            // Drop unnecessary columns
            if (Schema::hasColumn('alumni', 'success_status')) {
                $table->dropColumn('success_status');
            }
            if (Schema::hasColumn('alumni', 'ranking_saat_rekomendasi')) {
                $table->dropColumn('ranking_saat_rekomendasi');
            }
            if (Schema::hasColumn('alumni', 'predicted_score')) {
                $table->dropColumn('predicted_score');
            }
            if (Schema::hasColumn('alumni', 'ipk_lulus')) {
                $table->dropColumn('ipk_lulus');
            }
            if (Schema::hasColumn('alumni', 'karir_outcome')) {
                $table->dropColumn('karir_outcome');
            }
        });

        // Update preferensi_studi dengan raw SQL untuk menghindari Doctrine enum issue
        Schema::table('alumni', function (Blueprint $table) {
            if (Schema::hasColumn('alumni', 'preferensi_studi')) {
                $table->dropColumn('preferensi_studi');
            }
        });

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

