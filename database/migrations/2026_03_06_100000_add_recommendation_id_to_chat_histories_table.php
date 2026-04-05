<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('recommendation_id')->nullable()->after('session_id');
            $table->foreign('recommendation_id')->references('id')->on('recommendations')->onDelete('set null');
        });

        // Backfill: untuk setiap session, cari rekomendasi terdekat sebelum chat pertama user tersebut
        $sessions = DB::table('chat_histories')
            ->select('session_id', 'user_id', DB::raw('MIN(created_at) as first_chat_at'))
            ->whereNotNull('session_id')
            ->whereNull('recommendation_id')
            ->groupBy('session_id', 'user_id')
            ->get();

        foreach ($sessions as $session) {
            // Cari rekomendasi terakhir user sebelum atau tepat saat sesi dimulai
            $rec = DB::table('recommendations')
                ->where('user_id', $session->user_id)
                ->where('created_at', '<=', $session->first_chat_at)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($rec) {
                DB::table('chat_histories')
                    ->where('session_id', $session->session_id)
                    ->update(['recommendation_id' => $rec->id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->dropForeign(['recommendation_id']);
            $table->dropColumn('recommendation_id');
        });
    }
};
