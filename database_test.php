<?php
/**
 * DATABASE INTEGRITY TEST SCRIPT
 * Untuk memverifikasi data sebelum sidang
 * 
 * Jalankan dari terminal:
 * php artisan tinker < database_test.php
 * atau copy-paste di tinker session
 */

// ============================================
// TEST 1: CHECK NULL VALUES IN RECOMMENDATIONS
// ============================================
echo "\n============================================\n";
echo "✅ TEST 1: CHECK NULL VALUES IN RECOMMENDATIONS\n";
echo "============================================\n";

$nullCount = \App\Models\Recommendation::whereRaw("JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan') IS NULL")
    ->orWhereRaw("JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan') = 'null'")
    ->count();

if ($nullCount === 0) {
    echo "✅ PASS: Tidak ada NULL values\n";
    echo "   Total recommendations: " . \App\Models\Recommendation::count() . "\n";
} else {
    echo "❌ FAIL: Ditemukan $nullCount NULL values!\n";
}

// ============================================
// TEST 2: CHECK MAJOR NAMES CONSISTENCY
// ============================================
echo "\n============================================\n";
echo "✅ TEST 2: CHECK MAJOR NAMES CONSISTENCY\n";
echo "============================================\n";

$majors = \App\Models\PolijeMajor::pluck('nama_jurusan')->unique();
echo "Jurusan yang ada di database:\n";
foreach ($majors as $major) {
    echo "  - $major\n";
}

// Check for wrong naming
$wrongNaming = \App\Models\Recommendation::selectRaw("JSON_EXTRACT(hasil_rekomendasi, '$[0].jurusan') as major_name")
    ->distinct()
    ->get();

echo "\nMajors dalam rekomendasi:\n";
$hasWrongName = false;
foreach ($wrongNaming as $item) {
    $name = trim($item->major_name, '\" ');
    if (strpos($name, 'Teknik Informatika') !== false) {
        echo "  ⚠️ $name (SHOULD BE: Teknologi Informasi)\n";
        $hasWrongName = true;
    } elseif (!empty($name) && $name !== 'null') {
        echo "  ✅ $name\n";
    }
}

if ($hasWrongName) {
    echo "\n❌ FAIL: Ada major name yang salah! (Teknik Informatika should be Teknologi Informasi)\n";
} else {
    echo "\n✅ PASS: Semua major names konsisten\n";
}

// ============================================
// TEST 3: CHECK WEIGHTS STANDARDIZATION
// ============================================
echo "\n============================================\n";
echo "✅ TEST 3: CHECK WEIGHTS STANDARDIZATION\n";
echo "============================================\n";

$config = config('polije.jurusan');
$expectedWeights = [
    'nilai' => 0.156,
    'minat' => 0.456,
    'pref' => 0.256,
    'cita_cita' => 0.090,
    'prestasi' => 0.040
];

$weightsValid = true;
echo "Expected weights:\n";
foreach ($expectedWeights as $key => $weight) {
    echo "  $key: $weight\n";
}

echo "\nActual weights in config:\n";
foreach ($config as $jurusan => $data) {
    $weights = $data['weights'];
    $total = array_sum($weights);
    
    $isValid = true;
    foreach ($expectedWeights as $key => $expectedWeight) {
        if ($weights[$key] != $expectedWeight) {
            $isValid = false;
            break;
        }
    }
    
    if ($isValid && abs($total - 1.0) < 0.001) {
        echo "  ✅ $jurusan (total: $total)\n";
    } else {
        echo "  ❌ $jurusan - MISMATCH!\n";
        echo "     Weights: " . json_encode($weights) . "\n";
        $weightsValid = false;
    }
}

if ($weightsValid) {
    echo "\n✅ PASS: Semua jurusan memiliki weights yang sama dan valid\n";
} else {
    echo "\n❌ FAIL: Ada weights yang tidak sesuai!\n";
}

