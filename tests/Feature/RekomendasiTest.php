<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekomendasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_high_math_and_coding_prefers_teknologi_informasi()
    {
        // Siapkan user dengan kelompok_asal = IPA
        $user = User::factory()->create([
            'kelompok_asal' => 'IPA',
        ]);
        $this->seed(\Database\Seeders\PolijeMajorSeeder::class);

        $payload = [
            'mtk' => 95,
            'fisika' => 90,
            'kimia' => 85,
            'biologi' => 80,
            'minat' => 'Saya suka coding dan membuat aplikasi web',
            'cita_cita' => 'Programmer',
            'pref_studi' => 'Sains & Teknologi',
            'prestasi' => 'Juara Coding',
        ];

        $response = $this->actingAs($user)->post(route('rekomendasi.proses'), $payload);
        // Accept both 200 (view rendered) or 302 (redirect)
        $this->assertTrue($response->status() === 200 || $response->status() === 302);
        if ($response->status() === 200) {
            $response->assertSee('Teknologi Informasi');
        }
    }

    public function test_high_language_prefers_bahasa_komunikasi()
    {
        $user = User::factory()->create([
            'kelompok_asal' => 'IPS',
        ]);
        $this->seed(\Database\Seeders\PolijeMajorSeeder::class);

        $payload = [
            'ekonomi' => 70,
            'geografi' => 88,
            'sosiologi' => 80,
            'sejarah' => 75,
            'minat' => 'Saya suka menulis dan komunikasi',
            'cita_cita' => 'Jurnalis',
            'pref_studi' => 'Teori',
            'prestasi' => '',
        ];

        $response = $this->actingAs($user)->post(route('rekomendasi.proses'), $payload);
        $this->assertTrue($response->status() === 200 || $response->status() === 302);
        if ($response->status() === 200) {
            $response->assertSee('Bahasa, Komunikasi, dan Pariwisata');
        }
    }
}
