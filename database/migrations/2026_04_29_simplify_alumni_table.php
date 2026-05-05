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
        $driver = DB::connection()->getDriverName();

        // Skip for SQLite since it has limited ALTER TABLE support
        if ($driver === 'sqlite') {
            return;
        }

        // For MySQL/PostgreSQL: drop unnecessary columns
        $columnsToDrop = [];
        foreach (['success_status', 'ranking_saat_rekomendasi', 'predicted_score', 'ipk_lulus', 'karir_outcome'] as $col) {
            if (Schema::hasColumn('alumni', $col)) {
                $columnsToDrop[] = $col;
            }
        }

        if (!empty($columnsToDrop)) {
            Schema::table('alumni', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }

        // Drop and recreate preferensi_studi column
        if (Schema::hasColumn('alumni', 'preferensi_studi')) {
            Schema::table('alumni', function (Blueprint $table) {
                $table->dropColumn('preferensi_studi');
            });
        }

        Schema::table('alumni', function (Blueprint $table) {
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
        // Rollback logic (if needed)
    }
};

