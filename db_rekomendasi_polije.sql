-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 12, 2026 at 11:26 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_rekomendasi_polije`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_alumni` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelompok_asal` enum('IPA','IPS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_masuk_sma` year DEFAULT NULL,
  `tahun_lulus_sma` year DEFAULT NULL,
  `mtk` double(8,2) DEFAULT NULL,
  `fisika` double(8,2) DEFAULT NULL,
  `kimia` double(8,2) DEFAULT NULL,
  `biologi` double(8,2) DEFAULT NULL,
  `ekonomi` double(8,2) DEFAULT NULL,
  `geografi` double(8,2) DEFAULT NULL,
  `sosiologi` double(8,2) DEFAULT NULL,
  `sejarah` double(8,2) DEFAULT NULL,
  `nilai_rata_rata` double(8,2) DEFAULT NULL,
  `minat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cita_cita` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferensi_studi` enum('Sains & Teknologi','Pertanian & Lingkungan','Kesehatan & Ilmu Hayat','Bisnis & Manajemen','Sosial & Humaniora') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prestasi` text COLLATE utf8mb4_unicode_ci,
  `major_masuk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_masuk_polije` year DEFAULT NULL,
  `tahun_lulus_polije` year DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurusan_polije`
--

CREATE TABLE `jurusan_polije` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `keywords` json DEFAULT NULL,
  `preferensi_studi` json DEFAULT NULL,
  `bobot_mapel` json DEFAULT NULL,
  `prospek_kerja` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurusan_polije`
--

