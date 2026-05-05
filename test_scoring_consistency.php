<?php
/**
 * Test script untuk verify scoring consistency
 * Menjalankan 2x dengan input yang sama untuk check apakah hasil identik
 */

require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\RekomendasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Create mock user
$mockUser = new stdClass();
$mockUser->id = 1;
$mockUser->kelompok_asal = 'IPA';

// Test input
$testInput = [
    'mtk' => 85,
    'fisika' => 80,
    'kimia' => 82,
    'biologi' => 78,
    'ekonomi' => null,
    'geografi' => null,
    'sosiologi' => null,
    'sejarah' => null,
    'minat' => 'coding dan programming web',
    'pref_studi' => 'Sains & Teknologi',
    'cita_cita' => 'menjadi web developer profesional',
    'prestasi' => 'juara 1 kompetisi coding tingkat kabupaten',
];

echo "=== TEST SCORING CONSISTENCY ===\n";
echo "Test Input:\n";
print_r($testInput);

// Simulate first request
echo "\n--- FIRST RUN ---\n";
Auth::shouldReceive('user')->andReturn($mockUser);

$controller = new RekomendasiController();
$request = Request::create('/rekomendasi/proses', 'POST', $testInput);
$request->setUserResolver(fn() => $mockUser);
Auth::shouldReceive('user')->andReturn($mockUser);

// Call proses method reflection
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('proses');
$method->setAccessible(true);

// Simulate first execution - capture output
ob_start();
try {
    $response1 = $method->invoke($controller, $request);
    if ($response1 instanceof \Illuminate\Http\JsonResponse) {
        $data1 = $response1->getData(true);
    } else {
        $data1 = json_decode($response1, true);
    }
} catch (Exception $e) {
    echo "Error on first run: " . $e->getMessage() . "\n";
    $data1 = null;
}
ob_end_clean();

if ($data1) {
    echo "First run - Top 3 Results:\n";
    for ($i = 0; $i < min(3, count($data1 ?? [])); $i++) {
        $result = $data1[$i] ?? null;
        if ($result) {
            echo sprintf("  #%d: %s (Score: %.4f)\n", $i+1, $result['jurusan'], $result['skor']);
            echo sprintf("      Details: Nilai=%.4f, Minat=%.4f, Pref=%.4f, Cita=%.4f\n", 
                $result['detail']['nilai'] ?? 0,
                $result['detail']['minat'] ?? 0,
                $result['detail']['pref'] ?? 0,
                $result['detail']['cita'] ?? 0
            );
        }
    }
}

// Simulate second request
echo "\n--- SECOND RUN ---\n";
$request2 = Request::create('/rekomendasi/proses', 'POST', $testInput);
$request2->setUserResolver(fn() => $mockUser);
Auth::shouldReceive('user')->andReturn($mockUser);

ob_start();
try {
    $response2 = $method->invoke($controller, $request2);
    if ($response2 instanceof \Illuminate\Http\JsonResponse) {
        $data2 = $response2->getData(true);
    } else {
        $data2 = json_decode($response2, true);
    }
} catch (Exception $e) {
    echo "Error on second run: " . $e->getMessage() . "\n";
    $data2 = null;
}
ob_end_clean();

if ($data2) {
    echo "Second run - Top 3 Results:\n";
    for ($i = 0; $i < min(3, count($data2 ?? [])); $i++) {
        $result = $data2[$i] ?? null;
        if ($result) {
            echo sprintf("  #%d: %s (Score: %.4f)\n", $i+1, $result['jurusan'], $result['skor']);
            echo sprintf("      Details: Nilai=%.4f, Minat=%.4f, Pref=%.4f, Cita=%.4f\n", 
                $result['detail']['nilai'] ?? 0,
                $result['detail']['minat'] ?? 0,
                $result['detail']['pref'] ?? 0,
                $result['detail']['cita'] ?? 0
            );
        }
    }
}

// Compare results
echo "\n--- COMPARISON ---\n";
if ($data1 && $data2) {
    $json1 = json_encode($data1, JSON_PRETTY_PRINT | JSON_SORT_KEYS);
    $json2 = json_encode($data2, JSON_PRETTY_PRINT | JSON_SORT_KEYS);
    
    if ($json1 === $json2) {
        echo "✅ CONSISTENT: Results are identical\n";
    } else {
        echo "❌ INCONSISTENT: Results differ\n";
        
        // Find differences
        echo "\nDifferences:\n";
        for ($i = 0; $i < min(count($data1), count($data2)); $i++) {
            $r1 = $data1[$i] ?? null;
            $r2 = $data2[$i] ?? null;
            if ($r1 && $r2) {
                if ($r1['jurusan'] !== $r2['jurusan'] || abs($r1['skor'] - $r2['skor']) > 0.0001) {
                    echo sprintf("  Position %d: %s (%.4f) vs %s (%.4f)\n", 
                        $i, $r1['jurusan'], $r1['skor'], $r2['jurusan'], $r2['skor']);
                }
            }
        }
    }
} else {
    echo "⚠️ Cannot compare - one or both runs failed\n";
}

echo "\n=== END OF TEST ===\n";
