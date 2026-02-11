<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RekomendasiAlgorithmTest extends TestCase
{
    /**
     * Test Minat Mapping Function
     */
    public function test_minat_mapping_logika_komputer()
    {
        // Simulate RekomendasiController logic
        $minatRaw = strtolower('ngoding dan web development');
        $minatMapped = $this->mapMinat($minatRaw);
        
        $this->assertEquals('Logika & Komputer', $minatMapped);
    }

    public function test_minat_mapping_alam_tanaman()
    {
        $minatRaw = strtolower('pertanian dan bercocok tanam');
        $minatMapped = $this->mapMinat($minatRaw);
        
        $this->assertEquals('Alam & Tanaman', $minatMapped);
    }

    public function test_minat_mapping_bisnis()
    {
        $minatRaw = strtolower('bisnis dan entrepreneur');
        $minatMapped = $this->mapMinat($minatRaw);
        
        $this->assertEquals('Manajemen & Bisnis', $minatMapped);
    }

    /**
     * Test Nilai Kategorisasi
     */
    public function test_nilai_kategori_tinggi()
    {
        $nilai = [85, 90, 88];
        $average = array_sum($nilai) / count($nilai);
        
        $katNilai = $average >= 85 ? 'Tinggi' : ($average >= 70 ? 'Sedang' : 'Rendah');
        $this->assertEquals('Tinggi', $katNilai);
    }

    public function test_nilai_kategori_sedang()
    {
        $nilai = [70, 75, 80];
        $average = array_sum($nilai) / count($nilai);
        
        $katNilai = $average >= 85 ? 'Tinggi' : ($average >= 70 ? 'Sedang' : 'Rendah');
        $this->assertEquals('Sedang', $katNilai);
    }

    public function test_nilai_kategori_rendah()
    {
        $nilai = [50, 55, 60];
        $average = array_sum($nilai) / count($nilai);
        
        $katNilai = $average >= 85 ? 'Tinggi' : ($average >= 70 ? 'Sedang' : 'Rendah');
        $this->assertEquals('Rendah', $katNilai);
    }

    /**
     * Test Prestasi Scoring
     */
    public function test_prestasi_scoring_tinggi()
    {
        $prestasiRaw = 'Juara Lomba Coding Nasional';
        $score = $this->scorePrestasiScore($prestasiRaw);
        
        $this->assertGreaterThanOrEqual(0.85, $score);
    }

    public function test_prestasi_scoring_sedang()
    {
        $prestasiRaw = 'Finalis Olimpiade Sains';
        $score = $this->scorePrestasiScore($prestasiRaw);
        
        $this->assertGreaterThanOrEqual(0.70, $score);
    }

    public function test_prestasi_scoring_minimal()
    {
        $prestasiRaw = 'Mengikuti workshop';
        $score = $this->scorePrestasiScore($prestasiRaw);
        
        $this->assertLessThanOrEqual(0.65, $score);
    }

    /**
     * Helper Functions
     */
    private function mapMinat(string $minatRaw): string
    {
        if (preg_match('/(coding|komputer|laptop|web|aplikasi|logika|programming|software|development)/', $minatRaw)) {
            return 'Logika & Komputer';
        } elseif (preg_match('/(tanam|kebun|sawah|hewan|ternak|alam|pertanian|agri)/', $minatRaw)) {
            return 'Alam & Tanaman';
        } elseif (preg_match('/(obat|sakit|rawat|medis|gizi|sehat|kesehatan|perawat|dokter)/', $minatRaw)) {
            return 'Pelayanan & Kesehatan';
        } elseif (preg_match('/(bisnis|uang|jual|kantor|hitung|ekonomi|dagang|usaha|entrepreneur)/', $minatRaw)) {
            return 'Manajemen & Bisnis';
        } elseif (preg_match('/(mesin|bengkel|listrik|las|robot|motor|teknik|otomasi|elektronik)/', $minatRaw)) {
            return 'Mesin & Listrik';
        }
        return 'Umum';
    }

    private function scorePrestasiScore(string $prestasiRaw): float
    {
        if (empty($prestasiRaw)) {
            return 0.0;
        }

        if (preg_match('/(juara|menang|champion|first|gold|emas|terbaik)/', $prestasiRaw)) {
            return 0.90;
        } elseif (preg_match('/(finalis|semifinal|peringkat|ranking|podium|medali|silver|perak)/', $prestasiRaw)) {
            return 0.75;
        } elseif (preg_match('/(sertifikat|training|kursus|workshop|peserta|mengikuti)/', $prestasiRaw)) {
            return 0.60;
        }

        return 0.30;
    }
}
