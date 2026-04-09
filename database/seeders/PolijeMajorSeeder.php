<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PolijeMajor;

class PolijeMajorSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = [
            [
                'nama_jurusan' => 'Produksi Pertanian',
                'deskripsi' => 'Jurusan yang mempelajari budidaya tanaman, pengelolaan lahan, dan produksi hasil pertanian modern, termasuk bidang turunan yang terkait dengan agronomi, pangan, dan lingkungan.',
                'keywords' => ['pertanian', 'petani', 'kebun', 'sawah', 'panen', 'tanaman', 'budidaya', 'agronomi', 'tanam', 'bercocok tanam', 'alam', 'hortikultura', 'pupuk', 'bibit', 'agroteknologi', 'perkebunan', 'pangan', 'ketahanan pangan', 'hidroponik', 'organik'],
                'preferensi_studi' => ['Pertanian & Lingkungan'],
                'bobot_mapel' => [
                    'biologi' => 0.40, 'kimia' => 0.30, 'fisika' => 0.15, 'mtk' => 0.15,
                    'geografi' => 0.35, 'ekonomi' => 0.30, 'sosiologi' => 0.20, 'sejarah' => 0.15,
                ],
                'prospek_kerja' => 'Petani modern, konsultan pertanian, pengelola perkebunan, peneliti pertanian, agronomis.',
            ],
            [
                'nama_jurusan' => 'Teknologi Pertanian',
                'deskripsi' => 'Jurusan yang mengintegrasikan teknologi dengan pertanian, meliputi mekanisasi, pengolahan hasil, otomasi, dan inovasi teknologi pangan serta sistem produksi modern.',
                'keywords' => ['teknologi pertanian', 'mesin pertanian', 'inovasi', 'otomasi', 'pengolahan pangan', 'pangan', 'mekanisasi', 'teknologi pangan', 'alat pertanian', 'rekayasa', 'iot pertanian', 'smart farming', 'digital farming', 'kontrol kualitas', 'proses produksi'],
                'preferensi_studi' => ['Sains & Teknologi', 'Pertanian & Lingkungan'],
                'bobot_mapel' => [
                    'fisika' => 0.35, 'mtk' => 0.30, 'kimia' => 0.20, 'biologi' => 0.15,
                    'ekonomi' => 0.30, 'geografi' => 0.30, 'sosiologi' => 0.20, 'sejarah' => 0.20,
                ],
                'prospek_kerja' => 'Teknisi pertanian, ahli mekanisasi, quality control pangan, peneliti teknologi pangan.',
            ],
            [
                'nama_jurusan' => 'Peternakan',
                'deskripsi' => 'Jurusan yang mempelajari pengelolaan ternak, nutrisi hewan, reproduksi, dan pengolahan produk peternakan, termasuk kewirausahaan dan teknologi peternakan terapan.',
                'keywords' => ['ternak', 'hewan', 'peternakan', 'peternak', 'sapi', 'ayam', 'unggas', 'kambing', 'susu', 'pakan', 'nutrisi hewan', 'veteriner', 'ikan', 'aquaculture', 'budidaya hewan', 'farm management', 'kesehatan hewan', 'produksi ternak'],
                'preferensi_studi' => ['Pertanian & Lingkungan', 'Kesehatan & Ilmu Hayat'],
                'bobot_mapel' => [
                    'biologi' => 0.45, 'kimia' => 0.25, 'fisika' => 0.15, 'mtk' => 0.15,
                    'geografi' => 0.30, 'ekonomi' => 0.30, 'sosiologi' => 0.20, 'sejarah' => 0.20,
                ],
                'prospek_kerja' => 'Peternak profesional, konsultan peternakan, manajer peternakan, ahli nutrisi hewan.',
            ],
            [
                'nama_jurusan' => 'Manajemen Agribisnis',
                'deskripsi' => 'Jurusan yang menggabungkan ilmu pertanian dan bisnis, meliputi manajemen usaha, pemasaran hasil, rantai pasok, dan kewirausahaan agribisnis.',
                'keywords' => ['bisnis', 'agribisnis', 'usaha', 'entrepreneur', 'pengusaha', 'dagang', 'jual', 'pemasaran', 'kewirausahaan', 'manajemen', 'ekonomi pertanian', 'pasar', 'supply chain', 'logistik', 'analisis pasar', 'branding produk'],
                'preferensi_studi' => ['Bisnis & Manajemen', 'Pertanian & Lingkungan'],
                'bobot_mapel' => [
                    'mtk' => 0.35, 'biologi' => 0.25, 'kimia' => 0.20, 'fisika' => 0.20,
                    'ekonomi' => 0.45, 'geografi' => 0.20, 'sosiologi' => 0.20, 'sejarah' => 0.15,
                ],
                'prospek_kerja' => 'Manajer agribisnis, entrepreneur pertanian, konsultan pemasaran pertanian, analis pasar komoditas.',
            ],
            [
                'nama_jurusan' => 'Teknologi Informasi',
                'deskripsi' => 'Jurusan yang mempelajari pengembangan perangkat lunak, data, jaringan, keamanan siber, dan ekosistem teknologi digital, termasuk bidang turunan komputasi terapan.',
                'keywords' => ['programmer', 'developer', 'coding', 'software', 'web', 'aplikasi', 'komputer', 'it', 'jaringan', 'hacker', 'game', 'data', 'ai', 'robot', 'ngoding', 'laptop', 'teknologi', 'digital', 'internet', 'programming', 'desain grafis', 'ui ux', 'mobile app', 'cloud', 'database', 'machine learning'],
                'preferensi_studi' => ['Sains & Teknologi'],
                'bobot_mapel' => [
                    'mtk' => 0.45, 'fisika' => 0.25, 'kimia' => 0.15, 'biologi' => 0.15,
                    'ekonomi' => 0.25, 'geografi' => 0.20, 'sosiologi' => 0.25, 'sejarah' => 0.30,
                ],
                'prospek_kerja' => 'Software developer, web developer, network engineer, data analyst, cybersecurity specialist.',
            ],
            [
                'nama_jurusan' => 'Teknik',
                'deskripsi' => 'Jurusan yang mempelajari mesin, kelistrikan, elektronika, otomasi, dan sistem teknik industri dengan pendekatan praktik dan pemecahan masalah teknis.',
                'keywords' => ['mesin', 'bengkel', 'listrik', 'las', 'robot', 'motor', 'teknik', 'otomasi', 'elektronik', 'instalasi', 'panel', 'mekanik', 'industri', 'manufaktur', 'pabrik', 'bangunan', 'konstruksi', 'sipil', 'energi', 'maintenance', 'mekatronika', 'instrumentasi', 'quality control'],
                'preferensi_studi' => ['Sains & Teknologi'],
                'bobot_mapel' => [
                    'fisika' => 0.40, 'mtk' => 0.35, 'kimia' => 0.15, 'biologi' => 0.10,
                    'ekonomi' => 0.25, 'geografi' => 0.25, 'sosiologi' => 0.20, 'sejarah' => 0.30,
                ],
                'prospek_kerja' => 'Teknisi mesin, ahli listrik, engineer industri, maintenance engineer, kontraktor.',
            ],
            [
                'nama_jurusan' => 'Kesehatan',
                'deskripsi' => 'Jurusan yang mempelajari ilmu kesehatan terapan, gizi, rekam medis, dan pelayanan kesehatan masyarakat, termasuk aspek promotif dan preventif.',
                'keywords' => ['dokter', 'perawat', 'medis', 'gizi', 'kesehatan', 'pelayanan', 'terapis', 'obat', 'rumah sakit', 'klinik', 'farmasi', 'nutrisi', 'sanitasi', 'rawat', 'sehat', 'kesehatan masyarakat', 'laboratorium', 'diagnostik', 'wellness'],
                'preferensi_studi' => ['Kesehatan & Ilmu Hayat'],
                'bobot_mapel' => [
                    'biologi' => 0.40, 'kimia' => 0.35, 'mtk' => 0.15, 'fisika' => 0.10,
                    'sosiologi' => 0.30, 'ekonomi' => 0.25, 'geografi' => 0.25, 'sejarah' => 0.20,
                ],
                'prospek_kerja' => 'Ahli gizi, perekam medis, tenaga kesehatan, asisten apoteker, sanitarian.',
            ],
            [
                'nama_jurusan' => 'Bahasa, Komunikasi, dan Pariwisata',
                'deskripsi' => 'Jurusan yang mempelajari bahasa, komunikasi, perhotelan, layanan publik, dan industri pariwisata dengan orientasi pada kompetensi komunikasi profesional.',
                'keywords' => ['bahasa', 'komunikasi', 'pariwisata', 'tour guide', 'hotel', 'jurnalis', 'marketing', 'inggris', 'penerjemah', 'travel', 'wisata', 'hospitality', 'public speaking', 'media', 'broadcasting', 'content creator', 'humas', 'event', 'pelayanan tamu'],
                'preferensi_studi' => ['Sosial & Humaniora', 'Bisnis & Manajemen'],
                'bobot_mapel' => [
                    'biologi' => 0.20, 'kimia' => 0.20, 'fisika' => 0.20, 'mtk' => 0.40,
                    'sosiologi' => 0.30, 'sejarah' => 0.30, 'geografi' => 0.25, 'ekonomi' => 0.15,
                ],
                'prospek_kerja' => 'Tour guide, staf perhotelan, jurnalis, public relation, penerjemah, staf maskapai.',
            ],
            [
                'nama_jurusan' => 'Bisnis',
                'deskripsi' => 'Jurusan yang mempelajari akuntansi, manajemen bisnis, perbankan, keuangan, dan administrasi niaga, termasuk analisis data bisnis dan pengambilan keputusan.',
                'keywords' => ['manager', 'pimpinan', 'bisnis', 'accounting', 'marketing', 'sales', 'kantor', 'keuangan', 'bank', 'akuntansi', 'hitung', 'administrasi', 'perbankan', 'ekonomi', 'uang', 'investasi', 'pajak', 'wirausaha', 'audit', 'finance', 'analisis bisnis'],
                'preferensi_studi' => ['Bisnis & Manajemen'],
                'bobot_mapel' => [
                    'mtk' => 0.45, 'fisika' => 0.20, 'kimia' => 0.15, 'biologi' => 0.20,
                    'ekonomi' => 0.45, 'sosiologi' => 0.20, 'geografi' => 0.15, 'sejarah' => 0.20,
                ],
                'prospek_kerja' => 'Akuntan, staf perbankan, manajer bisnis, marketing executive, analis keuangan.',
            ],
        ];

        foreach ($jurusans as $jur) {
            PolijeMajor::updateOrCreate(
                ['nama_jurusan' => $jur['nama_jurusan']],
                [
                    'deskripsi' => $jur['deskripsi'],
                    'keywords' => $jur['keywords'],
                    'preferensi_studi' => $jur['preferensi_studi'],
                    'bobot_mapel' => $jur['bobot_mapel'],
                    'prospek_kerja' => $jur['prospek_kerja'],
                ]
            );
        }
    }
}