// ============================================
// TEST 4: CHECK KEYWORDS BALANCE
// ============================================
echo "\n============================================\n";
echo "✅ TEST 4: CHECK KEYWORDS BALANCE\n";
echo "============================================\n";

$keywords = config('polije.keywords');

echo "Minat Keywords Balance:\n";
$minatBalance = true;
foreach ($keywords['minat'] as $category => $kwords) {
    $count = count($kwords);
    if ($count >= 24 && $count <= 28) {
        echo "  ✅ $category: $count keywords\n";
    } else {
        echo "  ⚠️ $category: $count keywords (ideal: 26)\n";
        $minatBalance = false;
    }
}

echo "\nCita-Cita Keywords Balance:\n";
$citaBalance = true;
foreach ($keywords['cita_cita'] as $category => $kwords) {
    $count = count($kwords);
    if ($count >= 24 && $count <= 30) {
        echo "  ✅ $category: $count keywords\n";
    } else {
        echo "  ⚠️ $category: $count keywords (ideal: 24-30)\n";
        $citaBalance = false;
    }
}

if ($minatBalance && $citaBalance) {
    echo "\n✅ PASS: Keywords balance OK\n";
} else {
    echo "\n⚠️ WARNING: Ada keywords yang tidak balanced\n";
}

// ============================================
// TEST 5: CHECK TOTAL RECORDS
// ============================================
echo "\n============================================\n";
echo "✅ TEST 5: TOTAL RECORDS CHECK\n";
echo "============================================\n";

$stats = [
    'Total Users' => \App\Models\User::count(),
    'Total Siswa' => \App\Models\User::where('role', 'siswa')->count(),
    'Total BK' => \App\Models\User::where('role', 'bk')->count(),
    'Total Admin' => \App\Models\User::where('role', 'admin')->count(),
    'Total Recommendations' => \App\Models\Recommendation::count(),
    'Total Chat History' => \App\Models\ChatHistory::count(),
    'Total Jurusan' => \App\Models\PolijeMajor::count(),
];

foreach ($stats as $label => $count) {
    echo "  $label: $count\n";
}

// ============================================
// TEST 6: SAMPLE RECOMMENDATION CHECK
// ============================================
echo "\n============================================\n";
echo "✅ TEST 6: SAMPLE RECOMMENDATION CHECK\n";
echo "============================================\n";

$sample = \App\Models\Recommendation::with('user')->first();
if ($sample) {
    echo "Sample Recommendation:\n";
    echo "  User: " . $sample->user->name . "\n";
    echo "  Input Data:\n";
    echo "    - Nilai Rata-rata: " . ($sample->input_data['nilai_rata_rata'] ?? 'N/A') . "\n";
    echo "    - Minat (Raw): " . ($sample->input_data['minat_raw'] ?? 'N/A') . "\n";
    echo "    - Minat (Mapped): " . ($sample->input_data['minat_mapped'] ?? 'N/A') . "\n";
    echo "    - Cita-cita (Raw): " . ($sample->input_data['cita_cita_raw'] ?? 'N/A') . "\n";
    echo "    - Cita-cita (Mapped): " . ($sample->input_data['cita_cita_mapped'] ?? 'N/A') . "\n";
    echo "  Top Recommendation:\n";
    $topRec = $sample->hasil_rekomendasi[0];
    echo "    - Jurusan: " . $topRec['jurusan'] . "\n";
    echo "    - Percentage: " . $topRec['persentase'] . "%\n";
} else {
    echo "❌ Tidak ada rekomendasi ditemukan\n";
}

// ============================================
// SUMMARY
// ============================================
echo "\n============================================\n";
echo "✅ TEST COMPLETE\n";
echo "============================================\n";
echo "\nJika semua test PASS ✅, sistem siap untuk sidang!\n";
echo "Jika ada yang FAIL ❌, silahkan perbaiki terlebih dahulu.\n\n";
