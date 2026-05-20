<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\ChatHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecommendationAndChatSeeder extends Seeder
{
    public function run(): void
    {
        // Data rekomendasi detail dengan bobot ROC
        $recommendationData = [
            // Siswa IPA - Teknik & IT oriented
            [
                'user_id' => 2, // Siswa 1
                'mtk' => 85, 'fisika' => 88, 'kimia' => 82, 'biologi' => 75,
                'minat' => 'Suka coding dan bikin aplikasi, interested di cyber security juga',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Jadi software engineer di startup lokal, pengen develop aplikasi yang bermanfaat',
                'prestasi' => 'Juara 2 Kompetisi Programming tingkat provinsi tahun lalu',
            ],
            [
                'user_id' => 3, // Siswa 2
                'mtk' => 92, 'fisika' => 90, 'kimia' => 88, 'biologi' => 85,
                'minat' => 'Sangat tertarik dengan robotika dan automation, suka ngutak-atik elektronik',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Ingin bekerja di industri manufaktur sebagai engineer untuk bikin sistem otomasi',
                'prestasi' => 'Finalis Olimpiade Fisika Nasional, sudah ada 3 paten design robotik',
            ],
            [
                'user_id' => 4, // Siswa 3
                'mtk' => 78, 'fisika' => 81, 'kimia' => 79, 'biologi' => 92,
                'minat' => 'Passionate tentang bioteknologi dan kesehatan, sering baca tentang genomik',
                'preferensi_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Mau lanjut ke kedokteran atau jadi peneliti di bidang biomedis',
                'prestasi' => 'Presentasi paper biologi di seminar nasional, pernah volunteer di klinik',
            ],
            [
                'user_id' => 5, // Siswa 4
                'mtk' => 88, 'fisika' => 85, 'kimia' => 90, 'biologi' => 80,
                'minat' => 'Suka kimia organik, proses manufaktur, dan industri pertanian',
                'preferensi_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Bikin startup agritech yang sustainable, pengen improve produktivitas petani lokal',
                'prestasi' => 'Juara Karya Ilmiah tentang pupuk organik, punya UKM pupuk sendiri',
            ],
            [
                'user_id' => 6, // Siswa 5
                'mtk' => 82, 'fisika' => 84, 'kimia' => 86, 'biologi' => 88,
                'minat' => 'Selalu tertarik dengan peternakan modern dan nutrisi hewan',
                'preferensi_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Jadi manager farm modern atau ahli nutrisi ternak, pengen export hasil',
                'prestasi' => 'Punya kandang ternak sapi yang sudah operational, mengikuti workshop peternakan',
            ],

            // Siswa IPS - Bisnis & Sosial oriented
            [
                'user_id' => 7, // Siswa 6
                'ekonomi' => 85, 'geografi' => 88, 'sosiologi' => 80, 'sejarah' => 78,
                'minat' => 'Suka analisa bisnis dan strategi marketing, aktif di club entrepreneur',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Mau founder perusahaan logistik yang bisa layani seluruh Indonesia',
                'prestasi' => 'Ketua OSIS 2 tahun, udah coba business plan shipping dengan teman',
            ],
            [
                'user_id' => 8, // Siswa 7
                'ekonomi' => 90, 'geografi' => 85, 'sosiologi' => 88, 'sejarah' => 82,
                'minat' => 'Tertarik accounting dan finance, suka ngitung dan detail',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Jadi akuntan profesional dan buka kantor akuntan sendiri',
                'prestasi' => 'Juara Lomba Akuntansi, sudah belajar MYOB dan software akuntansi',
            ],
            [
                'user_id' => 9, // Siswa 8
                'ekonomi' => 78, 'geografi' => 82, 'sosiologi' => 92, 'sejarah' => 88,
                'minat' => 'Sangat peduli sosial dan keadilan, aktif di organisasi kemanusiaan',
                'preferensi_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jadi social worker atau policy maker yang bisa bantu masyarakat kecil',
                'prestasi' => 'Koordinator program CSR, pernah design program beasiswa untuk anak kurang mampu',
            ],
            [
                'user_id' => 10, // Siswa 9
                'ekonomi' => 88, 'geografi' => 90, 'sosiologi' => 85, 'sejarah' => 86,
                'minat' => 'Hobi pariwisata dan guide, suka cerita tentang budaya daerah',
                'preferensi_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Develop destinasi pariwisata lokal yang sustainable, buat kuliner branded',
                'prestasi' => 'Sudah jadi tour guide 6 bulan, punya grup social media wisata lokal',
            ],
            [
                'user_id' => 11, // Siswa 10
                'ekonomi' => 82, 'geografi' => 88, 'sosiologi' => 80, 'sejarah' => 85,
                'minat' => 'Senang dengan komunikasi dan public speaking, aktif di debat club',
                'preferensi_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jadi komunikasi profesional atau journalist yang investigatif',
                'prestasi' => 'Finalis Debat Nasional, pernah bikin dokumenter lokal, punya podcast',
            ],

            // Tambahan data diverse
            [
                'user_id' => 2,
                'mtk' => 79, 'fisika' => 75, 'kimia' => 80, 'biologi' => 88,
                'minat' => 'Biologi laut dan konservasi, sering ke pantai untuk research',
                'preferensi_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Jadi peneliti laut atau oceanographer, lindungi ekosistem terumbu karang',
                'prestasi' => 'Peserta program konservasi laut, pernah publikasi artikel tentang coral bleaching',
            ],
            [
                'user_id' => 3,
                'mtk' => 86, 'fisika' => 92, 'kimia' => 85, 'biologi' => 78,
                'minat' => 'Tertarik mesin presisi dan manufacturing, suka dismantle dan assemble',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Jadi chief engineer di pabrik industri atau buka workshop manufaktur',
                'prestasi' => 'Juara kompetisi mesin, punya skill CAD dan CNC machining',
            ],
            [
                'user_id' => 4,
                'mtk' => 81, 'fisika' => 80, 'kimia' => 88, 'biologi' => 90,
                'minat' => 'Farmasi dan obat-obatan, sering ikut diskusi medical science',
                'preferensi_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Jadi apoteker atau researcher farmasi, develop obat lokal berkualitas',
                'prestasi' => 'Internship di apotek besar, belajar formulation dan quality control',
            ],
            [
                'user_id' => 5,
                'mtk' => 84, 'fisika' => 82, 'kimia' => 92, 'biologi' => 85,
                'minat' => 'Proses industri dan chemical engineering, suka tonton cara produksi',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Jadi chemical engineer, optimalkan proses produksi di industri tekstil',
                'prestasi' => 'Design proposal chemical processing untuk usaha keluarga',
            ],
            [
                'user_id' => 6,
                'mtk' => 77, 'fisika' => 79, 'kimia' => 81, 'biologi' => 90,
                'minat' => 'Veteriner dan kesehatan hewan, punya pengalaman tangani hewan sakit',
                'preferensi_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Jadi dokter hewan dan buka klinik hewan modern di daerah',
                'prestasi' => 'Volunteer di animal shelter, treatment beberapa hewan terlantar',
            ],
            [
                'user_id' => 7,
                'ekonomi' => 82, 'geografi' => 85, 'sosiologi' => 78, 'sejarah' => 80,
                'minat' => 'Digital marketing dan e-commerce, aktif buat konten di social media',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Jadi digital marketing specialist atau content creator profesional',
                'prestasi' => 'Manage social media bisnis teman, follower tumbuh jadi 50 ribu',
            ],
            [
                'user_id' => 8,
                'ekonomi' => 88, 'geografi' => 82, 'sosiologi' => 85, 'sejarah' => 84,
                'minat' => 'Banking dan investment, suka belajar tentang saham dan cryptocurrency',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Jadi financial advisor atau investment manager di bank besar',
                'prestasi' => 'Peserta kompetisi investment game, udah trading saham sendiri',
            ],
            [
                'user_id' => 9,
                'ekonomi' => 75, 'geografi' => 88, 'sosiologi' => 90, 'sejarah' => 85,
                'minat' => 'Pengembangan masyarakat dan pendidikan, pernah ajar anak-anak kurang mampu',
                'preferensi_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jadi pendidik atau foundation director yang bisa rubah hidup banyak anak',
                'prestasi' => 'Founder program belajar gratis untuk anak pinggiran, sudah 50 peserta',
            ],
            [
                'user_id' => 10,
                'ekonomi' => 85, 'geografi' => 92, 'sosiologi' => 88, 'sejarah' => 86,
                'minat' => 'Travel dan budaya lokal, suka explore setiap daerah dan documenting',
                'preferensi_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jadi travel blogger profesional atau cultural ambassador',
                'prestasi' => 'Blog wisata udah 100k visitors, punya instagram tentang kuliner lokal',
            ],
            [
                'user_id' => 11,
                'ekonomi' => 80, 'geografi' => 85, 'sosiologi' => 88, 'sejarah' => 90,
                'minat' => 'Sejarah dan warisan budaya, aktif di club heritage preservation',
                'preferensi_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jadi historian atau kurator museum, lestarikan warisan budaya Indonesia',
                'prestasi' => 'Dokumentasi situs sejarah lokal, pernah present di forum budaya',
            ],
            [
                'user_id' => 2,
                'mtk' => 88, 'fisika' => 86, 'kimia' => 84, 'biologi' => 82,
                'minat' => 'AI dan machine learning, ikut online course dan kaggle competition',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Jadi AI specialist atau data scientist, develop solution untuk Indonesia',
                'prestasi' => 'Rank 500 di kaggle, bikin model prediksi untuk project sekolah',
            ],
            [
                'user_id' => 3,
                'mtk' => 89, 'fisika' => 88, 'kimia' => 87, 'biologi' => 75,
                'minat' => 'Game development dan creative coding, punya beberapa game project',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Jadi game developer di studio internasional atau bikin studio sendiri',
                'prestasi' => 'Game yang dibuat udah dimainkan ribuan orang, dapat award di expo',
            ],
            [
                'user_id' => 4,
                'mtk' => 80, 'fisika' => 79, 'kimia' => 90, 'biologi' => 92,
                'minat' => 'Gizi dan kesehatan masyarakat, aktif di program kesehatan komunitas',
                'preferensi_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Jadi ahli gizi profesional, program nutrisi untuk anak Indonesia',
                'prestasi' => 'Design program gizi untuk sekolah di daerah tertinggal, pernah grant',
            ],
            [
                'user_id' => 5,
                'mtk' => 83, 'fisika' => 84, 'kimia' => 89, 'biologi' => 86,
                'minat' => 'Sustainability dan green technology, interested di renewable energy',
                'preferensi_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Jadi engineer yang fokus sustainable development dan clean energy',
                'prestasi' => 'Project solar panel untuk sekolah, design water management system',
            ],
            [
                'user_id' => 6,
                'mtk' => 81, 'fisika' => 80, 'kimia' => 82, 'biologi' => 89,
                'minat' => 'Agronomi modern dan precision farming, ikuti webinar tentang smart farm',
                'preferensi_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Jadi smart farmer yang pakai teknologi untuk maksimalkan hasil panen',
                'prestasi' => 'Pilot project smart farming dengan sensor IoT, hasil bagus',
            ],
            [
                'user_id' => 7,
                'ekonomi' => 84, 'geografi' => 86, 'sosiologi' => 82, 'sejarah' => 76,
                'minat' => 'Supply chain dan logistik, suka pelajari efficient delivery systems',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Jadi supply chain director atau logistics innovator untuk e-commerce',
                'prestasi' => 'Analyze supply chain usaha keluarga, improve efficiency 40 persen',
            ],
            [
                'user_id' => 8,
                'ekonomi' => 87, 'geografi' => 84, 'sosiologi' => 86, 'sejarah' => 83,
                'minat' => 'Corporate finance dan management accounting, belajar SAP dan MYOB',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Jadi CFO atau finance director di perusahaan multinational',
                'prestasi' => 'Intern di finance department perusahaan, handle reconciliation',
            ],
            [
                'user_id' => 9,
                'ekonomi' => 76, 'geografi' => 87, 'sosiologi' => 92, 'sejarah' => 88,
                'minat' => 'Community development dan empowerment, pernah training leadership',
                'preferensi_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jadi community development officer di NGO atau social enterprise',
                'prestasi' => 'Facilitate community meeting dan program design untuk 500 orang',
            ],
            [
                'user_id' => 10,
                'ekonomi' => 86, 'geografi' => 89, 'sosiologi' => 87, 'sejarah' => 85,
                'minat' => 'Hospitality dan business management, experience internship di hotel',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Jadi hotel manager atau develop hospitality brand lokal kelas dunia',
                'prestasi' => 'Manage event 200 orang, guest satisfaction score 95 persen',
            ],
        ];

        // Insert recommendations
        foreach ($recommendationData as $data) {
            Recommendation::create(array_merge($data, [
                'hasil_rekomendasi' => [
                    [
                        'jurusan' => 'Teknologi Informasi',
                        'skor' => rand(70, 95) / 100,
                        'detail' => [
                            'nilai' => rand(60, 95) / 100,
                            'minat' => rand(70, 98) / 100,
                            'pref' => rand(60, 95) / 100,
                            'cita' => rand(75, 98) / 100,
                            'prestasi' => rand(50, 90) / 100,
                        ]
                    ]
                ]
            ]));
        }

        // Data chat history yang natural dan tidak template
        $chatData = [
            // Chat tentang rekomendasi
            ['user_id' => 2, 'id_sesi' => 'session_1', 'pertanyaan' => 'Jadi menurut sistem saya cocok ambil jurusan apa ya?', 'jawaban' => 'Berdasarkan hasil analisis, untuk profile Anda yang nilai MTK-Fisika bagus dan minat coding kuat, Teknologi Informasi adalah pilihan yang selaras. Anda bisa fokus cybersecurity atau development.'],
            ['user_id' => 2, 'id_sesi' => 'session_1', 'pertanyaan' => 'Cybersecurity lebih amankan karir atau development?', 'jawaban' => 'Dua-duanya prospek bagus. Cybersecurity ada demand dari government dan finance yang stabil. Development skills lebih flexible bisa startup atau big tech. Lihat passion Anda lebih ke yang mana.'],
            ['user_id' => 3, 'id_sesi' => 'session_2', 'pertanyaan' => 'Saya pengen jurusan yang bisa langsung applicable untuk robotika', 'jawaban' => 'Untuk robotika, Teknik Elektro atau Teknik Mesin sangat applicable. Dengan score fisika 90 Anda, dua jurusan ini cocok. Teknik Elektro kalau fokus control system, Teknik Mesin kalau fokus mechanical design.'],
            ['user_id' => 3, 'id_sesi' => 'session_2', 'pertanyaan' => 'Lebih pilih mana sih?', 'jawaban' => 'Lihat passion Anda lebih ke elektrik atau mekanik? Kalau suka coding dan control systems, Teknik Elektro. Kalau suka design fisik dan assemble-dismantle, Teknik Mesin. Keduanya relevant untuk robotika.'],
            ['user_id' => 4, 'id_sesi' => 'session_3', 'pertanyaan' => 'Bisa lanjut kedokteran dari TPI nggak?', 'jawaban' => 'Untuk kedokteran, value akademik dan jalur masuk lebih strict dibanding TPI. Tapi calondidik bisa persiapan dari TPI, terutama biologi dan kimia. Fokus banget di olimpiade sains untuk booster nilai masuk.'],
            ['user_id' => 4, 'id_sesi' => 'session_3', 'pertanyaan' => 'TPI itu apa ya singkatannya?', 'jawaban' => 'Teknik Pertanian dan Industri di Polije. Tapi kalau target kedokteran, mungkin bisa cek juga program kesehatan dulu untuk experience, atau aplikasi kedokteran pure dari outcome awal.'],
            
            // Chat tentang karir
            ['user_id' => 5, 'id_sesi' => 'session_4', 'pertanyaan' => 'Agribusiness itu nanti kerjanya kayak apa?', 'jawaban' => 'Bisa jadi manager farm, business development untuk agritech, supply chain untuk produk pertanian, atau entrepreneur. Banyak pilihan tergantung spesialisasi yang dipilih di kuliah.'],
            ['user_id' => 5, 'id_sesi' => 'session_4', 'pertanyaan' => 'Gaji fresh graduate agritech berapa kira-kira?', 'jawaban' => 'Startup agritech biasanya 4-6 juta tergantung posisi dan lokasi. Corporate agribusiness bisa 5-8 juta. Tapi kalau entrepreneurship sendiri, income tergantung success rate usaha Anda.'],
            
            // Chat tentang program belajar
            ['user_id' => 6, 'id_sesi' => 'session_5', 'pertanyaan' => 'Polije punya program internship nggak?', 'jawaban' => 'Ada, biasanya di semester akhir. Untuk program Peternakan, ada partnership dengan farm modern dan feed company. Cek dengan prodi langsung untuk detail placement.'],
            ['user_id' => 6, 'id_sesi' => 'session_5', 'pertanyaan' => 'Boleh apply internship di abroad nggak?', 'jawaban' => 'Tergantung kebijakan prodi, tapi generally bisa diskusi dengan pembimbing. Ada beberapa students yang udah internship di Malaysia atau Thailand. Usahakan komunikasi dengan prodi sejak early semester.'],
            
            // Chat tentang persiapan
            ['user_id' => 7, 'id_sesi' => 'session_6', 'pertanyaan' => 'Perlu les atau belajar sendiri sebelum masuk kuliah?', 'jawaban' => 'Tergantung kesiapan Anda. Kalau values academic udah solid, bisa self-study dengan referensi online. Tapi kalau mau jadi standout di semester pertama, bisa ambil kursus online tentang fondasi subject.'],
            ['user_id' => 7, 'id_sesi' => 'session_6', 'pertanyaan' => 'Ada rekomendasi website atau platform belajar nggak?', 'jawaban' => 'Udah banyak. Coursera, Udemy, Khan Academy bagus untuk foundational. Indonesia ada Ruangguru atau Zenius. Tapi yang paling penting engagement sama dosen dan teman-teman di kelas nanti.'],
            
            // Chat tentang keputusan
            ['user_id' => 8, 'id_sesi' => 'session_7', 'pertanyaan' => 'Agak ragu antara Akuntansi sama Management', 'jawaban' => 'Dua jurusan ini beda fokus. Akuntansi lebih technical dan detail-oriented, Management lebih strategic dan leader-focused. Lihat strength Anda: detail dan angka atau big picture dan people? Itu bisa guide decision.'],
            ['user_id' => 8, 'id_sesi' => 'session_7', 'pertanyaan' => 'Kalau pilih Management, bisa dapat nilai invest yang bagus nggak dari sekolah?', 'jawaban' => 'Bisa. Dengan nilai ekonomi 90 Anda, management akan mudah. Cuma akuntansi mungkin lebih certified langsung (JATS). Management lebih butuh experience dan soft skill yang dibangun di luar kelas.'],
            
            // Chat tentang scholarship
            ['user_id' => 9, 'id_sesi' => 'session_8', 'pertanyaan' => 'Ada beasiswa untuk jurusan sosial nggak?', 'jawaban' => 'Ada beberapa dari government dan foundation. Polije ada beasiswa akademik based on nilai. NGO juga sering sponsor untuk program kemanusiaan atau education. Tapi bisa juga dari corporate yang peduli CSR.'],
            ['user_id' => 9, 'id_sesi' => 'session_8', 'pertanyaan' => 'Requirement beasiswa biasanya apa?', 'jawaban' => 'Tergantung sumber. Academic scholarship butuh nilai bagus. Need-based butuh dokumentasi ekonomi. Full scholarship biasanya maintain GPA minimal 3.0 dan aktif di organisasi sosial.'],
            
            // Chat tentang prospek kerja
            ['user_id' => 10, 'id_sesi' => 'session_9', 'pertanyaan' => 'Pariwisata di Indonesia prospeknya gimana pasca COVID?', 'jawaban' => 'Recovery udah bagus. Domestic tourism naik, international mulai balik. Tapi industry butuh profesional yang innovative, bukan hanya tour guide. Positioning diri Anda sebagai strategic player, bukan operational level.'],
            ['user_id' => 10, 'id_sesi' => 'session_9', 'pertanyaan' => 'Jadi hospitality manager itu punya team berapa biasanya?', 'jawaban' => 'Tergantung size hotel. Small hotel (50 kamar) mungkin 5-10 staff. Mid-range (100-200 kamar) 20-50 staff. Besar (300+ kamar) bisa 100+. Semakin besar, semakin kompleks management-nya.'],
            
            // Chat about challenges
            ['user_id' => 11, 'id_sesi' => 'session_10', 'pertanyaan' => 'Komunikasi itu subject berat nggak di kuliah?', 'jawaban' => 'Tidak terlalu berat seperti teknik atau science. Tapi demanding dalam hal project, presentasi, dan banyak reading. Kalau Anda suka talking dan creative, ini cocok. Tantangan lebih ke academic rigor, bukan mathematical complexity.'],
            ['user_id' => 11, 'id_sesi' => 'session_10', 'pertanyaan' => 'Ada lab atau praktik langsung nggak?', 'jawaban' => 'Ada. Biasanya ada lab communicative, media production, bahkan ada broadcast studio. Praktik langsung dengan project nyata untuk client atau media partner. Itu yang membuat komunikasi menarik.'],
            
            // More natural chats
            ['user_id' => 2, 'id_sesi' => 'session_11', 'pertanyaan' => 'Kenalin dong senior-senior di Teknologi Informasi', 'jawaban' => 'Udah ada program mentoring di Polije. Senior bisa di-connect melalui alumni network atau student organization. Mereka helpful banget share experience dan tips lolos interview di tech company.'],
            ['user_id' => 3, 'id_sesi' => 'session_12', 'pertanyaan' => 'Lab di Teknik Elektro lengkap nggak untuk robotika?', 'jawaban' => 'Lumayan lengkap. Ada microcontroller lab, digital logic lab. Untuk advanced robotics, bisa kolaborasi dengan Teknik Mesin juga. Ada makerspace bersama yang membantu student projects.'],
            ['user_id' => 4, 'id_sesi' => 'session_13', 'pertanyaan' => 'Kesehatan di Polije fokus apa ya?', 'jawaban' => 'Ada program Ilmu Gizi, Teknologi Laboratorium Medis, dan Kesehatan Masyarakat. Fokus ke applied health, tidak pure medical science. Cocok untuk Anda yang peduli community health.'],
            ['user_id' => 5, 'id_sesi' => 'session_14', 'pertanyaan' => 'Mahasiswa agribusiness banyak nggak?', 'jawaban' => 'Cukup banyak, sekitar 100 per tahun. Intake lumayan tinggi karena demand industri. Networking sama peers bisa bagus untuk future business partnership.'],
            ['user_id' => 6, 'id_sesi' => 'session_15', 'pertanyaan' => 'Peternakan di Polije punya farm sendiri nggak?', 'jawaban' => 'Ada. Farm sendiri untuk praktik langsung sama students. Facilities bagus, ada kandang modern, equipment hewan medis. Bisa langsung hands-on dari semester pertama.'],
            ['user_id' => 7, 'id_sesi' => 'session_16', 'pertanyaan' => 'Manajemen jurusan itu terlalu general nggak?', 'jawaban' => 'Dalam kuliah ada specialization. Semester awal general foundation, nanti semester 5-6 bisa pilih tracks seperti operations, finance, marketing, atau digital business. Jadi nggak general-general amat.'],
            ['user_id' => 8, 'id_sesi' => 'session_17', 'pertanyaan' => 'Akuntansi itu paling banyak ngitung nggak si?', 'jawaban' => 'Lumayan. Tapi sekarang accounting lebih ke understanding system, analysis, dan business advisory. Ngitung masih ada tapi banyak software yang bantu. Focus lebih ke thinking skills.'],
            ['user_id' => 9, 'id_sesi' => 'session_18', 'pertanyaan' => 'Social welfare program itu study apa aja sih?', 'jawaban' => 'Social policy, community development methods, case management, research methods. Praktik langsung di komunitas juga. Banyak fieldwork yang meaningful.'],
            ['user_id' => 10, 'id_sesi' => 'session_19', 'pertanyaan' => 'Tourism management itu enaknya atau challenging?', 'jawaban' => 'Enaklah karena hands-on banyak, networking luas. Challenging dari fast-paced industry yang selalu berubah. Tapi kalau Anda adaptable dan social, ini worth it.'],
        ];

        // Insert chat histories
        foreach ($chatData as $chat) {
            ChatHistory::create($chat);
        }

        echo "✅ Created " . count($recommendationData) . " recommendations and " . count($chatData) . " chat histories\n";
    }
}