INSERT INTO `jurusan_polije` (`id`, `nama_jurusan`, `deskripsi`, `keywords`, `preferensi_studi`, `bobot_mapel`, `prospek_kerja`, `created_at`, `updated_at`) VALUES
(1, 'Produksi Pertanian', 'Jurusan Produksi Pertanian merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan pendidikan vokasi di bidang budidaya tanaman, benih, hortikultura, dan perkebunan untuk menghasilkan lulusan yang profesional, kompeten, dan mampu bersaing di dunia kerja. Jurusan ini memiliki program studi D3 Produksi Tanaman Hortikultura, D3 Produksi Tanaman Perkebunan, D4 Teknologi Produksi Tanaman Pangan, D4 Teknologi Produksi Benih, D4 Budidaya Tanaman Perkebunan, dan D4 Pengelolaan Perkebunan Kopi dengan sistem pembelajaran berbasis praktik yang didukung berbagai fasilitas laboratorium dan lahan praktik pertanian. Mahasiswa dibekali kompetensi dalam pembibitan, budidaya tanaman, teknologi benih, perlindungan tanaman, pengelolaan perkebunan, serta penerapan teknologi pertanian untuk mendukung sektor agrikultur berkelanjutan. Lulusan Jurusan Produksi Pertanian memiliki prospek kerja sebagai agronomis, teknisi benih, supervisor perkebunan, quality control pertanian, penyuluh pertanian, peneliti tanaman, pengusaha agribisnis, manajer perkebunan, konsultan pertanian, dan tenaga ahli budidaya tanaman.', '[\"pertanian\", \"petani\", \"kebun\", \"sawah\", \"panen\", \"tanaman\", \"budidaya\", \"agronomi\", \"tanam\", \"bercocok tanam\", \"alam\", \"hortikultura\", \"pupuk\", \"bibit\", \"agroteknologi\", \"perkebunan\", \"pangan\", \"ketahanan pangan\", \"hidroponik\", \"organik\"]', '[\"Pertanian & Lingkungan\"]', '{\"ipa\": {\"mtk\": 0.15, \"kimia\": 0.3, \"fisika\": 0.1, \"biologi\": 0.45}, \"ips\": {\"ekonomi\": 0.15, \"sejarah\": 0.3, \"geografi\": 0.35, \"sosiologi\": 0.2}}', 'Agronomis, teknisi benih, supervisor perkebunan, quality control pertanian, penyuluh pertanian, peneliti tanaman, pengusaha agribisnis, manajer perkebunan, konsultan pertanian, dan tenaga ahli budidaya tanaman.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(2, 'Teknologi Pertanian', 'Jurusan Teknologi Pertanian merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan pendidikan vokasi di bidang teknologi pertanian, pengolahan pangan, dan rekayasa pertanian untuk menghasilkan lulusan yang profesional, terampil, dan mampu bersaing di dunia kerja. Jurusan ini memiliki program studi D3 Keteknikan Pertanian, D3 Teknologi Industri Pangan, D4 Teknologi Rekayasa Pangan, dan D4 Teknologi Rekayasa Pengemasan dengan sistem pembelajaran berbasis praktik yang didukung berbagai fasilitas laboratorium dan sarana praktik. Mahasiswa dibekali kompetensi dalam pengolahan pangan, alat dan mesin pertanian, teknologi pengemasan, pengawasan mutu pangan, serta penerapan teknologi terapan di bidang agroindustri dan pertanian modern. Lulusan Jurusan Teknologi Pertanian memiliki prospek kerja sebagai teknisi alat mesin pertanian, quality control pangan, supervisor produksi, analis pangan, pengelola agroindustri, wirausaha pangan, teknolog pangan, staf industri makanan dan minuman, serta tenaga ahli rekayasa pertanian.', '[\"teknologi\", \"pangan\", \"mesin\", \"industri\", \"mekanisasi\", \"pengolahan\", \"rekayasa\", \"agroindustri\", \"manufaktur\", \"alat\", \"logistik\", \"pabrik\", \"quality control\", \"haccp\", \"gmp\", \"kemasan\", \"limbah\", \"bahan pangan\", \"proses produksi\", \"otomasi\"]', '[\"Teknik & Teknologi\", \"Pertanian & Lingkungan\"]', '{\"ipa\": {\"mtk\": 0.3, \"kimia\": 0.25, \"fisika\": 0.25, \"biologi\": 0.2}, \"ips\": {\"ekonomi\": 0.35, \"sejarah\": 0.15, \"geografi\": 0.3, \"sosiologi\": 0.2}}', 'Teknisi alat mesin pertanian, quality control pangan, supervisor produksi, analis pangan, pengelola agroindustri, wirausaha pangan, teknolog pangan, staf industri makanan dan minuman, dan tenaga ahli rekayasa pertanian.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(3, 'Peternakan', 'Jurusan Peternakan Politeknik Negeri Jember merupakan jurusan vokasi yang berfokus pada pengembangan ilmu dan teknologi peternakan untuk menghasilkan lulusan yang profesional, terampil, inovatif, dan mampu bersaing secara global di bidang peternakan modern. Jurusan ini memiliki program studi D3 Produksi Ternak, D4 Manajemen Bisnis Unggas, dan D4 Teknologi Pakan Ternak dengan pembelajaran berbasis praktik yang didukung laboratorium, teaching farm, serta penerapan teknologi terapan di bidang peternakan. D3 Produksi Ternak memiliki keunggulan “Local Potential to Sustainable Global Livestock through Vocational Collaboration” yang berfokus pada pengembangan potensi lokal menuju peternakan berkelanjutan berbasis kolaborasi vokasi. Program Studi D4 Manajemen Bisnis Unggas memiliki fokus pada pengembangan tenaga profesional unggas yang terampil dan siap kerja di bidang perunggasan modern, sedangkan Program Studi D4 Teknologi Pakan Ternak berfokus pada teknologi pakan terapan dan pengembangan smart nutritionist untuk mendukung produktivitas ternak. Jurusan Peternakan juga membekali mahasiswa dengan kompetensi dalam nutrisi hewan, reproduksi ternak, manajemen peternakan, teknologi pengolahan hasil ternak, kewirausahaan, dan pemanfaatan teknologi modern di bidang peternakan. Lulusan Jurusan Peternakan memiliki prospek kerja sebagai peternak profesional, konsultan peternakan, manajer peternakan, ahli nutrisi hewan, pengusaha peternakan, supervisor farm, quality control produk peternakan, formulasi pakan ternak, industri perunggasan, dan bidang agribisnis peternakan.', '[\"ternak\", \"hewan\", \"sapi\", \"ayam\", \"kambing\", \"telur\", \"susu\", \"daging\", \"pakan\", \"kandang\", \"veteriner\", \"breeding\", \"penggemukan\", \"unggas\", \"ruminansia\", \"bioteknologi hewan\", \"produksi ternak\", \"kesehatan hewan\", \"hasil ternak\", \"peternak\"]', '[\"Pertanian & Lingkungan\"]', '{\"ipa\": {\"mtk\": 0.15, \"kimia\": 0.25, \"fisika\": 0.1, \"biologi\": 0.5}, \"ips\": {\"ekonomi\": 0.3, \"sejarah\": 0.1, \"geografi\": 0.3, \"sosiologi\": 0.3}}', 'Peternak profesional, konsultan peternakan, manajer peternakan, ahli nutrisi hewan, supervisor farm, pengusaha peternakan, quality control produk peternakan, formulator pakan ternak, industri perunggasan, teknisi peternakan, dan agribisnis peternakan.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(4, 'Manajemen Agribisnis', 'Jurusan Manajemen Agribisnis merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan pendidikan vokasi di bidang agribisnis dan agroindustri untuk menghasilkan lulusan yang profesional, inovatif, dan mampu bersaing di tingkat nasional maupun internasional. Jurusan ini memiliki program studi D3 Manajemen Agribisnis, D4 Manajemen Agroindustri, Magister Terapan Agribisnis, D3 Manajemen Agribisnis PSDKU Nganjuk, D4 Manajemen Agroindustri PSDKU Sidoarjo, D4 Manajemen Agribisnis PSDKU Ngawi, serta program rintisan D4 Manajemen Agribisnis Bondowoso. Sistem pembelajaran dilaksanakan berbasis praktik melalui kegiatan perkuliahan di kelas, laboratorium, praktik lapang, dan program magang di perusahaan yang relevan dengan bidang agribisnis dan agroindustri. Mahasiswa dibekali kompetensi dalam pengelolaan permodalan, keuangan, pemasaran, sumber daya manusia, teknologi proses produksi, dan teknologi usaha tani yang mencakup sektor pertanian, peternakan, perikanan, perkebunan, dan kehutanan. Lulusan Jurusan Manajemen Agribisnis memiliki prospek kerja sebagai penyuluh pertanian, tenaga pemasar operasional, mandor atau pengawas lahan, wirausahawan agribisnis, asisten manajer agribisnis, supervisor agribisnis, analis agroindustri, konsultan agroindustri, auditor internal agroindustri, staf perbankan, dan staf instansi pemerintahan.', '[\"bisnis\", \"ekonomi\", \"manajemen\", \"pemasaran\", \"keuangan\", \"akuntansi\", \"wirausaha\", \"ekspor\", \"impor\", \"pasar\", \"agribisnis\", \"manajerial\", \"perencanaan\", \"analisis\", \"investasi\", \"modal\", \"peluang\", \"strategi\", \"jual beli\", \"dagang\"]', '[\"Bisnis & Manajemen\"]', '{\"ipa\": {\"mtk\": 0.45, \"kimia\": 0.1, \"fisika\": 0.1, \"biologi\": 0.35}, \"ips\": {\"ekonomi\": 0.5, \"sejarah\": 0.1, \"geografi\": 0.2, \"sosiologi\": 0.2}}', 'Penyuluh pertanian, tenaga pemasar operasional, mandor atau pengawas lahan, wirausahawan agribisnis, asisten manajer agribisnis, supervisor agribisnis, analis agroindustri, konsultan agroindustri, auditor internal agroindustri, staf perbankan, dan staf instansi pemerintahan.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(5, 'Teknologi Informasi', 'Jurusan Teknologi Informasi merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan pendidikan vokasi di bidang teknologi informasi, komputer, dan pengembangan perangkat lunak untuk menghasilkan lulusan yang profesional, inovatif, dan mampu bersaing di tingkat nasional maupun internasional. Jurusan ini memiliki program studi D3 Manajemen Informatika, D3 Teknik Komputer, D4 Teknik Informatika, D4 Teknik Informatika PSDKU Nganjuk, D4 Teknik Informatika PSDKU Sidoarjo, dan D4 Teknologi Rekayasa Perangkat Lunak PSDKU Sabu Raijua dengan sistem pembelajaran berbasis praktik yang didukung laboratorium komputer, jaringan, rekayasa perangkat lunak, multimedia cerdas, dan sistem informasi. Mahasiswa dibekali kompetensi dalam pemrograman, pengembangan aplikasi desktop, web, dan mobile, jaringan komputer, Internet of Things (IoT), sistem informasi, data analyst, business intelligence, artificial intelligence, serta pengelolaan teknologi informasi untuk mendukung kebutuhan industri digital modern. Jurusan Teknologi Informasi juga aktif dalam pengembangan penelitian terapan, sertifikasi kompetensi, pelatihan teknologi, dan kerja sama industri guna meningkatkan kualitas lulusan. Lulusan Jurusan Teknologi Informasi memiliki prospek kerja sebagai programmer, web developer, mobile developer, UI/UX designer, data analyst, network engineer, system analyst, software engineer, IT support, database administrator, business intelligence developer, dan technopreneur.', '[\"komputer\", \"it\", \"coding\", \"program\", \"aplikasi\", \"website\", \"jaringan\", \"data\", \"software\", \"hardware\", \"informatika\", \"digital\", \"internet\", \"cyber security\", \"artificial intelligence\", \"cloud\", \"database\", \"ui ux\", \"sistem\", \"teknologi informasi\"]', '[\"Teknik & Teknologi\", \"Komputer & IT\"]', '{\"ipa\": {\"mtk\": 0.5, \"kimia\": 0.1, \"fisika\": 0.3, \"biologi\": 0.1}, \"ips\": {\"ekonomi\": 0.4, \"sejarah\": 0.1, \"geografi\": 0.3, \"sosiologi\": 0.2}}', 'Programmer, web developer, mobile developer, UI/UX designer, data analyst, network engineer, system analyst, software engineer, IT support, database administrator, business intelligence developer, dan technopreneur.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(6, 'Bahasa, Komunikasi, dan Pariwisata', 'Jurusan Bahasa, Komunikasi dan Pariwisata (BKP) merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan kompetensi di bidang bahasa, komunikasi, media kreatif, dan pariwisata untuk menghasilkan lulusan yang profesional, kreatif, inovatif, serta mampu bersaing di tingkat nasional maupun internasional. Jurusan ini memiliki program studi D4 Produksi Media, D4 Destinasi Pariwisata, dan D3 Bahasa Inggris dengan sistem pembelajaran berbasis vokasi yang mengutamakan praktik serta didukung fasilitas seperti laboratorium multimedia, laboratorium bahasa Inggris, dan laboratorium perhotelan. Selain kegiatan akademik, mahasiswa juga dibekali praktik lapang dan pengembangan keterampilan sesuai kebutuhan dunia industri. Lulusan D4 Produksi Media memiliki prospek kerja di bidang content creator, broadcasting, video editing, public relations, dan industri multimedia, sedangkan lulusan D4 Destinasi Pariwisata memiliki peluang kerja sebagai tour guide, travel consultant, pengelola destinasi wisata, event organizer, dan hospitality industry. Sementara itu, lulusan D3 Bahasa Inggris memiliki prospek kerja sebagai translator, interpreter, administrative staff, customer service internasional, public relations, dan front office hotel. Selain itu, Jurusan BKP juga menjalin kerja sama dengan dunia usaha dan dunia industri untuk meningkatkan kompetensi mahasiswa serta mendukung kesiapan kerja lulusan.', '[\"bahasa\", \"inggris\", \"komunikasi\", \"pariwisata\", \"tour\", \"travel\", \"hotel\", \"wisata\", \"guide\", \"budaya\", \"internasional\", \"public relations\", \"content writer\", \"akomodasi\", \"destinasi\", \"event\", \"mice\", \" hospitality\", \"penerjemah\", \"diplomasi\"]', '[\"Bahasa & Seni\", \"Sosial & Humaniora\"]', '{\"ipa\": {\"mtk\": 0.25, \"kimia\": 0.1, \"fisika\": 0.1, \"biologi\": 0.55}, \"ips\": {\"ekonomi\": 0.2, \"sejarah\": 0.3, \"geografi\": 0.25, \"sosiologi\": 0.25}}', 'Content creator, broadcaster, video editor, public relations, translator, interpreter, administrative staff, customer service internasional, tour guide, travel consultant, pengelola destinasi wisata, event organizer, staf perhotelan, hospitality industry, dan industri multimedia.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(7, 'Kesehatan', 'Jurusan Kesehatan merupakan bagian integral dari Politeknik Negeri Jember yang memiliki mandat strategis dalam menghasilkan sumber daya manusia vokasi bidang kesehatan, meliputi gizi, promosi kesehatan, dan manajemen informasi kesehatan. Jurusan ini memiliki program studi Sarjana Terapan Gizi Klinik, Sarjana Terapan Manajemen Informasi Kesehatan, Sarjana Terapan Manajemen Informasi Kesehatan PSDKU Ngawi, dan Sarjana Terapan Promosi Kesehatan dengan sistem pembelajaran berbasis praktik yang didukung berbagai fasilitas laboratorium kesehatan dan teknologi informasi. Program Studi Gizi Klinik diarahkan untuk menghasilkan lulusan yang kompeten dalam pelayanan gizi terapan, intervensi gizi berbasis masalah kesehatan masyarakat, serta pengembangan produk dan layanan gizi yang relevan dengan kebutuhan lokal, termasuk isu stunting dan ketahanan pangan. Program Studi Manajemen Informasi Kesehatan, baik di Kampus Utama maupun PSDKU Ngawi, difokuskan pada pengelolaan data dan informasi kesehatan berbasis sistem informasi, rekam medis, serta dukungan pengambilan keputusan di fasilitas pelayanan kesehatan. Sementara itu, Program Studi Promosi Kesehatan berfokus pada pengembangan kompetensi edukasi, komunikasi, dan pemberdayaan masyarakat dalam upaya promotif dan preventif bidang kesehatan. Lulusan Jurusan Kesehatan memiliki prospek kerja sebagai tenaga gizi, nutrisionis, promotor kesehatan, health educator, perekam medis, analis informasi kesehatan, administrator rumah sakit, pengelola sistem informasi kesehatan, staf pelayanan kesehatan, dan tenaga administrasi fasilitas kesehatan.', '[\"kesehatan\", \"medis\", \"dokter\", \"perawat\", \"gizi\", \"obat\", \"rumah sakit\", \"klinik\", \"rekam medis\", \"diet\", \"pasien\", \"analis kesehatan\", \"farmasi\", \"bidan\", \"puskesmas\", \"manajemen kesehatan\", \"pangan bergizi\", \"stunting\", \"laboratorium medis\", \"pelayanan kesehatan\"]', '[\"Kesehatan & Kedokteran\"]', '{\"ipa\": {\"mtk\": 0.2, \"kimia\": 0.3, \"fisika\": 0.15, \"biologi\": 0.35}, \"ips\": {\"ekonomi\": 0.15, \"sejarah\": 0.1, \"geografi\": 0.35, \"sosiologi\": 0.4}}', 'Tenaga gizi, nutrisionis, konsultan gizi, penyuluh kesehatan, promotor kesehatan, health educator, staf dinas kesehatan, pengelola program kesehatan masyarakat, perekam medis, analis informasi kesehatan, administrator rumah sakit, staf manajemen pelayanan kesehatan, pengelola sistem informasi kesehatan, dan tenaga administrasi fasilitas kesehatan.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(8, 'Teknik', 'Jurusan Teknik merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan pendidikan vokasi dan teknik terapan untuk menghasilkan lulusan yang profesional, inovatif, dan berdaya saing di tingkat nasional maupun internasional. Jurusan ini memiliki program studi D4 Teknik Energi Terbarukan, D4 Mesin Otomotif, dan D4 Teknologi Rekayasa Mekatronika dengan sistem pembelajaran berbasis praktik yang didukung berbagai fasilitas seperti Laboratorium Perawatan Otomotif, Rekayasa Otomotif, Listrik dan Pembangkitan Daya, Workshop Energi dan Mekanik, Elektronika Instrumentasi, Otomasi, serta Gambar dan Komputasi. Program Studi Teknik Energi Terbarukan berfokus pada pengembangan teknologi energi terbarukan dan energi proses, Program Studi Mesin Otomotif berfokus pada rekayasa mesin dan teknologi otomotif, sedangkan Program Studi Teknologi Rekayasa Mekatronika berfokus pada integrasi teknologi mekanik, elektronika, kontrol, dan otomasi untuk mendukung sektor industri modern. Jurusan Teknik juga aktif dalam pengembangan penelitian terapan, kompetisi teknologi, dan kerja sama industri untuk meningkatkan kompetensi mahasiswa sesuai kebutuhan dunia kerja. Lulusan Jurusan Teknik memiliki prospek kerja sebagai engineer energi terbarukan, teknisi otomotif, supervisor teknik, teknisi mekatronika, automation engineer, maintenance engineer, teknisi pembangkit listrik, quality control engineer, teknisi industri manufaktur, dan technopreneur.', '[\"teknik\", \"mesin\", \"listrik\", \"energi\", \"otomotif\", \"surya\", \"angin\", \"air\", \"mekanik\", \"konstruksi\", \"listrik\", \"engineer\", \"pembangkit\", \"workshop\", \"kendaraan\", \"motor\", \"listrik terbarukan\", \"manufaktur\", \"bengkel\", \"rekayasa\"]', '[\"Teknik & Teknologi\"]', '{\"ipa\": {\"mtk\": 0.45, \"kimia\": 0.15, \"fisika\": 0.3, \"biologi\": 0.1}, \"ips\": {\"ekonomi\": 0.35, \"sejarah\": 0.1, \"geografi\": 0.4, \"sosiologi\": 0.15}}', 'Engineer energi terbarukan, teknisi otomotif, supervisor teknik, teknisi mekatronika, automation engineer, maintenance engineer, teknisi pembangkit listrik, quality control engineer, teknisi industri manufaktur, dan technopreneur.', '2026-04-09 07:45:30', '2026-05-06 05:40:00'),
(9, 'Bisnis', 'Jurusan Bisnis merupakan salah satu jurusan di Politeknik Negeri Jember yang berfokus pada pengembangan pendidikan vokasi di bidang bisnis, pemasaran, dan akuntansi untuk menghasilkan lulusan yang profesional, inovatif, dan mampu bersaing di tingkat nasional maupun internasional. Jurusan ini memiliki program studi Sarjana Terapan Manajemen Pemasaran Internasional dan Sarjana Terapan Akuntansi Sektor Publik dengan sistem pembelajaran berbasis praktik yang didukung fasilitas seperti Laboratorium Pajak dan Audit, Laboratorium Sistem Informasi dan Komputer Akuntansi, serta Laboratorium Pemasaran Digital, Inovasi, dan Logistik. Mahasiswa dibekali kompetensi dalam pemasaran internasional, digital marketing, perpajakan, auditing, akuntansi sektor publik, pengelolaan bisnis, kewirausahaan, serta pemanfaatan teknologi informasi dalam dunia bisnis modern. Jurusan Bisnis juga aktif dalam pengembangan kompetisi akademik, program kewirausahaan, penelitian terapan, dan kerja sama dengan berbagai stakeholder untuk meningkatkan kualitas lulusan sesuai kebutuhan dunia industri dan bisnis. Lulusan Jurusan Bisnis memiliki prospek kerja sebagai digital marketer, public relations, auditor, akuntan sektor publik, staf perpajakan, financial analyst, business consultant, entrepreneur, marketing executive, dan staf administrasi bisnis.', '[\"bisnis\", \"ekonomi\", \"manajemen\", \"ritel\", \"digital\", \"ecommerce\", \"marketing\", \"wirausaha\", \"startup\", \"perdagangan\", \"penjualan\", \"karir\", \"analisis bisnis\", \"pemasaran digital\", \"strategi\", \"industri kreatif\", \"investasi\", \"manajer\", \"kewirausahaan\", \"toko\"]', '[\"Bisnis & Manajemen\"]', '{\"ipa\": {\"mtk\": 0.45, \"kimia\": 0.1, \"fisika\": 0.1, \"biologi\": 0.35}, \"ips\": {\"ekonomi\": 0.5, \"sejarah\": 0.1, \"geografi\": 0.2, \"sosiologi\": 0.2}}', 'Digital marketer, public relations, auditor, akuntan sektor publik, staf perpajakan, financial analyst, business consultant, entrepreneur, marketing executive, dan staf administrasi bisnis.', '2026-04-09 07:45:30', '2026-05-06 05:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_01_28_143443_add_role_to_users_table', 1),
(6, '2026_01_28_143445_create_students_table', 1),
(7, '2026_01_28_143446_create_polije_majors_table', 1),
(8, '2026_01_28_143448_create_recommendations_table', 1),
(9, '2026_01_28_143453_create_chat_histories_table', 1),
(10, '2026_02_06_000000_merge_students_into_users', 1),
(11, '2026_02_11_000000_add_student_fields_to_users_table', 1),
(12, '2026_02_12_add_bk_role_to_users', 1),
(13, '2026_02_12_create_alumni_table', 1),
(14, '2026_02_12_fix_alumni_table_structure', 1),
(15, '2026_02_12_update_alumni_table_structure', 1),
(16, '2026_02_21_100000_add_keywords_to_polije_majors_table', 1),
(17, '2026_02_26_100000_fix_recommendations_hasil_column', 1),
(18, '2026_02_26_120000_add_bobot_mapel_to_polije_majors_table', 1),
(19, '2026_02_26_200000_add_session_id_to_chat_histories_table', 1),
(20, '2026_02_26_210000_backfill_session_id_on_chat_histories', 1),
(21, '2026_03_06_100000_add_recommendation_id_to_chat_histories_table', 1),
(22, '2026_04_22_000000_rename_domain_tables_to_indonesian', 1),
(23, '2026_04_29_drop_preferensi_studi_lanjutan', 1),
(24, '2026_04_29_simplify_alumni_table', 1),
(25, '2026_05_06_000000_convert_bobot_mapel_to_ipa_ips', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekomendasi`
--

CREATE TABLE `rekomendasi` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `mtk` double(8,2) DEFAULT NULL,
  `fisika` double(8,2) DEFAULT NULL,
  `kimia` double(8,2) DEFAULT NULL,
  `biologi` double(8,2) DEFAULT NULL,
  `ekonomi` double(8,2) DEFAULT NULL,
  `geografi` double(8,2) DEFAULT NULL,
  `sosiologi` double(8,2) DEFAULT NULL,
  `sejarah` double(8,2) DEFAULT NULL,
  `minat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferensi_studi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cita_cita` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prestasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hasil_rekomendasi` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rekomendasi`
--

INSERT INTO `rekomendasi` (`id`, `user_id`, `mtk`, `fisika`, `kimia`, `biologi`, `ekonomi`, `geografi`, `sosiologi`, `sejarah`, `minat`, `preferensi_studi`, `cita_cita`, `prestasi`, `hasil_rekomendasi`, `created_at`, `updated_at`) VALUES
(1, 3, 92.00, 88.00, 85.00, 78.00, NULL, NULL, NULL, NULL, 'Logika Komputer', 'Sains & Teknologi', 'Software Engineer', 'Juara 2 Olimpiade Informatika', '[{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":92,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"},{\"jurusan\":\"Akuntansi\",\"skor\":92,\"detail\":\"Untuk yang tertarik dengan keuangan dan akuntansi.\"},{\"jurusan\":\"Teknologi Informasi\",\"skor\":88.3,\"detail\":\"Cocok untuk minat teknologi dan programming.\"}]', '2026-05-12 11:06:30', '2026-05-12 11:06:30'),
(2, 4, 85.00, 90.00, 88.00, 80.00, NULL, NULL, NULL, NULL, 'Mesin & Otomasi', 'Sains & Teknologi', 'Insinyur Mesin', 'Juara Lomba Robotika Regional', '[{\"jurusan\":\"Teknik\",\"skor\":87.5,\"detail\":\"Sesuai dengan kemampuan fisika dan matematika tinggi.\"},{\"jurusan\":\"Teknologi Informasi\",\"skor\":86.9,\"detail\":\"Cocok untuk minat teknologi dan programming.\"},{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":85,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(3, 5, 78.00, 75.00, 88.00, 92.00, NULL, NULL, NULL, NULL, 'Alam Tanaman', 'Pertanian & Lingkungan', 'Agronomis', 'Juara Pameran Tanaman Hidroponik', '[{\"jurusan\":\"Peternakan\",\"skor\":87.3,\"detail\":\"Bidang peternakan dan manajemen hewan ternak.\"},{\"jurusan\":\"Produksi Pertanian\",\"skor\":86.4,\"detail\":\"Cocok untuk minat di bidang pertanian dan lingkungan.\"},{\"jurusan\":\"Teknologi Informasi\",\"skor\":79.9,\"detail\":\"Cocok untuk minat teknologi dan programming.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(4, 6, 82.00, 80.00, 92.00, 88.00, NULL, NULL, NULL, NULL, 'Kimia & Biologi', 'Kesehatan & Ilmu Hayat', 'Peneliti Biologi', 'Penulis Jurnal Ilmiah Tingkat Sekolah', '[{\"jurusan\":\"Peternakan\",\"skor\":87.4,\"detail\":\"Bidang peternakan dan manajemen hewan ternak.\"},{\"jurusan\":\"Produksi Pertanian\",\"skor\":87.2,\"detail\":\"Cocok untuk minat di bidang pertanian dan lingkungan.\"},{\"jurusan\":\"Teknik\",\"skor\":83.9,\"detail\":\"Sesuai dengan kemampuan fisika dan matematika tinggi.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(5, 7, NULL, NULL, NULL, NULL, 90.00, 87.00, 85.00, 80.00, 'Bisnis', 'Bisnis & Manajemen', 'Entrepreneur Muda', 'Juara Kompetisi Bisnis Plan Nasional', '[{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":88,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"},{\"jurusan\":\"Akuntansi\",\"skor\":86.9,\"detail\":\"Untuk yang tertarik dengan keuangan dan akuntansi.\"},{\"jurusan\":\"Bahasa Komunikasi\",\"skor\":85.1,\"detail\":\"Bidang komunikasi dan seni untuk yang ekspresif.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(6, 8, 85.00, NULL, NULL, NULL, 88.00, NULL, 80.00, 82.00, 'Akuntansi', 'Bisnis & Manajemen', 'Akuntan Profesional', 'Finalis Kompetisi Akuntansi Wilayah', '[{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":85.4,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"},{\"jurusan\":\"Akuntansi\",\"skor\":85.3,\"detail\":\"Untuk yang tertarik dengan keuangan dan akuntansi.\"},{\"jurusan\":\"Teknologi Informasi\",\"skor\":85,\"detail\":\"Cocok untuk minat teknologi dan programming.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(7, 9, NULL, NULL, NULL, NULL, 80.00, 85.00, 92.00, 88.00, 'Komunikasi Sosial', 'Seni & Komunikasi', 'Presenter Berita', 'Juara Debat Tingkat Propinsi', '[{\"jurusan\":\"Bahasa Komunikasi\",\"skor\":87.1,\"detail\":\"Bidang komunikasi dan seni untuk yang ekspresif.\"},{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":84.1,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"},{\"jurusan\":\"Akuntansi\",\"skor\":83.7,\"detail\":\"Untuk yang tertarik dengan keuangan dan akuntansi.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(8, 10, 80.00, NULL, NULL, NULL, 85.00, 88.00, 82.00, NULL, 'Bisnis', 'Bisnis & Manajemen', 'Manajer Bisnis', 'Pengusaha Kuliner Muda', '[{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":84.3,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"},{\"jurusan\":\"Bahasa Komunikasi\",\"skor\":84.3,\"detail\":\"Bidang komunikasi dan seni untuk yang ekspresif.\"},{\"jurusan\":\"Akuntansi\",\"skor\":82.9,\"detail\":\"Untuk yang tertarik dengan keuangan dan akuntansi.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(9, 11, 88.00, 85.00, 86.00, 84.00, NULL, NULL, NULL, NULL, 'Teknologi & Inovasi', 'Sains & Teknologi', 'Product Manager Tech', 'Top 10 Innovation Challenge', '[{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":88,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"},{\"jurusan\":\"Akuntansi\",\"skor\":88,\"detail\":\"Untuk yang tertarik dengan keuangan dan akuntansi.\"},{\"jurusan\":\"Teknologi Informasi\",\"skor\":86.3,\"detail\":\"Cocok untuk minat teknologi dan programming.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(10, 12, 90.00, 92.00, 84.00, 76.00, NULL, NULL, NULL, NULL, 'Listrik & Energi', 'Sains & Teknologi', 'Insinyur Elektro', 'Juara Kompetisi Energi Terbarukan', '[{\"jurusan\":\"Manajemen Agribisnis\",\"skor\":90,\"detail\":\"Kombinasi bisnis dan pertanian yang sempurna.\"},{\"jurusan\":\"Akuntansi\",\"skor\":90,\"detail\":\"Untuk yang tertarik dengan keuangan dan akuntansi.\"},{\"jurusan\":\"Teknik\",\"skor\":88.6,\"detail\":\"Sesuai dengan kemampuan fisika dan matematika tinggi.\"}]', '2026-05-12 11:06:32', '2026-05-12 11:06:32');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_chat`
--

CREATE TABLE `riwayat_chat` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `id_sesi` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_rekomendasi` bigint UNSIGNED DEFAULT NULL,
  `pertanyaan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jawaban` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_chat`
--

INSERT INTO `riwayat_chat` (`id`, `user_id`, `id_sesi`, `id_rekomendasi`, `pertanyaan`, `jawaban`, `created_at`, `updated_at`) VALUES
(1, 3, '53686574-9e0e-4890-b826-b98dd5474718', 1, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Teknologi Informasi adalah pilihan terbaik. Dengan prestasi di bidang juara 2 olimpiade informatika, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(2, 3, '53686574-9e0e-4890-b826-b98dd5474718', 1, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai mtk, fisika, kimia, biologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(3, 4, '99f41a25-5daa-454e-9b6f-5885c93cf242', 2, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Teknik adalah pilihan terbaik. Dengan prestasi di bidang juara lomba robotika regional, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(4, 4, '99f41a25-5daa-454e-9b6f-5885c93cf242', 2, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai mtk, fisika, kimia, biologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(5, 5, 'b6c7ea5a-e835-4126-ac7a-4bc27637b760', 3, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Produksi Pertanian adalah pilihan terbaik. Dengan prestasi di bidang juara pameran tanaman hidroponik, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(6, 5, 'b6c7ea5a-e835-4126-ac7a-4bc27637b760', 3, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai mtk, fisika, kimia, biologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(7, 6, '5322a8f1-70d6-4630-bd63-ad405d33b1f6', 4, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Peternakan adalah pilihan terbaik. Dengan prestasi di bidang penulis jurnal ilmiah tingkat sekolah, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(8, 6, '5322a8f1-70d6-4630-bd63-ad405d33b1f6', 4, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai mtk, fisika, kimia, biologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(9, 7, '83a54747-4c2c-4a65-a78f-9671962a44fd', 5, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Manajemen Agribisnis adalah pilihan terbaik. Dengan prestasi di bidang juara kompetisi bisnis plan nasional, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(10, 7, '83a54747-4c2c-4a65-a78f-9671962a44fd', 5, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai ekonomi, geografi, sosiologi, sejarah Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(11, 8, '04fa19e6-b97a-415f-ab0f-c6ea19661dc9', 6, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Akuntansi adalah pilihan terbaik. Dengan prestasi di bidang finalis kompetisi akuntansi wilayah, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(12, 8, '04fa19e6-b97a-415f-ab0f-c6ea19661dc9', 6, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai ekonomi, mtk, sejarah, sosiologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(13, 9, '7c6ae641-8ec1-4b2d-8c68-ea93971005dd', 7, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Bahasa Komunikasi adalah pilihan terbaik. Dengan prestasi di bidang juara debat tingkat propinsi, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(14, 9, '7c6ae641-8ec1-4b2d-8c68-ea93971005dd', 7, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai sosiologi, sejarah, ekonomi, geografi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(15, 10, 'b3595271-87e8-4310-a20b-0ea8a4d56f1b', 8, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Manajemen Agribisnis adalah pilihan terbaik. Dengan prestasi di bidang pengusaha kuliner muda, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(16, 10, 'b3595271-87e8-4310-a20b-0ea8a4d56f1b', 8, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai ekonomi, geografi, mtk, sosiologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(17, 11, 'a7e3bd9c-f82e-4d19-88a6-39054bb08b8c', 9, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Teknologi Informasi adalah pilihan terbaik. Dengan prestasi di bidang top 10 innovation challenge, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(18, 11, 'a7e3bd9c-f82e-4d19-88a6-39054bb08b8c', 9, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai mtk, fisika, kimia, biologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(19, 12, '945df9f6-f859-48db-ad72-beafef166a79', 10, 'Jurusan apa yang cocok untuk saya?', 'Berdasarkan nilai dan minat Anda, Teknik adalah pilihan terbaik. Dengan prestasi di bidang juara kompetisi energi terbarukan, Anda memiliki potensi besar untuk sukses.', '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(20, 12, '945df9f6-f859-48db-ad72-beafef166a79', 10, 'Mengapa jurusan tersebut cocok untuk saya?', 'Karena nilai fisika, mtk, kimia, biologi Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.', '2026-05-12 11:06:32', '2026-05-12 11:06:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelompok_asal` enum('IPA','IPS') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','bk','siswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `nis`, `kelompok_asal`, `foto`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Polije', 'admin@polije.ac.id', NULL, NULL, NULL, '2026-05-12 11:04:07', '$2y$10$UHgtoqev8zyMvvOcjf.yjees7c4jWtkLdidVpMXKzkVBd389CS9Sm', 'admin', NULL, '2026-05-12 11:04:07', '2026-05-12 11:04:07'),
(2, 'Konselor BK', 'gurubk@polije.ac.id', NULL, NULL, NULL, '2026-05-12 11:04:07', '$2y$10$PAAsq5zHzgDHwx3O/Bm2POKRZSPtjZfOD/lsXxKXlZiFtFE9ArNMm', 'bk', NULL, '2026-05-12 11:04:07', '2026-05-12 11:04:07'),
(3, 'Adi Pratama', 'adi.pratama@student.polije.ac.id', '001', 'IPA', NULL, NULL, '$2y$10$3LgVvuDaa1/1UjejHrZkC.OTqkajjWd1PU63KyqP2wJ0UIEonuA8W', 'siswa', NULL, '2026-05-12 11:06:30', '2026-05-12 11:06:30'),
(4, 'Bella Maharani', 'bella.maharani@student.polije.ac.id', '002', 'IPA', NULL, NULL, '$2y$10$ZO/CNDv7a2XDHbFBb.08JuokxGPvirrLZ.d9pXRdEXXa3KHQ26vXa', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(5, 'Citra Dewi', 'citra.dewi@student.polije.ac.id', '003', 'IPA', NULL, NULL, '$2y$10$S2ovT1150.gqAMRJU8NgcOIYUBkraC3qLWi7IvHMaHH3sNwj3WGUW', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(6, 'Doni Kusuma', 'doni.kusuma@student.polije.ac.id', '004', 'IPA', NULL, NULL, '$2y$10$BZ9fzN5B9Ai4goPK/EYfe.USy/ChNFwaFTcghQL.CKm2LpqfkRDfe', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(7, 'Eka Prasetyo', 'eka.prasetyo@student.polije.ac.id', '005', 'IPS', NULL, NULL, '$2y$10$DLd6yXVOVrg5KgodVUJbyuOjQRKAYNppdJhfiVz567.JYywS4lNxy', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(8, 'Fitri Handayani', 'fitri.handayani@student.polije.ac.id', '006', 'IPS', NULL, NULL, '$2y$10$bboU6Vh3mOraVWufvyy4eeeBbNJpkfG43XMSkIbfRRSEyhH0SpnxW', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(9, 'Gita Salsabila', 'gita.salsabila@student.polije.ac.id', '007', 'IPS', NULL, NULL, '$2y$10$r6fQXfAbPkLJMV/dcjhXK.Aox4R7WF9beKETJPr6A7PspgizcyfMC', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(10, 'Hendra Wijaya', 'hendra.wijaya@student.polije.ac.id', '008', 'IPS', NULL, NULL, '$2y$10$RprJgLR1m/6l5sp4WjOB1OPD1KmEmjqUvaObHsHqHms6FEFybdn/6', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(11, 'Ibu Musim', 'ibu.musim@student.polije.ac.id', '009', 'IPA', NULL, NULL, '$2y$10$QGeWFS9pfSV5TQl91kDjQ.7M7DOZZ0NYpVEGFYsPT3em/eDgJk/Qq', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32'),
(12, 'Joko Santoso', 'joko.santoso@student.polije.ac.id', '010', 'IPA', NULL, NULL, '$2y$10$qOs6qlLclgOaLcTxDTjnweSbDZ3lnip6Fx4hZEaO3pfQ5.VYrVjL6', 'siswa', NULL, '2026-05-12 11:06:32', '2026-05-12 11:06:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumni_nis_unique` (`nis`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jurusan_polije`
--
ALTER TABLE `jurusan_polije`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `polije_majors_nama_jurusan_unique` (`nama_jurusan`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recommendations_user_id_foreign` (`user_id`);

--
-- Indexes for table `riwayat_chat`
--
ALTER TABLE `riwayat_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_histories_user_id_foreign` (`user_id`),
  ADD KEY `chat_histories_session_id_index` (`id_sesi`),
  ADD KEY `chat_histories_recommendation_id_foreign` (`id_rekomendasi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nis_unique` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurusan_polije`
--
ALTER TABLE `jurusan_polije`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `riwayat_chat`
--
ALTER TABLE `riwayat_chat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  ADD CONSTRAINT `recommendations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_chat`
--
ALTER TABLE `riwayat_chat`
  ADD CONSTRAINT `chat_histories_recommendation_id_foreign` FOREIGN KEY (`id_rekomendasi`) REFERENCES `rekomendasi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
