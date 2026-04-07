<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PolijeMajor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExplainableRecommendationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create some jurusan data
        PolijeMajor::create([
            'nama_jurusan' => 'Teknologi Informasi',
            'singkatan' => 'TI',
            'tujuan_kompetensi' => 'Menghasilkan profesional IT',
            'prospek_kerja' => 'Software Developer, Software Engineer, IT Consultant',
            'kelompok_asal' => 'IPA',
            'mtk' => 30, 'fisika' => 20, 'kimia' => 10, 'biologi' => 5,
            'minat1' => 'Logika Komputer', 'minat2' => '', 'minat3' => '',
            'pref_sains' => 70, 'pref_pertanian' => 20, 'pref_kesehatan' => 10,
            'pref_bisnis' => 15, 'pref_sosial' => 10, 'pref_praktik' => 50, 'pref_teori' => 50,
        ]);

        PolijeMajor::create([
            'nama_jurusan' => 'Akuntansi',
            'singkatan' => 'AK',
            'tujuan_kompetensi' => 'Menghasilkan profesional akuntansi',
            'prospek_kerja' => 'Accountant, Financial Analyst, Tax Consultant',
            'kelompok_asal' => 'IPS',
            'ekonomi' => 30, 'geografi' => 15, 'sosiologi' => 10, 'sejarah' => 5,
            'minat1' => 'Bisnis', 'minat2' => '', 'minat3' => '',
            'pref_bisnis' => 80, 'pref_sosial' => 15, 'pref_pertanian' => 5,
            'pref_sains' => 10, 'pref_kesehatan' => 5, 'pref_praktik' => 60, 'pref_teori' => 40,
        ]);
    }

    /**
     * Test bahwa response recommendation mencakup explanation
     */
    public function test_recommendation_includes_explanation()
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
        ]);

        $this->actingAs($user)->post(route('rekomendasi.proses'), [
            'mtk' => 90,
            'fisika' => 85,
            'kimia' => 80,
            'biologi' => 75,
            'minat' => 'Logika Komputer',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'Software Developer',
            'prestasi' => 'Juara Kompetisi Coding Nasional',
        ]);

        // Verify recommendation is stored
        $lastRecommendation = \App\Models\Recommendation::latest()->first();
        $this->assertNotNull($lastRecommendation);

        // Verify hasil_rekomendasi contains explanation
        $hasil = $lastRecommendation->hasil_rekomendasi;
        $this->assertIsArray($hasil);
        $this->assertNotEmpty($hasil);

        // Check top recommendation has explanation field
        $topRec = $hasil[0];
        $this->assertArrayHasKey('explanation', $topRec);
        $this->assertIsArray($topRec['explanation']);

        // Verify all 5 explanation keys exist
        $expectedKeys = ['nilai', 'minat', 'pref', 'cita', 'prestasi'];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $topRec['explanation']);
            $this->assertIsString($topRec['explanation'][$key]);
            $this->assertNotEmpty($topRec['explanation'][$key]);
        }
    }

    /**
     * Test bahwa explanation berisi teks yang meaningful
     */
    public function test_explanation_contains_meaningful_text()
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
        ]);

        $this->actingAs($user)->post(route('rekomendasi.proses'), [
            'mtk' => 95,
            'fisika' => 90,
            'kimia' => 88,
            'biologi' => 92,
            'minat' => 'Logika Komputer',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'Software Engineer',
            'prestasi' => 'Juara Olimpiade Komputer',
        ]);

        $lastRecommendation = \App\Models\Recommendation::latest()->first();
        $hasil = $lastRecommendation->hasil_rekomendasi;
        $topRec = $hasil[0];
        $explanation = $topRec['explanation'];

        // Verify explanation contains meaningful indicators and text
        $this->assertStringContainsString('Nilai akademik', $explanation['nilai']);
        $this->assertStringContainsString('Minat', $explanation['minat']);
        $this->assertStringContainsString('pembelajaran', $explanation['pref']);
        $this->assertStringContainsString('cita', $explanation['cita']);
        $this->assertStringContainsString('prestasi', strtolower($explanation['prestasi']));

        // Verify checkmarks or indicators are present
        $combinedExplanation = implode(' ', $explanation);
        $this->assertTrue(
            str_contains($combinedExplanation, '✅') || 
            str_contains($combinedExplanation, '✓') ||
            str_contains($combinedExplanation, 'sesuai') ||
            str_contains($combinedExplanation, 'cocok')
        );
    }

    /**
     * Test scoring detail (nilai, minat, pref, cita, prestasi) tersimpan dengan tepat
     */
    public function test_scoring_detail_stored_correctly()
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
        ]);

        $this->actingAs($user)->post(route('rekomendasi.proses'), [
            'mtk' => 88,
            'fisika' => 82,
            'kimia' => 85,
            'biologi' => 80,
            'minat' => 'Logika Komputer dan Pemrograman',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'Software Engineer',
            'prestasi' => 'Sertifikat Oracle Java',
        ]);

        $lastRecommendation = \App\Models\Recommendation::latest()->first();
        $hasil = $lastRecommendation->hasil_rekomendasi;

        // Verify detail breakdown exists for top recommendation
        $topRec = $hasil[0];
        $this->assertArrayHasKey('detail', $topRec);
        $detail = $topRec['detail'];

        // Verify all 5 scoring components exist
        $scores = ['nilai', 'minat', 'pref', 'cita', 'prestasi'];
        foreach ($scores as $score) {
            $this->assertArrayHasKey($score, $detail);
            $this->assertIsNumeric($detail[$score]);
            $this->assertGreaterThanOrEqual(0, $detail[$score]);
            $this->assertLessThanOrEqual(1, $detail[$score]);
        }
    }

    /**
     * Test bahwa setiap jurusan dalam hasil punya explanation
     */
    public function test_all_recommendations_have_explanations()
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
        ]);

        $this->actingAs($user)->post(route('rekomendasi.proses'), [
            'mtk' => 80,
            'fisika' => 75,
            'kimia' => 78,
            'biologi' => 76,
            'minat' => 'Sains dan Teknologi',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'Profesional Teknologi',
            'prestasi' => 'Aktif dalam kegiatan STEM',
        ]);

        $lastRecommendation = \App\Models\Recommendation::latest()->first();
        $hasil = $lastRecommendation->hasil_rekomendasi;

        // Verify each recommendation has explanation and detail
        foreach ($hasil as $rec) {
            $this->assertArrayHasKey('explanation', $rec);
            $this->assertArrayHasKey('detail', $rec);
            $this->assertIsArray($rec['explanation']);
            $this->assertIsArray($rec['detail']);

            // Count should match (5 criteria)
            $this->assertCount(5, $rec['explanation']);
            $this->assertCount(5, $rec['detail']);
        }
    }

    /**
     * Test explanation rendered in view
     */
    public function test_explanation_displayed_in_view()
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
        ]);

        $response = $this->actingAs($user)->post(route('rekomendasi.proses'), [
            'mtk' => 85,
            'fisika' => 80,
            'kimia' => 78,
            'biologi' => 82,
            'minat' => 'Logika Komputer',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'Software Developer',
            'prestasi' => 'Juara Informatika',
        ]);

        $response->assertStatus(200);

        // View should contain explanation indicators
        $response->assertViewHas('hasilAkhir');
        $hasil = $response->viewData('hasilAkhir');

        // Check explanation is in view data
        $this->assertNotEmpty($hasil);
        $topRec = $hasil[0];
        $this->assertArrayHasKey('explanation', $topRec);
        $explanation = $topRec['explanation'];

        // Verify explanation content in response
        foreach ($explanation as $key => $text) {
            $this->assertNotEmpty($text);
            // Check that text contains expected keywords
            $hasContent = str_contains($text, '✅') || 
                         str_contains($text, '✓') || 
                         str_contains($text, 'sesuai') ||
                         str_contains($text, 'cocok') ||
                         str_contains($text, 'relevan');
            $this->assertTrue($hasContent, "Explanation for $key should have meaningful content");
        }
    }
}
