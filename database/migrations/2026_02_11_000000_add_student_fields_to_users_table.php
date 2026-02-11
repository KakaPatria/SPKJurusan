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
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('users', 'nis')) {
                $table->string('nis')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'kelompok_asal')) {
                $table->enum('kelompok_asal', ['IPA', 'IPS'])->nullable()->after('nis');
            }
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('kelompok_asal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nis')) {
                $table->dropColumn('nis');
            }
            if (Schema::hasColumn('users', 'kelompok_asal')) {
                $table->dropColumn('kelompok_asal');
            }
            if (Schema::hasColumn('users', 'foto')) {
                $table->dropColumn('foto');
            }
        });
    }
};
