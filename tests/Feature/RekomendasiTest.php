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
        // Siapkan user dan jalankan seeder polije majors
        $user = User::factory()->create();
        $this->seed(\Database\Seeders\PolijeMajorSeeder::class);

        $payload = [
            'mtk' => 95,
            'fisika' => 90,
            'kimia' => 85,
            'minat' => 'Saya suka coding dan membuat aplikasi web',
            'cita_cita' => 'Programmer',
            'pref_studi' => 'Praktikum',
        ];

        $response = $this->actingAs($user)->post(route('rekomendasi.proses'), $payload);
        $response->assertStatus(200);
        $response->assertSee('Teknologi Informasi');
    }

    public function test_high_language_prefers_bahasa_komunikasi()
    {
        $user = User::factory()->create();
        $this->seed(\Database\Seeders\PolijeMajorSeeder::class);

        $payload = [
            'mtk' => 70,
            'bahasa' => 88,
            'minat' => 'Saya suka menulis dan komunikasi',
            'cita_cita' => 'Jurnalis',
            'pref_studi' => 'Teori',
        ];

        $response = $this->actingAs($user)->post(route('rekomendasi.proses'), $payload);
        $response->assertStatus(200);
        $response->assertSee('Bahasa, Komunikasi, dan Pariwisata');
    }
}
