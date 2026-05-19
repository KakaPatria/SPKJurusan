<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumni;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportAlumni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alumni:import {file : Path to Excel file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data alumni dari file Excel ke database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        // Check if file exists
        if (!file_exists($filePath)) {
            $this->error("❌ File tidak ditemukan: {$filePath}");
            return 1;
        }

        // Check if file is Excel
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['xlsx', 'xls', 'csv'])) {
            $this->error("❌ Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv");
            return 1;
        }

        try {
            $this->info("📂 Membaca file: {$filePath}");
            
            // Read Excel file
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            if (count($data) < 2) {
                $this->error("❌ File Excel kosong atau hanya memiliki header");
                return 1;
            }

            // Get headers
            $headers = array_map('strtolower', $data[0]);
            $headers = array_map(fn($h) => trim(str_replace([' ', '-', '_'], '_', $h)), $headers);
            
            $this->line("✓ Header ditemukan: " . implode(', ', $headers));

            // Normalize column mapping
            $columnMap = $this->normalizeColumns($headers);
            $this->line("✓ Kolom di-mapping");

            // Process rows
            $rows = array_slice($data, 1);
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            $this->info("\n📊 Memproses " . count($rows) . " baris data...");
            
            // Progress bar
            $bar = $this->output->createProgressBar(count($rows));
            $bar->start();

            foreach ($rows as $idx => $row) {
                $record = $this->mapRow($row, $headers, $columnMap);

                if ($record === null || empty($record['nama_alumni'])) {
                    $bar->advance();
                    continue;
                }

                // Validate required fields
                $validator = Validator::make($record, [
                    'nama_alumni' => 'required|string',
                    'nis' => 'nullable|string',
                    'kelompok_asal' => 'nullable|string',
                    'nilai_rata_rata' => 'nullable|numeric',
                    'minat' => 'nullable|string',
                    'cita_cita' => 'nullable|string',
                    'preferensi_studi' => 'nullable|string',
                    'prestasi' => 'nullable|string',
                    'major_masuk' => 'nullable|string',
                    'tahun_lulus_polije' => 'nullable|numeric',
                ]);

                if ($validator->fails()) {
                    $errorCount++;
                    $errors[] = "Baris " . ($idx + 2) . ": " . implode(', ', $validator->errors()->all());
                    $bar->advance();
                    continue;
                }

                try {
                    // Check if already exists
                    $existing = Alumni::where('nis', $record['nis'] ?? null)
                        ->where('nama_alumni', $record['nama_alumni'] ?? null)
                        ->first();

                    if (!$existing) {
                        Alumni::create($record);
                        $successCount++;
                    } else {
                        // Update jika sudah ada
                        $existing->update($record);
                        $successCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Baris " . ($idx + 2) . ": " . $e->getMessage();
                }

                $bar->advance();
            }

            $bar->finish();

            // Summary
            $this->newLine(2);
            $this->info("=" . str_repeat("=", 58) . "=");
            $this->info("✓ IMPORT SELESAI");
            $this->info("=" . str_repeat("=", 58) . "=");
            $this->line("✓ Data berhasil di-import: {$successCount}");
            if ($errorCount > 0) {
                $this->line("⚠ Baris error/skip: {$errorCount}");
                
                if (count($errors) > 0 && count($errors) <= 20) {
                    $this->newLine();
                    $this->warn("Errors:");
                    foreach ($errors as $error) {
                        $this->line("  - {$error}");
                    }
                }
            }
            $this->newLine();

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Normalize column headers to database column names
     */
    private function normalizeColumns($headers)
    {
        $mapping = [
            'nama' => 'nama_alumni',
            'nama_alumni' => 'nama_alumni',
            'nis' => 'nis',
            'no_induk_siswa' => 'nis',
            'kelompok_asal' => 'kelompok_asal',
            'kelompok' => 'kelompok_asal',
            'nilai_(rata_rata)' => 'nilai_rata_rata',
            'nilai_rata_rata' => 'nilai_rata_rata',
            'rata_rata' => 'nilai_rata_rata',
            'average' => 'nilai_rata_rata',
            'minat' => 'minat',
            'interest' => 'minat',
            'cita_cita' => 'cita_cita',
            'cita' => 'cita_cita',
            'dream_job' => 'cita_cita',
            'preferensi_studi' => 'preferensi_studi',
            'preferensi' => 'preferensi_studi',
            'preference' => 'preferensi_studi',
            'prestasi' => 'prestasi',
            'achievement' => 'prestasi',
            'major_masuk' => 'major_masuk',
            'jurusan_masuk' => 'major_masuk',
            'jurusan_keterima_di_polije' => 'major_masuk',
            'jurusan' => 'major_masuk',
            'major' => 'major_masuk',
            'tahun_lulus_polije' => 'tahun_lulus_polije',
            'tahun_lulus' => 'tahun_lulus_polije',
            'graduation_year' => 'tahun_lulus_polije',
            'tahun' => 'tahun_lulus_polije',
            'catatan' => 'catatan',
            'keterangan' => 'catatan',
            'notes' => 'catatan',
        ];

        return $mapping;
    }

    /**
     * Map row data to Alumni model
     */
    private function mapRow($row, $headers, $columnMap)
    {
        $record = [];

        foreach ($headers as $idx => $header) {
            $value = $row[$idx] ?? null;

            // Map column name
            $dbColumn = $columnMap[strtolower($header)] ?? null;
            
            if (!$dbColumn) {
                continue;
            }

            // Type conversion
            if (in_array($dbColumn, ['nilai_rata_rata', 'tahun_lulus_polije'])) {
                $record[$dbColumn] = $value ? (float) $value : null;
            } else {
                $cleanValue = $value ? trim((string) $value) : null;
                
                // Special handling for preferensi_studi - truncate to enum value
                if ($dbColumn === 'preferensi_studi' && $cleanValue) {
                    // Extract only the category part (before the parenthesis)
                    $parts = explode('(', $cleanValue);
                    $cleanValue = trim($parts[0]);
                }
                
                $record[$dbColumn] = $cleanValue;
            }
        }

        return empty($record['nama_alumni'] ?? null) ? null : $record;
    }
}
