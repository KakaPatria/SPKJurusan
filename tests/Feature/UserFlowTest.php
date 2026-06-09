<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PolijeMajor;
use App\Models\Recommendation;
use App\Models\ChatHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PolijeMajorSeeder::class);
    }

    public function test_siswa_complete_flow()
    {
        $this->withoutExceptionHandling();
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
            'email' => 'siswa@test.com',
        ]);

        $response = $this->actingAs($siswa)->get(route('rekomendasi.index'));
        $response->assertStatus(200);

        $payload = [
            'mtk' => 85,
            'fisika' => 82,
            'kimia' => 88,
            'biologi' => 90,
            'minat' => 'Saya suka coding dan teknologi',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'Menjadi Software Engineer',
            'prestasi' => 'Juara Lomba Coding Nasional',
        ];

        $response = $this->actingAs($siswa)->post(route('rekomendasi.proses'), $payload);
        $response->assertStatus(200);

        $rec = Recommendation::where('user_id', $siswa->id)->first();
        $this->assertNotNull($rec);

        $hasil = $rec->hasil_rekomendasi;
        $this->assertIsArray($hasil);
        $this->assertGreaterThan(0, count($hasil));

        $response = $this->actingAs($siswa)->get(route('history.rekomendasi'));
        $response->assertStatus(200);

        $response = $this->actingAs($siswa)->get(route('chatbot.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($siswa)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_bk_complete_flow()
    {
        $this->withoutExceptionHandling();
        $bk = User::factory()->create([
            'role' => 'bk',
            'email' => 'bk@test.com',
        ]);

        $students = User::factory(3)->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
        ]);

        foreach ($students as $student) {
            Recommendation::create([
                'user_id' => $student->id,
                'mtk' => 85,
                'fisika' => 82,
                'kimia' => 88,
                'biologi' => 90,
                'minat' => 'Teknologi dan Inovasi',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Menjadi Engineer',
                'prestasi' => 'Medali Kompetisi',
                'hasil_rekomendasi' => json_encode([
                    [
                        'jurusan' => 'Teknologi Informasi',
                        'score' => 0.85,
                        'ranking' => 1
                    ]
                ]),
            ]);
        }

        $response = $this->actingAs($bk)->get(route('bk.dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($bk)->get(route('bk.students'));
        $response->assertStatus(200);

        $response = $this->actingAs($bk)->get(route('bk.student.detail', $students[0]->id));
        $response->assertStatus(200);

        $response = $this->actingAs($bk)->get(route('bk.riwayat-rekomendasi'));
        $response->assertStatus(200);

        $response = $this->actingAs($bk)->get(route('bk.riwayat-chatbot'));
        $response->assertStatus(200);

        $response = $this->actingAs($bk)->get(route('admin.dashboard'));
        $response->assertStatus(302);
    }

    public function test_admin_complete_flow()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.jurusan'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.jurusan.create'));
        $response->assertStatus(200);

        $jurusanData = [
            'nama_jurusan' => 'Teknik Mesin Test',
            'singkatan' => 'TM',
            'tujuan_kompetensi' => 'Engineer profesional',
            'prospek_kerja' => 'Mechanical Engineer',
            'kelompok_asal' => 'IPA',
            'mtk' => 30,
            'fisika' => 35,
            'kimia' => 15,
            'biologi' => 5,
        ];

        $response = $this->actingAs($admin)->post(route('admin.jurusan.store'), $jurusanData);
        $response->assertRedirect();

        $this->assertDatabaseHas('jurusan_polije', ['nama_jurusan' => 'Teknik Mesin Test']);

        $response = $this->actingAs($admin)->get(route('admin.guru-bk'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.guru-bk.create'));
        $response->assertStatus(200);

        $guruData = [
            'name' => 'Guru BK Test',
            'email' => 'guru_bk_new@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($admin)->post(route('admin.guru-bk.store'), $guruData);
        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'guru_bk_new@test.com',
            'role' => 'bk'
        ]);

        $response = $this->actingAs($admin)->get(route('admin.students'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.riwayat-rekomendasi'));
        $response->assertStatus(200);
    }

    public function test_access_control()
    {
        $siswa = User::factory()->create(['role' => 'siswa']);
        $bk = User::factory()->create(['role' => 'bk']);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($siswa)->get(route('admin.dashboard'));
        $response->assertStatus(302);

        $response = $this->actingAs($siswa)->get(route('bk.dashboard'));
        $response->assertStatus(302);

        $response = $this->actingAs($bk)->get(route('admin.dashboard'));
        $response->assertStatus(302);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}
