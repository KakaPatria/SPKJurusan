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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alumni');
            $table->string('nis')->nullable();
            $table->enum('kelompok_asal', ['IPA', 'IPS']);
            $table->year('tahun_masuk');
            
            // === NILAI SAAT ENTRY ===
            $table->float('mtk')->nullable();
            $table->float('fisika')->nullable();
            $table->float('kimia')->nullable();
            $table->float('biologi')->nullable();
            $table->float('ekonomi')->nullable();
            $table->float('geografi')->nullable();
            $table->float('sosiologi')->nullable();
            $table->float('sejarah')->nullable();
            $table->float('nilai_rata_rata')->nullable(); // auto-calculated
            
            // === VARIABEL NON-AKADEMIK SAAT ENTRY ===
            $table->string('minat')->nullable();
            $table->string('cita_cita')->nullable();
            $table->string('preferensi_studi')->nullable(); // Praktik Langsung, DuDi, Project Based, Blended
            $table->string('prestasi')->nullable();
            
            // === MAJOR & PREDICTION ===
            $table->string('major_masuk');
            $table->integer('ranking_saat_rekomendasi')->nullable(); // ranking berapa di list 9 jurusan
            $table->float('predicted_score')->nullable(); // score dari algoritma saat itu
            
            // === OUTCOME & PERFORMANCE ===
            $table->float('ipk_lulus')->nullable(); // cumulative GPA
            $table->year('tahun_lulus')->nullable();
            $table->text('karir_outcome')->nullable(); // deskripsi: jadi apa, kerja dimana
            $table->enum('success_status', ['sangat_sukses', 'sukses', 'cukup', 'kurang_sukses'])->nullable();
            $table->text('catatan')->nullable();
            
            // === METADATA ===
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
