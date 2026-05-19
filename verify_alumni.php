<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Alumni;

$count = Alumni::count();
$alumni = Alumni::latest()->take(5)->get(['nama_alumni', 'minat', 'cita_cita', 'preferensi_studi', 'major_masuk']);

echo "Total Alumni in Database: $count\n\n";
echo "=== Last 5 Imported Alumni ===\n";
foreach ($alumni as $a) {
    echo "- {$a->nama_alumni}\n  Minat: {$a->minat}\n  Cita-cita: {$a->cita_cita}\n  Preferensi: {$a->preferensi_studi}\n  Major Masuk: {$a->major_masuk}\n\n";
}
?>
