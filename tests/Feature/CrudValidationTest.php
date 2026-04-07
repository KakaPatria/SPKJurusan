<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_jurusan_data(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $payload = [
            'nama_jurusan' => 'Jurusan Uji Admin',
            'deskripsi' => 'Deskripsi jurusan uji dari admin',
            'keywords' => 'uji,admin',
            'preferensi_studi' => 'Sains & Teknologi',
            'prospek_kerja' => 'Tester aplikasi',
            'bobot_mtk' => 0.8,
            'bobot_fisika' => 0.7,
        ];

        $response = $this->actingAs($admin)->post(route('admin.jurusan.store'), $payload);

        $response->assertRedirect(route('admin.jurusan'));
        $this->assertDatabaseHas('polije_majors', [
            'nama_jurusan' => 'Jurusan Uji Admin',
        ]);
    }

    public function test_bk_can_add_jurusan_data(): void
    {
        $bk = User::factory()->create([
            'role' => 'bk',
            'email_verified_at' => now(),
        ]);

        $payload = [
            'nama_jurusan' => 'Jurusan Uji BK',
            'deskripsi' => 'Deskripsi jurusan uji dari BK',
            'keywords' => 'uji,bk',
            'preferensi_studi' => 'Bisnis & Manajemen',
            'prospek_kerja' => 'Konsultan BK',
            'bobot_ekonomi' => 0.9,
            'bobot_sosiologi' => 0.6,
        ];

        $response = $this->actingAs($bk)->post(route('bk.jurusan.store'), $payload);

        $response->assertRedirect(route('bk.jurusan'));
        $this->assertDatabaseHas('polije_majors', [
            'nama_jurusan' => 'Jurusan Uji BK',
        ]);
    }

    public function test_admin_guru_bk_store_validates_email_and_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->from(route('admin.guru-bk.create'))->post(route('admin.guru-bk.store'), [
            'name' => 'Guru BK Uji',
            'email' => 'email-tidak-valid',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertRedirect(route('admin.guru-bk.create'));
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_rekomendasi_ipa_requires_all_ipa_scores(): void
    {
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($siswa)->from(route('rekomendasi.index'))->post(route('rekomendasi.proses'), [
            'mtk' => 90,
            'minat' => 'coding',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'programmer',
        ]);

        $response->assertRedirect(route('rekomendasi.index'));
        $response->assertSessionHasErrors(['fisika', 'kimia', 'biologi']);
    }

    public function test_admin_student_detail_only_accepts_siswa_id(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $bk = User::factory()->create([
            'role' => 'bk',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.student.detail', $bk->id))
            ->assertNotFound();
    }
}
