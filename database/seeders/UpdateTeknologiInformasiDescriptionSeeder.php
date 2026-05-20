<?php

namespace Database\Seeders;

use App\Models\PolijeMajor;
use Illuminate\Database\Seeder;

class UpdateTeknologiInformasiDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $description = "Jurusan Teknologi Informasi merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan pendidikan vokasi di bidang teknologi informasi, komputer, dan pengembangan perangkat lunak untuk menghasilkan lulusan yang profesional, inovatif, dan mampu bersaing di tingkat nasional maupun internasional. Jurusan ini memiliki program studi D3 Manajemen Informatika, D3 Manajemen Informatika – Internasional (CZIMT China), D3 Teknik Komputer, D3 Teknik Komputer – Internasional (WXIT China), D4 Teknik Informatika, D4 Teknik Informatika – PSDKU Nganjuk, D4 Teknik Informatika – PSDKU Sidoarjo, D4 Teknik Informatika – Internasional (MSU Malaysia), D4 Teknologi Rekayasa Komputer, D4 Teknologi Rekayasa Perangkat Lunak – PSDKU Sabu Raijua NTT. Mahasiswa dibekali kompetensi dalam pemrograman, pengembangan aplikasi desktop, web, dan mobile, jaringan komputer, Internet of Things (IoT), sistem informasi, data analyst, business intelligence, artificial intelligence, serta pengelolaan teknologi informasi untuk mendukung kebutuhan industri digital modern.";

        PolijeMajor::where('nama_jurusan', 'Teknologi Informasi')
            ->update(['deskripsi' => $description]);

        $this->command->info('✅ Deskripsi Teknologi Informasi telah diperbarui.');
    }
}
