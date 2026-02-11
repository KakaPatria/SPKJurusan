<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) Tambahkan kolom siswa ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('nis')->nullable()->unique()->after('email');
            $table->enum('kelompok_asal', ['IPA', 'IPS'])->nullable()->after('nis');
            $table->string('foto')->nullable()->after('kelompok_asal');
        });

        // 2) Salin data dari tabel students jika ada
        if (Schema::hasTable('students')) {
            $students = DB::table('students')->get();
            foreach ($students as $s) {
                // Pastikan user ada
                $user = DB::table('users')->where('id', $s->user_id)->first();
                if ($user) {
                    DB::table('users')->where('id', $s->user_id)->update([
                        'nis' => $s->nis ?? null,
                        'kelompok_asal' => $s->kelompok_asal ?? null,
                        'foto' => $s->foto ?? null,
                        'updated_at' => now(),
                    ]);
                }
            }

            // 3) Hapus tabel students
            Schema::dropIfExists('students');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan dengan menambah tabel students lagi (kosong)
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nis')->unique()->nullable();
            $table->enum('kelompok_asal', ['IPA', 'IPS'])->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nis', 'kelompok_asal', 'foto']);
        });
    }
};
