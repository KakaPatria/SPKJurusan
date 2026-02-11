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
    Schema::create('recommendations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Atribut Nilai
        $table->float('mtk')->nullable();
        $table->float('fisika')->nullable();
        $table->float('kimia')->nullable();
        $table->float('biologi')->nullable();
        $table->float('ekonomi')->nullable();
        $table->float('geografi')->nullable();
        $table->float('sosiologi')->nullable();
        $table->float('sejarah')->nullable();

        // Atribut Pendukung
        $table->string('minat');
        $table->string('preferensi_studi');
        $table->string('cita_cita');
        $table->string('prestasi');

        $table->string('hasil_rekomendasi')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
