<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('recommendations') && !Schema::hasTable('rekomendasi')) {
            Schema::rename('recommendations', 'rekomendasi');
        }

        if (Schema::hasTable('polije_majors') && !Schema::hasTable('jurusan_polije')) {
            Schema::rename('polije_majors', 'jurusan_polije');
        }

        if (Schema::hasTable('chat_histories') && !Schema::hasTable('riwayat_chat')) {
            Schema::rename('chat_histories', 'riwayat_chat');
        }

        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'session_id') && !Schema::hasColumn('riwayat_chat', 'id_sesi')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('session_id', 'id_sesi');
            });
        }

        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'prompt') && !Schema::hasColumn('riwayat_chat', 'pertanyaan')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('prompt', 'pertanyaan');
            });
        }

        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'response') && !Schema::hasColumn('riwayat_chat', 'jawaban')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('response', 'jawaban');
            });
        }

        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'recommendation_id') && !Schema::hasColumn('riwayat_chat', 'id_rekomendasi')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('recommendation_id', 'id_rekomendasi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'id_sesi') && !Schema::hasColumn('riwayat_chat', 'session_id')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('id_sesi', 'session_id');
            });
        }

        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'pertanyaan') && !Schema::hasColumn('riwayat_chat', 'prompt')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('pertanyaan', 'prompt');
            });
        }

        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'jawaban') && !Schema::hasColumn('riwayat_chat', 'response')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('jawaban', 'response');
            });
        }

        if (Schema::hasTable('riwayat_chat') && Schema::hasColumn('riwayat_chat', 'id_rekomendasi') && !Schema::hasColumn('riwayat_chat', 'recommendation_id')) {
            Schema::table('riwayat_chat', function (Blueprint $table) {
                $table->renameColumn('id_rekomendasi', 'recommendation_id');
            });
        }

        if (Schema::hasTable('riwayat_chat') && !Schema::hasTable('chat_histories')) {
            Schema::rename('riwayat_chat', 'chat_histories');
        }

        if (Schema::hasTable('jurusan_polije') && !Schema::hasTable('polije_majors')) {
            Schema::rename('jurusan_polije', 'polije_majors');
        }

        if (Schema::hasTable('rekomendasi') && !Schema::hasTable('recommendations')) {
            Schema::rename('rekomendasi', 'recommendations');
        }
    }
};
