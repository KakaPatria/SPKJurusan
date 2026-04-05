<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Backfill session_id untuk record lama yang belum punya.
     * Mengelompokkan berdasarkan user_id + tanggal.
     */
    public function up(): void
    {
        // Ambil semua record tanpa session_id
        $records = DB::table('chat_histories')
            ->whereNull('session_id')
            ->orderBy('user_id')
            ->orderBy('created_at')
            ->get();

        // Kelompokkan per user_id + tanggal
        $groups = $records->groupBy(function ($record) {
            return $record->user_id . '_' . substr($record->created_at, 0, 10);
        });

        foreach ($groups as $key => $chats) {
            $uuid = Str::uuid()->toString();
            $ids = $chats->pluck('id')->toArray();

            DB::table('chat_histories')
                ->whereIn('id', $ids)
                ->update(['session_id' => $uuid]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak bisa di-reverse secara akurat
    }
};
