<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PolijeMajor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin dapat menambah data jurusan
     */
    public function test_admin_can_add_jurusan_data()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.jurusan.store'), [
            'nama_jurusan' => 'Informatika',
            'singkatan' => 'IF',
            'tujuan_kompetensi' => 'Profesional IT sejati',
            'prospek_kerja' => 'Software Engineer, System Analyst',
            'kelompok_asal' => 'IPA',
            'mtk' => 25,
            'fisika' => 20,
            'kimia' => 10,
            'biologi' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jurusan_polije', ['nama_jurusan' => 'Informatika']);
    }

    /**
     * Test BK dapat menambah data jurusan
     */
    public function test_bk_can_add_jurusan_data()
    {
        $bk = User::factory()->create(['role' => 'bk']);

        $response = $this->actingAs($bk)->post(route('bk.jurusan.store'), [
            'nama_jurusan' => 'Akuntansi',
            'singkatan' => 'AK',
            'tujuan_kompetensi' => 'Profesional akuntansi',
            'prospek_kerja' => 'Akuntan, Auditor',
            'kelompok_asal' => 'IPS',
            'ekonomi' => 25,
            'geografi' => 20,
            'sosiologi' => 10,
            'sejarah' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jurusan_polije', ['nama_jurusan' => 'Akuntansi']);
    }

    /**
     * Test guru BK store validates email and password
     */
    public function test_admin_guru_bk_store_validates_email_and_password()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Invalid email format
        $response = $this->actingAs($admin)->post(route('admin.guru-bk.store'), [
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');

        // Password too short
        $response = $this->actingAs($admin)->post(route('admin.guru-bk.store'), [
            'email' => 'valid@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test rekomendasi IPA requires all IPA scores
     */
    public function test_rekomendasi_ipa_requires_all_ipa_scores()
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'kelompok_asal' => 'IPA',
        ]);

        // Missing fisika, kimia, biologi
        $response = $this->actingAs($student)->post(route('rekomendasi.proses'), [
            'mtk' => 85,
            'minat' => 'Logika Komputer',
            'pref_studi' => 'Sains & Teknologi',
            'cita_cita' => 'Software Engineer',
        ]);

        // Should redirect with errors
        $response->assertSessionHasErrors(['fisika', 'kimia', 'biologi']);
    }

    /**
     * Test admin student detail only accepts siswa ID
     */
    public function test_admin_student_detail_only_accepts_siswa_id()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $bk = User::factory()->create(['role' => 'bk']);

        $response = $this->actingAs($admin)->get(route('admin.student.detail', $bk->id));
        $response->assertStatus(404);
    }
}
