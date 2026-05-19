<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop kolom nilai mapel individual, keep nilai_rata_rata
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn([
                'mtk',
                'fisika',
                'kimia',
                'biologi',
                'ekonomi',
                'geografi',
                'sosiologi',
                'sejarah'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->float('mtk')->nullable();
            $table->float('fisika')->nullable();
            $table->float('kimia')->nullable();
            $table->float('biologi')->nullable();
            $table->float('ekonomi')->nullable();
            $table->float('geografi')->nullable();
            $table->float('sosiologi')->nullable();
            $table->float('sejarah')->nullable();
        });
    }
};
