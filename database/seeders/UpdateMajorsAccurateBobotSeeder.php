<?php

namespace Database\Seeders;

use App\Models\PolijeMajor;
use Illuminate\Database\Seeder;

class UpdateMajorsAccurateBobotSeeder extends Seeder
{
    public function run(): void
    {
        // Data jurusan dengan bobot mapel yang akurat untuk Naive Bayes
        $majorsData = [
            [
                'nama_jurusan' => 'Produksi Pertanian',
                'bobot_mapel' => [
                    'mtk' => 0.15,
                    'fisika' => 0.15,
                    'kimia' => 0.30,
                    'biologi' => 0.40, // Tertinggi - biologi alam/tanaman
                    'ekonomi' => 0.25,
                    'geografi' => 0.35, // Tinggi - lokasi/iklim/lahan
                    'sejarah' => 0.10,
                    'sosiologi' => 0.15,
                ],
                'keywords' => ['pertanian', 'petani', 'kebun', 'sawah', 'panen', 'tanaman', 'budidaya', 'agronomi', 'tanam', 'bercocok tanam', 'alam', 'hortikultura', 'pupuk', 'bibit', 'agroteknologi', 'perkebunan', 'pangan', 'ketahanan pangan', 'hidroponik', 'organik', 'lahan', 'irigasi', 'cuaca', 'musim'],
                'preferensi_studi' => ['Pertanian & Lingkungan', 'Sains & Teknologi', 'Inovasi & Teknologi', 'Sustainable Development', 'Agribisnis Modern'],
            ],
            [
                'nama_jurusan' => 'Teknologi Pertanian',
                'bobot_mapel' => [
                    'mtk' => 0.35, // Tinggi - rumus & hitungan
                    'fisika' => 0.35, // Tertinggi - mesin/energi/gerakan
                    'kimia' => 0.20,
                    'biologi' => 0.15,
                    'ekonomi' => 0.25,
                    'geografi' => 0.25,
                    'sejarah' => 0.15,
                    'sosiologi' => 0.15,
                ],
                'keywords' => ['teknologi pertanian', 'mesin pertanian', 'inovasi', 'otomasi', 'pengolahan pangan', 'pangan', 'mekanisasi', 'teknologi pangan', 'alat pertanian', 'rekayasa', 'iot pertanian', 'smart farming', 'digital farming', 'kontrol kualitas', 'proses produksi', 'efisiensi', 'presisi pertanian'],
                'preferensi_studi' => ['Sains & Teknologi', 'Inovasi & Teknologi', 'Pertanian & Lingkungan', 'Sustainable Development', 'Digital Transformation'],
            ],
            [
                'nama_jurusan' => 'Peternakan',
                'bobot_mapel' => [
                    'mtk' => 0.20,
                    'fisika' => 0.15,
                    'kimia' => 0.25,
                    'biologi' => 0.45, // Tertinggi - ilmu hewan
                    'ekonomi' => 0.30, // Tinggi - pasar/bisnis ternak
                    'geografi' => 0.25,
                    'sejarah' => 0.10,
                    'sosiologi' => 0.15,
                ],
                'keywords' => ['ternak', 'hewan', 'peternakan', 'peternak', 'sapi', 'ayam', 'unggas', 'kambing', 'susu', 'pakan', 'nutrisi hewan', 'veteriner', 'ikan', 'aquaculture', 'budidaya hewan', 'farm management', 'kesehatan hewan', 'produksi ternak', 'perikanan', 'kolam', 'perkembangbiakan', 'reproduksi'],
                'preferensi_studi' => ['Pertanian & Lingkungan', 'Kesehatan & Ilmu Hayat', 'Agribisnis Modern', 'Sustainable Development', 'Sains & Teknologi'],
            ],
            [
                'nama_jurusan' => 'Manajemen Agribisnis',
                'bobot_mapel' => [
                    'mtk' => 0.35, // Tinggi - akuntansi/perhitungan
                    'fisika' => 0.15,
                    'kimia' => 0.10,
                    'biologi' => 0.15,
                    'ekonomi' => 0.45, // Tertinggi - bisnis/pasar
                    'geografi' => 0.20,
                    'sejarah' => 0.15,
                    'sosiologi' => 0.20,
                ],
                'keywords' => ['bisnis', 'agribisnis', 'usaha', 'entrepreneur', 'pengusaha', 'dagang', 'jual', 'pemasaran', 'kewirausahaan', 'manajemen', 'ekonomi pertanian', 'pasar', 'supply chain', 'logistik', 'analisis pasar', 'branding produk', 'akuntansi', 'keuangan', 'investasi', 'ekspor', 'strategi bisnis'],
                'preferensi_studi' => ['Bisnis & Manajemen', 'Entrepreneurship & Inovasi', 'Pertanian & Lingkungan', 'Agribisnis Modern', 'Digital Transformation'],
            ],
            [
                'nama_jurusan' => 'Teknologi Informasi',
                'bobot_mapel' => [
                    'mtk' => 0.50, // Tertinggi - logika/algoritma
                    'fisika' => 0.20,
                    'kimia' => 0.10,
                    'biologi' => 0.10,
                    'ekonomi' => 0.20,
                    'geografi' => 0.10,
                    'sejarah' => 0.15,
                    'sosiologi' => 0.15,
                ],
                'keywords' => ['programmer', 'developer', 'coding', 'software', 'web', 'aplikasi', 'komputer', 'it', 'jaringan', 'hacker', 'game', 'data', 'ai', 'robot', 'ngoding', 'laptop', 'teknologi', 'digital', 'internet', 'programming', 'desain grafis', 'ui ux', 'mobile app', 'cloud', 'database', 'machine learning', 'cybersecurity', 'backend', 'frontend'],
                'preferensi_studi' => ['Sains & Teknologi', 'Inovasi & Teknologi', 'Digital Transformation', 'Entrepreneurship & Inovasi', 'Problem Solving & Logic'],
            ],
            [
                'nama_jurusan' => 'Teknik',
                'bobot_mapel' => [
                    'mtk' => 0.40, // Tinggi - perhitungan teknis
                    'fisika' => 0.45, // Tertinggi - gaya/energi/mekanika
                    'kimia' => 0.15,
                    'biologi' => 0.10,
                    'ekonomi' => 0.20,
                    'geografi' => 0.15,
                    'sejarah' => 0.15,
                    'sosiologi' => 0.10,
                ],
                'keywords' => ['mesin', 'bengkel', 'listrik', 'las', 'robot', 'motor', 'teknik', 'otomasi', 'elektronik', 'instalasi', 'panel', 'mekanik', 'industri', 'manufaktur', 'pabrik', 'bangunan', 'konstruksi', 'sipil', 'energi', 'maintenance', 'mekatronika', 'instrumentasi', 'quality control', 'produksi', 'assembly'],
                'preferensi_studi' => ['Sains & Teknologi', 'Industri & Manufaktur', 'Problem Solving & Logic', 'Inovasi & Teknologi', 'Infrastruktur & Pembangunan'],
            ],
            [
                'nama_jurusan' => 'Kesehatan',
                'bobot_mapel' => [
                    'mtk' => 0.20,
                    'fisika' => 0.15,
                    'kimia' => 0.35, // Tinggi - farmasi/reaksi
                    'biologi' => 0.45, // Tertinggi - anatomi/fisiologi
                    'ekonomi' => 0.15,
                    'geografi' => 0.10,
                    'sejarah' => 0.10,
                    'sosiologi' => 0.30, // Tinggi - interaksi pasien
                ],
                'keywords' => ['dokter', 'perawat', 'medis', 'gizi', 'kesehatan', 'pelayanan', 'terapis', 'obat', 'rumah sakit', 'klinik', 'farmasi', 'nutrisi', 'sanitasi', 'rawat', 'sehat', 'kesehatan masyarakat', 'laboratorium', 'diagnostik', 'wellness', 'vaksin', 'fisioterapi', 'psikologi', 'kebidanan'],
                'preferensi_studi' => ['Kesehatan & Ilmu Hayat', 'Pelayanan & Sosial', 'Sains & Teknologi', 'Inovasi Medis', 'Humanitarian & Community'],
            ],
            [
                'nama_jurusan' => 'Bahasa, Komunikasi, dan Pariwisata',
                'bobot_mapel' => [
                    'mtk' => 0.15, // Rendah - bukan fokus
                    'fisika' => 0.10,
                    'kimia' => 0.10,
                    'biologi' => 0.10,
                    'ekonomi' => 0.25, // Tinggi - pariwisata/bisnis
                    'geografi' => 0.35, // Tinggi - destinasi/lokasi wisata
                    'sejarah' => 0.35, // Tertinggi - konteks budaya/sejarah
                    'sosiologi' => 0.35, // Tertinggi - interaksi sosial/komunikasi
                ],
                'keywords' => ['bahasa', 'komunikasi', 'pariwisata', 'tour guide', 'hotel', 'jurnalis', 'marketing', 'inggris', 'penerjemah', 'travel', 'wisata', 'hospitality', 'public speaking', 'media', 'broadcasting', 'content creator', 'humas', 'event', 'pelayanan tamu', 'budaya', 'sejarah', 'linguistik', 'turis', 'destinasi'],
                'preferensi_studi' => ['Sosial & Humaniora', 'Bisnis & Manajemen', 'Kreativitas & Komunikasi', 'Entrepreneurship & Inovasi', 'Digital Marketing & Content'],
            ],
            [
                'nama_jurusan' => 'Bisnis',
                'bobot_mapel' => [
                    'mtk' => 0.45, // Tinggi - akuntansi/analisis
                    'fisika' => 0.10,
                    'kimia' => 0.10,
                    'biologi' => 0.10,
                    'ekonomi' => 0.50, // Tertinggi - bisnis/ekonomi
                    'geografi' => 0.15,
                    'sejarah' => 0.15,
                    'sosiologi' => 0.20,
                ],
                'keywords' => ['manager', 'pimpinan', 'bisnis', 'accounting', 'marketing', 'sales', 'kantor', 'keuangan', 'bank', 'akuntansi', 'hitung', 'administrasi', 'perbankan', 'ekonomi', 'uang', 'investasi', 'pajak', 'wirausaha', 'audit', 'finance', 'analisis bisnis', 'customer', 'strategi', 'leadership'],
                'preferensi_studi' => ['Bisnis & Manajemen', 'Entrepreneurship & Inovasi', 'Kepemimpinan & Manajemen', 'Digital Transformation', 'Analisis & Problem Solving'],
            ],
        ];

        foreach ($majorsData as $data) {
            PolijeMajor::where('nama_jurusan', $data['nama_jurusan'])
                ->update([
                    'bobot_mapel' => $data['bobot_mapel'],
                    'keywords' => $data['keywords'],
                    'preferensi_studi' => $data['preferensi_studi'],
                ]);
        }

        $this->command->info('✅ Bobot mapel semua jurusan sudah diperbarui dengan akurat!');
    }
}
