<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Alumni;

$alumni = Alumni::select('nama_alumni', 'kelompok_asal', 'nilai_rata_rata', 'major_masuk')->limit(5)->get();

echo "=== ALUMNI DATA ===\n\n";
foreach($alumni as $a) {
    echo $a->nama_alumni . " | " . $a->kelompok_asal . " | " . $a->nilai_rata_rata . " | " . $a->major_masuk . "\n";
}

echo "\nTotal Alumni: " . Alumni::count() . "\n";
echo "With nilai_rata_rata: " . Alumni::whereNotNull('nilai_rata_rata')->count() . "\n";
