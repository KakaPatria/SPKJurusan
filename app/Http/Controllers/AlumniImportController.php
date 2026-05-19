<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class AlumniImportController extends Controller
{
    /**
     * Show upload form
     */
    public function showForm()
    {
        return view('alumni.import-form');
    }

    /**
     * Handle file upload & import
     */
    public function import(Request $request)
    {
        // Validate file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // max 10MB
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus format .xlsx, .xls, atau .csv',
            'file.max' => 'File tidak boleh lebih dari 10MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('file');
            $filePath = $file->store('temp', 'local');
            $fullPath = storage_path('app/' . $filePath);

            // Read Excel
            $spreadsheet = IOFactory::load($fullPath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            if (count($data) < 2) {
                return back()->with('error', 'File Excel kosong atau hanya memiliki header');
            }

            // Get headers
            $headers = array_map('strtolower', $data[0]);
            $headers = array_map(fn($h) => trim(str_replace([' ', '-', '_'], '_', $h)), $headers);

            // Normalize column mapping
            $columnMap = $this->normalizeColumns();

            // Process rows
            $rows = array_slice($data, 1);
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($rows as $idx => $row) {
                $record = $this->mapRow($row, $headers, $columnMap);

                if ($record === null || empty($record['nama_alumni'])) {
                    continue;
                }

                // Validate required fields
                $validator = Validator::make($record, [
                    'nama_alumni' => 'required|string',
                    'kelompok_asal' => 'nullable|string',
                    'nilai_rata_rata' => 'nullable|numeric',
                ]);

                if ($validator->fails()) {
                    $errorCount++;
                    $errors[] = "Baris " . ($idx + 2) . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }

                try {
                    // Check if already exists
                    $existing = Alumni::where('nis', $record['nis'] ?? '')
                        ->where('nama_alumni', $record['nama_alumni'])
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
            }

            // Clean up temp file
            @unlink($fullPath);

            // Prepare response
            $message = "✓ Import Selesai! {$successCount} data berhasil diimport";
            if ($errorCount > 0) {
                $message .= " ({$errorCount} error/skip)";
            }

            return back()
                ->with('success', $message)
                ->with('successCount', $successCount)
                ->with('errorCount', $errorCount)
                ->with('errors', count($errors) <= 10 ? $errors : array_slice($errors, 0, 10));

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Normalize column headers to database column names
     */
    private function normalizeColumns()
    {
        return [
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
    }

    /**
     * Map row data to Alumni model
     */
    private function mapRow($row, $headers, $columnMap)
    {
        $record = [];

        foreach ($headers as $idx => $header) {
            $value = $row[$idx] ?? null;

            if (!$value) {
                continue;
            }

            // Map column name
            $dbColumn = $columnMap[strtolower($header)] ?? null;

            if (!$dbColumn) {
                continue;
            }

            // Type conversion
            if (in_array($dbColumn, ['nilai_rata_rata', 'tahun_lulus_polije'])) {
                $record[$dbColumn] = (float) $value;
            } else {
                $record[$dbColumn] = trim((string) $value);
            }
        }

        return empty($record) ? null : $record;
    }
}
