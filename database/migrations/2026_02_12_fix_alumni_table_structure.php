<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop alumni table dan buat ulang dengan struktur benar
        Schema::dropIfExists('alumni');
        
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            
            // === IDENTITAS ALUMNI SMA ===
            $table->string('nama_alumni');
            $table->string('nis')->nullable()->unique(); // NIS dari SMA Bima Ambulu
            $table->enum('kelompok_asal', ['IPA', 'IPS']);
            
            // === NILAI SAAT SMA (INPUT) ===
            // IPA: Matematika, Fisika, Kimia, Biologi
            $table->float('mtk')->nullable();
            $table->float('fisika')->nullable();
            $table->float('kimia')->nullable();
            $table->float('biologi')->nullable();
            
            // IPS: Ekonomi, Geografi, Sosiologi, Sejarah
            $table->float('ekonomi')->nullable();
            $table->float('geografi')->nullable();
            $table->float('sosiologi')->nullable();
            $table->float('sejarah')->nullable();
            
            $table->float('nilai_rata_rata')->nullable(); // auto-calculated
            
            // === VARIABEL NON-AKADEMIK SMA (INPUT) ===
            $table->string('minat')->nullable();
            $table->string('cita_cita')->nullable();
            $table->enum('preferensi_studi', ['Praktik_Langsung', 'DuDi', 'Project_Based', 'Blended'])->nullable();
            $table->text('prestasi')->nullable();
            
            // === HASIL KEPUTUSAN MASUK POLIJE (OUTPUT) ===
            $table->string('major_masuk'); // Jurusan yang dipilih di Polije
            $table->integer('ranking_saat_rekomendasi')->nullable(); // Ranking rekomendasi 1-9
            
            // === VALIDASI AKURASI EKSTRAKTION ===
            // Success = ranking rekomendasi cocok dengan pilihan sebenarnya
            $table->enum('success_status', ['sangat_sukses', 'sukses', 'cukup', 'kurang_sukses'])->nullable();
            $table->text('catatan')->nullable(); // Notas analyst
            
            // === METADATA ===
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
